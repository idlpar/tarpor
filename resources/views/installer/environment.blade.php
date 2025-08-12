@extends('layouts.app')

@push('styles')
    <style>
        :root {
            --primary: #008080;
            --primary-dark: #006666;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dbConnection = document.getElementById('db_connection');
            const dbFieldsContainer = document.getElementById('db_fields');
            const dbFields = [
                document.getElementById('db_host'),
                document.getElementById('db_port'),
                document.getElementById('db_database'),
                document.getElementById('db_username'),
                document.getElementById('db_password')
            ];
            const mailMailer = document.getElementById('mail_mailer');
            const mailFieldsContainer = document.getElementById('mail_fields');
            const mailFields = [
                document.getElementById('mail_host'),
                document.getElementById('mail_port'),
                document.getElementById('mail_username'),
                document.getElementById('mail_password'),
                document.getElementById('mail_from_address'),
                document.getElementById('mail_from_name')
            ];

            function toggleDbFields() {
                const isSqlite = dbConnection.value === 'sqlite';
                if (dbFieldsContainer) {
                    dbFieldsContainer.style.display = isSqlite ? 'none' : 'block';
                }
                dbFields.forEach(field => {
                    if (field) {
                        if (isSqlite) {
                            field.removeAttribute('required');
                            field.value = ''; // Clear value when hidden
                        } else {
                            if (field.id !== 'db_password') { // db_password is optional
                                field.setAttribute('required', 'required');
                            }
                        }
                    }
                });
            }

            function toggleMailFields() {
                const isLog = mailMailer.value === 'log';
                if (mailFieldsContainer) {
                    mailFieldsContainer.style.display = isLog ? 'none' : 'block';
                }
                mailFields.forEach(field => {
                    if (field) {
                        if (isLog) {
                            field.removeAttribute('required');
                            field.value = ''; // Clear value when hidden
                        } else {
                            if (field.id !== 'mail_password') { // mail_password is optional
                                field.setAttribute('required', 'required');
                            }
                        }
                    }
                });
            }

            dbConnection.addEventListener('change', toggleDbFields);
            mailMailer.addEventListener('change', toggleMailFields);
            toggleDbFields(); // Initial call
            toggleMailFields(); // Initial call
        });
    </script>
@endpush

@section('title', 'Environment Setup')

