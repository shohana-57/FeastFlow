<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FeastFlow - Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f5f5f5; }
        .navbar { background: linear-gradient(135deg, #c0392b, #e74c3c) !important; }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }
        .low-stock { background: #fff3cd !important; }
        .critical-stock { background: #f8d7da !important; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark mb-4">
    <div class="container-fluid px-4">
        <span class="navbar-brand fw-bold fs-3">🍽️ FeastFlow</span>
        <div class="d-flex align-items-center gap-2">
            <span class="text-white">
                👤 {{ session('user_name') }}
                <span class="badge bg-light text-danger ms-1">
                    {{ strtoupper(session('user_role')) }}
                </span>
            </span>
            <a href="/dashboard" class="btn btn-outline-light btn-sm">Dashboard</a>
            <a href="/logout" class="btn btn-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container-fluid px-4">

    <!-- {{-- Success Message --}} -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Add New Item Form --}}
        <div class="col-md-4">
            <div class="card p-4">
                <h5 class="mb-4 text-danger">
                    <i class="bi bi-plus-circle"></i> Add New Ingredient
                </h5>
                <form method="POST" action="/inventory">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ingredient Name</label>
                        <input type="text" name="ingredient_name" 
                               class="form-control" required
                               placeholder="e.g. Tomato">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Quantity</label>
                        <input type="number" name="quantity" 
                               class="form-control" required
                               placeholder="e.g. 10" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Unit</label>
                        <select name="unit" class="form-select" required>
                            <option value="kg">kg</option>
                            <option value="g">g</option>
                            <option value="liter">liter</option>
                            <option value="pcs">pcs</option>
                            <option value="dozen">dozen</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Minimum Stock Alert</label>
                        <input type="number" name="min_stock" 
                               class="form-control" required
                               placeholder="e.g. 5" step="0.01">
                    </div>
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-plus"></i> Add Ingredient
                    </button>
                </form>
            </div>
        </div>

        {{-- Inventory List --}}
        <div class="col-md-8">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="text-danger mb-0">
                        <i class="bi bi-box-seam"></i> Inventory Stock
                    </h5>
                    <span class="badge bg-danger">
                        {{ count($inventory) }} Items
                    </span>
                </div>

                <table class="table table-hover">
                    <thead class="table-danger">
                        <tr>
                            <th>#</th>
                            <th>Ingredient</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Min Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inventory as $index => $item)
                        @php
                            $rowClass = '';
                            if($item->quantity <= 0) $rowClass = 'critical-stock';
                            elseif($item->quantity < $item->min_stock) $rowClass = 'low-stock';
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $item->ingredient_name }}</strong></td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->unit }}</td>
                            <td>{{ $item->min_stock }}</td>
                            <td>
                                @if($item->quantity <= 0)
                                    <span class="badge bg-danger">Critical!</span>
                                @elseif($item->quantity < $item->min_stock)
                                    <span class="badge bg-warning text-dark">Low Stock</span>
                                @else
                                    <span class="badge bg-success">OK</span>
                                @endif
                            </td>
                            <td>
                                {{-- Edit Button --}}
                                <button class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $item->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                {{-- Delete Button --}}
                                <form method="POST" action="/inventory/delete/{{ $item->id }}"
                                   onsubmit="return confirm('Delete this item?')"
                                   style="display:inline;">
                                           @csrf
                                         <button type="submit" class="btn btn-sm btn-outline-danger">
                                              <i class="bi bi-trash"></i>
                                         </button>
                                </form>
                            </td>
                        </tr>

                        {{-- Edit Modal --}}
                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">
                                            Edit: {{ $item->ingredient_name }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" 
                                                data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" 
                                          action="/inventory/update/{{ $item->id }}">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Ingredient Name</label>
                                                <input type="text" name="ingredient_name"
                                                       class="form-control"
                                                       value="{{ $item->ingredient_name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Quantity</label>
                                                <input type="number" name="quantity"
                                                       class="form-control"
                                                       value="{{ $item->quantity }}"
                                                       required step="0.01">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Unit</label>
                                                <select name="unit" class="form-select">
                                                    <option value="kg" {{ $item->unit=='kg'?'selected':'' }}>kg</option>
                                                    <option value="g" {{ $item->unit=='g'?'selected':'' }}>g</option>
                                                    <option value="liter" {{ $item->unit=='liter'?'selected':'' }}>liter</option>
                                                    <option value="pcs" {{ $item->unit=='pcs'?'selected':'' }}>pcs</option>
                                                    <option value="dozen" {{ $item->unit=='dozen'?'selected':'' }}>dozen</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Min Stock Alert</label>
                                                <input type="number" name="min_stock"
                                                       class="form-control"
                                                       value="{{ $item->min_stock }}"
                                                       required step="0.01">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">
                                                Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
