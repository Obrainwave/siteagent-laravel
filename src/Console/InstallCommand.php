<?php

namespace ZuqoLab\SiteAgent\Console;

use Illuminate\Console\Command;
use ZuqoLab\SiteAgent\StateManager;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'siteagent:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the SiteAgent SDK and initialize the state file.';

    /**
     * Execute the console command.
     */
    public function handle(StateManager $stateManager): void
    {
        $this->info('🚀 Installing SiteAgent SDK...');

        // 1. Publish Configuration
        $this->call('vendor:publish', [
            '--tag' => 'siteagent-config'
        ]);

        // 2. Publish Views
        $this->call('vendor:publish', [
            '--tag' => 'siteagent-views'
        ]);

        // 3. Initialize State File
        $this->info('📁 Initializing state file...');
        $stateManager->setState('suspended');

        // 4. Register Middleware Automatically
        $this->registerMiddleware();

        $this->info('✅ Installation complete.');
        $this->warn('⚠️  Site has been defaulted to SUSPENDED. Ensure you configure your API keys in .env.');
    }

    /**
     * Attempt to register the middleware automatically.
     */
    protected function registerMiddleware(): void
    {
        $middlewareClass = \ZuqoLab\SiteAgent\Http\Middleware\EnforceSiteAgent::class;
        $laravelVersion = app()->version();

        // 1. Laravel 11+ uses bootstrap/app.php
        if (version_compare($laravelVersion, '11.0.0', '>=')) {
            if (file_exists(base_path('bootstrap/app.php'))) {
                $this->info("Detected Laravel {$laravelVersion}. Updating bootstrap/app.php...");
                $this->updateBootstrapApp($middlewareClass);
                return;
            }
        }

        // 2. Laravel 10 and below uses app/Http/Kernel.php
        if (file_exists(app_path('Http/Kernel.php'))) {
            $this->info("Detected Laravel {$laravelVersion}. Updating app/Http/Kernel.php...");
            $this->updateKernel($middlewareClass);
            return;
        }

        $this->line("Please register the middleware manually: {$middlewareClass}");
    }

    /**
     * Update bootstrap/app.php for Laravel 11+
     */
    protected function updateBootstrapApp(string $class): void
    {
        $path = base_path('bootstrap/app.php');
        $content = file_get_contents($path);

        if (str_contains($content, $class)) {
            $this->line('Middleware already registered.');
            return;
        }

        // Look for ->withMiddleware(function (Middleware $middleware) [maybe : void] {
        $pattern = '/->withMiddleware\(function\s*\(Middleware\s*\$middleware\)\s*(?::\s*\w+\s*)?\{/';
        
        if (preg_match($pattern, $content)) {
            $replacement = "$0\n        \$middleware->append(\\{$class}::class);";
            $newContent = preg_replace($pattern, $replacement, $content);
            file_put_contents($path, $newContent);
            $this->info('Successfully registered middleware in bootstrap/app.php.');
        } else {
            $this->warn('Could not automatically update bootstrap/app.php. Please configure manually.');
        }
    }

    /**
     * Update app/Http/Kernel.php for Laravel 10 and below
     */
    protected function updateKernel(string $class): void
    {
        $path = app_path('Http/Kernel.php');
        $content = file_get_contents($path);

        if (str_contains($content, $class)) {
            $this->line('Middleware already registered.');
            return;
        }

        // Look for protected $middleware = [
        $pattern = '/protected\s+\$middleware\s*=\s*\[/';
        
        if (preg_match($pattern, $content)) {
            $replacement = "$0\n        \\{$class}::class,";
            $newContent = preg_replace($pattern, $replacement, $content);
            file_put_contents($path, $newContent);
            $this->info('Successfully registered middleware in Kernel.php.');
        } else {
            $this->warn('Could not automatically update Kernel.php. Please configure manually.');
        }
    }
}
