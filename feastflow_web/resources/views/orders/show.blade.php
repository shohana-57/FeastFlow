<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details | FeastFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-danger mb-4">
    <div class="container-fluid px-4">
        <a class="navbar-brand text-white" href="/orders">🍽️ FeastFlow</a>
        <div>
            <a href="/orders" class="btn btn-outline-light btn-sm">Back</a>
            <a href="/logout" class="btn btn-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h4 class="card-title text-danger">Order #{{ $order->id }}</h4>
                            <p class="text-muted mb-1">Customer: {{ $order->customer_name }}</p>
                            <p class="text-muted mb-0">Table: {{ $order->table_id ? 'Table ' . $order->table_id : 'Takeaway' }}</p>
                        </div>
                        <span class="badge bg-warning text-dark">{{ ucfirst($order->status) }}</span>
                    </div>

                    <h6 class="mb-3">Items</h6>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->unit_price, 2) }} TK</td>
                                    <td>{{ number_format($item->item_subtotal, 2) }} TK</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($payment)
                        <div class="mb-4">
                            <h6>Payment</h6>
                            <p class="mb-1"><strong>Method:</strong> {{ ucfirst($payment->method) }}</p>
                            <p class="mb-1"><strong>Total Paid:</strong> {{ number_format($payment->total, 2) }} TK</p>
                            <p class="mb-0"><strong>Paid At:</strong> {{ $payment->paid_at }}</p>
                        </div>
                    @endif

                    @if(session('user_role') == 'waiter' || session('user_role') == 'admin' || session('user_role') == 'manager')
                        <div class="card bg-light p-3">
                            <h6 class="mb-3">Update Order Status</h6>
                            <form method="POST" action="/orders/{{ $order->id }}/status">
                                @csrf
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-6">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select" required>
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                            <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                            <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-danger w-100">Update Status</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
