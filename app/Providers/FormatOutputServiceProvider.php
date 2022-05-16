<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\FormatOutput;

class FormatOutputServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('format_output', function() {
			return new FormatOutput();
		});
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
