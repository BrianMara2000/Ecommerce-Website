<h2>
  Your order status was changed into "{{$order->status}}"
</h2>
<p>
  Link to your order:
  <a href="{{route('my-order.view', $order->id, true)}}">Order #{{$order->id}}</a>
</p>