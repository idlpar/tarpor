<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class InstallerController extends Controller
{
    public function index()
    {
        Log::info('Installer index', [
            'session_id' => session()->getId(),
            'session_started' => session()->isStarted(),
            'csrf_token' => csrf_token()
        ]);
        return view('installer.welcome');
    }

    public function showEnvironmentForm()
    {
        $sessionId = session()->getId();
        $csrfToken = csrf_token();
        Log::info('Environment form displayed', [
            'session_id' => $sessionId,
            'session_started' => session()->isStarted(),
            'csrf_token' => $csrfToken,
            'session_data' => session()->all()
        ]);
        if (empty($csrfToken)) {
            Log::error('CSRF token is empty in showEnvironmentForm', ['session_id' => $sessionId]);
        }
        return view('installer.environment');
    }

    public function saveEnvironment(Request $request)
    {
        Log::info('Environment form submitted', [
            'session_id' => session()->getId(),
            'session_started' => session()->isStarted(),
            'csrf_token' => csrf_token(),
            'submitted_token' => $request->_token,
            'input' => $request->except(['_token', 'db_password', 'mail_password'])
        ]);
        if (empty($request->_token)) {
            Log::error('Submitted CSRF token is empty', ['input' => $request->all()]);
        }

        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'app_timezone' => 'required|string',
            'db_connection' => 'required|string|in:mysql,sqlite,pgsql',
            'db_host' => 'required_if:db_connection,mysql,pgsql|string|nullable',
            'db_port' => 'required_if:db_connection,mysql,pgsql|numeric|nullable',
            'db_database' => 'required_if:db_connection,mysql,pgsql|string|nullable',
            'db_username' => 'required_if:db_connection,mysql,pgsql|string|nullable',
            'db_password' => 'nullable|string',
            'mail_mailer' => 'required|string|in:smtp,log',
            'mail_host' => 'required_if:mail_mailer,smtp|string|nullable',
            'mail_port' => 'required_if:mail_mailer,smtp|numeric|nullable',
            'mail_username' => 'required_if:mail_mailer,smtp|string|nullable',
            'mail_password' => 'nullable|string',
            'mail_from_address' => 'required_if:mail_mailer,smtp|email|nullable',
            'mail_from_name' => 'required_if:mail_mailer,smtp|string|nullable',
            'queue_connection' => 'required|string|in:sync,database',
        ]);

        try {
            $config = [
                'driver' => $request->db_connection,
                'database' => $request->db_connection === 'sqlite' ? base_path('database/database.sqlite') : ($request->db_database ?? ':memory:'),
                'username' => $request->db_username ?? '',
                'password' => $request->db_password ?? '',
            ];
            if ($request->db_connection !== 'sqlite') {
                $config['host'] = $request->db_host ?? '127.0.0.1';
                $config['port'] = $request->db_port ?? ($request->db_connection === 'mysql' ? '3306' : '5432');
            }
            config(['database.connections.temp' => $config]);

            // For SQLite, ensure the database file exists
            if ($request->db_connection === 'sqlite') {
                $sqlitePath = base_path('database/database.sqlite');
                if (!file_exists($sqlitePath)) {
                    touch($sqlitePath);
                    chmod($sqlitePath, 0775);
                }
            }

            DB::connection('temp')->getPdo();
            Log::info('Database connection successful', ['db_connection' => $request->db_connection]);
        } catch (\Exception $e) {
            Log::error('Database connection failed: ' . $e->getMessage(), [
                'db_connection' => $request->db_connection,
                'db_host' => $request->db_host,
                'db_port' => $request->db_port,
                'db_database' => $request->db_database,
            ]);
            return back()->withErrors(['db_connection' => 'Could not connect to the database: ' . $e->getMessage()])->withInput();
        }

        session(['installer_db_connection' => $request->db_connection]);

        $dbHost = $request->db_connection === 'sqlite' ? '' : ($request->db_host ?? '127.0.0.1');
        $dbPort = $request->db_connection === 'sqlite' ? '' : ($request->db_port ?? ($request->db_connection === 'mysql' ? '3306' : '5432'));
        $dbDatabase = $request->db_connection === 'sqlite' ? 'database/database.sqlite' : ($request->db_database ?? '');
        $dbUsername = $request->db_connection === 'sqlite' ? '' : ($request->db_username ?? '');
        $dbPassword = $request->db_connection === 'sqlite' ? '' : ($request->db_password ?? '');

        $mailHost = $request->mail_mailer === 'log' ? '' : ($request->mail_host ?? '');
        $mailPort = $request->mail_mailer === 'log' ? '' : ($request->mail_port ?? '');
        $mailUsername = $request->mail_mailer === 'log' ? '' : ($request->mail_username ?? '');
        $mailPassword = $request->mail_mailer === 'log' ? '' : ($request->mail_password ?? '');
        $mailFromAddress = $request->mail_mailer === 'log' ? '' : ($request->mail_from_address ?? '');
        $mailFromName = $request->mail_mailer === 'log' ? '' : ($request->mail_from_name ?? '');

        $envContent = <<<EOT
