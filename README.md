# Filament Croppie Plugin

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nuhel/filament-croppie.svg?style=flat-square)](https://packagist.org/packages/nuhel/filament-croppie)
[![Total Downloads](https://img.shields.io/packagist/dt/nuhel/filament-croppie.svg?style=flat-square)](https://packagist.org/packages/nuhel/filament-croppie)


## Installation

You can install the package via composer:

```bash
composer require nuhel/filament-croppie
```

This field has most of the same functionality of the [Filament File Upload](https://filamentphp.com/docs/2.x/forms/fields#file-upload) field.

![screenshot of square croppie](./images/square-example.png)
```php
  Croppie::make('image')
      ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
          return (string)str("image_path/" . $file->hashName());
      })->enableDownload()
      ->enableOpen()
      ->imageResizeTargetWidth('1000')
      ->imageResizeTargetHeight('800'),
```
Using `imageResizeTargetWidth` and `imageResizeTargetHeight` we can set width and height of the cropper.

```php
Croppie::make('avatar')->avatar()
    ->enableOpen()->enableDownload()
    ->imageResizeTargetWidth('300')
    ->imageResizeTargetHeight('300')
    ->modalSize('xl'),
```
We can make croppie circular using `avater` method.
![screenshot of big modal](./images/circural-example.png)

Modal size can be customized if it is needed,
using `modalSize` method.
```php
Croppie::make('background')
    ->enableDownload()
    ->enableOpen()
    ->imageResizeTargetWidth('1000')
    ->imageResizeTargetHeight('400')
    ->modalSize('6xl')
    ->modalHeading("Crop Background Image")
```
![screenshot of big modal](./images/7xl-modal-example.png)

This Plugin is still under development. Some Croppie Feature need's to be implemented, but it provides the main feature of Croppie.  
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
