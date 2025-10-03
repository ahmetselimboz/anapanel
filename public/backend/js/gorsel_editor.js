class GorselEditor {
    constructor() {
        this.settings = {
            width: 777,
            height: 510,
            loaderText: "Editör Yükleniyor"
        };

        this.state = {
            isDragging: false,
            isDraggingImage: false,
            dragStartX: 0,
            dragStartY: 0,
            imageScale: 1,
            imageX: undefined,
            imageY: undefined,
            controlPoints: null,
            isResizing: false,
            activeControlPoint: null,
            startX: 0,
            startY: 0,
            originalWidth: 0,
            originalHeight: 0,
            originalX: 0,
            originalY: 0,
            originalRotation: 0,
            isModifying: false,
            imageRotation: 0
        };

        this.elements = [];
        this.selectedElement = null;

        this.ui = {
            button: null,
            fileInput: null,
            modal: null,
            canvas: null,
            ctx: null,
            loader: null,
            innerCanvasDiv: null
        };

        this.oldFileInputValue = null;
        this.image = null;

        this.boundEventHandlers = {
            handleMouseDown: this.handleMouseDown.bind(this),
            handleMouseUp: this.handleMouseUp.bind(this),
            handleWheel: this.handleWheel.bind(this),
            handleDblClick: this.handleDoubleClick.bind(this)
        };

        this.boundEventHandlersForModal = {
            handleMouseMove: this.handleMouseMove.bind(this),
            handleMouseDown: this.handleMouseDownModal.bind(this),
            handleMouseUp: this.handleMouseUpModal.bind(this),
        }

        this.history = {
            undoStack: [],
            redoStack: [],
            maxSteps: 50
        };

        this.clipboard = null;
        this.savedState = null;
    }

    init(selector = false, width = 777, height = 510, savedState = null) {
        if (!selector) {
            console.error("Hata: Init fonksiyonu seçici eksik!");
            return false;
        }

        this.ui.button = document.querySelector(selector);
        if (!this.ui.button) {
            console.error("Hata: Buton bulunamadı!");
            return false;
        }

        this.settings.width = width;
        this.settings.height = height;
        this.savedState = savedState;

        this.ui.button.addEventListener("click", () => this.buttonHandleClick());
        return true;
    }

    getResponse() {
        if(!this.oldFileInputValue)
            return;
        const file = this.oldFileInputValue[0];

        const formData = new FormData();
        formData.append('image', file);

        return fetch(editor_originalsave_url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                return {
                    elements: this.elements,
                    imageState: {
                        scale: this.state.imageScale,
                        x: this.state.imageX,
                        y: this.state.imageY,
                        rotation: this.state.imageRotation
                    },
                    original_image: data.image_url
                };
            })
            .catch(error => {
                console.error('EDİTÖR KAYIT SIKINTILI:', error);
                alert('EDİTÖR KAYIT SIKINTILI.');
                this.modalLoaderClose();
                return null;  // Return null if there was an error
            });
    }

    buttonHandleClick() {
        const inputSelector = this.ui.button.getAttribute("data-input-ref");
        this.ui.fileInput = document.querySelector(inputSelector);

        if (!this.ui.fileInput) {
            console.error("Hata: Bağlı input bulunamadı!");
            return;
        }

        let timeout = 0;
        if (this.savedState) {
            timeout = 500;
            this.loadSavedState(this.savedState);
        }

        setTimeout(() => {
            if (this.ui.fileInput.files.length > 0) {
                this.oldFileInputValue = this.ui.fileInput.files;
                this.startProcess();
            } else {
                this.showError();
            }
        }, timeout);
    }

    start() {
        return this.buttonHandleClick();
    }

    startProcess() {
        this.removeErrorMessage();
        this.modalCreate();
    }

    async loadSavedState(savedState) {
        if (!savedState) return;

        const imageUrl = public_upload_url + '/' + savedState.original_image;
        const response = await fetch(imageUrl);
        const blob = await response.blob();
        const file = new File([blob], "image.jpg", {type: blob.type});

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        this.ui.fileInput.files = dataTransfer.files;

        if (savedState.elements) {
            this.elements = savedState.elements;
        }

        if (savedState.imageState) {
            this.state.imageScale = savedState.imageState.scale || 1;
            this.state.imageX = savedState.imageState.x;
            this.state.imageY = savedState.imageState.y;
            this.state.imageRotation = savedState.imageState.rotation || 0;
        }
    }

    modalCreate() {
        document.body.style.overflow = 'hidden';
        this.ui.modal = document.createElement('div');
        this.ui.modal.classList.add('gorsel-editor-wrapper', 'open');

        const overlay = document.createElement('div');
        overlay.classList.add('gorsel-editor-overlay');
        this.ui.modal.appendChild(overlay);

        const inner = document.createElement('div');
        inner.classList.add('gorsel-editor-inner');

        const modalHeader = this.createHeader();
        inner.appendChild(modalHeader);

        const editorContainer = document.createElement('div');
        editorContainer.classList.add('editor-container');

        const toolbox = this.createToolbox();
        editorContainer.appendChild(toolbox);


        const canvasContainer = document.createElement('div');
        canvasContainer.classList.add('editor-canvas-container');

        const canvasContainerInner = document.createElement('div');
        this.ui.innerCanvasDiv = canvasContainerInner;
        canvasContainer.appendChild(canvasContainerInner);

        this.ui.canvas = document.createElement('canvas');
        this.ui.canvas.width = this.settings.width;
        this.ui.canvas.height = this.settings.height;
        this.ui.ctx = this.ui.canvas.getContext('2d');

        canvasContainerInner.appendChild(this.ui.canvas);
        editorContainer.appendChild(canvasContainer);

        inner.appendChild(editorContainer);

        const loader = this.createLoader();
        inner.appendChild(loader);

        this.ui.modal.appendChild(inner);
        document.body.appendChild(this.ui.modal);

        this.setupEventListeners();

        setTimeout(() => {
            this.loadImage();
            this.ui.modal.classList.add('show');
        }, 100);
    }

    createHeader() {
        const header = document.createElement('div');
        header.className = "gorsel-editor-header";
        header.innerHTML = `
            <h1>Resim Tasarlama</h1>
            <button type='button'>x</button>
        `;
        this.listenClose(header);
        return header;
    }

    createToolbox() {
        const toolbox = document.createElement('div');
        toolbox.classList.add('editor-toolbox');


        const reloadBtn = document.createElement('button');
        reloadBtn.textContent = 'Yenile';
        reloadBtn.onclick = () => this.reload();

        const addTextBtn = document.createElement('button');
        addTextBtn.textContent = 'Yazı Ekle';
        addTextBtn.onclick = () => this.addText();


        const addShapeBtn = document.createElement('button');
        addShapeBtn.textContent = 'Şekil Ekle';
        addShapeBtn.onclick = (e) => this.showShapePicker(e);


        const addImageBtn = document.createElement('button');
        addImageBtn.textContent = 'Resim Liste';
        addImageBtn.onclick = () => this.showImagePicker();


        const layerUpBtn = document.createElement('button');
        layerUpBtn.textContent = 'Katmanı Yukarı Taşı';
        layerUpBtn.onclick = () => this.moveLayer(1);

        const layerDownBtn = document.createElement('button');
        layerDownBtn.textContent = 'Katmanı Aşağı Taşı';
        layerDownBtn.onclick = () => this.moveLayer(-1);


        const deleteBtn = document.createElement('button');
        deleteBtn.textContent = 'Seçili Öğeyi Sil';
        deleteBtn.classList.add('deleteBtn');
        deleteBtn.onclick = () => this.deleteSelectedElement();

        const saveBtn = document.createElement('button');
        saveBtn.textContent = 'Tasarımı Kaydet';
        saveBtn.classList.add('saveBtn');
        saveBtn.onclick = () => this.saveChanges();

        toolbox.append(reloadBtn , addTextBtn, addShapeBtn, addImageBtn, layerUpBtn, layerDownBtn, deleteBtn, saveBtn);
        return toolbox;
    }

    reload() {
        return this.redraw();
    }

    saveChanges() {
        this.modalLoaderOpen();

        const finalImage = this.ui.canvas.toDataURL('image/jpeg', 0.9);

        // Base64'ten Blob'a çevir
        const byteString = atob(finalImage.split(',')[1]);
        const mimeString = finalImage.split(',')[0].split(':')[1].split(';')[0];
        const ab = new ArrayBuffer(byteString.length);
        const ia = new Uint8Array(ab);
        for (let i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        const blob = new Blob([ab], {type: mimeString});

        const fileInput = this.ui.fileInput;

        let newFileName = fileInput.files[0].name.split('.').slice(0, -1).join('.');
        newFileName = newFileName + '__edited';
        newFileName = newFileName + '_' + Math.floor(Date.now() / 1000);

        const fileName = newFileName + ".jpg";
        const file = new File([blob], fileName, {type: "image/jpeg"});

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;

        const previewContainer = fileInput.parentElement;
        let preview = previewContainer.querySelector('.image-preview');
        if (!preview) {
            preview = document.createElement('div');
            preview.className = 'image-preview mt-2';
            preview.style.maxWidth = '100px';
            previewContainer.appendChild(preview);
        }

        preview.innerHTML = `
        <img src="${finalImage}" style="width: 100%; height: auto; border-radius: 4px;">
    `;

        const event = new Event('change', {bubbles: true});
        fileInput.dispatchEvent(event);

        document.body.style.overflow = '';
        this.ui.modal.classList.remove('show');
        setTimeout(() => {
            this.ui.modal.classList.remove('open');
            this.modalLoaderClose();
        }, 300);
    }

    createShapePicker() {
        const shapes = [

            {name: 'Düz Çizgi', type: 'line', path: 'M0 10h100'},
            {name: 'Kesikli Çizgi', type: 'line', path: 'M0 10h100', style: 'dashed'},
            {name: 'Noktalı Çizgi', type: 'line', path: 'M0 10h100', style: 'dotted'},
            {name: 'Ok', type: 'line', path: 'M0 10h100', arrow: true},


            {name: 'Kare', type: 'rect'},
            {name: 'Yuvarlak Kare', type: 'roundRect'},
            {name: 'Daire', type: 'circle'},
            {name: 'Üçgen', type: 'triangle'},


            {name: 'Beşgen', type: 'polygon', sides: 5},
            {name: 'Altıgen', type: 'polygon', sides: 6},
            {name: 'Sekizgen', type: 'polygon', sides: 8},
            {name: 'Yıldız', type: 'star', points: 5},
            {name: 'Çift Yıldız', type: 'star', points: 8},
            {name: 'Çoklu Yıldız', type: 'star', points: 12}
        ];

        const picker = document.createElement('div');
        picker.classList.add('shape-picker');

        shapes.forEach(shape => {
            const option = document.createElement('div');
            option.classList.add('shape-option');
            option.innerHTML = `<svg viewBox="0 0 100 100"></svg>`;

            const svg = option.querySelector('svg');
            this.drawShapePreview(svg, shape);

            option.onclick = () => {
                this.addShape(shape);
                picker.remove();
            };

            picker.appendChild(option);
        });

        return picker;
    }

    drawShapePreview(svg, shape) {
        const path = document.createElementNS("http://www.w3.org/2000/svg", "path");

        switch (shape.type) {
            case 'line':
                if (shape.style === 'dashed') {
                    path.setAttribute('stroke-dasharray', '10,5');
                } else if (shape.style === 'dotted') {
                    path.setAttribute('stroke-dasharray', '2,5');
                }
                path.setAttribute('d', shape.path);
                path.setAttribute('stroke', '#000');
                path.setAttribute('stroke-width', '4');
                path.setAttribute('fill', 'none');
                break;

            case 'rect':
                path.setAttribute('d', 'M20 20h60v60h-60z');
                break;

            case 'roundRect':
                path.setAttribute('d', 'M20 20h60v60h-60z');
                path.setAttribute('rx', '10');
                break;

            case 'circle':
                path.setAttribute('d', 'M50 20 A30 30 0 0 1 50 80 A30 30 0 0 1 50 20');
                break;

            case 'triangle':
                path.setAttribute('d', 'M50 20 L80 80 L20 80 Z');
                break;

            case 'polygon':
                const points = this.calculatePolygonPoints(50, 50, 30, shape.sides);
                path.setAttribute('d', `M ${points.join(' L ')} Z`);
                break;

            case 'star':
                const starPoints = this.calculateStarPoints(50, 50, 30, 15, shape.points);
                path.setAttribute('d', `M ${starPoints.join(' L ')} Z`);
                break;
        }

        path.setAttribute('fill', '#475569');
        svg.appendChild(path);
    }

    calculatePolygonPoints(cx, cy, r, sides) {
        const points = [];
        for (let i = 0; i < sides; i++) {
            const angle = (i * 2 * Math.PI / sides) - Math.PI / 2;
            points.push(`${cx + r * Math.cos(angle)},${cy + r * Math.sin(angle)}`);
        }
        return points;
    }

    calculateStarPoints(cx, cy, outerR, innerR, points) {
        const starPoints = [];
        for (let i = 0; i < points * 2; i++) {
            const angle = (i * Math.PI / points) - Math.PI / 2;
            const r = i % 2 === 0 ? outerR : innerR;
            starPoints.push(`${cx + r * Math.cos(angle)},${cy + r * Math.sin(angle)}`);
        }
        return starPoints;
    }

    addShape(shapeConfig) {
        this.saveState();
        const shapeElement = {
            type: 'box',
            shapeType: shapeConfig.type,
            x: 100,
            y: 100,
            width: 100,
            height: 100,
            color: '#000000',
            opacity: 100,
            shadowColor: 'transparent',
            shadowBlur: 0,
            shadowOffsetX: 0,
            shadowOffsetY: 0,
            shadowOpacity: 50,
            shadowInset: false,

            style: shapeConfig.style,
            sides: shapeConfig.sides,
            points: shapeConfig.points,
            arrow: shapeConfig.arrow,
            rotation: 0
        };

        this.elements.push(shapeElement);
        this.selectedElement = shapeElement;
        this.redraw();
        const controls = this.createElementControls();
        const canvasContainer = this.ui.canvas.parentElement;
        canvasContainer.appendChild(controls);
    }

    createElementControls() {
        const controls = document.createElement('div');
        controls.classList.add('element-controls');


        const closeButton = document.createElement('button');
        closeButton.classList.add('close-controls-btn');
        closeButton.innerHTML = '×';
        closeButton.onclick = () => {
            this.selectedElement = null;
            this.state.controlPoints = null;
            controls.remove();
            this.redraw();
        };
        controls.appendChild(closeButton);


        if (this.selectedElement.type === 'image') {

            const sizeGroup = document.createElement('div');
            sizeGroup.classList.add('control-group');
            sizeGroup.innerHTML = '<h3>Boyut Ayarları</h3>';

            const widthInput = document.createElement('input');
            widthInput.type = 'number';
            widthInput.value = Math.round(this.selectedElement.width);
            widthInput.onchange = (e) => {
                this.selectedElement.width = parseInt(e.target.value);
                this.redraw();
            };

            const heightInput = document.createElement('input');
            heightInput.type = 'number';
            heightInput.value = Math.round(this.selectedElement.height);
            heightInput.onchange = (e) => {
                this.selectedElement.height = parseInt(e.target.value);
                this.redraw();
            };

            sizeGroup.append(
                this.createLabel('Genişlik:', widthInput),
                this.createLabel('Yükseklik:', heightInput)
            );


            const opacityGroup = document.createElement('div');
            opacityGroup.classList.add('control-group');
            opacityGroup.innerHTML = '<h3>Görünüm Ayarları</h3>';

            const opacitySlider = this.createSlider('Opaklık', 0, 100,
                this.selectedElement.opacity || 100, (value) => {
                    this.selectedElement.opacity = value;
                    this.redraw();
                });

            opacityGroup.appendChild(opacitySlider);

            controls.append(sizeGroup, opacityGroup);
            return controls;
        }


        if (this.selectedElement.type === 'box') {
            const sizeGroup = document.createElement('div');
            sizeGroup.classList.add('control-group');
            sizeGroup.innerHTML = '<h3>Boyut Ayarları</h3>';

            const widthInput = document.createElement('input');
            widthInput.type = 'number';
            widthInput.value = this.selectedElement.width;
            widthInput.onchange = (e) => {
                this.selectedElement.width = parseInt(e.target.value);
                this.redraw();
            };

            const heightInput = document.createElement('input');
            heightInput.type = 'number';
            heightInput.value = this.selectedElement.height;
            heightInput.onchange = (e) => {
                this.selectedElement.height = parseInt(e.target.value);
                this.redraw();
            };

            sizeGroup.append(
                this.createLabel('Genişlik:', widthInput),
                this.createLabel('Yükseklik:', heightInput)
            );

            controls.appendChild(sizeGroup);
        }

        if (this.selectedElement.type === 'text') {
            const textGroup = document.createElement('div');
            textGroup.classList.add('control-group');
            textGroup.innerHTML = '<h3>Yazı Ayarları</h3>';

            const fontSizeSlider = this.createSlider('Yazı Boyutu', 12, 100,
                parseInt(this.selectedElement.font) || 20, (value) => {
                    this.selectedElement.font = `${value}px ${this.selectedElement.fontFamily || 'Arial'}`;
                    this.redraw();
                });

            const fontSelect = this.createFontSelect(
                this.selectedElement.fontFamily || 'Arial',
                (font) => {
                    this.selectedElement.fontFamily = font;
                    this.selectedElement.font = `${parseInt(this.selectedElement.font)}px ${font}`;
                    this.redraw();
                }
            );

            textGroup.append(
                this.createLabel('Font', fontSelect),
                fontSizeSlider
            );

            controls.appendChild(textGroup);
        }

        if (this.selectedElement) {

            const colorGroup = document.createElement('div');
            colorGroup.classList.add('control-group');
            colorGroup.innerHTML = '<h3>Renk ve Opaklık</h3>';

            const colorPicker = this.createColorPicker(this.selectedElement.color || '#000000', (color) => {
                this.selectedElement.color = color;
                this.redraw();
            });

            const opacitySlider = this.createSlider('Opaklık', 0, 100,
                this.selectedElement.opacity || 100, (value) => {
                    this.selectedElement.opacity = value;
                    this.redraw();
                });

            colorGroup.append(
                this.createLabel('Renk', colorPicker),
                opacitySlider
            );


            const shadowGroup = document.createElement('div');
            shadowGroup.classList.add('control-group');
            shadowGroup.innerHTML = '<h3>Gölge Ayarları</h3>';

            const shadowToggle = this.createToggle('Gölge Aktif',
                this.selectedElement.shadowColor !== 'transparent', (checked) => {
                    if (checked) {
                        this.selectedElement.shadowColor = this.selectedElement.shadowColor === 'transparent' ?
                            '#000000' : this.selectedElement.shadowColor;

                        this.selectedElement.shadowBlur = this.selectedElement.shadowBlur || 3;
                        this.selectedElement.shadowOffsetX = this.selectedElement.shadowOffsetX || 2;
                        this.selectedElement.shadowOffsetY = this.selectedElement.shadowOffsetY || 2;
                        this.selectedElement.shadowOpacity = this.selectedElement.shadowOpacity || 50;
                    } else {
                        this.selectedElement.shadowColor = 'transparent';
                    }
                    this.updateShadowColor();
                    this.redraw();
                });

            const shadowColorPicker = this.createColorPicker(
                this.selectedElement.shadowColor || '#000000',
                (color) => {
                    if (this.selectedElement.shadowColor !== 'transparent') {
                        this.selectedElement.shadowColor = color;
                        this.updateShadowColor();
                        this.redraw();
                    }
                }
            );

            const shadowOpacitySlider = this.createSlider('Gölge Opaklığı', 0, 100,
                this.selectedElement.shadowOpacity || 50, (value) => {
                    this.selectedElement.shadowOpacity = value;
                    this.updateShadowColor();
                    this.redraw();
                });

            const shadowBlurSlider = this.createSlider('Bulanıklık', 0, 50,
                this.selectedElement.shadowBlur || 0, (value) => {
                    this.selectedElement.shadowBlur = value;
                    this.redraw();
                });

            const shadowOffsetXSlider = this.createSlider('X Offset', -50, 50,
                this.selectedElement.shadowOffsetX || 0, (value) => {
                    this.selectedElement.shadowOffsetX = value;
                    this.redraw();
                });

            const shadowOffsetYSlider = this.createSlider('Y Offset', -50, 50,
                this.selectedElement.shadowOffsetY || 0, (value) => {
                    this.selectedElement.shadowOffsetY = value;
                    this.redraw();
                });

            shadowGroup.append(
                shadowToggle,
                this.createLabel('Gölge Rengi', shadowColorPicker),
                shadowOpacitySlider,
                shadowBlurSlider,
                shadowOffsetXSlider,
                shadowOffsetYSlider
            );

            controls.append(colorGroup, shadowGroup);
        }

        return controls;
    }

    createLabel(text, input) {
        const label = document.createElement('div');
        label.classList.add('control-label');
        label.innerHTML = `<span>${text}</span>`;
        label.appendChild(input);
        return label;
    }

    createLoader() {
        const loader = document.createElement('div');
        loader.classList.add('gorsel-editor--loader');

        const spinner = document.createElement('div');
        spinner.classList.add('loader-spinner');

        const loaderText = document.createElement('p');
        loaderText.innerText = this.settings.loaderText;

        loader.append(spinner, loaderText);
        this.ui.loader = loader;
        return loader;
    }

    setupEventListeners() {
        Object.entries(this.boundEventHandlers).forEach(([event, handler]) => {
            this.ui.canvas.addEventListener(event.replace('handle', '').toLowerCase(), handler);
        });

        Object.entries(this.boundEventHandlersForModal).forEach(([event, handler]) => {
            this.ui.modal.addEventListener(event.replace('handle', '').toLowerCase(), handler);
        });

        this.ui.canvas.addEventListener('wheel', (e) => {
            e.preventDefault();

            const zoomFactor = 0.1;
            if (e.deltaY < 0) {
                this.state.imageScale += zoomFactor;
            } else {
                this.state.imageScale -= zoomFactor;
            }

            this.state.imageScale = Math.max(0.1, Math.min(this.state.imageScale, 3));
            this.drawImage();
        });

        document.addEventListener('keydown', (e) => {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

            if (e.ctrlKey || e.metaKey) {
                switch (e.key.toLowerCase()) {
                    case 'z':
                        e.preventDefault();
                        if (e.shiftKey) {
                            this.redo();
                        } else {
                            this.undo();
                        }
                        break;

                    case 'c':
                        e.preventDefault();
                        this.copySelectedElement();
                        break;

                    case 'v':
                        e.preventDefault();
                        this.pasteElement();
                        break;
                }
            } else if (e.key === 'Delete' || e.key === 'Backspace') {
                e.preventDefault();
                this.deleteSelectedElement();
            }
        });


        document.addEventListener('mousedown', (e) => {
            if (!this.ui.canvas.contains(e.target) &&
                !e.target.closest('.element-controls') &&
                !e.target.closest('.editor-toolbox')) {
                this.selectedElement = null;
                this.state.controlPoints = null;
                this.removeElementControls();
                this.redraw();
            }
        });
    }

    removeEventListeners() {
        Object.entries(this.boundEventHandlers).forEach(([event, handler]) => {
            this.ui.canvas.removeEventListener(event.replace('handle', '').toLowerCase(), handler);
        });
    }


    throttle(func, limit) {
        let inThrottle;
        return function (...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    handleMouseMove = this.throttle((e) => {
        const rect = this.ui.canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        if (this.state.isCropping) {
            this.state.cropEndX = x;
            this.state.cropEndY = y;
            this.redraw();
            return;
        }

        if (this.state.isDraggingImage) {
            const deltaX = x - this.state.dragStartX;
            const deltaY = y - this.state.dragStartY;

            const scaledWidth = this.image.width * this.state.imageScale;
            const scaledHeight = this.image.height * this.state.imageScale;

            this.state.imageX += deltaX;
            this.state.imageY += deltaY;

            this.state.dragStartX = x;
            this.state.dragStartY = y;

            this.redraw();
            return;
        }

        if (this.state.isResizing && this.selectedElement) {
            const point = this.state.activeControlPoint;
            const dx = x - this.state.startX;
            const dy = y - this.state.startY;

            if (point.type === 'rotate') {
                const centerX = this.selectedElement.x + this.selectedElement.width / 2;
                const centerY = this.selectedElement.y + this.selectedElement.height / 2;
                const startAngle = Math.atan2(this.state.startY - centerY, this.state.startX - centerX);
                const currentAngle = Math.atan2(y - centerY, x - centerX);
                let rotation = (currentAngle - startAngle) * (180 / Math.PI);

                if (e.shiftKey) {
                    rotation = Math.round(rotation / 15) * 15;
                }

                this.selectedElement.rotation = (this.state.originalRotation + rotation) % 360;
                this.redraw();
            } else {
                switch (point.cursor) {
                    case 'nw-resize':
                        this.selectedElement.x = this.state.originalX + dx;
                        this.selectedElement.y = this.state.originalY + dy;
                        this.selectedElement.width = this.state.originalWidth - dx;
                        this.selectedElement.height = this.state.originalHeight - dy;
                        break;
                    case 'ne-resize':
                        this.selectedElement.y = this.state.originalY + dy;
                        this.selectedElement.width = this.state.originalWidth + dx;
                        this.selectedElement.height = this.state.originalHeight - dy;
                        break;
                    case 'se-resize':
                        this.selectedElement.width = this.state.originalWidth + dx;
                        this.selectedElement.height = this.state.originalHeight + dy;
                        break;
                    case 'sw-resize':
                        this.selectedElement.x = this.state.originalX + dx;
                        this.selectedElement.width = this.state.originalWidth - dx;
                        this.selectedElement.height = this.state.originalHeight + dy;
                        break;
                    case 'n-resize':
                        this.selectedElement.y = this.state.originalY + dy;
                        this.selectedElement.height = this.state.originalHeight - dy;
                        break;
                    case 'e-resize':
                        this.selectedElement.width = this.state.originalWidth + dx;
                        break;
                    case 's-resize':
                        this.selectedElement.height = this.state.originalHeight + dy;
                        break;
                    case 'w-resize':
                        this.selectedElement.x = this.state.originalX + dx;
                        this.selectedElement.width = this.state.originalWidth - dx;
                        break;
                }
            }
            this.redraw();
        } else if (this.state.isDragging && this.selectedElement) {
            this.selectedElement.x = x - this.state.dragStartX;
            this.selectedElement.y = y - this.state.dragStartY;
            this.redraw();
        }

        this.updateCursor(x, y);

        if (this.state.isResizing || this.state.isDragging) {
            if (!this.state.isModifying) {
                this.saveState();
                this.state.isModifying = true;
            }
        }
    }, 16);

    handleMouseDown(e) {
        window.addEventListener("mouseup", this.handleMouseUp);
        const rect = this.ui.canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        if (this.state.isCropping) {
            this.state.cropStartX = x;
            this.state.cropStartY = y;
            this.state.cropEndX = x;
            this.state.cropEndY = y;
            return;
        }

        let clickedOnElement = false;
        let clickedOnControlPoint = false;

        if (this.selectedElement && this.state.controlPoints) {
            for (let point of this.state.controlPoints) {
                if (this.isPointInControlPoint(x, y, point)) {
                    clickedOnControlPoint = true;
                    this.state.isResizing = true;
                    this.state.activeControlPoint = point;
                    this.state.startX = x;
                    this.state.startY = y;
                    this.state.originalWidth = this.selectedElement.width;
                    this.state.originalHeight = this.selectedElement.height;
                    this.state.originalX = this.selectedElement.x;
                    this.state.originalY = this.selectedElement.y;
                    this.state.originalRotation = this.selectedElement.rotation || 0;
                    break;
                }
            }
        }


        if (!clickedOnControlPoint) {
            for (let i = this.elements.length - 1; i >= 0; i--) {
                const element = this.elements[i];
                if (this.isPointInElement(x, y, element)) {
                    clickedOnElement = true;

                    if (this.selectedElement !== element) {
                        this.selectedElement = element;
                        this.removeElementControls();
                        const controls = this.createElementControls();
                        const canvasContainer = this.ui.canvas.parentElement;
                        canvasContainer.appendChild(controls);
                    }

                    this.state.isDragging = true;
                    this.state.dragStartX = x - element.x;
                    this.state.dragStartY = y - element.y;
                    break;
                }
            }
        }


        if (!clickedOnElement && !clickedOnControlPoint && this.isPointInImage(x, y)) {

            if (this.isPointInImage(x, y) && !this.selectedElement) {
                this.state.isDraggingImage = true;
                this.state.dragStartX = x;
                this.state.dragStartY = y;
            }

            if (!document.querySelector('.image-controls')) {
                this.removeElementControls();
                const controls = this.createImageControls();
                const canvasContainer = this.ui.canvas.parentElement;
                canvasContainer.appendChild(controls);
            }
            return;
        }


        if (!clickedOnElement && !clickedOnControlPoint && !this.isPointInImage(x, y)) {
            this.selectedElement = null;
            this.state.controlPoints = null;
            this.removeElementControls();
            this.redraw();
        }
    }

    handleMouseDownModal(e) {
        return true;
    }

    removeElementControls() {
        const existingControls = document.querySelectorAll('.element-controls');
        existingControls.forEach(control => control.remove());
    }

    handleMouseUp() {
        window.removeEventListener("mouseup", this.handleMouseUp);
        if (this.state.isModifying) {
            this.state.isModifying = false;
        }
        this.state.isDragging = false;
        this.state.isResizing = false;
        this.state.isDraggingImage = false;
        this.state.activeControlPoint = null;
    }

    handleMouseUpModal() {
        window.removeEventListener("mouseup", this.handleMouseUp);
        if (this.state.isModifying) {
            this.state.isModifying = false;
        }
        this.state.isDragging = false;
        this.state.isResizing = false;
        this.state.activeControlPoint = null;
    }

    isPointInElement(x, y, element) {
        if (element.type === 'text') {

            this.ui.ctx.font = element.font;
            const metrics = this.ui.ctx.measureText(element.text);
            const textHeight = parseInt(element.font);

            return x >= element.x &&
                x <= element.x + metrics.width &&
                y >= element.y - textHeight &&
                y <= element.y;
        } else if (element.rotation) {

            const centerX = element.x + element.width / 2;
            const centerY = element.y + element.height / 2;


            const dx = x - centerX;
            const dy = y - centerY;


            const angle = -element.rotation * Math.PI / 180;
            const rotatedX = dx * Math.cos(angle) - dy * Math.sin(angle);
            const rotatedY = dx * Math.sin(angle) + dy * Math.cos(angle);


            return Math.abs(rotatedX) <= element.width / 2 &&
                Math.abs(rotatedY) <= element.height / 2;
        } else {

            return x >= element.x &&
                x <= element.x + element.width &&
                y >= element.y &&
                y <= element.y + element.height;
        }
    }

    addText() {
        const text = prompt('Metin giriniz:', 'Örnek Metin');
        if (text) {
            this.saveState();
            const textElement = {
                type: 'text',
                text: text,
                x: 50,
                y: 50,
                font: '20px Arial',
                color: '#000000',
                opacity: 100,
                shadowColor: '#ffffff',
                shadowBlur: 3,
                shadowOffsetX: 0,
                shadowOffsetY: 0,
                shadowOpacity: 100
            };
            this.elements.push(textElement);
            this.selectedElement = textElement;
            this.redraw();
            const controls = this.createElementControls();
            const canvasContainer = this.ui.canvas.parentElement;
            canvasContainer.appendChild(controls);
        }
    }

    moveLayer(direction) {
        if (!this.selectedElement) return;

        const currentIndex = this.elements.indexOf(this.selectedElement);
        const newIndex = currentIndex + direction;

        if (newIndex >= 0 && newIndex < this.elements.length) {
            this.elements.splice(currentIndex, 1);
            this.elements.splice(newIndex, 0, this.selectedElement);
            this.redraw();
        }
    }

    validateFileType(file) {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        return allowedTypes.includes(file.type);
    }

    validateFileSize(file) {
        const maxSize = 10 * 1024 * 1024;
        return file.size <= maxSize;
    }

    loadImage() {
        this.modalLoaderOpen();
        const file = this.ui.fileInput.files[0];

        if (!file || !this.validateFileType(file) || !this.validateFileSize(file)) {
            this.showError("Geçersiz dosya formatı veya boyutu!");
            this.modalLoaderClose();
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            this.image = new Image();
            this.image.crossOrigin = "anonymous";
            this.image.onload = () => {
                this.drawImage();
                this.modalLoaderClose();
            };
            this.image.onerror = () => {
                this.showError("Resim yüklenirken hata oluştu!");
                this.modalLoaderClose();
            };
            this.image.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    drawImage() {
        this.ui.ctx.clearRect(0, 0, this.ui.canvas.width, this.ui.canvas.height);

        const scaledWidth = this.image.width * this.state.imageScale;
        const scaledHeight = this.image.height * this.state.imageScale;

        if (this.state.imageX === undefined || this.state.imageY === undefined) {
            this.state.imageX = (this.ui.canvas.width - scaledWidth) / 2;
            this.state.imageY = (this.ui.canvas.height - scaledHeight) / 2;
        }

        const x = this.state.imageX;
        const y = this.state.imageY;

        this.ui.ctx.save();

        if (this.state.imageRotation) {
            this.ui.ctx.translate(this.ui.canvas.width / 2, this.ui.canvas.height / 2);
            this.ui.ctx.rotate(this.state.imageRotation * Math.PI / 180);
            this.ui.ctx.translate(-this.ui.canvas.width / 2, -this.ui.canvas.height / 2);
        }

        this.ui.ctx.drawImage(
            this.image,
            x, y,
            scaledWidth,
            scaledHeight
        );

        this.ui.ctx.restore();

        if (this.state.isCropping) {
            this.drawCropOverlay();
        }

        this.elements.forEach(element => {
            if (element.type === 'text') {
                this.drawText(element);
            } else {
                this.drawBox(element);
            }
        });
    }

    drawText(element) {
        if (element.isHidden) return;
        this.ui.ctx.save();
        this.ui.ctx.font = element.font || '20px Arial';

        const opacity = element.opacity !== undefined ? element.opacity / 100 : 1;
        const rgb = this.hexToRgb(element.color || '#000000');
        this.ui.ctx.fillStyle = `rgba(${rgb.r},${rgb.g},${rgb.b},${opacity})`;

        this.ui.ctx.fillText(element.text, element.x, element.y);
        const lines = element.text.split("\n");
        let lineHeight = parseInt(element.font);

        lines.forEach((line, index) => {
            this.ui.ctx.fillText(line, element.x, element.y + (index * lineHeight));
        });

        if (this.selectedElement === element) {
            const metrics = this.ui.ctx.measureText(element.text);
            const textHeight = parseInt(element.font);
            this.ui.ctx.strokeStyle = "#3b82f6";
            this.ui.ctx.lineWidth = 2;
            this.ui.ctx.strokeRect(element.x, element.y - textHeight, metrics.width, textHeight);
        }

        this.ui.ctx.restore();
    }

    drawBox(element) {
        this.ui.ctx.save();

        const centerX = element.x + element.width / 2;
        const centerY = element.y + element.height / 2;

        if (element.rotation) {
            this.ui.ctx.translate(centerX, centerY);
            this.ui.ctx.rotate(element.rotation * Math.PI / 180);
            this.ui.ctx.translate(-centerX, -centerY);
        }

        if (element.type === 'image') {

            const img = new Image();
            img.src = element.url;

            this.ui.ctx.globalAlpha = element.opacity / 100;
            this.ui.ctx.drawImage(img, element.x, element.y, element.width, element.height);
            this.ui.ctx.globalAlpha = 1;
        } else {

            this.ui.ctx.fillStyle = this.getRGBAColor(element.color, element.opacity);


            if (element.shadowColor && element.shadowColor !== 'transparent') {
                const shadowOpacity = element.shadowOpacity / 100;
                const shadowRgb = this.hexToRgb(element.shadowColor);
                if (shadowRgb) {
                    this.ui.ctx.shadowColor = `rgba(${shadowRgb.r},${shadowRgb.g},${shadowRgb.b},${shadowOpacity})`;
                    this.ui.ctx.shadowBlur = element.shadowBlur || 0;
                    this.ui.ctx.shadowOffsetX = element.shadowOffsetX || 0;
                    this.ui.ctx.shadowOffsetY = element.shadowOffsetY || 0;
                }
            }


            switch (element.shapeType) {
                case 'line':
                    this.ui.ctx.beginPath();
                    this.ui.ctx.moveTo(element.x, element.y);
                    this.ui.ctx.lineTo(element.x + element.width, element.y);

                    if (element.style === 'dashed') {
                        this.ui.ctx.setLineDash([10, 5]);
                    } else if (element.style === 'dotted') {
                        this.ui.ctx.setLineDash([2, 5]);
                    }

                    this.ui.ctx.strokeStyle = this.getRGBAColor(element.color, element.opacity);
                    this.ui.ctx.lineWidth = 4;
                    this.ui.ctx.stroke();

                    if (element.arrow) {

                        const arrowSize = 15;
                        const angle = Math.PI / 6;

                        this.ui.ctx.beginPath();
                        this.ui.ctx.moveTo(element.x + element.width, element.y);
                        this.ui.ctx.lineTo(element.x + element.width - arrowSize * Math.cos(angle),
                            element.y - arrowSize * Math.sin(angle));
                        this.ui.ctx.moveTo(element.x + element.width, element.y);
                        this.ui.ctx.lineTo(element.x + element.width - arrowSize * Math.cos(angle),
                            element.y + arrowSize * Math.sin(angle));
                        this.ui.ctx.stroke();
                    }
                    break;

                case 'rect':
                    this.ui.ctx.fillRect(element.x, element.y, element.width, element.height);
                    break;

                case 'roundRect':
                    this.ui.ctx.beginPath();
                    this.ui.ctx.roundRect(element.x, element.y, element.width, element.height, 10);
                    this.ui.ctx.fill();
                    break;

                case 'circle':
                    this.ui.ctx.beginPath();
                    const radius = Math.min(element.width, element.height) / 2;
                    this.ui.ctx.arc(
                        element.x + element.width / 2,
                        element.y + element.height / 2,
                        radius,
                        0,
                        Math.PI * 2
                    );
                    this.ui.ctx.fill();
                    break;

                case 'triangle':
                    this.ui.ctx.beginPath();
                    this.ui.ctx.moveTo(element.x + element.width / 2, element.y);
                    this.ui.ctx.lineTo(element.x + element.width, element.y + element.height);
                    this.ui.ctx.lineTo(element.x, element.y + element.height);
                    this.ui.ctx.closePath();
                    this.ui.ctx.fill();
                    break;

                case 'polygon':
                    if (element.sides) {
                        this.ui.ctx.beginPath();
                        const points = this.calculatePolygonPoints(
                            element.x + element.width / 2,
                            element.y + element.height / 2,
                            Math.min(element.width, element.height) / 2,
                            element.sides
                        );
                        this.ui.ctx.moveTo(...points[0].split(',').map(Number));
                        for (let i = 1; i < points.length; i++) {
                            this.ui.ctx.lineTo(...points[i].split(',').map(Number));
                        }
                        this.ui.ctx.closePath();
                        this.ui.ctx.fill();
                    }
                    break;

                case 'star':
                    if (element.points) {
                        this.ui.ctx.beginPath();
                        const points = this.calculateStarPoints(
                            element.x + element.width / 2,
                            element.y + element.height / 2,
                            Math.min(element.width, element.height) / 2,
                            Math.min(element.width, element.height) / 4,
                            element.points
                        );
                        this.ui.ctx.moveTo(...points[0].split(',').map(Number));
                        for (let i = 1; i < points.length; i++) {
                            this.ui.ctx.lineTo(...points[i].split(',').map(Number));
                        }
                        this.ui.ctx.closePath();
                        this.ui.ctx.fill();
                    }
                    break;

                default:
                    this.ui.ctx.fillRect(element.x, element.y, element.width, element.height);
            }
        }

        this.ui.ctx.restore();


        if (this.selectedElement === element) {
            this.drawControlPoints(element);
        }
    }

    getRGBAColor(color, opacity = 100) {
        const rgb = this.hexToRgb(color);
        return `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, ${opacity / 100})`;
    }

    redraw() {
        if (this.image) {
            this.drawImage();
        }
    }

    modalLoaderOpen() {
        this.ui.loader.classList.add('active');
    }

    modalLoaderClose() {
        this.ui.loader.classList.remove('active');
    }

    listenClose(modalHeader) {
        modalHeader.querySelector('button').addEventListener("click", () => {
            document.body.style.overflow = '';
            this.ui.modal.classList.remove('show');
            setTimeout(() => {
                this.ui.modal.classList.remove('open');
            }, 300);
        });
    }

    showError() {
        this.ui.fileInput.classList.add("error-border");
        this.displayErrorMessage("Lütfen bir dosya seçin!");
    }

    displayErrorMessage(message) {
        this.removeErrorMessage();
        const errorMsg = document.createElement("span");
        errorMsg.className = "error-message";
        errorMsg.innerText = message;
        this.ui.fileInput.parentNode.appendChild(errorMsg);
    }

    removeErrorMessage() {
        const existingError = this.ui.fileInput?.parentNode.querySelector(".error-message");
        if (existingError) {
            existingError.remove();
        }
    }

    handleWheel(e) {
        if (e.ctrlKey) {
            e.preventDefault();
            const delta = e.deltaY * -0.01;
            const newScale = this.state.imageScale + delta;

            if (newScale >= 0.1 && newScale <= 3) {
                this.state.imageScale = newScale;
                this.redraw();
            }
        }
    }

    handleDoubleClick(e) {
        const rect = this.ui.canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        for (let element of this.elements) {
            if (element.type === 'text' && this.isPointInElement(x, y, element)) {
                this.enableTextEditing(element, x, y);
                return;
            }
        }
    }

    enableTextEditing(element, x, y) {
        element.isHidden = true;
        this.redraw();

        const input = document.createElement("textarea");
        input.value = element.text;
        input.style.position = "absolute";
        input.style.left = `${x}px`;
        input.style.top = `${y}px`;
        input.style.width = "auto";
        input.style.height = "auto";
        input.style.minWidth = "400px";
        input.style.font = element.font;
        input.style.border = "2px solid #3b82f6";
        input.style.background = "transparent";
        input.style.padding = "5px";
        input.style.boxShadow = "0px 0px 5px rgba(0,0,0,0.3)";
        input.style.zIndex = "9999";

        this.ui.innerCanvasDiv.appendChild(input);
        input.focus();

        input.addEventListener("blur", () => {
            element.text = input.value;
            element.isHidden = false;
            this.redraw();
            input.remove();
        });

        input.addEventListener("keydown", (e) => {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault();
                input.blur();
            }
        });
    }

    isPointInImage(x, y) {
        if (!this.image) return false;

        const scaledWidth = this.image.width * this.state.imageScale;
        const scaledHeight = this.image.height * this.state.imageScale;
        const imageX = (this.ui.canvas.width - scaledWidth) / 2;
        const imageY = (this.ui.canvas.height - scaledHeight) / 2;


        if (this.state.imageRotation) {
            const centerX = this.ui.canvas.width / 2;
            const centerY = this.ui.canvas.height / 2;


            const dx = x - centerX;
            const dy = y - centerY;


            const angle = -this.state.imageRotation * Math.PI / 180;
            const rotatedX = dx * Math.cos(angle) - dy * Math.sin(angle) + centerX;
            const rotatedY = dx * Math.sin(angle) + dy * Math.cos(angle) + centerY;

            return rotatedX >= imageX &&
                rotatedX <= imageX + scaledWidth &&
                rotatedY >= imageY &&
                rotatedY <= imageY + scaledHeight;
        }

        return x >= imageX &&
            x <= imageX + scaledWidth &&
            y >= imageY &&
            y <= imageY + scaledHeight;
    }

    createColorPicker(initialColor, onChange) {
        const wrapper = document.createElement('div');
        wrapper.classList.add('color-picker-wrapper');

        const colorInput = document.createElement('input');
        colorInput.type = 'color';
        colorInput.value = initialColor;
        colorInput.onchange = (e) => onChange(e.target.value);

        wrapper.appendChild(colorInput);
        return wrapper;
    }

    createSlider(label, min, max, value, onChange) {
        const wrapper = document.createElement('div');
        wrapper.classList.add('slider-wrapper');

        const labelElem = document.createElement('span');
        labelElem.textContent = label;

        const slider = document.createElement('input');
        slider.type = 'range';
        slider.min = min;
        slider.max = max;
        slider.value = value;

        const valueDisplay = document.createElement('span');
        valueDisplay.classList.add('slider-value');
        value = Math.floor(value);
        valueDisplay.textContent = value;

        slider.oninput = (e) => {
            const val = parseInt(e.target.value);
            valueDisplay.textContent = val;
            onChange(val);
        };

        wrapper.append(labelElem, slider, valueDisplay);
        return wrapper;
    }

    createToggle(label, checked, onChange) {
        const wrapper = document.createElement('div');
        wrapper.classList.add('toggle-wrapper');

        const toggle = document.createElement('input');
        toggle.type = 'checkbox';
        toggle.checked = checked;
        toggle.onchange = (e) => onChange(e.target.checked);

        const labelElem = document.createElement('span');
        labelElem.textContent = label;

        wrapper.append(toggle, labelElem);
        return wrapper;
    }

    createFontSelect(currentFont, onChange) {
        const select = document.createElement('select');
        const fonts = [
            'Arial', 'Helvetica', 'Times New Roman', 'Courier New',
            'Georgia', 'Verdana', 'Impact'
        ];

        fonts.forEach(font => {
            const option = document.createElement('option');
            option.value = font;
            option.textContent = font;
            option.style.fontFamily = font;
            if (font === currentFont) option.selected = true;
            select.appendChild(option);
        });

        select.onchange = (e) => onChange(e.target.value);
        return select;
    }

    updateShadowColor() {
        if (this.selectedElement.shadowColor && this.selectedElement.shadowColor !== 'transparent') {
            const opacity = this.selectedElement.shadowOpacity / 100;
            const rgb = this.hexToRgb(this.selectedElement.shadowColor);
            if (rgb) {
                this.selectedElement.currentShadowColor = `rgba(${rgb.r},${rgb.g},${rgb.b},${opacity})`;
            }
        }
    }

    hexToRgb(hex) {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }

    destroy() {
        this.removeEventListeners();
        this.elements = [];
        this.selectedElement = null;
        this.image = null;

        if (this.ui.modal && this.ui.modal.parentNode) {
            this.ui.modal.parentNode.removeChild(this.ui.modal);
        }
    }

    drawControlPoints(element) {
        if (this.selectedElement === element) {

            const points = [
                {x: element.x, y: element.y, cursor: 'nw-resize', type: 'corner'},
                {x: element.x + element.width, y: element.y, cursor: 'ne-resize', type: 'corner'},
                {x: element.x, y: element.y + element.height, cursor: 'sw-resize', type: 'corner'},
                {x: element.x + element.width, y: element.y + element.height, cursor: 'se-resize', type: 'corner'},

                {x: element.x + element.width / 2, y: element.y, cursor: 'n-resize', type: 'edge'},
                {x: element.x + element.width, y: element.y + element.height / 2, cursor: 'e-resize', type: 'edge'},
                {x: element.x + element.width / 2, y: element.y + element.height, cursor: 's-resize', type: 'edge'},
                {x: element.x, y: element.y + element.height / 2, cursor: 'w-resize', type: 'edge'},

                {x: element.x + element.width / 2, y: element.y - 30, cursor: 'crosshair', type: 'rotate'}
            ];

            points.forEach(point => {
                this.ui.ctx.beginPath();
                if (point.type === 'rotate') {

                    this.ui.ctx.moveTo(element.x + element.width / 2, element.y);
                    this.ui.ctx.lineTo(point.x, point.y);
                    this.ui.ctx.strokeStyle = '#3b82f6';
                    this.ui.ctx.stroke();

                    this.ui.ctx.beginPath();
                    this.ui.ctx.arc(point.x, point.y, 6, 0, Math.PI * 2);
                    this.ui.ctx.fillStyle = '#fff';
                    this.ui.ctx.fill();
                    this.ui.ctx.strokeStyle = '#3b82f6';
                    this.ui.ctx.stroke();
                } else {

                    this.ui.ctx.rect(point.x - 4, point.y - 4, 8, 8);
                    this.ui.ctx.fillStyle = '#fff';
                    this.ui.ctx.fill();
                    this.ui.ctx.strokeStyle = '#3b82f6';
                    this.ui.ctx.stroke();
                }
            });


            this.state.controlPoints = points;
        }
    }

    isPointInControlPoint(x, y, point) {
        return Math.abs(x - point.x) <= 6 && Math.abs(y - point.y) <= 6;
    }

    updateCursor(x, y) {
        if (this.state.controlPoints) {
            for (let point of this.state.controlPoints) {
                if (this.isPointInControlPoint(x, y, point)) {
                    this.ui.canvas.style.cursor = point.cursor;
                    return;
                }
            }
        }
        this.ui.canvas.style.cursor = 'default';
    }

    saveState() {

        const state = {
            elements: JSON.parse(JSON.stringify(this.elements)),
            selectedIndex: this.selectedElement ? this.elements.indexOf(this.selectedElement) : -1
        };

        this.history.undoStack.push(state);
        this.history.redoStack = [];


        if (this.history.undoStack.length > this.history.maxSteps) {
            this.history.undoStack.shift();
        }
    }

    undo() {
        if (this.history.undoStack.length > 0) {

            const currentState = {
                elements: JSON.parse(JSON.stringify(this.elements)),
                selectedIndex: this.selectedElement ? this.elements.indexOf(this.selectedElement) : -1
            };
            this.history.redoStack.push(currentState);


            const previousState = this.history.undoStack.pop();
            this.elements = previousState.elements;
            this.selectedElement = previousState.selectedIndex >= 0 ? this.elements[previousState.selectedIndex] : null;

            this.redraw();
        }
    }

    redo() {
        if (this.history.redoStack.length > 0) {

            const currentState = {
                elements: JSON.parse(JSON.stringify(this.elements)),
                selectedIndex: this.selectedElement ? this.elements.indexOf(this.selectedElement) : -1
            };
            this.history.undoStack.push(currentState);


            const nextState = this.history.redoStack.pop();
            this.elements = nextState.elements;
            this.selectedElement = nextState.selectedIndex >= 0 ? this.elements[nextState.selectedIndex] : null;

            this.redraw();
        }
    }

    copySelectedElement() {
        if (this.selectedElement) {
            this.clipboard = JSON.parse(JSON.stringify(this.selectedElement));
        }
    }

    pasteElement() {
        if (this.clipboard) {
            const newElement = JSON.parse(JSON.stringify(this.clipboard));

            newElement.x += 20;
            newElement.y += 20;

            this.saveState();
            this.elements.push(newElement);
            this.selectedElement = newElement;
            this.redraw();
        }
    }

    deleteSelectedElement() {
        if (this.selectedElement) {
            this.saveState();
            const index = this.elements.indexOf(this.selectedElement);
            if (index > -1) {
                this.elements.splice(index, 1);
                this.selectedElement = null;
                this.redraw();
            }
        }
    }

    drawCropOverlay() {
        const ctx = this.ui.ctx;
        ctx.save();


        ctx.fillStyle = 'rgba(0, 0, 0, 0.5)';
        ctx.fillRect(0, 0, this.ui.canvas.width, this.ui.canvas.height);


        ctx.globalCompositeOperation = 'destination-out';
        ctx.fillStyle = 'black';
        ctx.fillRect(
            Math.min(this.state.cropStartX, this.state.cropEndX),
            Math.min(this.state.cropStartY, this.state.cropEndY),
            Math.abs(this.state.cropEndX - this.state.cropStartX),
            Math.abs(this.state.cropEndY - this.state.cropStartY)
        );


        ctx.globalCompositeOperation = 'source-over';
        ctx.strokeStyle = '#fff';
        ctx.lineWidth = 2;
        ctx.strokeRect(
            Math.min(this.state.cropStartX, this.state.cropEndX),
            Math.min(this.state.cropStartY, this.state.cropEndY),
            Math.abs(this.state.cropEndX - this.state.cropStartX),
            Math.abs(this.state.cropEndY - this.state.cropStartY)
        );

        ctx.restore();
    }

    startCropping() {
        this.state.isCropping = true;
        this.ui.canvas.style.cursor = 'crosshair';


        const scaledWidth = this.image.width * this.state.imageScale;
        const scaledHeight = this.image.height * this.state.imageScale;
        const x = (this.ui.canvas.width - scaledWidth) / 2;
        const y = (this.ui.canvas.height - scaledHeight) / 2;

        this.state.cropStartX = x;
        this.state.cropStartY = y;
        this.state.cropEndX = x + scaledWidth;
        this.state.cropEndY = y + scaledHeight;


        const cropApplyBtn = document.querySelector('.crop-apply-btn');
        if (cropApplyBtn) {
            cropApplyBtn.style.display = 'block';
        }

        this.redraw();
    }

    applyCrop() {
        if (!this.state.isCropping) return;

        const tempCanvas = document.createElement('canvas');
        const tempCtx = tempCanvas.getContext('2d');


        const cropWidth = Math.abs(this.state.cropEndX - this.state.cropStartX);
        const cropHeight = Math.abs(this.state.cropEndY - this.state.cropStartY);


        const scaledWidth = this.image.width * this.state.imageScale;
        const scaledHeight = this.image.height * this.state.imageScale;
        const imageX = (this.ui.canvas.width - scaledWidth) / 2;
        const imageY = (this.ui.canvas.height - scaledHeight) / 2;


        const cropX = Math.min(this.state.cropStartX, this.state.cropEndX) - imageX;
        const cropY = Math.min(this.state.cropStartY, this.state.cropEndY) - imageY;


        const sourceX = (cropX / this.state.imageScale);
        const sourceY = (cropY / this.state.imageScale);
        const sourceWidth = cropWidth / this.state.imageScale;
        const sourceHeight = cropHeight / this.state.imageScale;


        tempCanvas.width = sourceWidth;
        tempCanvas.height = sourceHeight;


        tempCtx.drawImage(
            this.image,
            sourceX, sourceY,
            sourceWidth, sourceHeight,
            0, 0,
            sourceWidth, sourceHeight
        );


        const croppedImage = new Image();
        croppedImage.onload = () => {
            this.saveState();
            this.image = croppedImage;
            this.state.imageScale = 1;
            this.state.isCropping = false;
            this.state.imageRotation = 0;
            this.ui.canvas.style.cursor = 'default';
            this.redraw();
        };
        croppedImage.src = tempCanvas.toDataURL();
    }

    createImageControls() {
        const controls = document.createElement('div');
        controls.classList.add('element-controls', 'image-controls');

        const closeButton = document.createElement('button');
        closeButton.classList.add('close-controls-btn');
        closeButton.innerHTML = '×';
        closeButton.onclick = () => {
            controls.remove();
        };
        controls.appendChild(closeButton);

        const controlGroups = [
            {
                title: 'Boyutlandırma',
                controls: [
                    {
                        type: 'slider',
                        label: 'Genişlik',
                        min: 50,
                        max: this.settings.width,
                        value: this.image.width * this.state.imageScale,
                        onChange: (value) => {
                            const ratio = this.image.height / this.image.width;
                            this.state.imageScale = value / this.image.width;
                            this.redraw();
                        }
                    },
                    {
                        type: 'slider',
                        label: 'Yükseklik',
                        min: 50,
                        max: this.settings.height,
                        value: this.image.height * this.state.imageScale,
                        onChange: (value) => {
                            const ratio = this.image.width / this.image.height;
                            this.state.imageScale = value / this.image.height;
                            this.redraw();
                        }
                    }
                ]
            },
            {
                title: 'Döndürme',
                controls: [
                    {
                        type: 'slider',
                        label: 'Açı',
                        min: -180,
                        max: 180,
                        value: this.state.imageRotation,
                        onChange: (value) => {
                            this.state.imageRotation = value;
                            this.redraw();
                        }
                    }
                ]
            }
        ];

        controlGroups.forEach(group => {
            const groupDiv = document.createElement('div');
            groupDiv.classList.add('control-group');

            const title = document.createElement('h3');
            title.textContent = group.title;
            groupDiv.appendChild(title);

            group.controls.forEach(control => {
                if (control.type === 'slider') {
                    const wrapper = this.createSlider(control.label, control.min, control.max, control.value, control.onChange);
                    groupDiv.appendChild(wrapper);
                }
            });

            controls.appendChild(groupDiv);
        });

        return controls;
    }

    showShapePicker(e) {
        e.preventDefault();
        e.stopPropagation();


        const existingPicker = document.querySelector('.shape-picker');
        if (existingPicker !== null) {
            existingPicker.remove();
            return;
        }

        const shapePicker = document.createElement('div');
        shapePicker.classList.add('shape-picker');

        const shapes = [
            {name: 'Kare', type: 'rect'},
            {name: 'Daire', type: 'circle'},
            {name: 'Üçgen', type: 'triangle'},
            {name: 'Yuvarlak Kare', type: 'roundRect'},
            {name: 'Beşgen', type: 'polygon', sides: 5},
            {name: 'Altıgen', type: 'polygon', sides: 6},
            {name: 'Sekizgen', type: 'polygon', sides: 8},
            {name: 'Yıldız', type: 'star', points: 5},
            {name: 'Çift Yıldız', type: 'star', points: 8},
            {name: 'Düz Çizgi', type: 'line'},
            {name: 'Kesikli Çizgi', type: 'line', style: 'dashed'},
            {name: 'Noktalı Çizgi', type: 'line', style: 'dotted'},
            {name: 'Ok', type: 'line', arrow: true}
        ];

        shapes.forEach(shape => {
            const option = document.createElement('div');
            option.classList.add('shape-option');
            option.innerHTML = `<div class="shape-name">${shape.name}</div>`;

            option.onclick = () => {
                this.addShape(shape);
                shapePicker.remove();
            };

            shapePicker.appendChild(option);
        });


        const button = e.currentTarget;
        const buttonRect = button.getBoundingClientRect();
        shapePicker.style.position = 'fixed';
        shapePicker.style.left = `${buttonRect.right + 10}px`;
        shapePicker.style.top = `${buttonRect.top}px`;

        document.body.appendChild(shapePicker);


        setTimeout(() => {
            document.addEventListener('click', function closeShapePicker(e) {
                if (!shapePicker.contains(e.target) && e.target !== button) {
                    shapePicker.remove();
                    document.removeEventListener('click', closeShapePicker);
                }
            });
        }, 0);
    }

    showImagePicker() {

        const existingPicker = document.querySelector('.image-picker');
        if (existingPicker) {
            existingPicker.remove();
            return;
        }

        const picker = document.createElement('div');
        picker.classList.add('image-picker');


        const recentImages = document.createElement('div');
        recentImages.classList.add('recent-images');
        recentImages.innerHTML = `
            <h3>Son Yüklenen Resimler</h3>
            <div class="recent-images-grid"></div>
            <div class="image-loader">
                <div class="spinner"></div>
                <p>Resimler Yükleniyor...</p>
            </div>
        `;


        const uploadArea = document.createElement('div');
        uploadArea.classList.add('upload-area');
        uploadArea.innerHTML = `
            <h3>Yeni Resim Yükle</h3>
            <input type="file" accept="image/*" style="display: none" />
            <div class="drop-zone">
                Resim yüklemek için tıklayın veya sürükleyin
            </div>
            <div class="upload-loader" style="display: none;">
                <div class="spinner"></div>
                <p>Resim Yükleniyor...</p>
            </div>
        `;

        const fileInput = uploadArea.querySelector('input');
        const dropZone = uploadArea.querySelector('.drop-zone');
        const uploadLoader = uploadArea.querySelector('.upload-loader');


        uploadArea.onclick = () => fileInput.click();


        fileInput.onchange = (e) => {
            const file = e.target.files[0];
            if (file) {
                dropZone.style.display = 'none';
                uploadLoader.style.display = 'flex';
                this.handleImageUpload(file, () => {
                    dropZone.style.display = 'block';
                    uploadLoader.style.display = 'none';
                    picker.remove();
                });
            }
        };


        dropZone.ondragover = (e) => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        };

        dropZone.ondragleave = () => {
            dropZone.classList.remove('drag-over');
        };

        dropZone.ondrop = (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                dropZone.style.display = 'none';
                uploadLoader.style.display = 'flex';
                this.handleImageUpload(file, () => {
                    dropZone.style.display = 'block';
                    uploadLoader.style.display = 'none';
                    picker.remove();
                });
            }
        };

        picker.append(recentImages, uploadArea);
        this.ui.modal.querySelector('.editor-container').appendChild(picker);


        this.loadRecentImages(recentImages.querySelector('.recent-images-grid'));


        setTimeout(() => {
            document.addEventListener('click', function closeImagePicker(e) {
                if (!picker.contains(e.target) && !e.target.closest('.editor-toolbox')) {
                    picker.remove();
                    document.removeEventListener('click', closeImagePicker);
                }
            });
        }, 0);
    }

    loadRecentImages(container) {
        const loader = container.parentElement.querySelector('.image-loader');
        loader.style.display = 'flex';


        fetch(editor_list_img_url)
            .then(response => response.json())
            .then(data => {
                container.innerHTML = '';

                if (data.length === 0) {
                    container.innerHTML = '<p class="no-images">Henüz yüklenmiş resim bulunmuyor.</p>';
                    return;
                }

                data.forEach(imgData => {
                    const imgContainer = document.createElement('div');
                    imgContainer.classList.add('recent-image-item');

                    const deleteImgContainerButton = document.createElement('button');
                    deleteImgContainerButton.classList.add('close-controls-btn');
                    deleteImgContainerButton.innerHTML = 'x';
                    deleteImgContainerButton.onclick = () => this.deleteImageElement(imgData.url, imgContainer);
                    imgContainer.appendChild(deleteImgContainerButton);

                    const img = document.createElement('img');
                    img.src = imgData.url;
                    img.onclick = () => this.addImageElement(imgData.url);

                    imgContainer.appendChild(img);
                    container.appendChild(imgContainer);
                });
            })
            .catch(error => {
                container.innerHTML = '<p class="error-message">Resimler yüklenirken bir hata oluştu.</p>';
                console.error('Resimler yüklenirken hata:', error);
            })
            .finally(() => {
                loader.style.display = 'none';
            });
    }

    deleteImageElement(url, imgContainer) {

        this.modalLoaderOpen();
        const formData = new FormData();
        formData.append('url', url);

        fetch(editor_delete_img_url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                alert("Resim başarıyla silindi.");
                imgContainer.remove();
                this.modalLoaderClose();
            })
            .catch(error => {
                console.error('Resim silirken hata:', error);
                alert('Resim silinirken bir hata oluştu.');
                this.modalLoaderClose();
            });

    }

    handleImageUpload(file, callback) {
        const formData = new FormData();
        formData.append('image', file);

        fetch(editor_upload_img_url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                this.addImageElement(data.url);
                if (callback) callback();
            })
            .catch(error => {
                console.error('Resim yüklenirken hata:', error);
                alert('Resim yüklenirken bir hata oluştu.');
                if (callback) callback();
            });
    }


    addImageElement(imageUrl) {
        this.saveState();

        const img = new Image();
        img.onload = () => {

            const scale = Math.min(
                (this.settings.width * 0.5) / img.width,
                (this.settings.height * 0.5) / img.height
            );

            const imageElement = {
                type: 'image',
                url: imageUrl,
                x: (this.settings.width - img.width * scale) / 2,
                y: (this.settings.height - img.height * scale) / 2,
                width: img.width * scale,
                height: img.height * scale,
                opacity: 100,
                rotation: 0
            };

            this.elements.push(imageElement);
            this.selectedElement = imageElement;
            this.redraw();
        };
        img.src = imageUrl;
    }
}