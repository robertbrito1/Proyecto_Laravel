{{-- Partial form reutilizable para create y edit de empresa --}}

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

    {{-- Datos principales --}}
    <div class="col-12">
        <h6 class="text-muted fw-semibold text-uppercase small mb-2">Datos de la empresa</h6>
    </div>

    <div class="col-12 col-md-8">
        <label class="form-label fw-semibold">Razon social <span class="text-danger">*</span></label>
        <input type="text" name="business_name" class="form-control @error('business_name') is-invalid @enderror"
            value="{{ old('business_name', $company?->business_name) }}" maxlength="255" required>
        @error('business_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label fw-semibold">CIF / NIF</label>
        <input type="text" name="tax_id" class="form-control @error('tax_id') is-invalid @enderror"
            value="{{ old('tax_id', $company?->tax_id) }}" maxlength="20">
        @error('tax_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-8">
        <label class="form-label">Actividad / Sector</label>
        <input type="text" name="activity" class="form-control @error('activity') is-invalid @enderror"
            value="{{ old('activity', $company?->activity) }}" maxlength="255">
        @error('activity')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Categoria</label>
        <select name="category" class="form-select @error('category') is-invalid @enderror">
            <option value="">Sin clasificar</option>
            @foreach ($categories as $key => $label)
                <option value="{{ $key }}" {{ old('category', $company?->category) === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Contacto --}}
    <div class="col-12"><hr class="my-1"><h6 class="text-muted fw-semibold text-uppercase small mb-2">Contacto</h6></div>

    <div class="col-12 col-md-4">
        <label class="form-label">Telefono principal</label>
        <input type="text" name="main_phone" class="form-control @error('main_phone') is-invalid @enderror"
            value="{{ old('main_phone', $company?->main_phone) }}" maxlength="30">
        @error('main_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Telefono secundario</label>
        <input type="text" name="secondary_phone" class="form-control @error('secondary_phone') is-invalid @enderror"
            value="{{ old('secondary_phone', $company?->secondary_phone) }}" maxlength="30">
        @error('secondary_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $company?->email) }}" maxlength="255">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Domicilio social --}}
    <div class="col-12"><hr class="my-1"><h6 class="text-muted fw-semibold text-uppercase small mb-2">Domicilio social</h6></div>

    <div class="col-12 col-md-6">
        <label class="form-label">Direccion</label>
        <input type="text" name="social_address" class="form-control @error('social_address') is-invalid @enderror"
            value="{{ old('social_address', $company?->social_address) }}" maxlength="255">
        @error('social_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-2">
        <label class="form-label">Cod. postal</label>
        <input type="text" name="social_postal_code" class="form-control @error('social_postal_code') is-invalid @enderror"
            value="{{ old('social_postal_code', $company?->social_postal_code) }}" maxlength="10">
        @error('social_postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-2">
        <label class="form-label">Municipio</label>
        <input type="text" name="social_municipality" class="form-control @error('social_municipality') is-invalid @enderror"
            value="{{ old('social_municipality', $company?->social_municipality) }}" maxlength="100">
        @error('social_municipality')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-2">
        <label class="form-label">Provincia</label>
        <input type="text" name="social_province" class="form-control @error('social_province') is-invalid @enderror"
            value="{{ old('social_province', $company?->social_province) }}" maxlength="100">
        @error('social_province')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Representante legal --}}
    <div class="col-12"><hr class="my-1"><h6 class="text-muted fw-semibold text-uppercase small mb-2">Representante legal</h6></div>

    <div class="col-12 col-md-3">
        <label class="form-label">NIF representante</label>
        <input type="text" name="representative_nif" class="form-control @error('representative_nif') is-invalid @enderror"
            value="{{ old('representative_nif', $company?->representative_nif) }}" maxlength="20">
        @error('representative_nif')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="representative_name" class="form-control @error('representative_name') is-invalid @enderror"
            value="{{ old('representative_name', $company?->representative_name) }}" maxlength="100">
        @error('representative_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-3">
        <label class="form-label">Primer apellido</label>
        <input type="text" name="representative_last_name_1" class="form-control @error('representative_last_name_1') is-invalid @enderror"
            value="{{ old('representative_last_name_1', $company?->representative_last_name_1) }}" maxlength="100">
        @error('representative_last_name_1')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-3">
        <label class="form-label">Segundo apellido</label>
        <input type="text" name="representative_last_name_2" class="form-control @error('representative_last_name_2') is-invalid @enderror"
            value="{{ old('representative_last_name_2', $company?->representative_last_name_2) }}" maxlength="100">
        @error('representative_last_name_2')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Notas --}}
    <div class="col-12">
        <label class="form-label">Notas internas</label>
        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" maxlength="2000">{{ old('notes', $company?->notes) }}</textarea>
        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

</div>
