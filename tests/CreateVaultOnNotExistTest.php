<?php


namespace DionTech\Vault\Tests;


use DionTech\Vault\Support\Tests\User;
use DionTech\Vault\Models\Secret;
use DionTech\Vault\Models\Vault;
use DionTech\Vault\Support\Contracts\VaultServiceContract;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateVaultOnNotExistTest extends TestCase
{
    public function test_creating_on_not_exists_when_adding_secret()
    {
        \DionTech\Vault\Support\Facades\Vault::open("test_vault");

        $this->assertEquals(1,Vault::count());
    }

}
