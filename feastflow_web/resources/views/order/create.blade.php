<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order {{ $item->name }} | FeastFlow</title>
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
                    <h4 class="card-title text-danger">Order {{ $item->name }}</h4>
                    <p class="text-muted">Complete your order details and proceed to payment.</p>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="/order">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <div class="mb-3">
                            <label class="form-label">Food Item</label>
                            <input type="text" class="form-control" value="{{ $item->name }}" readonly>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Price per item</label>
                                <input type="text" class="form-control" value="{{ $item->price }} TK" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Your Name</label>
                            <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Delivery Address</label>
                            <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Special Instructions</label>
                            <textarea name="instructions" class="form-control" rows="2">{{ old('instructions') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Proceed to Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
