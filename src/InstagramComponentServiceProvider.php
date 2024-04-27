<?php

namespace Vormkracht10\InstagramComponent;

use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Vormkracht10\InstagramComponent\Components\InstagramComponent;

class InstagramComponentServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-instagram-component')
            ->hasConfigFile()
            ->hasViews();
    }

    public function packageRegistered()
    {
        Blade::component('instagram', InstagramComponent::class);
    }
}
