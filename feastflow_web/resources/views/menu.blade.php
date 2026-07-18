<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FeastFlow - Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f5f5f5; }

        .navbar { background: linear-gradient(135deg, #c0392b, #e74c3c) !important; }

        .search-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .menu-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
            cursor: pointer;
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .food-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
        }

        .food-img-placeholder {
            background: linear-gradient(135deg, #f8d7d7, #fce8e8);
            height: 180px;
            border-radius: 12px 12px 0 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
        }

        .price-badge {
            background: #e74c3c;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: bold;
        }

        .category-badge {
            background: #fff3cd;
            color: #856404;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 13px;
        }

        .sidebar-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 20px;
            position: sticky;
            top: 20px;
        }

        .filter-title {
            color: #c0392b;
            font-weight: bold;
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .btn-order {
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 6px 16px;
            font-size: 14px;
            transition: background 0.2s;
        }
        .btn-order:hover { background: #c0392b; color: white; }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="navbar navbar-dark mb-4">
    <div class="container-fluid px-4">
        <span class="navbar-brand fw-bold fs-3">🍽️ FeastFlow</span>
        <div class="d-flex align-items-center gap-2">
            @if(session('user_name'))
                <span class="text-white">
                    👤 {{ session('user_name') }}
                    <span class="badge bg-light text-danger ms-1">
                        {{ strtoupper(session('user_role')) }}
                    </span>
                </span>
                <a href="/orders" class="btn btn-outline-light btn-sm">My Orders</a>
                <a href="/booking/create" class="btn btn-outline-light btn-sm">Book Table</a>
                @if(session('user_role') == 'admin' || session('user_role') == 'manager')
                    <a href="/dashboard" class="btn btn-outline-light btn-sm">Dashboard</a>
                @endif
                <a href="/logout" class="btn btn-light btn-sm">Logout</a>
            @else
                <a href="/login" class="btn btn-light btn-sm">Login</a>
            @endif
        </div>
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="row g-3">

        {{-- Left Sidebar Filter --}}
        <div class="col-md-3 mb-4">
            <div class="sidebar-card">
                <p class="filter-title"><i class="bi bi-funnel"></i> Filter Menu</p>

                {{-- Category Filter --}}
                <p class="fw-bold text-muted small mb-2">CATEGORY</p>
                <div class="d-flex flex-column gap-2 mb-4">
                    <a href="/menu" class="btn btn-outline-danger btn-sm text-start
                        {{ !request('category') ? 'active' : '' }}">
                        <i class="bi bi-grid"></i> All Items
                    </a>
                    <a href="/menu?category=Rice and Biryani"
                       class="btn btn-outline-secondary btn-sm text-start
                       {{ request('category')=='Rice and Biryani' ? 'active' : '' }}">
                        🍚 Rice and Biryani
                    </a>
                    <a href="/menu?category=Burger and Sandwich"
                       class="btn btn-outline-secondary btn-sm text-start
                       {{ request('category')=='Burger and Sandwich' ? 'active' : '' }}">
                        🍔 Burger and Sandwich
                    </a>
                    <a href="/menu?category=Drinks"
                       class="btn btn-outline-secondary btn-sm text-start
                       {{ request('category')=='Drinks' ? 'active' : '' }}">
                        🥤 Drinks
                    </a>
                    <a href="/menu?category=Desserts"
                       class="btn btn-outline-secondary btn-sm text-start
                       {{ request('category')=='Desserts' ? 'active' : '' }}">
                        🍰 Desserts
                    </a>
                    <a href="/menu?category=Soup and Salad"
                       class="btn btn-outline-secondary btn-sm text-start
                       {{ request('category')=='Soup and Salad' ? 'active' : '' }}">
                        🥗 Soup and Salad
                    </a>
                </div>

                {{-- Price Filter --}}
                <p class="fw-bold text-muted small mb-2">PRICE RANGE</p>
                <form method="GET" action="/menu">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <div class="d-flex gap-2 mb-2">
                        <input type="number" name="min_price" class="form-control form-control-sm"
                               placeholder="Min" value="{{ request('min_price') }}">
                        <input type="number" name="max_price" class="form-control form-control-sm"
                               placeholder="Max" value="{{ request('max_price') }}">
                    </div>
                    <button type="submit" class="btn btn-danger btn-sm w-100">
                        Apply Filter
                    </button>
                </form>

                @if(request()->anyFilled(['search','category','min_price','max_price','sort']))
                    <a href="/menu" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                        <i class="bi bi-x-circle"></i> Clear Filters
                    </a>
                @endif
            </div>
        </div>

        {{-- Main Content --}}
        <div class="col-md-9">

            {{-- Search and Sort --}}
            <div class="search-section">
                <form method="GET" action="/menu">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="row g-2 align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-danger text-white">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="search" class="form-control"
                                       placeholder="Search food items..."
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select name="sort" class="form-select">
                                <option value="">Sort By</option>
                                <option value="price_asc"
                                    {{ request('sort')=='price_asc'?'selected':'' }}>
                                    Price: Low to High
                                </option>
                                <option value="price_desc"
                                    {{ request('sort')=='price_desc'?'selected':'' }}>
                                    Price: High to Low
                                </option>
                                <option value="name"
                                    {{ request('sort')=='name'?'selected':'' }}>
                                    Name A-Z
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-danger w-100">Search</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Results Count --}}
            <p class="text-muted mb-3">
                Showing <strong>{{ count($menu) }}</strong> items
                @if(request('search'))
                    for "<strong>{{ request('search') }}</strong>"
                @endif
                @if(request('category'))
                    in <strong>{{ request('category') }}</strong>
                @endif
            </p>

            {{-- Menu Cards --}}
            <div class="row g-4">
                @forelse($menu as $item)
                    <div class="col-md-4">
                        <div class="card menu-card">
                            {{-- Image --}}
                            @if($item->image)
                                <img src="{{ $item->image }}"
                                     class="food-img" alt="{{ $item->name }}">
                            @else
                                <div class="food-img-placeholder">🍽️</div>
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $item->name }}</h5>
                                <span class="category-badge mb-2 d-inline-block">
                                    {{ $item->category }}
                                </span>
                                @if($item->description ?? false)
                                    <p class="text-muted small mt-1 mb-2">
                                        {{ $item->description }}
                                    </p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="price-badge">{{ $item->price }} TK</span>
                                    @if(session('user_id'))
                                        <a href="/order/create?item_id={{ $item->id }}"
                                           class="btn-order">
                                            <i class="bi bi-cart-plus"></i> Order
                                        </a>
                                    @else
                                        <a href="/login" class="btn-order">
                                            Login to Order
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            <i class="bi bi-search"></i>
                            No items found! Try different filters.
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>