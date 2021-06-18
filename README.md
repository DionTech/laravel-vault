[![Latest Version](https://img.shields.io/packagist/v/diontech/laravel-vault?label=version)](https://packagist.org/packages/diontech/laravel-vault/)
[![run-tests](https://github.com/DionTech/laravel-vault/actions/workflows/run_tests.yml/badge.svg)](https://github.com/DionTech/laravel-vault/actions/workflows/run_tests.yml)
![GitHub last commit](https://img.shields.io/github/last-commit/diontech/laravel-vault)
![GitHub issues](https://img.shields.io/github/issues-raw/diontech/laravel-vault)
[![Packagist Downloads](https://img.shields.io/packagist/dm/diontech/laravel-vault.svg?label=packagist%20downloads)](https://packagist.org/packages/diontech/laravel-vault)
[![License](https://img.shields.io/badge/license-mit-blue.svg)](https://github.com/diontech/laravel-vault/blob/main/LICENSE.md)
![Twitter Follow](https://img.shields.io/twitter/follow/dion_tech?style=social)

 
# about Vault

With Vault, you can create vaults as application standalones or related to a model in your app, 
for example every user can have personal vaults. Each vault can contain secrets. Instead of using
the default decrypt/encrypt functions, Vault will protect secrets using the keys you will choose.
For example, an user can define its 'magic secret password' and must provide it every
time he wants to have access to a secret. The way you will handle this is dependant to your case.

Vault will handle the key length internally and make sure, the length is 16 or 32, dependant to the cipher
you will use (AES-128-CBC = 16, AES-256-CBC = 32). So you can choose the secret key you will want to.

# installation

```shell
composer require diontech/laravel-vault
```

```shell
php artisan migrate
```

# usage

```php

//creating a vault without a related model
$vault = \DionTech\Vault\Models\Vault::create([
    'name' => 'application vault'
]);

//use default APP_KEY, adding secret
\DionTech\Vault\Support\Facades\Vault::open($vault)->add("facaded_secret", "AN_API_KEY");

//use default APP_KEY, overwrite secret
\DionTech\Vault\Support\Facades\Vault::open($vault)->overwrite("facaded_secret", "AN_API_KEY_overwritten");

//use default APP_KEY, get secret
\DionTech\Vault\Support\Facades\Vault::open($vault)->get("facaded_secret");


//use own key, adding secret
\DionTech\Vault\Support\Facades\Vault::open($vault)->useKey("DO_NOT_FORGETT_IT")->add("facaded_secret", "AN_API_KEY");

//use own key, overwrite secret
\DionTech\Vault\Support\Facades\Vault::open($vault)->useKey("DO_NOT_FORGETT_IT")->overwrite("facaded_secret", "AN_API_KEY_overwritten");

//use own key, get secret
\DionTech\Vault\Support\Facades\Vault::open($vault)->useKey("DO_NOT_FORGETT_IT")->get("facaded_secret");

```



```php
//adding a vault using the polymorphic relation
//at your model, add morphMany relation, for example at User:

use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{
    //...
    
    public function vaults()
    {
        return $this->morphMany(\DionTech\Vault\Models\Vault::class, 'vaultable');
    }
}


//now add a vault at an $user instance later
$user = User::first();

$user->vaults()->create([
    'name' => 'personal'
]);

//now you will have access to the methods like using a facade when you will use the related model based vaults(), starting with open()

$user->vaults()->first()->add("AN_API_KEY", "this-is-the-sensible-value");
$user->vaults()->first()->overwrite("AN_API_KEY", "this-is-the-sensible-value-overwritten");
$user->vaults()->first()->get("AN_API_KEY");
```

There are now a few new options more now of how to write the code:

```php 
Vault::open("a vault name")->add("facaded_secret", "simple value"); //will create the vault
```

or when want a relational based vault:

```php 
$user = User::first(); 
Vault::setContext($user)->open("personal")->add('bad_password_storing_itself', '12345678'); //will create a vault in relation to the user
```

So vaults are created or loaded (when already exists) when you only type a string.

# changing the KeyService hash alogorithm

You can publish the config file - after that, you can change the algo at config/vault.php; default is set to sha512.
Supported are listed at https://www.php.net/manual/de/function.hash-hmac-algos.php.


