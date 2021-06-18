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
        //when narg is a string, it will becreated
        \DionTech\Vault\Support\Facades\Vault::open("test_vault");

        $this->assertEquals(1,Vault::count());

        //when opening again, it must NOT be created again
        \DionTech\Vault\Support\Facades\Vault::open("test_vault");

        $this->assertEquals(1,Vault::count());
    }

}
