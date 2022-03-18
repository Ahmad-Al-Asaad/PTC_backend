<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpansiveCost extends Model
{
    protected $guarded = [];

    public function expansiveCost()
    {
        return $this->belongsTo('App\Models\ItemCost');
    }
}
