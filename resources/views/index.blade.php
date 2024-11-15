@extends('installer::layouts.app', [
    'title' => $showAgreement ? 'End-user License Agreement' : 'Welcome to the Installer',
])

@section('content')
    @if($showAgreement)
        <div class="scrollable">
            <x-markdown>
                {!! $agreement !!}
            </x-markdown>
        </div>

        <form action="{{ route('installer.agreement.index') }}" method="post">
            @csrf
            <div class="button-group">
                <div class="row g-4 justify-content-end">
                    <div class="col-12 col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="agreeCheckbox" name="agree" required>
                            <label class="form-check-label text-primary" for="agreeCheckbox">
                                I have read all the rules and agree with these.
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <button type="submit" id="continueButton" class="btn btn-success disabled w-100">
                            I Agree & Continue
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    @else
        <form action="{{ route('installer.agreement.index') }}" method="post">
            @csrf
            <div class="button-group">
                <div class="row g-4 justify-content-end">
                    <div class="col-12">
                        <input type="hidden" value="1" id="agreeCheckbox" name="agree" required>
                        <p>Enjoy a seamless installation journey with clear, step-by-step guidance. Let's begin! 🚀</p>
                    </div>

                    <div class="col-12">
                        <button type="submit" id="continueButton" class="btn btn-success w-100">
                            Continue
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    @endif
@endsection
