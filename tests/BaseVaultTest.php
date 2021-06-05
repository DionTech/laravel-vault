<?php


namespace DionTech\Vault\Tests;


use DionTech\Vault\Models\Secret;
use DionTech\Vault\Models\Vault;
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
            'value' => 'a sensible value here'
        ]);

        $this->assertEquals(1, Secret::count());

        $this->assertDatabaseHas('secrets', [
            'vault_id' => $vault->id,
            'alias' => 'app_secret',
        ]);

        $this->assertDatabaseMissing('secrets', [
            'value' => 'a sensible value here'
        ]);
    }
}
