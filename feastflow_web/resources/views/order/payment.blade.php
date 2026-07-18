<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment | FeastFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-danger mb-4">
    <div class="container-fluid px-4">
        <a class="navbar-brand text-white" href="/menu">🍽️ FeastFlow</a>
        <div>
            <a href="/menu" class="btn btn-outline-light btn-sm">Menu</a>
            <a href="/logout" class="btn btn-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title text-danger">Payment for Order</h4>
                    <p class="text-muted">Complete the payment to confirm your order.</p>

                    <div class="mb-4 p-3 bg-light rounded">
                        <h6>Order summary</h6>
                        <p class="mb-1"><strong>Item:</strong> {{ $order['item_name'] }}</p>
                        <p class="mb-1"><strong>Quantity:</strong> {{ $order['quantity'] }}</p>
                        <p class="mb-1"><strong>Unit price:</strong> {{ $order['unit_price'] }} TK</p>
                        <p class="mb-0"><strong>Total:</strong> {{ $order['total'] }} TK</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="/order/payment">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">Select payment method</option>
                                <option value="card">Card</option>
                                <option value="cash">Cash</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Submit Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
