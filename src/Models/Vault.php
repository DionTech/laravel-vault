<?php


namespace DionTech\Vault\Models;


use Illuminate\Database\Eloquent\Model;

class Vault extends Model
{
    protected $fillable = ['name', 'model_id', 'model_type'];
}
