<?php

namespace App\Models;

use App\Casts\SafeEncrypted;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Modelo que representa un convenio firmado o en trámite con una empresa.
 */
class Agreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'department_id',
        'assigned_teacher_id',
        'ies_tutor_user_id',
        'created_by_user_id',
        'management_contact_name',
        'management_contact_phone',
        'management_contact_email',
        'signed_at',
        'status',
        'notes',
    ];

    protected $appends = ['expires_at', 'validity_label'];

    protected function casts(): array
    {
        return [
            'signed_at' => 'date',
            'management_contact_name' => SafeEncrypted::class,
            'management_contact_phone' => SafeEncrypted::class,
            'management_contact_email' => SafeEncrypted::class,
            'notes' => SafeEncrypted::class,
        ];
    }

    public function scopeVigentes(Builder $query): Builder
    {
        return $query
            ->whereNotNull('signed_at')
            ->whereDate('signed_at', '>=', now()->subYears(4)->toDateString());
    }

    public function scopeProximosACaducar(Builder $query, int $months = 12): Builder
    {
        $start = now()->subYears(4);
        $end = now()->subYears(4)->addMonths($months);

        return $query
            ->whereNotNull('signed_at')
            ->whereBetween('signed_at', [$start->toDateString(), $end->toDateString()]);
    }

    public function scopeCaducados(Builder $query): Builder
    {
        return $query
            ->whereNotNull('signed_at')
            ->whereDate('signed_at', '<', now()->subYears(4)->toDateString());
    }

    public function getExpiresAtAttribute(): ?string
    {
        return $this->signed_at?->copy()->addYears(4)->format('Y-m-d');
    }

    public function getValidityLabelAttribute(): string
    {
        if (! $this->signed_at) {
            return 'Sin firmar';
        }

        $days = now()->diffInDays($this->signed_at->copy()->addYears(4), false);

        return match (true) {
            $days < 0 => 'Caducado',
            $days <= 90 => 'Renovar en 3 meses',
            $days <= 180 => 'Renovar en 6 meses',
            $days <= 365 => 'Renovar en 1 año',
            default => 'Vigente',
        };
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_teacher_id');
    }

    public function iesTutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ies_tutor_user_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(AgreementDocument::class);
    }

    public function latestDocument(): HasOne
    {
        return $this->hasOne(AgreementDocument::class)->latestOfMany('version');
    }

    public function companyTutors(): HasMany
    {
        return $this->hasMany(AgreementCompanyTutor::class);
    }
}
