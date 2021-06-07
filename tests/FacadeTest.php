<?php


namespace DionTech\Vault\Tests;


use DionTech\Vault\Models\Vault as VaultModel;
use DionTech\Vault\Support\Facades\Vault;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FacadeTest extends TestCase
{
    use DatabaseMigrations;

    public function test_get_secret()
    {
        $vault = VaultModel::create([
            'name' => 'facade'
        ]);

        Vault::open($vault)->add("facaded_secret", "simple value");

        $this->assertEquals(1, VaultModel::count());

        $this->assertEquals("simple value",Vault::open($vault)->get("facaded_secret"));
    }

    public function test_overwrite_secret()
    {
        $vault = VaultModel::create([
            'name' => 'facade'
        ]);

        Vault::open($vault)->add("facaded_secret", "simple value");

        $this->assertEquals(1, VaultModel::count());

        Vault::open($vault)->overwrite("facaded_secret", "simple value overwritten");

        $this->assertEquals(1, VaultModel::count());

        $this->assertEquals("simple value overwritten",Vault::open($vault)->get("facaded_secret"));
    }


    public function test_get_secret_with_own_key()
    {
        $vault = VaultModel::create([
            'name' => 'facade'
        ]);

        Vault::open($vault)->useKey("my password")->add("facaded_secret", "simple value");

        $this->assertEquals(1, VaultModel::count());

        $this->expectErrorMessage("The MAC is invalid");
        Vault::open($vault)->useKey(env("APP_KEY"))->get("facaded_secret");

        $this->assertEquals("simple value",Vault::open($vault)->useKey("my password")->get("facaded_secret"));
    }
}
