<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Fournisseur;
use App\Models\Livreur;
use App\Models\Vehicule;
use App\Models\Produit;
use App\Models\Objectif;
use App\Models\User;
use App\Policies\ClientPolicy;
use App\Policies\CommandePolicy;
use App\Policies\LivreurPolicy;
use App\Policies\VehiculePolicy;
use App\Policies\FournisseurPolicy;
use App\Policies\ObjectifPolicy;
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
        Vehicule::class => VehiculePolicy::class,
        Livreur::class => LivreurPolicy::class,
        Objectif::class => ObjectifPolicy::class,
        Commande::class => CommandePolicy::class,
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

        Gate::define('view_all_livreurs', 'App\Policies\LivreurPolicy@viewAllLivreurs');
        Gate::define('create_livreurs', 'App\Policies\LivreurPolicy@createLivreur');
        Gate::define('view_livreurs', 'App\Policies\LivreurPolicy@viewLivreur');
        Gate::define('update_livreurs', 'App\Policies\LivreurPolicy@editLivreur');
        Gate::define('delete_livreurs', 'App\Policies\LivreurPolicy@deleteLivreur');


        Gate::define('view_all_vehicules', 'App\Policies\VehiculePolicy@viewAllVehicules');
        Gate::define('create_vehicules', 'App\Policies\VehiculePolicy@createVehicule');
        Gate::define('view_vehicules', 'App\Policies\VehiculePolicy@viewVehicule');
        Gate::define('update_vehicules', 'App\Policies\VehiculePolicy@editVehicule');
        Gate::define('delete_vehicules', 'App\Policies\VehiculePolicy@deleteVehicule');

        Gate::define('view_all_objectifs', 'App\Policies\ObjectifPolicy@viewAllObjectifs');
        Gate::define('create_objectifs', 'App\Policies\ObjectifPolicy@createObjectif');
        Gate::define('view_objectifs', 'App\Policies\ObjectifPolicy@viewObjectif');
        Gate::define('update_objectifs', 'App\Policies\ObjectifPolicy@editObjectif');
        Gate::define('delete_objectifs', 'App\Policies\ObjectifPolicy@deleteObjectif');

        Gate::define('view_all_commandes', 'App\Policies\CommandePolicy@viewAllCommandes');
        Gate::define('create_commandes', 'App\Policies\CommandePolicy@createCommande');
        Gate::define('view_commandes', 'App\Policies\CommandePolicy@viewCommande');
        Gate::define('update_commandes', 'App\Policies\CommandePolicy@editCommande');
        Gate::define('delete_commandes', 'App\Policies\CommandePolicy@deleteCommande');


        Gate::define('view_all_users', 'App\Policies\UserPolicy@viewAllUsers');
        Gate::define('create_user', 'App\Policies\UserPolicy@createUser');
        Gate::define('view_user', 'App\Policies\UserPolicy@viewUser');
        Gate::define('edit_user', 'App\Policies\UserPolicy@editUser');
        Gate::define('delete_user', 'App\Policies\UserPolicy@deleteUser');
    }
}
