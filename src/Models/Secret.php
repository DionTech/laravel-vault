<?php


namespace DionTech\Vault\Models;


use Illuminate\Database\Eloquent\Model;

class Secret extends Model
{
    protected $fillable = ['vault_id', 'alias', 'value'];
}
