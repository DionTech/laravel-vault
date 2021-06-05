<?php


namespace DionTech\Vault\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vault extends Model
{
    protected $fillable = ['name', 'model_id', 'model_type'];

    public function secrets(): HasMany
    {
        return $this->hasMany(Secret::class);
    }
}
