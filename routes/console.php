<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('merch:status', function () {
    $this->table(
        ['Metric', 'Count'],
        [
            ['Users', User::count()],
            ['Categories', Category::count()],
            ['Active categories', Category::where('is_active', true)->count()],
            ['Products', Product::count()],
            ['Active products', Product::where('is_active', true)->count()],
            ['Featured products', Product::where('is_featured', true)->count()],
        ],
    );
})->purpose('Show whether the merch database has the minimum production data');

Artisan::command('merch:admin {email?} {password?} {--name=}', function (?string $email = null, ?string $password = null) {
    if ($email !== null) {
        putenv("ADMIN_EMAIL={$email}");
        $_ENV['ADMIN_EMAIL'] = $email;
        $_SERVER['ADMIN_EMAIL'] = $email;
    }

    if ($password !== null) {
        putenv("ADMIN_PASSWORD={$password}");
        $_ENV['ADMIN_PASSWORD'] = $password;
        $_SERVER['ADMIN_PASSWORD'] = $password;
    }

    if ($this->option('name')) {
        $name = (string) $this->option('name');
        putenv("ADMIN_NAME={$name}");
        $_ENV['ADMIN_NAME'] = $name;
        $_SERVER['ADMIN_NAME'] = $name;
    }

    $this->call('db:seed', ['--class' => AdminUserSeeder::class, '--force' => true]);

    $createdEmail = $email ?? env('ADMIN_EMAIL', 'admin@example.com');

    $this->info("Admin listo: {$createdEmail}");
    $this->line('Ahora puedes iniciar sesion en /admin con ese correo y la contrasena indicada.');
})->purpose('Create or update the admin user for Filament access');

Artisan::command('merch:catalog', function () {
    $this->call('db:seed', ['--class' => CatalogSeeder::class, '--force' => true]);
    $this->info('Catalogo base creado o actualizado.');
})->purpose('Seed a minimal active catalog for storefront validation');
