@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        Generador de firma de correo
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <p class="text-muted">Tu nombre y correo se completan con la información de tu cuenta.</p>

                <form method="POST" action="{{ route('admin.firma.generate') }}">
                    @csrf

                    <div class="form-group">
                        <label class="required">Nombre</label>

                        @if(Auth::user()->email == 'admin@admin.com')
                        <input type="text" name="name" class="form-control" value="">
                        @else
                        <input type="text" name="name" class="form-control" value="{{ $defaults['name'] }}" readonly>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="required">Correo</label>
                        @if(Auth::user()->email == 'admin@admin.com')
                        <input type="email" name="email" class="form-control" value="">
                        @else
                        <input type="email" name="name" class="form-control" value="{{ $defaults['email'] }}" readonly>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="required" for="role">Cargo</label>
                        <input type="text" class="form-control @error('role') is-invalid @enderror" id="role" name="role" value="{{ $defaults['role'] }}" required maxlength="120">
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="required" for="phone">Teléfono</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ $defaults['phone'] }}" required maxlength="50" placeholder="+56999999999">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="required" for="texto">Mensaje para WhatsApp</label>
                        <input type="text" class="form-control @error('texto') is-invalid @enderror" id="texto" name="texto" value="{{ $defaults['texto'] }}" required maxlength="150" placeholder="Hola, gracias por contactarme.">
                        @error('texto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- <div class="form-group">
                        <label for="address_line_1">Dirección línea 1</label>
                        <input type="text" class="form-control @error('address_line_1') is-invalid @enderror" id="address_line_1" name="address_line_1" value="{{ $defaults['address_line_1'] }}">
                        @error('address_line_1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address_line_2">Dirección línea 2</label>
                        <input type="text" class="form-control @error('address_line_2') is-invalid @enderror" id="address_line_2" name="address_line_2" value="{{ $defaults['address_line_2'] }}">
                        @error('address_line_2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="website">Sitio web</label>
                        <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ $defaults['website'] }}">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="linkedin">LinkedIn</label>
                        <input type="url" class="form-control @error('linkedin') is-invalid @enderror" id="linkedin" name="linkedin" value="{{ $defaults['linkedin'] }}">
                        @error('linkedin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="instagram">Instagram</label>
                        <input type="url" class="form-control @error('instagram') is-invalid @enderror" id="instagram" name="instagram" value="{{ $defaults['instagram'] }}">
                        @error('instagram')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> --}}

                    <button type="submit" class="btn btn-success btn-block">Generar firma</button>
                </form>
            </div>

            <div class="col-lg-8">
                @if($signature)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Vista previa</h5>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="copy-signature-html">
                            Copiar HTML
                        </button>
                    </div>

                    <div id="signature-preview">
                        @include('admin.firma.business-card', $signature)
                    </div>

                    <p class="text-muted mt-3 small">
                        Usa el botón "Copiar HTML" y pega el resultado en la sección de firma de tu cliente de correo.
                    </p>
                @else
                    <div class="text-center text-muted py-5 border rounded">
                        Completa el formulario y presiona "Generar firma" para ver la vista previa aquí.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
document.addEventListener('DOMContentLoaded', function () {
    const copyButton = document.getElementById('copy-signature-html');

    if (!copyButton) {
        return;
    }

    copyButton.addEventListener('click', function () {
        const container = document.getElementById('signature-preview');

        if (!container) {
            return;
        }

        const html = container.innerHTML.trim();

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(html)
                .then(() => notifyCopySuccess(copyButton))
                .catch(() => fallbackCopy(html, copyButton));
        } else {
            fallbackCopy(html, copyButton);
        }
    });

    function fallbackCopy(text, button) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.setAttribute('readonly', '');
        textarea.style.position = 'absolute';
        textarea.style.left = '-9999px';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            if (document.execCommand('copy')) {
                notifyCopySuccess(button);
            }
        } catch (err) {
            console.error('No se pudo copiar el HTML de la firma', err);
        }
        document.body.removeChild(textarea);
    }

    function notifyCopySuccess(button) {
        const original = button.textContent;
        button.textContent = 'HTML copiado';
        button.classList.remove('btn-outline-primary');
        button.classList.add('btn-success');
        setTimeout(function () {
            button.textContent = original;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-primary');
        }, 2000);
    }
});
</script>
@endsection
