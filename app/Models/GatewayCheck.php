<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GatewayCheck extends Model
{
    public $timestamps = false;

    protected $fillable = ['provider_status', 'database_status', 'details', 'checked_at'];

    protected function casts(): array
    {
        return ['checked_at' => 'datetime'];
    }
}
