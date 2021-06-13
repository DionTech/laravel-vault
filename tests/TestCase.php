<?php

namespace DionTech\Vault\Tests;

use DionTech\Vault\VaultServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            VaultServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        include_once __DIR__ . '/../database/migrations/create_users_table.php.stub';
        include_once __DIR__ . '/../database/migrations/2021_06_05_173734_create_vault.php';
        include_once __DIR__ . '/../database/migrations/2021_06_05_173741_create_secret.php';

        (new \CreateUsersTable)->up();
        (new \CreateSecret())->up();
        (new \CreateVault())->up();
    }
}
