<?php

namespace Nuhel\FilamentCroppie\Components;

use Closure;
use Filament\Forms\Components\FileUpload;

class Croppie extends FileUpload
{
    protected string $view = 'filament-croppie::components.croppie';

    protected string | Closure | null $imageResizeTargetHeight = '400';

    protected string | Closure | null $imageResizeTargetWidth = '400';

    protected string | Closure | null $modalSize = '6xl';

    protected string | Closure | null $modalHeading = 'Manage Image';

    protected bool | Closure $isLeftRotationEnabled = false;

    protected bool | Closure $isRightRotationEnabled = false;

    public function getAcceptedFileTypes(): ?array
    {
        $this->acceptedFileTypes([
            "image/png"," image/gif","image/jpeg","image/webp"
        ]);

        return parent::getAcceptedFileTypes();
    }

    public function modalSize(string | Closure | null $modalSize) : static
    {
        $this->modalSize = $modalSize;

        return $this;
    }

    public function getModalSize(): ?string
    {
        return $this->evaluate($this->modalSize);
    }

    public function modalHeading(string | Closure | null $modalHeading) : static
    {
        $this->modalHeading = $modalHeading;

        return $this;
    }

    public function getModalHeading(): ?string
    {
        return $this->evaluate($this->modalHeading);
    }

    public function enableImageRotation(bool | Closure $condition = true)
    {
        $this->isRightRotationEnabled = $condition;

        $this->isLeftRotationEnabled = $condition;

        return $this;
    }

    public function enableRightImageRotation(bool | Closure $condition = true)
    {

        $this->isRightRotationEnabled = $condition;

        return $this;
    }


    public function isRightRotationEnabled()
    {
        return $this->evaluate($this->isRightRotationEnabled);
    }

    public function enableLeftImageRotation(bool | Closure $condition = true)
    {

        $this->isLeftRotationEnabled = $condition;

        return $this;
    }

    public function isLeftRotationEnabled()
    {
        return $this->evaluate($this->isLeftRotationEnabled);
    }

}
