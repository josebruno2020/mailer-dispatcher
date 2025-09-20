<?php

namespace App\Models;

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
    ];
    protected $appends = [
        'parameters'
    ];

    protected function getParametersAttribute(): array
    {
        //TODO colocar em helper
        preg_match_all('/\{\{(.*?)\}\}/', $this->body, $matches);
        return array_unique(array_map('trim', $matches[1]));
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
