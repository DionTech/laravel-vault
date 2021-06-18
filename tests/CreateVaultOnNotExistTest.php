<?php


namespace DionTech\Vault\Tests;


use DionTech\Vault\Models\Vault as VaultModel;
use DionTech\Vault\Support\Tests\User;
use DionTech\Vault\Models\Secret;
use DionTech\Vault\Support\Facades\Vault;
use DionTech\Vault\Support\Contracts\VaultServiceContract;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateVaultOnNotExistTest extends TestCase
{
    public function test_creating_on_not_exists_when_adding_secret()
    {
        //when narg is a string, it will becreated
        Vault::open("test_vault");

        $this->assertEquals(1,VaultModel::count());

        //when opening again, it must NOT be created again
        Vault::open("test_vault");

        $this->assertEquals(1,VaultModel::count());
    }

    public function test_get_secret()
    {
        Vault::open("facade")->add("facaded_secret", "simple value");

        $this->assertEquals(1, VaultModel::count());

        $this->assertEquals("simple value",Vault::open("facade")->get("facaded_secret"));
    }

    public function test_overwrite_secret()
    {
        Vault::open("facade")->add("facaded_secret", "simple value");

        $this->assertEquals(1, VaultModel::count());

        Vault::open("facade")->overwrite("facaded_secret", "simple value overwritten");

        $this->assertEquals(1, VaultModel::count());

        $this->assertEquals("simple value overwritten",Vault::open("facade")->get("facaded_secret"));
    }


    public function test_get_secret_with_own_key()
    {
        Vault::open("facade")->useKey("my password")->add("facaded_secret", "simple value");

        $this->assertEquals(1, VaultModel::count());

        $this->expectErrorMessage("The MAC is invalid");
        Vault::open("facade")->useKey(env("APP_KEY"))->get("facaded_secret");

        $this->assertEquals("simple value",Vault::open("facade")->useKey("my password")->get("facaded_secret"));
    }

    public function test_get_secret_with_own_key_when_not_exists()
    {
        Vault::open("facade")->useKey("my password")->add("facaded_secret", "simple value");

        $this->assertEquals(1, VaultModel::count());

        $this->expectErrorMessage("The MAC is invalid");
        Vault::open("facade")->useKey(env("APP_KEY"))->get("facaded_secret");

        $this->assertEquals("simple value",Vault::open("facade")->useKey("my password")->get("facaded_secret"));
    }

    public function test_by_user_when_not_exists()
    {
        $user = User::create([
            'name' => 'Daniel Koch',
            'email' => 'daniel.koch@diontech.de'
        ]);

        $this->assertEquals('personal', Vault::setContext($user)->open("personal")->getVault()->name);

        $this->assertDatabaseHas('vaults', [
            'vaultable_id' => $user->id,
            'vaultable_type' => get_class($user),
            'name' => 'personal'
        ]);

        Vault::setContext($user)->open("personal")->add('bad_password_storing_itself', '12345678');

        $this->assertEquals(1, $user->vaults()->first()->secrets()->count());

        $this->assertDatabaseHas('secrets', [
            'vault_id' => $user->vaults()->first()->id,
            'alias' => 'bad_password_storing_itself'
        ]);

        $this->assertDatabaseMissing('secrets',[
            'value' => '12345678'
        ]);

        $this->assertEquals('12345678', Vault::setContext($user)->open("personal")->get('bad_password_storing_itself'));
    }

}
