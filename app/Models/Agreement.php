<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    protected function casts(): array
    {
        return [
            'signed_at' => 'date',
        ];
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

    public function companyTutors(): HasMany
    {
        return $this->hasMany(AgreementCompanyTutor::class);
    }
}
