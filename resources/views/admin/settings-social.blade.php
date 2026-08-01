@extends('layouts.admin')

@section('admin_content')
<div class="py-4">
    <h2 class="fw-bold text-denim mb-1">
        <i class="bi bi-share me-2"></i>Redes Sociales y WhatsApp
    </h2>
    <p class="text-secondary small mb-4">Editá los enlaces que aparecen en el footer de la tienda.</p>

    @if (session('success'))
        <div class="alert alert-success rounded-3">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger rounded-3">
            <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.social.update') }}" method="POST">
        @csrf
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="row g-4">
                <div class="col-md-4">
                    <label class="fw-semibold text-denim small mb-2">
                        <i class="bi bi-whatsapp text-success me-1"></i> WhatsApp (solo números)
                    </label>
                    <input type="text" name="whatsapp" class="form-control rounded-3"
                        value="{{ config('app.whatsapp_owner') }}" placeholder="543795016705" required>
                    <div class="form-text small">Ej: 543795016705 (sin + ni espacios)</div>
                </div>
                <div class="col-md-4">
                    <label class="fw-semibold text-denim small mb-2">
                        <i class="bi bi-instagram text-danger me-1"></i> Instagram (URL completa)
                    </label>
                    <input type="url" name="instagram" class="form-control rounded-3"
                        value="{{ config('app.instagram_url') }}" placeholder="https://instagram.com/intensa.ok" required>
                </div>
                <div class="col-md-4">
                    <label class="fw-semibold text-denim small mb-2">
                        <i class="bi bi-facebook text-primary me-1"></i> Facebook (URL completa)
                    </label>
                    <input type="url" name="facebook" class="form-control rounded-3"
                        value="{{ config('app.facebook_url') }}" placeholder="https://facebook.com/intensa.ok" required>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-denim px-4 rounded-3">
                    <i class="bi bi-save me-2"></i>Guardar Cambios
                </button>
            </div>
        </div>
    </form>
</div>
@endsection