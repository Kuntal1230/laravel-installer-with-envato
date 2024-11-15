@extends('installer::layouts.app', [
    'title' => 'Admin Setup'
])

@section('content')
    <form action="{{ route('installer.admin.store') }}" method="post" class="ajaxform">
        @csrf
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Name" required>
            </div>

            <div class="col-12 col-md-6">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" required>
            </div>

            <div class="col-12 col-md-6">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" minlength="8" required>
            </div>

            <div class="col-12 col-md-6">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" minlength="8" required>
            </div>

            <div class="button-group">
                <div class="row justify-content-end">
                    <div class="col-12 col-md-6">
                        <button class="btn btn-success w-100" type="submit">
                            Save & Continue
                            <i class="bi bi-check-circle"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