@section('content')
    <section class="py-12 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight mb-8">Environment Setup</h1>
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                <p class="text-gray-600 mb-4">Configure your application's environment settings.</p>

                <!-- Display all errors -->
                @if ($errors->any())
                    <div class="mb-4">
                        <ul class="list-disc list-inside text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('install.environment') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <!-- Application Settings -->
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Application Settings</h2>
                            <div class="space-y-4">
                                <div>
                                    <label for="app_name" class="block text-sm font-medium text-gray-700">Application Name</label>
                                    <input type="text" id="app_name" name="app_name" value="{{ old('app_name', 'Tarpor') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm" required>
                                    @error('app_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="app_url" class="block text-sm font-medium text-gray-700">Application URL</label>
                                    <input type="url" id="app_url" name="app_url" value="{{ old('app_url', 'http://localhost') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm" required>
                                    @error('app_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="app_timezone" class="block text-sm font-medium text-gray-700">Timezone</label>
                                    <select id="app_timezone" name="app_timezone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm" required>
                                        @foreach (timezone_identifiers_list() as $timezone)
                                            <option value="{{ $timezone }}" {{ old('app_timezone', 'UTC') === $timezone ? 'selected' : '' }}>{{ $timezone }}</option>
                                        @endforeach
                                    </select>
                                    @error('app_timezone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Database Settings -->
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Database Settings</h2>
                            <div class="space-y-4">
                                <div>
                                    <label for="db_connection" class="block text-sm font-medium text-gray-700">Database Type</label>
                                    <select id="db_connection" name="db_connection" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm" required>
                                        <option value="mysql" {{ old('db_connection', 'mysql') === 'mysql' ? 'selected' : '' }}>MySQL</option>
                                        <option value="sqlite" {{ old('db_connection') === 'sqlite' ? 'selected' : '' }}>SQLite</option>
                                        <option value="pgsql" {{ old('db_connection') === 'pgsql' ? 'selected' : '' }}>PostgreSQL</option>
                                    </select>
                                    @error('db_connection')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div id="db_fields" class="space-y-4">
                                    <div>
                                        <label for="db_host" class="block text-sm font-medium text-gray-700">Database Host</label>
                                        <input type="text" id="db_host" name="db_host" value="{{ old('db_host', '127.0.0.1') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm">
                                        @error('db_host')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="db_port" class="block text-sm font-medium text-gray-700">Database Port</label>
                                        <input type="number" id="db_port" name="db_port" value="{{ old('db_port', '3306') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm">
                                        @error('db_port')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="db_database" class="block text-sm font-medium text-gray-700">Database Name</label>
                                        <input type="text" id="db_database" name="db_database" value="{{ old('db_database') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm">
                                        @error('db_database')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="db_username" class="block text-sm font-medium text-gray-700">Database Username</label>
                                        <input type="text" id="db_username" name="db_username" value="{{ old('db_username') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm">
                                        @error('db_username')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="db_password" class="block text-sm font-medium text-gray-700">Database Password</label>
                                        <input type="password" id="db_password" name="db_password" value="{{ old('db_password') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm">
                                        @error('db_password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mail Settings -->
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Mail Settings</h2>
                            <div class="space-y-4">
                                <div>
                                    <label for="mail_mailer" class="block text-sm font-medium text-gray-700">Mail Driver</label>
                                    <select id="mail_mailer" name="mail_mailer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm" required>
                                        <option value="smtp" {{ old('mail_mailer', 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                        <option value="log" {{ old('mail_mailer') === 'log' ? 'selected' : '' }}>Log (for testing)</option>
                                    </select>
                                    @error('mail_mailer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div id="mail_fields" class="space-y-4">
                                    <div>
                                        <label for="mail_host" class="block text-sm font-medium text-gray-700">Mail Host</label>
                                        <input type="text" id="mail_host" name="mail_host" value="{{ old('mail_host') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm">
                                        @error('mail_host')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="mail_port" class="block text-sm font-medium text-gray-700">Mail Port</label>
                                        <input type="number" id="mail_port" name="mail_port" value="{{ old('mail_port') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm">
                                        @error('mail_port')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="mail_username" class="block text-sm font-medium text-gray-700">Mail Username</label>
                                        <input type="text" id="mail_username" name="mail_username" value="{{ old('mail_username') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm">
                                        @error('mail_username')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="mail_password" class="block text-sm font-medium text-gray-700">Mail Password</label>
                                        <input type="password" id="mail_password" name="mail_password" value="{{ old('mail_password') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm">
                                        @error('mail_password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="mail_from_address" class="block text-sm font-medium text-gray-700">Mail From Address</label>
                                        <input type="email" id="mail_from_address" name="mail_from_address" value="{{ old('mail_from_address') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm">
                                        @error('mail_from_address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="mail_from_name" class="block text-sm font-medium text-gray-700">Mail From Name</label>
                                        <input type="text" id="mail_from_name" name="mail_from_name" value="{{ old('mail_from_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm">
                                        @error('mail_from_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Queue Settings -->
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Queue Settings</h2>
                            <div class="space-y-4">
                                <div>
                                    <label for="queue_connection" class="block text-sm font-medium text-gray-700">Queue Driver</label>
                                    <select id="queue_connection" name="queue_connection" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm" required>
                                        <option value="sync" {{ old('queue_connection', 'sync') === 'sync' ? 'selected' : '' }}>Sync (No worker needed)</option>
                                        <option value="database" {{ old('queue_connection') === 'database' ? 'selected' : '' }}>Database (Requires cron)</option>
                                    </select>
                                    <p class="mt-1 text-sm text-gray-500">Choose 'Sync' for simple setups or 'Database' for asynchronous processing with a cron job.</p>
                                    @error('queue_connection')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-[var(--primary)] text-white font-semibold rounded-md hover:bg-[var(--primary-dark)]">
                            Save and Continue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
