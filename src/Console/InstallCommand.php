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
        $this->warn('⚠️  Site has been defaulted to SUSPENDED. Ensure you configure your API keys and sync with the Control Center.');

        $this->info('✅ Installation complete.');
        $this->line('Register the middleware ZuqoLab\SiteAgent\Http\Middleware\EnforceSiteAgent::class in your bootstrap/app.php or Kernel.php');
    }
}
