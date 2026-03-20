<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo que guarda el histórico de llamadas o gestiones de contacto con empresas.
 */
class CompanyOutreachLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'teacher_user_id',
        'company_name',
        'contact_email',
        'contact_phone',
        'status',
        'notes',
        'contacted_at',
    ];

    protected function casts(): array
    {
        return [
            'contacted_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_user_id');
    }
}
