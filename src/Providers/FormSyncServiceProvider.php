<?php

namespace Matrixbrains\FormSync\Providers;

use Illuminate\Support\ServiceProvider;
use Matrixbrains\FormSync\Console\SyncFormCommand;

class FormSyncServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SyncFormCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../../stubs/react/useFormSync.ts' => base_path('resources/js/hooks/useFormSync.ts'),
            ], 'react');

            $this->publishes([
                __DIR__.'/../../stubs/vue/useFormSync.ts' => base_path('resources/js/composables/useFormSync.ts'),
            ], 'vue');
        }
    }
}
