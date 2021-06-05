<?php


namespace DionTech\Vault\Support\Contracts;


interface KeyServiceContract
{
    public function getKey(string $value): string;
}
