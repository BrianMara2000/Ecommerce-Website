<!DOCTYPE html>
<html>
<head>
    <title>{{ $user->is_admin ? 'New Order Received' : 'Order Confirmation' }}</title>
    <style>
      @font-face {
            font-family: 'Manrope';
            font-style: normal;
            font-weight: 400;
            src: url(data:font/woff2;charset=utf-8;base64,/* Base64 encoded string here */) format('woff2');
        }
        @font-face {
            font-family: 'Manrope';
            font-style: normal;
            font-weight: 500;
            src: url(data:font/woff2;charset=utf-8;base64,/* Base64 encoded string here */) format('woff2');
        }
        @font-face {
            font-family: 'Manrope';
            font-style: normal;
            font-weight: 600;
            src: url(data:font/woff2;charset=utf-8;base64,/* Base64 encoded string here */) format('woff2');
        }
        @font-face {
            font-family: 'Manrope';
            font-style: normal;
            font-weight: 700;
            src: url(data:font/woff2;charset=utf-8;base64,/* Base64 encoded string here */) format('woff2');
        }

        body {
            font-family: 'Manrope', Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 0 auto;
            padding: 20px;
            width: 80%;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .order-details {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            display: flex;
            justify-content: space-between;
        }
        .product {
            display: flex;
            align-items: center;
            row-gap: 10px;
        }
        
        .quantity {
            display: flex;
            align-items: center
        }
        .quantity p {
            margin-right: 10px;
        }
        .title, .price {
            font-size: 18px;
            font-weight: 600;
        }

        @media (max-width: 600px) {
            .quantity {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
  <div class="container">
    <h1>{{ $user->is_admin ? 'New Order Received' : 'Thank you for your order!' }}</h1>
    <a href="{{ $user->is_admin ? env('BACKEND_URL') . 'app/orders/' . $order->id : route('my-order.view', $order->id, true) }}">Order # {{ $order->id }}</a>
    <p>Total Price: ${{ $order->total_price }}</p>
    <p>Order Status: {{ $order->status }}</p>

    <h2>Order Details</h2>

    @foreach ($order->items as $item)
        
    <div class="order-details">
      <div class="product">
        <img src="{{ $item->product->image }}" style="width: 100px" alt="">
        <p class="title">{{ $item->product->title }}</p>
      </div>
      <div class="quantity">
        <p class="items">Qty: {{ $item->quantity }}</p>
        <p class="price">$ {{ $item->unit_price }}</p>
      </div>
    </div>
    @endforeach

</div>
</body>
</html>