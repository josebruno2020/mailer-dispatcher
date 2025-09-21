<?php

namespace App\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Setting extends Model implements Arrayable
{
    use SoftDeletes, HasUuids;
    protected $fillable = [
        'account_id',
        'name',
        'host',
        'port',
        'username',
        'password',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function toArray()
    {
        $array = parent::toArray();
        // $array['password'] = Str::of($this->password)->substr(0, 2)->append(str_repeat('*', max(0, strlen($this->password) - 2)))->toString();
        return $array;
    }
}
