<?php

namespace App\Models;

use App\Casts\SafeEncrypted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo que almacena los tutores de empresa asociados a un convenio.
 */
class AgreementCompanyTutor extends Model
{
    use HasFactory;

    protected $fillable = [
        'agreement_id',
        'full_name',
        'dni',
        'default_schedule',
    ];

    protected function casts(): array
    {
        return [
            'full_name' => SafeEncrypted::class,
            'dni' => SafeEncrypted::class,
            'default_schedule' => SafeEncrypted::class,
        ];
    }

    public function agreement(): BelongsTo
    {
        return $this->belongsTo(Agreement::class);
    }
}
