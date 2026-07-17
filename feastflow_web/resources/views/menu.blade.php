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
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .card-img-placeholder {
            background: linear-gradient(135deg, #f8d7d7, #fce8e8);
            height: 160px;
            border-radius: 12px 12px 0 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
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
        }

        .filter-title {
            color: #c0392b;
            font-weight: bold;
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<!-- {{-- Navbar --}} -->
<nav class="navbar navbar-dark mb-4">
    <div class="container">
        <span class="navbar-brand fw-bold fs-3">🍽️ FeastFlow</span>
        <div>
            <a href="/menu" class="btn btn-outline-light btn-sm me-2">Menu</a>
            <a href="/login" class="btn btn-light btn-sm">Login</a>
        </div>
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="row">

        <!-- Left Sidebar Filter -->
        <div class="col-md-3 mb-4">
            <div class="sidebar-card">
                <p class="filter-title"><i class="bi bi-funnel"></i> Filter Menu</p>

                <!-- Category Filter -->
                <p class="fw-bold text-muted small mb-2">CATEGORY</p>
                <div class="d-flex flex-column gap-2 mb-4">
                    <a href="/menu" class="btn btn-outline-danger btn-sm text-start">
                        <i class="bi bi-grid"></i> All Items
                    </a>
                    <a href="/menu?category=Rice and Biryani" class="btn btn-outline-secondary btn-sm text-start">
                        🍚 Rice and Biryani
                    </a>
                    <a href="/menu?category=Burger and Sandwich" class="btn btn-outline-secondary btn-sm text-start">
                        🍔 Burger and Sandwich
                    </a>
                    <a href="/menu?category=Drinks" class="btn btn-outline-secondary btn-sm text-start">
                        🥤 Drinks
                    </a>
                    <a href="/menu?category=Desserts" class="btn btn-outline-secondary btn-sm text-start">
                        🍰 Desserts
                    </a>
                    <a href="/menu?category=Soup and Salad" class="btn btn-outline-secondary btn-sm text-start">
                        🥗 Soup and Salad
                    </a>
                </div>

                <!-- Price Filter -->
                <p class="fw-bold text-muted small mb-2">PRICE RANGE</p>
                <form method="GET" action="/menu">
                    <div class="d-flex gap-2 mb-2">
                        <input type="number" name="min_price" class="form-control form-control-sm"
                               placeholder="Min" value="{{ request('min_price') }}">
                        <input type="number" name="max_price" class="form-control form-control-sm"
                               placeholder="Max" value="{{ request('max_price') }}">
                    </div>
                    <button type="submit" class="btn btn-danger btn-sm w-100">Apply Filter</button>
                </form>
            </div>
        </div>

        <!-- {{-- Main Content --}} -->
        <div class="col-md-9">

            <!-- Search and Sort -->
            <div class="search-section">
                <form method="GET" action="/menu">
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
                                <option value="price_asc" {{ request('sort')=='price_asc'?'selected':'' }}>
                                    Price: Low to High
                                </option>
                                <option value="price_desc" {{ request('sort')=='price_desc'?'selected':'' }}>
                                    Price: High to Low
                                </option>
                                <option value="name" {{ request('sort')=='name'?'selected':'' }}>
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

            <!-- {{-- Results Count --}} -->
            <p class="text-muted mb-3">
                Showing <strong>{{ count($menu) }}</strong> items
            </p>

            <!-- {{-- Menu Cards --}} -->
            <div class="row g-4">
                @forelse($menu as $item)
                    <div class="col-md-4">
                        <div class="card menu-card">
                          @php
                             $images = [
                                  'Chicken Biryani'  => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=400&q=80',
                                  'Beef Tehari'      => 'https://images.unsplash.com/photo-1596797038530-2c107229654b?w=400&q=80',
                                 'Chicken Burger'   => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&q=80',
                                  'Beef Burger'      => 'https://images.unsplash.com/photo-1586816001966-79b736744398?w=400&q=80',
                                  'Coca Cola'        => 'https://images.unsplash.com/photo-1554866585-cd94860890b7?w=400&q=80',
                                  'Fresh Lemonade'   => 'https://images.unsplash.com/photo-1621263764928-df1444c5e859?w=400&q=80',
                                  'Chocolate Cake'   => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400&q=80',
                                 'Chicken Soup'     => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=400&q=80',
                                ];
                              $img = $images[$item->name] ?? 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&q=80';
                             @endphp

                           <img src="{{ $img }}"
                           alt="{{ $item->name }}"
                          style="width:100%; height:180px; object-fit:cover; border-radius:12px 12px 0 0;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">{{ $item->name }}</h5>
                                </div>
                                <span class="category-badge mb-2 d-inline-block">
                                    {{ $item->category }}
                                </span>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="price-badge">{{ $item->price }} TK</span>
                                    <span class="badge bg-success">Available</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
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