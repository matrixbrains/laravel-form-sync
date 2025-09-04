<?php

namespace Matrixbrains\FormSync\Console;

use Illuminate\Console\Command;
use Matrixbrains\FormSync\Services\FormSyncService;

class SyncFormCommand extends Command
{
    protected $signature = 'form:sync {request?} {--all}';
    protected $description = 'Sync Laravel FormRequest validation rules into frontend schema';

    public function handle()
    {
        $service = new FormSyncService();

        if ($this->option('all')) {
            $count = $service->syncAll();
            $this->info("✅ Synced {$count} FormRequests.");
        } else {
            $request = $this->argument('request');
            if (!$request) {
                $this->error("❌ Please provide a FormRequest class name or use --all.");
                return 1;
            }
            $service->sync($request);
            $this->info("✅ Synced {$request}.");
        }
    }
}
