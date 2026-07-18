<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback | FeastFlow</title>
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
                    <h4 class="card-title text-danger">Order Feedback</h4>
                    <p class="text-muted">Let us know how your order experience was.</p>

                    <div class="mb-4 p-3 bg-light rounded">
                        <h6>Order completed</h6>
                        <p class="mb-1"><strong>Item:</strong> {{ $order['item_name'] }}</p>
                        <p class="mb-1"><strong>Payment Method:</strong> {{ ucfirst($payment['payment_method']) }}</p>
                        <p class="mb-0"><strong>Amount Paid:</strong> {{ $order['total'] }} TK</p>
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

                    <form method="POST" action="/order/feedback">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order['id'] }}">
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <select name="rating" class="form-select" required>
                                <option value="">Select a rating</option>
                                <option value="5">5 - Excellent</option>
                                <option value="4">4 - Very Good</option>
                                <option value="3">3 - Good</option>
                                <option value="2">2 - Fair</option>
                                <option value="1">1 - Poor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Comments</label>
                            <textarea name="comments" class="form-control" rows="4">{{ old('comments') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Send Feedback</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
