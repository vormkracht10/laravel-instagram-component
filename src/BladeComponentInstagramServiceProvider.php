<?php

namespace Vormkracht10\BladeComponentInstagram;

use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Vormkracht10\BladeComponentInstagram\Components\InstagramComponent;

class BladeComponentInstagramServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('blade-component-instagram')
            ->hasConfigFile('instagram-component')
            ->hasViews();
    }

    public function packageRegistered()
    {
        Blade::component('instagram', InstagramComponent::class);
    }
}
