<?php

namespace Applicazza\MailwizzApiClient;

use Illuminate\Support\ServiceProvider;

/**
 * Class MailwizzApiClientServiceProvider
 * @package Applicazza\MailwizzApiClient
 */
class MailwizzApiClientServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfiguration();
    }

    /**
     *
     */
    protected function mergeConfiguration()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/mailwizz.php', 'mailwizz'
        );
    }

    /**
     *
     */
    protected function publishConfiguration()
    {
        $this->publishes([
            __DIR__ . '/../config/mailwizz.php' => config_path('mailwizz.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfiguration();
    }
}
