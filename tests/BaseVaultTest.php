<?php


namespace DionTech\Vault\Tests;


use App\Models\User;
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

        $this->assertEquals('sensible value', $service->getSecret($vault, 'new secret'));
    }

    public function test_with_a_really_random_password_like_key()
    {
        $vault = Vault::create([
            'name' => 'AppVault'
        ]);

        //testing with a really bad password :)
        $service = $this->app->makeWith(VaultServiceContract::class, ['key' => '12345678']);

        $service->addSecret($vault, 'bad_password_storing_itself', '12345678');

        $this->assertEquals(1, $vault->fresh()->secrets()->count());

        $this->assertDatabaseHas('secrets', [
            'vault_id' => $vault->id,
            'alias' => 'bad_password_storing_itself'
        ]);

        $this->assertDatabaseMissing('secrets',[
            'value' => '12345678'
        ]);

        $this->assertEquals('12345678', $service->getSecret($vault, 'bad_password_storing_itself'));
    }

    public function test_relation()
    {
        $user = User::factory()->create();
        $user->vaults()->create([
            'name' => 'personal'
        ]);

        $this->assertEquals(1, $user->vaults()->count());
        $this->assertEquals('personal', $user->vaults->first()->name);

        $user->vaults->first()->open()->add('bad_password_storing_itself', '12345678');

        $this->assertEquals(1, $user->vaults()->first()->secrets()->count());

        $this->assertDatabaseHas('secrets', [
            'vault_id' => $user->vaults()->first()->id,
            'alias' => 'bad_password_storing_itself'
        ]);

        $this->assertDatabaseMissing('secrets',[
            'value' => '12345678'
        ]);

        $this->assertEquals('12345678', $user->vaults()->first()->open()->get('bad_password_storing_itself'));
    }

}
