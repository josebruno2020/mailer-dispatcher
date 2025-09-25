<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $fillable = [
        'name',
        'document',
        'webhook_url',
        'webhook_headers',
    ];

    public function webhookHeaders(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? json_decode($value, true) : null,
            set: fn ($value) => $value ? json_encode($value) : null,
        );
    }
}
