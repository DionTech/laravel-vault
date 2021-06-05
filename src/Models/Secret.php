<?php


namespace DionTech\Vault\Models;


use DionTech\Vault\Support\Casts\EncrypterDecrypter;
use Illuminate\Database\Eloquent\Model;

class Secret extends Model
{
    protected $fillable = ['vault_id', 'alias', 'value'];

    protected $casts = [
        'value' => EncrypterDecrypter::class
    ];
}
