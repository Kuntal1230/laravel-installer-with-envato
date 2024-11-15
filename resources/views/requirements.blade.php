@extends('installer::layouts.app', [
    'title' => 'Server Requirements'
])

@section('content')
    <!-- Single extension -->
    <div class="extension-alert alert alert-primary" role="alert">
        <h5 class="mb-0">{{ trans('PHP Version (:version required)', ['version' => $phpSupportInfo['minimum']]) }}</h5>
        <span>{{ trans('Current: :current', ['current' => $phpSupportInfo['current']]) }}</span>
        <div class="status">
            <i class="bi bi-check"></i>
            <i class="bi bi-x"></i>
        </div>
    </div>

    @foreach($requirements['requirements'] as $key => $module)
        @foreach($module as $ext => $enabled)
            <div @class(['extension-alert alert', $enabled ? 'alert-primary' : 'alert-danger']) role="alert">
                <h5 class="mb-0">{{ $ext }}</h5>
                <div class="status">
                    <i class="bi bi-check"></i>
                    <i class="bi bi-x"></i>
                </div>
            </div>
        @endforeach
    @endforeach

    <form action="{{ route('installer.requirements.store') }}" method="post">
        @csrf
        <div class="button-group">
            <div class="row justify-content-end">
                <div class="col-12 col-md-6">
                    @if($hasError)
                        <a href="{{ route('installer.requirements.index') }}" class="btn btn-danger w-100" type="button">Re-check
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                        @else
                        <button class="btn btn-success w-100" type="submit">Next Step
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </form>
@endsection
