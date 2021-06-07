<?php


namespace DionTech\Vault\Services;


use DionTech\Vault\Exceptions\VaultSecretNotExists;
use DionTech\Vault\Models\Secret;
use DionTech\Vault\Models\Vault;
use DionTech\Vault\Support\Contracts\KeyServiceContract;
use DionTech\Vault\Support\Contracts\VaultServiceContract;
use Illuminate\Encryption\Encrypter;

class VaultService implements VaultServiceContract
{
    protected Encrypter $encrypter;

    public function __construct(string $key)
    {
        $this->encrypter = new Encrypter(
            app()->make(KeyServiceContract::class)->getKey($key),
            config('app.cipher', 'AES-256-CBC')
        );
    }

    public function addSecret(Vault $vault, string $alias, string $value)
    {
        $vault->secrets()->create([
            'alias' => $alias,
            'value' => $this->encrypter->encrypt($value)
        ]);
    }

    public function overwriteSecret(Vault $vault, string $alias, string $value)
    {
        $secret = $this->getSecretModel($vault, $alias);

        $secret->update([
            'value' => $this->encrypter->encrypt($value)
        ]);
    }

    public function getSecret(Vault $vault, string $alias): string
    {
        $secret = $this->getSecretModel($vault, $alias);

        return $this->encrypter->decrypt($secret->value);
    }

    /**
     * @param Vault $vault
     * @param string $alias
     * @return Secret
     * @throws VaultSecretNotExists
     */
    protected function getSecretModel(Vault $vault, string $alias): Secret
    {
        $secret = $vault->secrets()->where('alias', $alias)->first();

        if (! $secret instanceof Secret) {
            throw new VaultSecretNotExists($alias . ' not exists at ' . $vault->name);
        }

        return $secret;
    }
}
