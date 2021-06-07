<?php


namespace DionTech\Vault\Support\Facades;


use Illuminate\Support\Facades\Facade;

class Vault extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'vault';
    }
}
