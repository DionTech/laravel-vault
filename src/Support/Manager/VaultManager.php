<?php


namespace DionTech\Vault\Support\Manager;


use DionTech\Vault\Models\Vault;
use DionTech\Vault\Support\Contracts\VaultServiceContract;

class VaultManager
{
    protected string $key;

    protected $vault;

    public function __construct()
    {
        $this->key = env("APP_KEY");
    }

    public function useKey(string $key): VaultManager
    {
        $this->key = $key;

        return $this;
    }

    public function open(Vault $vault): VaultManager
    {
        $this->vault = $vault;

        return $this;
    }

    public function get(string $secret): string
    {
        return app()->makeWith(VaultServiceContract::class, [
            'key' => $this->key
        ])->getSecret($this->vault, $secret);
    }

    public function add(string $secret, string $value): void
    {
        app()->makeWith(VaultServiceContract::class, [
            'key' => $this->key
        ])->addSecret($this->vault, $secret, $value);
    }

    public function overwrite(string $secret, string $value): void
    {
        app()->makeWith(VaultServiceContract::class, [
            'key' => $this->key
        ])->overwriteSecret($this->vault, $secret, $value);
    }
}
