@extends('installer::layouts.app', [
    'title' => 'Database'
])

@section('content')
    <form action="{{ route('installer.database.store') }}" method="post" class="ajaxform" id="databaseForm">
        <input type="hidden" name="force" value="0">
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <label for="driver">Database Driver</label>
                <select class="formSelect" name="driver" id="driver" aria-label="Default select example" required>
                    @if(config('installer.database.mysql'))
                        <option value="mysql" @selected(config('installer.database.default') == 'mysql')>MySQL</option>
                    @endif
                    @if(config('installer.database.pgsql'))
                        <option value="pgsql" @selected(config('installer.database.default') == 'pgsql')>PgSQL</option>
                    @endif
                    @if(config('installer.database.sqlsrv'))
                        <option value="sqlsrv" @selected(config('installer.database.default') == 'sqlsrv')>SQLSrv</option>
                    @endif
                </select>
            </div>

            <div class="col-12 col-md-6">
                <label for="host">Database Host</label>
                <input type="text" class="form-control" name="host" id="host" value="localhost"
                       placeholder="Database Host" required>
            </div>

            <div class="col-12 col-md-6">
                <label for="port">Database Port</label>
                <input type="number" class="form-control" name="port" id="port" value="3306" placeholder="Database Port"
                       required>
            </div>

            <div class="col-12 col-md-6">
                <label for="database">Database Name</label>
                <input type="text" class="form-control" name="database" id="database" placeholder="Database Name"
                       required>
            </div>

            <div class="col-12 col-md-6">
                <label for="username">Database User Username</label>
                <input type="text" class="form-control" name="username" id="username"
                       placeholder="Database User Username" required>
            </div>

            <div class="col-12 col-md-6">
                <label for="password">Database User Password</label>
                <input type="text" class="form-control" name="password" id="password"
                       placeholder="Database User Password">
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

@push('pageScripts')
    <script>
        $('#driver').on('change', function () {
            if ($(this).val() === 'mysql') {
                $('#port').val('3306')
            } else if ($(this).val() === 'pgsql') {
                $('#port').val('5432')
            } else if ($(this).val() === 'sqlsrv') {
                $('#port').val('1433')
            }
        })


        $('#databaseForm').on('ajaxFormError', function (e, response) {
            response = response.responseJSON

            if(response?.data?.ask_for_force){
                $.confirm({
                    title: 'Are you sure!',
                    content: 'Your database is not empty, do you want to force the installation?',
                    theme: 'modern',
                    icon: 'bi bi-database-lock text-warning',
                    autoClose: 'cancel|8000',
                    buttons: {
                        yes: {
                            btnClass: 'btn-warning',
                            action: function () {
                                $.confirm({
                                    title: 'This action is irreversible!',
                                    content: 'I understand the consequences, proceed anyway?',
                                    theme: 'modern',
                                    icon: 'bi bi-database-gear text-danger',
                                    autoClose: 'cancel|8000',
                                    buttons: {
                                        proceed: {
                                            btnClass: 'btn-danger',
                                            action: function () {
                                                $('#databaseForm').find('input[name="force"]').val(1)
                                                $('#databaseForm').submit()
                                            }
                                        },
                                        cancel: function () {
                                        }
                                    }
                                })
                            }
                        },
                        no: function () {
                        }
                    }
                });
            }
        })
    </script>
@endpush
