<?php

namespace Nuhel\FilamentCroppie;

use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;

class FilamentCroppieServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-croppie';

    protected array $styles = [
        'filament-croppie-style' => __DIR__ . '/../resources/dist/css/filament-croppie.css',
    ];

    protected array $scripts = [
        'filament-croppie-script' => __DIR__ . '/../resources/dist/js/filament-croppie.js',
        'filament-croppie-exif-js' => __DIR__ . '/../resources/dist/js/exif.js',
    ];

    protected array $beforeCoreScripts = [
        'filament-croppie-croppie-component-script' => __DIR__ . '/../resources/dist/js/component.js',
    ];

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasAssets()
            ->hasViews();
    }
}