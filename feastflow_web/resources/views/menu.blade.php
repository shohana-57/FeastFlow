<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FeastFlow - Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-danger mb-4">
        <div class="container">
            <span class="navbar-brand fs-3 fw-bold">🍽️ FeastFlow</span>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4">Our Menu</h2>

        <div class="row">
            @foreach($menu as $item)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->name }}</h5>
                            <p class="card-text text-muted">{{ $item->category }}</p>
                            <p class="fw-bold text-danger fs-5">{{ $item->price }} TK</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</body>
</html>
