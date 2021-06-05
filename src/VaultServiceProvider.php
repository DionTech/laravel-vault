<?php


namespace DionTech\Vault;


use DionTech\Vault\Services\KeyService;
use DionTech\Vault\Services\VaultService;
use DionTech\Vault\Support\Contracts\KeyServiceContract;
use DionTech\Vault\Support\Contracts\VaultServiceContract;
use DionTech\Vault\Support\Facades\Vault;
use Illuminate\Support\ServiceProvider;

class VaultServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/vault.php' => config_path('vault.php'),
        ], 'config');

        $this->app->bind(VaultServiceContract::class, VaultService::class);

        $this->app->bind(KeyServiceContract::class, function() {
            return new KeyService();
        });
    }

    public function register()
    {
        $this->app->bind('vault', function($app) {
            return new Vault();
        });
    }

}
