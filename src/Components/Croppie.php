<?php

namespace Nuhel\FilamentCroppie\Components;

use Closure;
use Filament\Forms\Components\FileUpload;

class Croppie extends FileUpload
{
    protected string $view = 'filament-croppie::components.croppie';

    protected string | Closure | null $imageResizeTargetHeight = '400';
    protected string | Closure | null $imageResizeTargetWidth = '400';

    public function getAcceptedFileTypes(): ?array
    {
        $this->acceptedFileTypes([
            "image/png"," image/gif","image/jpeg"
        ]);
        return parent::getAcceptedFileTypes();

    }

}
