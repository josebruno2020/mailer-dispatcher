<?php

namespace App\Models;

use App\Helper\StringHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use SoftDeletes, HasUuids;
    protected $fillable = [
        'account_id',
        'name',
        'description',
        'subject',
        'body',
        'webhook_url',
        'webhook_headers',
    ];
    protected $appends = [
        'parameters'
    ];

    protected function getParametersAttribute(): array
    {
        return StringHelper::extractParameters($this->body);
    }

    public function webhookHeaders(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? json_decode($value, true) : null,
            set: fn ($value) => $value ? json_encode($value) : null,
        );
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
