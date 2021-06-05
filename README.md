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

//adding a secret
app()->makeWith(\DionTech\Vault\Support\Contracts\VaultServiceContract::class, [
    'key' => "DO NOT FORGET THIS KEY OR DATA CANNOT BE DECRYPTED LATER" 
])->addSecret($vault, "AN_API_KEY", "this-is-the-sensible-value");


//getting the secret
app()->makeWith(\DionTech\Vault\Support\Contracts\VaultServiceContract::class, [
    'key' => "DO NOT FORGET THIS KEY OR DATA CANNOT BE DECRYPTED LATER" 
])->getSecret($vault, "AN_API_KEY");
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

//adding a secret
app()->makeWith(\DionTech\Vault\Support\Contracts\VaultServiceContract::class, [
    'key' => "key-setted-by-user" 
])->addSecret($user->vaults->first(), "AN_API_KEY", "this-is-the-sensible-value");
```
