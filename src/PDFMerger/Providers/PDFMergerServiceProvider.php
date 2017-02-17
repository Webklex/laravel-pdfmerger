<?php
/*
* File:     PDFMergerServiceProvider.php
* Category: Provider
* Author:   M. Goldenbaum
* Created:  01.12.16 20:28
* Updated:  -
*
* Description:
*  -
*/

namespace Webklex\PDFMerger\Providers;

use Illuminate\Support\ServiceProvider;
use Webklex\PDFMerger\PDFMerger;

class PDFMergerServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('PDFMerger', function ($app) {
            $oPDFMerger = new PDFMerger($app['files']);
            return $oPDFMerger;
        });
    }
}