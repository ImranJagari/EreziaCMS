<?php

namespace App\Shop;

use Illuminate\Database\Eloquent\Model;

class Object extends Model
{
    protected $table = 'shop_items';

    public $timestamps = false;

    public function item()
    {
        return $this->hasOne('App\ItemTemplate', 'Id', 'item_id');
    }
}
