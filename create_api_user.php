<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use App\Models\User;

$role = Role::firstOrCreate(['name' => 'user']);

$user = User::firstOrCreate(
    ['email' => 'apiuser@example.com'],
    ['name' => 'API User', 'password' => 'password123']
);

$user->roles()->sync([$role->id]);

echo "USER CREATED: email=apiuser@example.com password=password123\n";
