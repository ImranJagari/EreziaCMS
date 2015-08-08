<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    public $timestamps = false;

    protected $fillable = [
        'account',
        'state',
        'code',
        'points',
        'country',
        'palier_name',
        'palier_id',
        'type',
    ];
}
