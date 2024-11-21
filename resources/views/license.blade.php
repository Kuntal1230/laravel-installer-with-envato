@extends('installer::layouts.app', [
    'title' => 'License Activation',
])

@section('content')
    <form action="{{ route('installer.license.store') }}" method="post" class="ajaxform">
        @csrf
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <label for="envato_username">Envato Username *</label>
                <input type="text" class="form-control" name="envato_username" id="envato_username"
                    placeholder="Envato Username">
            </div>

            <div class="col-12 col-md-6">
                <label for="purchase_code">Purchased Code *</label>
                <input type="text" class="form-control" name="purchase_code" id="purchase_code"
                    placeholder="Purchased Code">
            </div>

            <div class="button-group">
                <div class="row justify-content-end">
                    <div class="col-12 col-md-6">
                        <button class="btn btn-success w-100" type="submit">
                            Verify
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('pageScripts')
    <script>
        window.error = @json(session('error'));
        window.success = @json(session('success'));
        if (window.error) {
            flash('warning', window.error);
        }
        if (window.success) {
            flash('success', window.success);
        }
    </script>
@endpush