APP_NAME="{$request->app_name}"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE={$request->app_timezone}
APP_URL={$request->app_url}
DEBUGBAR_ENABLED=false

DB_CONNECTION={$request->db_connection}
DB_HOST={$dbHost}
DB_PORT={$dbPort}
DB_DATABASE={$dbDatabase}
DB_USERNAME={$dbUsername}
DB_PASSWORD="{$dbPassword}"

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

MAIL_MAILER={$request->mail_mailer}
MAIL_HOST={$mailHost}
MAIL_PORT={$mailPort}
MAIL_USERNAME={$mailUsername}
MAIL_PASSWORD="{$mailPassword}"
MAIL_FROM_ADDRESS="{$mailFromAddress}"
MAIL_FROM_NAME="{$mailFromName}"

QUEUE_CONNECTION={$request->queue_connection}
EOT;

        try {
            if (!is_writable(base_path())) {
                throw new \Exception('Base directory is not writable.');
            }
            file_put_contents(base_path('.env'), $envContent);
            Artisan::call('key:generate', ['--force' => true]);
            Log::info('.env file written successfully');
        } catch (\Exception $e) {
            Log::error('Failed to write .env file: ' . $e->getMessage());
            return back()->withErrors(['env' => 'Could not write .env file: ' . $e->getMessage()])->withInput();
        }

        // Clear config cache to ensure new .env is loaded
        Artisan::call('config:clear');

        return redirect()->route('install.database.form');
    }

    public function showDatabaseForm()
    {
        $dbConnection = session('installer_db_connection', 'mysql');
        Log::info('Database form displayed', [
            'session_id' => session()->getId(),
            'session_started' => session()->isStarted(),
            'csrf_token' => csrf_token(),
            'db_connection' => $dbConnection
        ]);
        return view('installer.database', ['db_connection' => $dbConnection]);
    }

    public function runDatabase(Request $request)
    {
        $dbConnection = $request->input('db_connection', session('installer_db_connection', 'mysql'));
        Log::info('Running database setup', [
            'db_connection' => $dbConnection,
            'session_id' => session()->getId(),
            'session_started' => session()->isStarted(),
        ]);

        try {
            // Reload .env to ensure latest DB_CONNECTION
            \Dotenv\Dotenv::createImmutable(base_path())->load();
            Artisan::call('config:clear');

            // Set up the install connection
            config(['database.connections.install' => [
                'driver' => $dbConnection,
                'database' => $dbConnection === 'sqlite' ? base_path('database/database.sqlite') : env('DB_DATABASE', 'tarpor'),
                'username' => $dbConnection === 'sqlite' ? '' : env('DB_USERNAME', 'root'),
                'password' => $dbConnection === 'sqlite' ? '' : env('DB_PASSWORD', ''),
                'host' => $dbConnection === 'sqlite' ? '' : env('DB_HOST', '127.0.0.1'),
                'port' => $dbConnection === 'sqlite' ? '' : env('DB_PORT', $dbConnection === 'mysql' ? '3306' : '5432'),
            ]]);

            DB::purge('install');
            DB::connection('install')->reconnect();

            // Run migrations and seeding on the install connection
            Artisan::call('migrate', [
                '--database' => 'install',
                '--force' => true,
            ]);
            Artisan::call('db:seed', [
                '--database' => 'install',
                '--force' => true,
            ]);
            Artisan::call('storage:link', ['--force' => true]);
            Log::info('Database setup and storage link created successfully');
        } catch (\Exception $e) {
            Log::error('Database setup or storage link failed: ' . $e->getMessage());
            return redirect()->route('install.database.form')
                ->withErrors(['database' => 'Setup failed: ' . $e->getMessage()])
                ->with('db_connection', $dbConnection);
        }

        return redirect()->route('install.admin');
    }

    public function createAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Ensure admin creation uses the install connection
            config(['database.connections.install' => [
                'driver' => session('installer_db_connection', 'mysql'),
                'database' => session('installer_db_connection') === 'sqlite' ? base_path('database/database.sqlite') : env('DB_DATABASE', 'tarpor'),
                'username' => session('installer_db_connection') === 'sqlite' ? '' : env('DB_USERNAME', 'root'),
                'password' => session('installer_db_connection') === 'sqlite' ? '' : env('DB_PASSWORD', ''),
                'host' => session('installer_db_connection') === 'sqlite' ? '' : env('DB_HOST', '127.0.0.1'),
                'port' => session('installer_db_connection') === 'sqlite' ? '' : env('DB_PORT', session('installer_db_connection') === 'mysql' ? '3306' : '5432'),
            ]]);

            DB::purge('install');
            DB::connection('install')->reconnect();

            User::on('install')->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
            Log::info('Admin user created successfully');
        } catch (\Exception $e) {
            Log::error('Admin user creation failed: ' . $e->getMessage());
            return back()->withErrors(['admin' => 'Failed to create admin user: ' . $e->getMessage()])->withInput();
        }

        session()->forget('installer_db_connection');
        return redirect()->route('home')->with('success', 'Installation completed successfully. You can now log in as the admin.');
    }
}
