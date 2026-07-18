<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FeastFlow - Manage Menu</title>
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
        .food-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }
        .food-thumb-placeholder {
            width: 50px;
            height: 50px;
            background: #f8d7d7;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
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
            <a href="/menu" class="btn btn-outline-light btn-sm">View Menu</a>
            <a href="/logout" class="btn btn-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container-fluid px-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Add New Item --}}
        <div class="col-md-4">
            <div class="card p-4">
                <h5 class="mb-4 text-danger">
                    <i class="bi bi-plus-circle"></i> Add New Food Item
                </h5>
                <form method="POST" action="/admin/menu">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Food Name</label>
                        <input type="text" name="name" class="form-control"
                               placeholder="e.g. Mutton Biryani" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Price (TK)</label>
                        <input type="number" name="price" class="form-control"
                               placeholder="e.g. 250" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="2"
                                  placeholder="Short description..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Image URL</label>
                        <input type="text" name="image" class="form-control"
                               placeholder="https://images.unsplash.com/...">
                        <small class="text-muted mt-1 d-block">
                            👉 <a href="https://unsplash.com/s/photos/food"
                                  target="_blank">Collect image URL from Unsplash  </a>
                        </small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-plus"></i> Add Item
                    </button>
                </form>
            </div>
        </div>

        {{-- Menu Items List --}}
        <div class="col-md-8">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="text-danger mb-0">
                        <i class="bi bi-list-ul"></i> All Menu Items
                    </h5>
                    <span class="badge bg-danger">
                        {{ count($menu) }} Items
                    </span>
                </div>

                <table class="table table-hover align-middle">
                    <thead class="table-danger">
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menu as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if($item->image)
                                    <img src="{{ $item->image }}"
                                         class="food-thumb" alt="{{ $item->name }}">
                                @else
                                    <div class="food-thumb-placeholder">🍽️</div>
                                @endif
                            </td>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>{{ $item->category_name }}</td>
                            <td>{{ $item->price }} TK</td>
                            <td>
                                @if($item->status == 'available')
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-secondary">Unavailable</span>
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
                                <form method="POST"
                                      action="/admin/menu/delete/{{ $item->id }}"
                                      onsubmit="return confirm('Delete {{ $item->name }}?')"
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
                                        <h5 class="modal-title">Edit: {{ $item->name }}</h5>
                                        <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST"
                                          action="/admin/menu/update/{{ $item->id }}">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Category</label>
                                                <select name="category_id" class="form-select" required>
                                                    @foreach($categories as $cat)
                                                        <option value="{{ $cat->id }}"
                                                            {{ $cat->id == $item->category_id ? 'selected' : '' }}>
                                                            {{ $cat->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Food Name</label>
                                                <input type="text" name="name"
                                                       class="form-control"
                                                       value="{{ $item->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Price (TK)</label>
                                                <input type="number" name="price"
                                                       class="form-control"
                                                       value="{{ $item->price }}"
                                                       step="0.01" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Description</label>
                                                <textarea name="description"
                                                          class="form-control" rows="2">{{ $item->description }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Image URL</label>
                                                <input type="text" name="image"
                                                       class="form-control"
                                                       value="{{ $item->image }}"
                                                       placeholder="https://images.unsplash.com/...">
                                                @if($item->image)
                                                    <img src="{{ $item->image }}"
                                                         class="mt-2 rounded"
                                                         style="width:100%; height:120px; object-fit:cover;">
                                                @endif
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="available"
                                                        {{ $item->status=='available'?'selected':'' }}>
                                                        Available
                                                    </option>
                                                    <option value="unavailable"
                                                        {{ $item->status=='unavailable'?'selected':'' }}>
                                                        Unavailable
                                                    </option>
                                                </select>
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