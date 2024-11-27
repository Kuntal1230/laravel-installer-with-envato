<?php

namespace Gupta\LaravelInstallerWithEnvato\Http\Controllers;

use Gupta\LaravelInstallerWithEnvato\Http\Requests\StoreAdminRequest;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        return view('installer::admin');
    }

    public function store(StoreAdminRequest $request)
    {
        $superAdmin = Admin::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => bcrypt($request->validated('password')),
            ...config('installer.admin.extra')
        ]);

        if (config('installer.admin.has_role')) {
            if (! Role::where('name', config('installer.admin.role', 'Super Admin'))->exists()) {
                Role::create(['name' => config('installer.admin.role')]);
            }

            $superAdmin->assignRole(config('installer.admin.role'));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Admin Created Successfully',
            'redirect' => route('installer.finish.index'),
        ]);
    }
}
