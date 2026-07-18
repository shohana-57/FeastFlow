<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();
$rows = Illuminate\Support\Facades\DB::select("SELECT column_name, nullable FROM user_tab_columns WHERE table_name = 'ORDERS'");
foreach ($rows as $r) {
    echo $r->COLUMN_NAME . ':' . $r->NULLABLE . "\n";
}
