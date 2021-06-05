<?php


namespace DionTech\Vault\Tests;


use DionTech\Vault\Models\Secret;
use DionTech\Vault\Models\Vault;
use DionTech\Vault\Support\Contracts\VaultServiceContract;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class BaseVaultTest extends TestCase
{
    use DatabaseMigrations;

    public function test_base_vault_with_secrets()
    {
        $vault = Vault::create([
            'name' => 'AppVault'
        ]);

        $this->assertEquals(1, Vault::count());

        $secret = Secret::create([
            'vault_id' => $vault->id,
            'alias' => 'app_secret',
            'value' => 'a sensible value here, directly inserting is not a good idea'
        ]);

        $this->assertEquals(1, Secret::count());

        $this->assertDatabaseHas('secrets', [
            'vault_id' => $vault->id,
            'alias' => 'app_secret',
            'value' => 'a sensible value here, directly inserting is not a good idea'
        ]);

        //disadvantage at the moment: key must be a length of 32 signs
        $service = $this->app->makeWith(VaultServiceContract::class, ['key' => 'a personal key must contain 32 s']);

        $service->addSecret($vault, 'new secret', 'sensible value');

        $this->assertEquals(2, $vault->fresh()->secrets()->count());

        $this->assertDatabaseHas('secrets', [
            'vault_id' => $vault->id,
            'alias' => 'new secret'
        ]);

        $this->assertDatabaseMissing('secrets',[
            'value' => 'sensible value'
        ]);
    }
}
