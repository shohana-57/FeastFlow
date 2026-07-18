<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FeastFlow - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f5f5f5; }
        .navbar { background: linear-gradient(135deg, #c0392b, #e74c3c) !important; }
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark mb-4">
    <div class="container">
        <span class="navbar-brand fw-bold fs-3">🍽️ FeastFlow</span>
        <div>
            <span class="text-white me-3">
                👤 {{ session('user_name') }} 
                ({{ strtoupper(session('user_role')) }})
            </span>
            <a href="/menu" class="btn btn-outline-light btn-sm me-2">Menu</a>
            <a href="/logout" class="btn btn-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <h3 class="mb-4">📊 Admin Dashboard</h3>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card text-white" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                <div class="card-body text-center p-4">
                    <i class="bi bi-bag-check fs-1"></i>
                    <h2 class="mt-2">{{ $total_orders }}</h2>
                    <p class="mb-0">Total Orders</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card text-white" style="background: linear-gradient(135deg, #27ae60, #2ecc71);">
                <div class="card-body text-center p-4">
                    <i class="bi bi-currency-dollar fs-1"></i>
                    <h2 class="mt-2">{{ $revenue }} TK</h2>
                    <p class="mb-0">Today Revenue</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card text-white" style="background: linear-gradient(135deg, #2980b9, #3498db);">
                <div class="card-body text-center p-4">
                    <i class="bi bi-table fs-1"></i>
                    <h2 class="mt-2">{{ $free_tables }}</h2>
                    <p class="mb-0">Free Tables</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card text-white" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                <div class="card-body text-center p-4">
                    <i class="bi bi-exclamation-triangle fs-1"></i>
                    <h2 class="mt-2">{{ $low_stock }}</h2>
                    <p class="mb-0">Low Stock Items</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Popular Items --}}
    <div class="card stat-card">
        <div class="card-body">
            <h5 class="mb-3">🔥 Popular Items</h5>
            <table class="table table-hover">
                <thead class="table-danger">
                    <tr>
                        <th>Item Name</th>
                        <th>Total Orders</th>
                        <th>Total Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($popular as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->order_count }}</td>
                        <td>{{ $item->total_quantity }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

 <div class="row g-3 mb-4">
    <div class="col-md-6">
        <a href="/admin/menu" class="btn btn-danger w-100 py-2">
            <i class="bi bi-list-ul"></i> Manage Menu Items
        </a>
    </div>
    <div class="col-md-6">
        <a href="/inventory" class="btn btn-outline-danger w-100 py-2">
            <i class="bi bi-box-seam"></i> Manage Inventory
        </a>
    </div>
   </div>
</div>

</body>
</html>
