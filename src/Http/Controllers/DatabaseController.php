<?php

namespace Gupta\LaravelInstallerWithEnvato\Http\Controllers;

use Gupta\LaravelInstallerWithEnvato\Facades\License;
use Gupta\LaravelInstallerWithEnvato\Http\Requests\StoreDatabaseRequest;
use App\Http\Controllers\Controller;
use Jackiedo\DotenvEditor\DotenvEditor;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DatabaseController extends Controller
{
    protected $dotenvEditor;
    public function __construct(DotenvEditor $dotenvEditor)
    {
        $this->dotenvEditor = $dotenvEditor;
    }

    public function index()
    {
        $license = License::verify();

        if (! $license['status']) {
            return redirect()->route('installer.license.index')->with('error', $license['message']);
        }

        return view('installer::database');
    }

    public function store(StoreDatabaseRequest $request)
    {
        $license = License::verify();

        if (! $license['status']) {
            return response()->json([
                'status' => 'error',
                'message' => $license['message'],
                'redirect' => route('installer.license.index'),
            ]);
        }

        try {
            $this->checkDatabaseConnection($request);

            // Check if database is empty
            $tables = DB::select('SHOW TABLES');

            if (!empty($tables) && !$request->validated('force')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Database is not empty.',
                    'data' => [
                        'ask_for_force' => true,
                    ],
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please check your database credentials.'
            ], 400);
        }

        // Save database credentials
        try {
            $this->updateDatabaseCredentials($request, false);

            // Migrate database
            Artisan::call('migrate:fresh --force');
            Artisan::call('storage:link');

            $this->updateDatabaseCredentials($request, true);

            return response()->json([
                'status' => 'success',
                'message' => 'Database credentials saved successfully',
                'redirect' => route('installer.admin.index'),
            ]);
        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }


    /**
     * Updating database credentials to .env
     */
    private function updateDatabaseCredentials($request, $persist)
    {
        $keys = [
            'DB_CONNECTION' => $request->validated('driver'),
            'DB_HOST' => $request->validated('host'),
            'DB_PORT' => $request->validated('port'),
            'DB_DATABASE' => $request->validated('database'),
            'DB_USERNAME' => $request->validated('username'),
            'DB_PASSWORD' => $request->validated('password'),
        ];

        if ($persist) {
            $this->dotenvEditor->setKeys($keys)->save();
        } else {
            $this->dotenvEditor->setKeys($keys);
        }
    }

    /**
     * Check database connection
     */
    private function checkDatabaseConnection(StoreDatabaseRequest $request): void
    {
        $driver = $request->validated('driver');

        $settings = config("database.connections.$driver");

        $connectionArray = array_merge($settings, [
            'driver' => $driver,
            'database' => $request->validated('database'),
            'username' => $request->validated('username'),
            'password' => $request->validated('password'),
            'host' => $request->validated('host'),
            'port' => $request->validated('port'),
        ]);

        config([
            'database' => [
                'migrations' => 'migrations',
                'default' => $driver,
                'connections' => [$driver => $connectionArray],
            ],
        ]);

        DB::purge($driver);

        DB::connection()->getPdo();
    }
}
