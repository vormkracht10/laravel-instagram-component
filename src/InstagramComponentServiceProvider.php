<?php

namespace Vormkracht10\InstagramComponent;

use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Vormkracht10\InstagramComponent\Components\InstagramComponent;
use Vormkracht10\InstagramComponent\Components\InstagramCachedComponent;

class InstagramComponentServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('instagram-component')
            ->hasConfigFile('instagram-component')
            ->hasViews();
    }

    public function packageRegistered()
    {
        Blade::component('instagram', InstagramComponent::class);
    }
}
