@extends('installer::layouts.app', [
    'title' => 'Folder Permissions',
])

@section('content')
    @foreach ($permissions as $permission)
        <div @class([
            'extension-alert alert',
            $permission['isSet'] ? 'alert-primary' : 'alert-danger',
        ]) role="alert">
            <h5 class="mb-0">{{ $permission['folder'] }} <span>(Permission to {{ $permission['permission'] }})</span></h5>
            <div class="status">
                <i class="bi bi-check"></i>
                <i class="bi bi-x"></i>
            </div>
        </div>
    @endforeach


    <div class="button-group">
        <div class="row justify-content-end">
            <div class="col-12 col-md-6">
                @if ($hasError)
                    <div class="alert alert-danger" role="alert">
                        <h5 class="mb-0">Please fix the above errors to continue.</h5>
                    </div>
                @else
                    <form action="{{ route('installer.permissions.store') }}" method="post">
                        @csrf
                        <button class="btn btn-success w-100" type="submit">
                            Next Step
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
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
