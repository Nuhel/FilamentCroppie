<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isAvatar() || $isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-action="$getHintAction()"
    :hint-color="$getHintColor()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>

    @php
        $imageCropAspectRatio = $getImageCropAspectRatio();
        $imageResizeTargetHeight = $getImageResizeTargetHeight();
        $imageResizeTargetWidth = $getImageResizeTargetWidth();
        $imageResizeMode = $getImageResizeMode();
        $shouldTransformImage = $imageCropAspectRatio || $imageResizeTargetHeight || $imageResizeTargetWidth;
    @endphp


    <div class="relative" x-data="{
        fileHasUploaded : false,
        fileHasDeleted: false,
     }"
    >

        <div

            x-data="fileUploadFormComponent({
            acceptedFileTypes: {{ json_encode($getAcceptedFileTypes()) }},
            canDownload: {{ $canDownload() ? 'true' : 'false' }},
            canOpen: {{ $canOpen() ? 'true' : 'false' }},
            canPreview: {{ $canPreview() ? 'true' : 'false' }},
            canReorder: {{ $canReorder() ? 'true' : 'false' }},
            deleteUploadedFileUsing: async (fileKey) => {
                fileHasDeleted = true;
                fileHasUploaded = false;
                return await $wire.deleteUploadedFile('{{ $getStatePath() }}', fileKey)
            },
            getUploadedFileUrlsUsing: async () => {
                return await $wire.getUploadedFileUrls('{{ $getStatePath() }}')
            },
            imageCropAspectRatio: {{ $imageCropAspectRatio ? "'{$imageCropAspectRatio}'" : 'null' }},
            imagePreviewHeight: {{ ($height = $getImagePreviewHeight()) ? "'{$height}'" : 'null' }},
            imageResizeMode: {{ $imageResizeMode ? "'{$imageResizeMode}'" : 'null' }},
            imageResizeTargetHeight: {{ $imageResizeTargetHeight ? "'{$imageResizeTargetHeight}'" : 'null' }},
            imageResizeTargetWidth: {{ $imageResizeTargetWidth ? "'{$imageResizeTargetWidth}'" : 'null' }},
            isAvatar: {{ $isAvatar() ? 'true' : 'false' }},
            loadingIndicatorPosition: '{{ $getLoadingIndicatorPosition() }}',
            locale: @js(app()->getLocale()),
            panelAspectRatio: {{ ($aspectRatio = $getPanelAspectRatio()) ? "'{$aspectRatio}'" : 'null' }},
            panelLayout: {{ ($layout = $getPanelLayout()) ? "'{$layout}'" : 'null' }},
            placeholder: @js($getPlaceholder()),
            maxSize: {{ ($size = $getMaxSize()) ? "'{$size} KB'" : 'null' }},
            minSize: {{ ($size = $getMinSize()) ? "'{$size} KB'" : 'null' }},
            removeUploadedFileUsing: async (fileKey) => {
                fileHasDeleted = true;
                fileHasUploaded = false;
                return await $wire.removeUploadedFile('{{ $getStatePath() }}', fileKey)
            },
            removeUploadedFileButtonPosition: '{{ $getRemoveUploadedFileButtonPosition() }}',
            reorderUploadedFilesUsing: async (files) => {
                return await $wire.reorderUploadedFiles('{{ $getStatePath() }}', files)
            },
            shouldAppendFiles: {{ $shouldAppendFiles() ? 'true' : 'false' }},
            shouldTransformImage: {{ $shouldTransformImage ? 'true' : 'false' }},
            state: $wire.{{ $applyStateBindingModifiers('entangle(\'' . $getStatePath() . '\')') }},
            uploadButtonPosition: '{{ $getUploadButtonPosition() }}',
            uploadProgressIndicatorPosition: '{{ $getUploadProgressIndicatorPosition() }}',
            uploadUsing: (fileKey, file, success, error, progress) => {
                $wire.upload(`{{ $getStatePath() }}.${fileKey}`, file, () => {
                    fileHasUploaded = true;
                    fileHasDeleted = false;
                    success(fileKey)
                }, error, progress)
            },
        })"
            wire:ignore
            {!! ($id = $getId()) ? "id=\"{$id}\"" : null !!}
            style="min-height: {{ $isAvatar() ? '8em' : ($getPanelLayout() === 'compact' ? '2.625em' : '4.75em') }}"
            {{ $attributes->merge($getExtraAttributes())->class([
                'filament-forms-file-upload-component',
                'w-32 mx-auto' => $isAvatar(),
            ]) }}
            {{ $getExtraAlpineAttributeBag() }}
        >
            <input
                x-ref="input"
                {{ $isDisabled() ? 'disabled' : '' }}
                {{ $isMultiple() ? 'multiple' : '' }}
                type="file"
                {{ $getExtraInputAttributeBag() }}
                dusk="filament.forms.{{ $getStatePath() }}"

            />
        </div>

        @php
            $uniquemodalevent = \Illuminate\Support\Str::of($getStatePath())->replace('.','')->replace('_','');
        @endphp



        <input
            {{ $isDisabled() ? 'disabled' : '' }}
            type="file"
            accept="{{\Illuminate\Support\Arr::join($getAcceptedFileTypes(),',','')}}"

            x-show = "(({{$getState() == null  ? 'true':'false'}} && !fileHasUploaded) || fileHasDeleted) || {{$isMultiple()?'true':'false'}}"

            @class([
                    'croppie-image-picker',
                    "left-0 w-full cursor-pointer" => !$isAvatar(),
                    "avatar  w-32  cursor-pointer" => $isAvatar(),
            ])

            type="file"
            x-on:change = "function(){
                var fileType = event.target.files[0]['type'];
                if (!(fileType.search(`image`) >= 0)) {
                    new Notification()
                    .title('Error')
                        .danger()
                        .body('Selected file is not an valid image')
                        .send()
                        return;
                }
                $dispatch('on-croppie-modal-show-{{$uniquemodalevent}}', {
                    id: 'croppie-modal-{{ $getStatePath() }}',
                    files: event.target.files,

                })
            }" />


    </div>

    <div x-data="{files:null,}" @on-croppie-modal-show-{{ $uniquemodalevent }}.window="
            files = $event.detail.files;
            id = $event.detail.id;
            $dispatch('open-modal', {id: id})
        ">
        <x-filament::modal
            class=""
            width="{{$getModalSize()}}"

            id="croppie-modal-{{ $getStatePath() }}"
        >
            <x-slot name="heading">
                <x-filament::modal.heading>
                    {{$getModalHeading()}}
                </x-filament::modal.heading>
            </x-slot>
            <div class=" z-5 w-full h-full flex flex-col justify-between"

                 x-data="imageCropper({
                        imageUrl: '',
                        shape: `{{$isAvatar()?'circle':'square'}}`,
                        files: files,
                        width: `{{$getImageResizeTargetWidth()}}`,
                        height: `{{$getImageResizeTargetHeight()}}`,
                        statePath : `{{$getStatePath()}}`

                    })" x-cloak
            >
                <div class="h-full w-full" wire:ignore >
                    {{-- init Alpine --}}
                    <div class="h-full w-full relative"  >
                        <div  x-on:click.prevent class="bg-transparent h-full">
                            <div class="m-auto flex-col" x-ref="croppie"></div>
                        </div>

                        <div x-show="!showCroppie" class="absolute top-0 left-0 w-full h-full bg-white z-10 flex items-center justify-center">

                            <div aria-label="Loading..." role="status" class="flex items-center space-x-2">
                                <span class="text-xs font-medium text-gray-500">Loading...</span>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="flex justify-center items-center gap-2">
                    <x-filament::button type="button"  x-on:click.prevent="saveCroppie()">
                        @lang('filament::resources/pages/edit-record.form.actions.save.label')
                    </x-filament::button>
                </div>

            </div>


        </x-filament::modal>
    </div>

</x-dynamic-component>



