<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();
$rows = Illuminate\Support\Facades\DB::select("SELECT column_name, nullable, data_type FROM user_tab_columns WHERE table_name = 'ORDERS' ORDER BY column_id");
var_dump($rows);
foreach ($rows as $r) {
    echo 'props: ' . implode(',', array_keys(get_object_vars($r))) . "\n";
    foreach (get_object_vars($r) as $k => $v) {
        echo "$k => $v\n";
    }
    echo "---\n";
}
