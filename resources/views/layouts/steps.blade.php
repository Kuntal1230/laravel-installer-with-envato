@if (!isset($withoutSteps))
    @php
        $withoutSteps = false;
    @endphp
@endif

@if (!$withoutSteps)
    <div class="installation-steps shadow-sm bg-white">
        <div @class([
            'active' => Route::is('installer.agreement.index'),
            'done' => Cache::get('installer.agreement'),
        ]) data-bs-toggle="tooltip" data-bs-placement="bottom"
            data-bs-title="{{ config('installer.show_user_agreement') ? 'End-user License Agreement' : 'Welcome' }}">
            <i class="bi {{ config('installer.show_user_agreement') ? 'bi-file-earmark-check' : 'bi-house-door' }}"></i>
            <span>Step: 1</span>
        </div>

        <div @class([
            'active' => Route::is('installer.requirements.index'),
            'done' => Cache::get('installer.requirements'),
        ]) data-bs-toggle="tooltip" data-bs-placement="bottom"
            data-bs-title="Server Requirements"><i class="bi bi-database"></i><span>Step: 2</span></div>

        <div @class([
            'active' => Route::is('installer.permissions.index'),
            'done' => Cache::get('installer.permissions'),
        ]) data-bs-toggle="tooltip" data-bs-placement="bottom"
            data-bs-title="Folder Permission"><i class="bi bi-folder"></i><span>Step: 3</span></div>

        <div @class([
            'active' => Route::is('installer.license.index'),
            'done' => Cache::get('installer.license'),
        ]) data-bs-toggle="tooltip" data-bs-placement="bottom"
            data-bs-title="License Activation"><i class="bi bi-shield-lock"></i><span>Step: 4</span></div>

        <div @class([
            'active' => Route::is('installer.database.index'),
            'done' => Cache::get('installer.database'),
        ]) data-bs-toggle="tooltip" data-bs-placement="bottom"
            data-bs-title="Configure Database"><i class="bi bi-database-check"></i><span>Step: 5</span></div>

        {{-- <div @class(['active' => Route::is('installer.smtp.index'), 'done' => Cache::get('installer.smtp')])
             data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Configure Mail Connection"><i
                class="bi bi-envelope-check"></i><span>Step: 6</span></div> --}}

        @if (config('installer.admin.show_form'))
            <div @class([
                'active' => Route::is('installer.admin.index'),
                'done' => Cache::get('installer.admin'),
            ]) data-bs-toggle="tooltip" data-bs-placement="bottom"
                data-bs-title="Admin Setup"><i class="bi bi-person-check"></i><span>Step: 6</span></div>
        @endif
    </div>
@endif
