<?php


namespace DionTech\Vault\Models;


use DionTech\Vault\Support\Manager\VaultManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Vault extends Model
{
    protected $fillable = ['name', 'vaultable_id', 'vaultable_type'];

    public function secrets(): HasMany
    {
        return $this->hasMany(Secret::class);
    }

    public function vaultable(): MorphTo
    {
        return $this->morphTo();
    }

    public function open(): VaultManager
    {
        return (new VaultManager())->open($this);
    }
}
