<?php

namespace App\Models;

use Database\Factories\PartnerConnectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerConnection extends Model
{
    /** @use HasFactory<PartnerConnectionFactory> */
    use HasFactory;

    public const PROVIDER_HAMA = 'hama';

    protected $fillable = [
        'provider',
        'service_name',
        'base_url',
        'access_token',
        'scopes',
        'capabilities',
        'last_status',
        'records_count',
        'last_verified_at',
        'last_synced_at',
        'last_error',
    ];

    protected $hidden = [
        'access_token',
    ];

    protected function casts(): array
    {
        return [
            'access_token' => 'encrypted',
            'scopes' => 'array',
            'capabilities' => 'array',
            'records_count' => 'integer',
            'last_verified_at' => 'datetime',
            'last_synced_at' => 'datetime',
        ];
    }
}
