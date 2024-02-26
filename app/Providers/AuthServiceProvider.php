<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\User;
use App\Policies\ClientPolicy;
use App\Policies\CommandePolicy;
use App\Policies\FournisseurPolicy;
use App\Policies\ProductPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Produit::class => ProductPolicy::class,
        Commande::class => CommandePolicy::class,
        Client::class => ClientPolicy::class,
        Fournisseur::class => FournisseurPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('view_all_products', 'App\Policies\ProductPolicy@viewAllProducts');
        Gate::define('create_product', 'App\Policies\ProductPolicy@createProduct');
        Gate::define('view_product', 'App\Policies\ProductPolicy@viewProduct');
        Gate::define('edit_product', 'App\Policies\ProductPolicy@editProduct');
        Gate::define('delete_product', 'App\Policies\ProductPolicy@deleteProduct');

        Gate::define('view_all_clients', 'App\Policies\ClientPolicy@viewAllClients');
        Gate::define('create_clients', 'App\Policies\ClientPolicy@createClient');
        Gate::define('view_clients', 'App\Policies\ClientPolicy@viewClients');
        Gate::define('update_clients', 'App\Policies\ClientPolicy@editClient');
        Gate::define('delete_clients', 'App\Policies\ClientPolicy@deleteClient');

        Gate::define('view_all_fournisseurs', 'App\Policies\FournisseurPolicy@viewAllFournisseurs');
        Gate::define('create_fournisseurs', 'App\Policies\FournisseurPolicy@createFournisseur');
        Gate::define('view_fournisseurs', 'App\Policies\FournisseurPolicy@viewFournisseur');
        Gate::define('update_fournisseurs', 'App\Policies\FournisseurPolicy@editFournisseur');
        Gate::define('delete_fournisseurs', 'App\Policies\FournisseurPolicy@deleteFournisseur');


        Gate::define('view_all_users', 'App\Policies\UserPolicy@viewAllUsers');
        Gate::define('create_user', 'App\Policies\UserPolicy@createUser');
        Gate::define('view_user', 'App\Policies\UserPolicy@viewUser');
        Gate::define('edit_user', 'App\Policies\UserPolicy@editUser');
        Gate::define('delete_user', 'App\Policies\UserPolicy@deleteUser');
    }
}
