<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo para los contactos de referencia que tiene registrada cada empresa.
 */
class CompanyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'department_id',
        'type',
        'full_name',
        'nif',
        'phone_1',
        'phone_2',
        'email',
        'notes',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
