@extends('installer::layouts.app', [
    'title' => 'License Activation',
    'withoutSteps' => true,
])

@section('content')
    <form action="{{ route('installer.license.activation') }}" method="post" class="ajaxform">
        @csrf

        <div class="alert alert-primary">
            Sorry for the inconvenience, but we need to verify your license before you can continue.
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-6">
                <label for="envato_username">Envato Username</label>
                <input type="text" class="form-control" name="envato_username" id="envato_username" placeholder="Envato Username" required>
            </div>

            <div class="col-12 col-md-6">
                <label for="purchase_code">Purchased Code</label>
                <input type="text" class="form-control" name="purchase_code" id="purchase_code" placeholder="Purchased Code" required>
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
