<?php

namespace Laravel\Breeze\Console;

use Illuminate\Filesystem\Filesystem;

trait InstallsBootstrapStack
{
    /**
     * Install the Bootstrap Breeze stack.
     *
     * @return void
     */
    protected function installBootstrapStack()
    {
        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
             return [
                'bootstrap' => '^5.2.1',
                '@popperjs/core' => '^2.10.2',
                'sass' => '^1.32.11',
            ] + $packages;
        });

        // Controllers...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/default/app/Http/Controllers/Auth', app_path('Http/Controllers/Auth'));

        // Requests...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests/Auth'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/default/app/Http/Requests/Auth', app_path('Http/Requests/Auth'));

        // Views...
        (new Filesystem)->ensureDirectoryExists(resource_path('views/auth'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views/layouts'));
        

        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/bootstrap/resources/views/auth', resource_path('views/auth'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/bootstrap/resources/views/layouts', resource_path('views/layouts'));
        

        copy(__DIR__.'/../../stubs/bootstrap/resources/views/dashboard.blade.php', resource_path('views/dashboard.blade.php'));

        // Components...
        //(new Filesystem)->ensureDirectoryExists(app_path('View/Components'));
        //(new Filesystem)->copyDirectory(__DIR__.'/../../stubs/default/app/View/Components', app_path('View/Components'));

        // Tests...
        $this->installTests();

        // Routes...
        copy(__DIR__.'/../../stubs/default/routes/web.php', base_path('routes/web.php'));
        copy(__DIR__.'/../../stubs/default/routes/auth.php', base_path('routes/auth.php'));

        // "Dashboard" Route...
        $this->replaceInFile('/home', '/dashboard', resource_path('views/welcome.blade.php'));
        $this->replaceInFile('Home', 'Dashboard', resource_path('views/welcome.blade.php'));
        $this->replaceInFile('/home', '/dashboard', app_path('Providers/RouteServiceProvider.php'));

        // Bootstrap / Vite...
        copy(__DIR__.'/../../stubs/bootstrap/vite.config.js', base_path('vite.config.js'));
        
        (new Filesystem)->ensureDirectoryExists(resource_path('sass'));
        copy(__DIR__.'/../../stubs/bootstrap/_variables.scss', resource_path('sass/_variables.scss'));
        copy(__DIR__.'/../../stubs/bootstrap/app.scss', resource_path('sass/app.scss'));
        copy(__DIR__.'/../../stubs/bootstrap/bootstrap.js', resource_path('js/bootstrap.js'));

        $this->runCommands(['npm install', 'npm run build']);

        $this->line('');
        $this->components->info('Breeze scaffolding installed successfully.');
    }
}
