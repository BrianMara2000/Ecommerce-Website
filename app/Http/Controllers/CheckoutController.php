<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Inertia\Inertia;
use App\Helpers\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Exception;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        list($products, $cartItems) = Cart::getProductsAndCartItems();
        $orderItems = [];
        $lineItems = [];
        $totalPrice = 0;


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

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
            'customer_creation' => 'always',
        ]);

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

        return Inertia::location($checkout_session->url);
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
                ->where(['session_id' => $session_id, 'status' => PaymentStatus::Pending])->first();

            if (!$payment) {
                return redirect()->route('checkout.failure', ['message', "Payment doesn't exist"]);
            }

            $payment->status = PaymentStatus::Paid;
            $payment->update();

            $order = $payment->order;

            $order->status = OrderStatus::Paid;
            $order->update();


            $customer = $stripe->customers->retrieve($session->customer);
            // dd($user);
            CartItem::where(['user_id' => $user->id])->delete();


            return Inertia::render('User/Cart/Checkout/Success', ['session' => $session, 'customer' => $customer]);
        } catch (Exception $e) {
            return redirect()->route('checkout.failure', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function failure(Request $request)
    {
        $message = $request->input('message');
        return Inertia::render('User/Cart/Checkout/Failure', ['message' => $message]);
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
}
