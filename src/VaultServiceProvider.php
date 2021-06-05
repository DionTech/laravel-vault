<?php


namespace DionTech\Vault;


use Illuminate\Support\ServiceProvider;

class VaultServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/vault.php' => config_path('vault.php'),
        ], 'config');
    }

    public function register()
    {

    }

}
