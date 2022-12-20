# Filament Croppie Plugin


This field has most of the same functionality of the [Filament File Upload](https://filamentphp.com/docs/2.x/forms/fields#file-upload) field.

```php
  Croppie::make('image2')->label('Slider Image')->image()
      ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
          return (string)str("homepage/hero_slider_image_" . $file->hashName());
      })->enableDownload()->enableOpen()->required()
      ->imageResizeTargetWidth('1440')
      ->imageResizeTargetHeight('746'),
```



## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
