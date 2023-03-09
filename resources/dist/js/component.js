document.addEventListener('alpine:init', () => {
    Alpine.data('imageCropper', (config) => ({
        showCroppie: false,
        hasImage: config.imageUrl.length > 0,
        filename: '',
        filetype: '',
        originalSrc: config.imageUrl,
        width: config.width,
        height: config.height,
        shape: config.shape,
        statePath: config.statePath,
        boundary: config.boundary,
        size: config.size,

        croppie: {},
        init() {
            this.$nextTick(
                () => {
                    this.initCroppie()
                }
            )
            this.$watch('files', (value) => {
                this.showCroppie = false
                this.updatePreview()
            })
        },
        async updatePreview() {
            let reader, files = this.files
            if (files == null || files[0] == undefined) {
                return;
            }
            this.filename = files[0].name;
            this.filetype = files[0].type

            reader = new FileReader()
            reader.onload = (e) => {
                this.originalSrc = e.target.result
                this.bindCroppie(e.target.result)
            }
            await reader.readAsDataURL(files[0])
        },
        initCroppie() {

            this.croppie = new Croppie(
                this.$refs.croppie, {
                viewport: { width: this.width, height: this.height, type: this.shape }, //circle or square
                boundary: { width: parseInt(this.width) + parseInt(this.boundary), height: parseInt(this.height) + parseInt(this.boundary) }, //boundary container
                showZoomer: true,
                enableResize: false,
                mouseWheelZoom: 'ctrl',
                enforceBoundary: true,
                enableOrientation: true,
                enableExif: true
            })
        },

        rotateLeft() {
            this.croppie.rotate(90);
        },

        rotateRight() {
            this.croppie.rotate(-90);
        },

        saveCroppie() {
            this.croppie.result({
                type: "blob",
                size: this.size,
                format: this.filetype,
                quality: 1
            }).then((croppedImage) => {
                this.showCroppie = false
                this.hasImage = true
                let input = document.getElementById(this.statePath).getElementsByTagName('input')[0]
                let event = new Event('change');
                let fileName = this.filename;
                let filetype = this.filetype;
                let file = new File(
                    [croppedImage],
                    fileName,
                    { type: filetype, lastModified: new Date().getTime() },
                    'utf-8'
                );
                let container = new DataTransfer();
                container.items.add(file);

                input.files = container.files;
                this.$dispatch("close-modal", { id: "croppie-modal-" + this.statePath, files: null })
                input.dispatchEvent(event);
            })
        },
        bindCroppie(src) {
            //avoid problems with croppie container not being visible when binding
            setTimeout(() => {
                this.croppie.bind({ url: src, orientation: 1 })
                setTimeout(() => {
                    this.showCroppie = true
                }, 200)
            }, 200)
        }
    }))
})
