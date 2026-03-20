{{--
    Formulario reutilizable para crear y editar usuarios.
    Agrupa datos de cuenta, rol, departamento y estado de activación.
--}}

@if ($errors->any())
    {{-- Resume los errores de validación para que el administrador los corrija. --}}
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3">

    {{-- Datos de cuenta --}}
    <div class="col-12">
        <h6 class="text-muted fw-semibold text-uppercase small mb-2">Datos de cuenta</h6>
    </div>

    <div class="col-12 col-md-6">
        <label for="name" class="form-label fw-semibold">Nombre completo <span class="text-danger" aria-hidden="true">*</span></label>
        <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $user?->name) }}" maxlength="255" required aria-required="true">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label for="email" class="form-label fw-semibold">Correo electronico <span class="text-danger" aria-hidden="true">*</span></label>
        <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $user?->email) }}" maxlength="255" required aria-required="true">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label for="password" class="form-label fw-semibold">
            Contraseña
            @if ($user)
                <span class="text-muted fw-normal small">(dejar vacío para no cambiar)</span>
            @else
                <span class="text-danger" aria-hidden="true">*</span>
            @endif
        </label>
        <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror"
            minlength="8" {{ $user ? '' : 'required aria-required="true"' }}>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label for="password_confirmation" class="form-label fw-semibold">Confirmar contraseña</label>
        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" minlength="8">
    </div>

    {{-- Rol y departamento --}}
    <div class="col-12"><hr class="my-1"><h6 class="text-muted fw-semibold text-uppercase small mb-2">Rol y departamento</h6></div>

    <div class="col-12 col-md-4">
        <label for="role" class="form-label fw-semibold">Rol <span class="text-danger" aria-hidden="true">*</span></label>
        <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required aria-required="true">
            <option value="">Selecciona un rol...</option>
            @foreach ($roles as $key => $label)
                <option value="{{ $key }}" {{ old('role', $user?->role) === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-4">
        <label for="department_id" class="form-label">Departamento</label>
        <select id="department_id" name="department_id" class="form-select @error('department_id') is-invalid @enderror">
            <option value="">— Sin departamento —</option>
            @foreach ($departments as $dept)
                <option value="{{ $dept->id }}" {{ old('department_id', $user?->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
            @endforeach
        </select>
        @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-4">
        <label for="phone" class="form-label">Telefono</label>
        <input id="phone" type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
            value="{{ old('phone', $user?->phone) }}" maxlength="30">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Estado --}}
    <div class="col-12">
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                {{ old('is_active', $user?->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Cuenta activa</label>
        </div>
        <p class="text-muted small mb-0">Las cuentas inactivas no pueden iniciar sesión pero conservan su historial.</p>
    </div>

</div>
