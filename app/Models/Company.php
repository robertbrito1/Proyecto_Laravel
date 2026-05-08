<?php

namespace App\Models;

use App\Casts\SafeEncrypted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo que agrupa la información general de una empresa colaboradora.
 */
class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'tax_id',
        'activity',
        'category',
        'social_province',
        'social_municipality',
        'social_address',
        'social_postal_code',
        'main_phone',
        'secondary_phone',
        'email',
        'representative_nif',
        'representative_name',
        'representative_last_name_1',
        'representative_last_name_2',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'tax_id' => SafeEncrypted::class,
            'social_address' => SafeEncrypted::class,
            'social_postal_code' => SafeEncrypted::class,
            'main_phone' => SafeEncrypted::class,
            'secondary_phone' => SafeEncrypted::class,
            'email' => SafeEncrypted::class,
            'representative_nif' => SafeEncrypted::class,
            'representative_name' => SafeEncrypted::class,
            'representative_last_name_1' => SafeEncrypted::class,
            'representative_last_name_2' => SafeEncrypted::class,
            'notes' => SafeEncrypted::class,
        ];
    }

    public function workCenters(): HasMany
    {
        return $this->hasMany(CompanyWorkCenter::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(CompanyContact::class);
    }

    public function agreements(): HasMany
    {
        return $this->hasMany(Agreement::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
