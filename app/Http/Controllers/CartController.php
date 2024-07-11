<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;

class CartController extends Controller
{
	public function index()
	{
		$cartItems = Cart::getCartItems();

		$ids = Arr::pluck($cartItems, 'product_id');
		$products = Product::query()->where('id', $ids)->get();
		$cartItems = Arr::keyBy($cartItems, 'product_id');
		$total = 0;

		foreach ($products as $product) {
			$total += $product->price * $cartItems[$product->id]['quantity'];
		}

		return Inertia::render('User/Cart/Index', compact('cartItems', 'products', 'total'));
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

			// return response([
			// 	'count' => Cart::getCartItemsCount(),
			// ]);
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

			// return response(['count' => Cart::getCountFromItems($cartItems)]);
			return redirect()->back()->with('success', 'item removed successfully');
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
