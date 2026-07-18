<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$results = Illuminate\Support\Facades\DB::select("SELECT constraint_name, constraint_type, index_name, search_condition, r_constraint_name FROM user_constraints WHERE table_name='INVENTORY'");
var_dump($results);
$cols = Illuminate\Support\Facades\DB::select("SELECT constraint_name, column_name FROM user_cons_columns WHERE table_name='INVENTORY' ORDER BY position");
var_dump($cols);
