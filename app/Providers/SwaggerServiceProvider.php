<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SwaggerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $path = config_path() . '/swagger';

        if (!is_dir($path)) {
            return;
        }

        $swaggerPath = scandir($path);

        collect($swaggerPath)->map(function ($item) {
            if (preg_match('/[a-zA-Z]/i', $item)) {
                $key = substr($item, 0, -4);

                config(["l5-swagger.documentations.$key" => config("swagger.$key")]);
            }
        });
    }
}
