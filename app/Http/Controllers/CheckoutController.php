<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Inertia\Inertia;
use App\Helpers\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use App\Mail\NewOrderEmail;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Api\Product;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        $checkoutItems = $request->post('checkoutItems');

        if (!$user->customer->shippingAddress || !$user->customer->billingAddress) {

            return redirect()->route('profile.edit')->withErrors('Please fill in your profile information before proceeding to checkout.')->withInput();
        }

        if (empty($checkoutItems)) {
            return redirect()->route('cart.index')->with('error', 'Please select items before proceeding to checkout.');
        }

        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        list($products, $cartItems) = Cart::getProductsAndCartItems($checkoutItems);
        $orderItems = [];
        $lineItems = [];
        $totalPrice = 0;

        DB::beginTransaction();
        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->price * $quantity;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->title,
                        'images' => [$product->image], // Please check the image url if you encountered NOT VALID URL error
                    ],
                    'unit_amount' => $product->price * 100,
                ],
                'quantity' => $quantity,
            ];

            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price,
            ];
        }

        dd($orderItems);
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
            'customer_creation' => 'always',
        ]);

        try {
            //Create order
            $orderData = [
                'total_price' => $totalPrice,
                'status' => OrderStatus::Unpaid,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ];
            $order = Order::create($orderData);

            //Create Order Items
            foreach ($orderItems as $orderItem) {
                $orderItem['order_id'] = $order->id;
                OrderItem::create($orderItem);
            }

            //Create payment
            $paymentData = [
                'order_id' => $order->id,
                'amount' => $totalPrice,
                'status' => PaymentStatus::Pending,
                'type' => 'cc',
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'session_id' => $checkout_session->id,
            ];

            Payment::create($paymentData);

            DB::commit();



            CartItem::where(['user_id' => $user->id])->whereIn('product_id', $checkoutItems)->delete();

            return Inertia::location($checkout_session->url);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            DB::rollBack();
            return redirect()->route('checkout.failure')->with('error', 'Payment processing error: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout.failure')->with('error', 'An unexpected error occurred. Please try again.');
        }
    }

    public function success(Request $request)
    {
        $user = $request->user();
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        try {
            $session_id = $request->get('session_id');
            $session = $stripe->checkout->sessions->retrieve($_GET['session_id']);

            if (!$session) {
                return redirect()->route('checkout.failure', ['message' => 'Invalid Session ID']);
            }

            $payment = Payment::query()
                ->where(['session_id' => $session_id])
                ->whereIn('status', [PaymentStatus::Pending, PaymentStatus::Paid])
                ->first();

            if (!$payment) {
                return redirect()->route('checkout.failure')->with('error', 'No pending payment found.');
            }

            if ($payment->status === PaymentStatus::Pending->value) {
                $this->updateOrderAndSession($payment);
            }

            DB::transaction(function () use ($payment) {
                foreach ($payment->order->items as $item) {
                    $product = $item->product;

                    if ($product->stock < $item->quantity) {
                        throw new \Exception("Insufficient stock for {$product->title}.");
                    }

                    $product->decrement('stock', $item->quantity);
                }
            });

            $order = $payment->order;
            $purchasedProductIds = $order->items->pluck('product_id')->toArray();

            CartItem::where('user_id', $user->id)
                ->whereIn('product_id', $purchasedProductIds)
                ->delete();

            $customer = $stripe->customers->retrieve($session->customer);

            return Inertia::render('User/Cart/Checkout/Success', ['session' => $session, 'customer' => $customer]);
        } catch (Exception $e) {
            Log::critical("Checkout failed: " . $e->getMessage());
            return redirect()->route('checkout.failure')->with('error', 'An error occurred during payment verification.');
        }
    }

    public function failure(Request $request)
    {
        return Inertia::render('User/Cart/Checkout/Failure', [
            'message' => session('error') ?? $request->query('error', 'Payment process was not completed.'),
        ]);
    }

    public function checkoutOrder(Order $order, Request $request)
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        $lineItems = [];

        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product->title,
                    ],
                    'unit_amount' => $item->unit_price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }


        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
            'customer_creation' => 'always',
        ]);

        $order->payment->session_id = $checkout_session->id;
        $order->payment->save();

        return Inertia::location($checkout_session->url);
    }

    public function webhook()
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        $endpoint_secret = 'whsec_3bd36932d19cab899c9593239aa48096f07ef10ae6b7f1e086cf4264c96b7f19';

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('', 401);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('', 402);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                $sessionId = $paymentIntent['id'];
                // handlePaymentIntentSucceeded($paymentIntent);
                break;
            case 'payment_method.attached':
                $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                // handlePaymentMethodAttached($paymentMethod);
                break;
            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response('', 200);
    }
    private function updateOrderAndSession(Payment $payment)
    {
        DB::beginTransaction();
        try {
            $payment->status = PaymentStatus::Paid->value;
            $payment->update();

            $order = $payment->order;

            $order->status = OrderStatus::Paid->value;
            $order->update();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::critical(__METHOD__ . ' method does not work. ' . $e->getMessage());
            throw $e;
        }

        DB::commit();

        try {
            $adminUsers = User::where('is_admin', 1)->get();

            foreach ([...$adminUsers, $order->user] as $user) {
                Mail::to($user)->send(new NewOrderEmail($order, $user));
            }
        } catch (\Exception $e) {
            Log::critical('Email sending does not work. ' . $e->getMessage());
        }
    }
}
