@extends('installer::layouts.app', [
    'title' => 'Mail Configuration'
])

@section('content')
    <form action="{{ route('installer.smtp.store') }}" method="post" class="ajaxform">
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <label for="driver">Mail Driver</label>
                <select class="formSelect" name="driver" id="driver" required>
                    <option value="smtp" selected>SMTP</option>
                </select>
            </div>

            <div class="col-12 col-md-6">
                <label for="host">Mail Host</label>
                <input type="text" class="form-control" name="host" id="host" placeholder="Mail Host" required>
            </div>

            <div class="col-12 col-md-6">
                <label for="port">Mail Port</label>
                <input type="text" class="form-control" name="port" id="port" placeholder="Mail Port" required>
            </div>

            <div class="col-12 col-md-6">
                <label for="username">Mail Username</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Mail Username" required>
            </div>

            <div class="col-12 col-md-6">
                <label for="password">Mail Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Mail Password" required>
            </div>

            <div class="col-12 col-md-6">
                <label for="encryption">Mail Encryption</label>
                <select class="formSelect" name="encryption" id="encryption" required>
                    <option value="ssl" selected>SSL</option>
                    <option value="tls">TLS</option>
                    <option value="starttls">StartTLS</option>
                </select>
            </div>

            <div class="col-12 col-md-6">
                <label for="name">Mail From Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Mail From Name" required>
            </div>

            <div class="col-12 col-md-6">
                <label for="email">Mail From Address</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Mail From Address" required>
            </div>

            <div class="button-group">
                <div class="row justify-content-end">
                    <div class="col-12 col-md-6">
                        <button class="btn btn-success w-100" type="submit">
                            Save & Continue
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
