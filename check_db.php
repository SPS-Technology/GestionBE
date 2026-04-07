<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

use App\Models\User;
use App\Models\Client;
use App\Models\Role;

echo "=== DATABASE SUMMARY ===\n";
echo "Total Users: " . User::count() . "\n";
echo "Total Clients: " . Client::count() . "\n";
echo "Total Roles: " . Role::count() . "\n\n";

echo "=== USERS ===\n";
User::all()->each(function($u) {
    echo "ID: {$u->id}, Name: {$u->name}, Email: {$u->email}\n";
});

echo "\n=== ROLES ===\n";
Role::all()->each(function($r) {
    echo "ID: {$r->id}, Name: {$r->name}\n";
});

echo "\n=== FIRST 5 CLIENTS ===\n";
Client::limit(5)->get()->each(function($c) {
    echo "ID: {$c->id}, Name: {$c->name}\n";
});
?>
