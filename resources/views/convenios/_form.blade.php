{{-- Partial reutilizable para create y edit --}}

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3">
    {{-- Empresa --}}
    <div class="col-12">
        <label class="form-label fw-semibold">Empresa <span class="text-danger">*</span></label>
        <select name="company_id" class="form-select @error('company_id') is-invalid @enderror" required>
            <option value="">Selecciona empresa...</option>
            @foreach ($companies as $company)
                <option value="{{ $company->id }}"
                    {{ old('company_id', $agreement?->company_id) == $company->id ? 'selected' : '' }}>
                    {{ $company->business_name }}
                </option>
            @endforeach
        </select>
        @error('company_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Departamento --}}
    <div class="col-12 col-md-6">
        <label class="form-label fw-semibold">Departamento <span class="text-danger">*</span></label>
        <select name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
            <option value="">Selecciona departamento...</option>
            @foreach ($departments as $dept)
                <option value="{{ $dept->id }}"
                    {{ old('department_id', $agreement?->department_id) == $dept->id ? 'selected' : '' }}>
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>
        @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Estado --}}
    <div class="col-12 col-md-6">
        <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            @foreach ($statuses as $key => $label)
                <option value="{{ $key }}"
                    {{ old('status', $agreement?->status ?? 'borrador') === $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Profesor / Tutor asignado --}}
    <div class="col-12 col-md-6">
        <label class="form-label">Profesor / Tutor asignado</label>
        <select name="assigned_teacher_id" class="form-select @error('assigned_teacher_id') is-invalid @enderror">
            <option value="">— Sin asignar —</option>
            @foreach ($teachers as $teacher)
                <option value="{{ $teacher->id }}"
                    {{ old('assigned_teacher_id', $agreement?->assigned_teacher_id) == $teacher->id ? 'selected' : '' }}>
                    {{ $teacher->name }} ({{ ucfirst($teacher->role) }})
                </option>
            @endforeach
        </select>
        @error('assigned_teacher_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Tutor IES --}}
    <div class="col-12 col-md-6">
        <label class="form-label">Tutor IES</label>
        <select name="ies_tutor_user_id" class="form-select @error('ies_tutor_user_id') is-invalid @enderror">
            <option value="">— Sin asignar —</option>
            @foreach ($teachers as $teacher)
                <option value="{{ $teacher->id }}"
                    {{ old('ies_tutor_user_id', $agreement?->ies_tutor_user_id) == $teacher->id ? 'selected' : '' }}>
                    {{ $teacher->name }} ({{ ucfirst($teacher->role) }})
                </option>
            @endforeach
        </select>
        @error('ies_tutor_user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Contacto de gestión --}}
    <div class="col-12"><hr class="my-1"><p class="text-muted small mb-1">Persona de contacto (gestión del convenio)</p></div>

    <div class="col-12 col-md-4">
        <label class="form-label">Nombre</label>
        <input type="text" name="management_contact_name" class="form-control @error('management_contact_name') is-invalid @enderror"
            value="{{ old('management_contact_name', $agreement?->management_contact_name) }}" maxlength="255">
        @error('management_contact_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Telefono</label>
        <input type="text" name="management_contact_phone" class="form-control @error('management_contact_phone') is-invalid @enderror"
            value="{{ old('management_contact_phone', $agreement?->management_contact_phone) }}" maxlength="30">
        @error('management_contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Email</label>
        <input type="email" name="management_contact_email" class="form-control @error('management_contact_email') is-invalid @enderror"
            value="{{ old('management_contact_email', $agreement?->management_contact_email) }}" maxlength="255">
        @error('management_contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Notas --}}
    <div class="col-12">
        <label class="form-label">Notas internas</label>
        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" maxlength="2000">{{ old('notes', $agreement?->notes) }}</textarea>
        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
