<?php


namespace DionTech\Vault\Support\Contracts;


use DionTech\Vault\Models\Vault;

interface VaultServiceContract
{
    public function __construct(string $key);

    public function addSecret(Vault $vault, string $alias, string $value);

    public function getSecret(Vault $vault, string $alias): string;

    public function overwriteSecret(Vault $vault, string $alias, string $value);
}
