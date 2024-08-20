<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Helpers\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
	public function index(Request $request, Product $product)
	{

		$user = $request->user();
		if ($user) {
			$cartItems = CartItem::where('user_id', $user->id)->get();
			if ($cartItems->count() > 0) {
				return Inertia::render(
					'User/Cart/Index',
					[
						'cartItems' => $cartItems,
					]
				);
			}
		} else {
			$cartItems = Cart::getCookieCartItems();
			if (count($cartItems) > 0) {
				$cartItems = new CartResource(Cart::getProductsAndCartItems());
				return  Inertia::render('User/Cart/Index', ['cartItems' => $cartItems]);
			} else {
				return redirect()->back();
			}
		}
	}

	public function add(Request $request, Product $product)
	{
		$quantity = $request->post('quantity', 1);
		$user = $request->user();

		if ($user) {
			$cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();
			if ($cartItem) {
				$cartItem->increment('quantity');
			} else {
				CartItem::create([
					'user_id' => $user->id,
					'product_id' => $product->id,
					'quantity' => 1,
				]);
			}
		} else {
			$cartItems = Cart::getCookieCartItems();
			$isProductExists = false;
			foreach ($cartItems as $item) {
				if ($item['product_id'] === $product->id) {
					$item['quantity'] += $quantity;
					$isProductExists = true;
					break;
				}
			}

			if (!$isProductExists) {
				$cartItems[] = [
					'user_id' => null,
					'product_id' => $product->id,
					'quantity' => $quantity,
					'price' => $product->price,
				];
			}
			Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);
		}

		return redirect()->back()->with('success', 'cart added successfully');
	}
	public function remove(Request $request, Product $product)
	{
		$user = $request->user();
		if ($user) {
			$cartItem = CartItem::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first();
			if ($cartItem) {
				$cartItem->delete();
			}
			return redirect()->back()->with('success', 'item removed successfully');
		} else {
			$cartItems = json_decode($request->cookie('cart_items', '[]'), true);
			foreach ($cartItems as $i => &$item) {
				if ($item['product_id'] === $product->id) {
					array_splice($cartItems, $i, 1);
					break;
				}
			}
			Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

			if (count($cartItems) <= 0) {
				return redirect()->route('user.home')->with('info', 'Your cart is now Empty');
			} else {
				return redirect()->back()->with('success', 'Item removed successfully');
			}
		}
	}
	public function updateQuantity(Request $request, Product $product)
	{
		$quantity = (int)$request->post('quantity');
		$user = $request->user();

		if ($product->quantity !== null && $product->quantity < $quantity) {
			return response([
				'message' => match ($product->quantity) {
					0 => 'The product is out of stock',
					1 => 'There is only one item left',
					default => 'There are only ' . $product->quantity . ' items left'
				}
			], 422);
		}

		if ($user) {
			CartItem::where(['user_id' => $request->user()->id, 'product_id' => $product->id])->update(['quantity' => $quantity]);

			// return response([
			// 	'count' => Cart::getCartItemsCount(),
			// ]);
		} else {
			$cartItems = json_decode($request->cookie('cart_items', '[]'), true);
			foreach ($cartItems as &$item) {
				if ($item['product_id'] === $product->id) {
					$item['quantity'] = $quantity;
					break;
				}
			}
			Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

			// return response(['count' => Cart::getCountFromItems($cartItems)]);
		}
		return redirect()->back();
	}
}
