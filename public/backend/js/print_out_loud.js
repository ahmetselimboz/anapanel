$(function () {
    let recognition;
    let selectedDeviceId = null;
    let recording;
    let input;

    if ('webkitSpeechRecognition' in window) {
        recognition = new webkitSpeechRecognition();
        recognition.continuous = true;
        recognition.interimResults = true;
        recognition.lang = 'tr-TR';
    } else {
        $('.print_out_loud_buttons').remove();
    }

    recognition.onstart = () => {
        $('.polb__record').html('<i class="fa fa-pause-circle" aria-hidden="true"></i>');
        recording = true;
    }

    recognition.onerror = (event) => {
        $('.polb__record').html('<i class="fa fa-circle" aria-hidden="true"></i>');

        if (event.error === 'network') {
            setTimeout(() => {
                if (recording) {
                    recognition.start();
                }
            }, 1000);
        }
    };

    recognition.onend = () => {
        $('.polb__record').html('<i class="fa fa-circle" aria-hidden="true"></i>');
        recording = false;
    };

    $('.polb__main').click(function () {

        $buttonsBox = $(this).closest('.print_out_loud_buttons');
        let show = false;
        if(!$buttonsBox.hasClass('show')) {
            show = true;
        }

        $('.print_out_loud_buttons').removeClass('show');
        if(show) {
            $buttonsBox.addClass('show');
        }
    });

    $('.polb__devices').click(function () {
        const devicesBox = $(this).closest('.print_out_loud_wrapper').find('.print_out_loud_devices');
        devicesBox.empty();
        devicesBox.toggleClass('show');

        navigator.mediaDevices.enumerateDevices()
            .then(devices => {
                devices.forEach(device => {
                    if (device.kind === "audioinput") {
                        const $deviceItem = $('<div/>', {
                            class: 'device-item',
                            text: device.label || 'Mikrofon erişimine izin verin.',
                            'data-device-id': device.deviceId
                        });
                        $deviceItem.click(function () {
                            $deviceItem.closest('.print_out_loud_devices').find('.device-item').removeClass('selected');
                            $deviceItem.addClass('selected');
                            selectedDeviceId = device.deviceId;

                            if (recognition && recognition.running) {
                                recognition.stop();
                                startRecording();
                            }
                        });
                        devicesBox.append($deviceItem);
                    }
                });
            })
            .catch(err => console.error("Mikrofon listesi alınamadı:", err));
    });

    function startRecording() {
        if (selectedDeviceId) {
            navigator.mediaDevices.getUserMedia({
                audio: {
                    deviceId: { exact: selectedDeviceId }
                }
            }).then(stream => {
                recognition.start();
            }).catch(err => {
                console.error("Mikrofon erişimi hatası:", err);
                recognition.start();
            });
        } else {
            recognition.start();
        }
        recording = true;
    }

    $('.polb__record').click(function () {
        let useTextFunc = false;
        const targetID = $(this).data('target');
        let input = $(this).closest('.print_out_loud_wrapper').find('input, textarea');

        if (!input.length) {
            useTextFunc = true;
            input = CKEDITOR.instances[targetID];
        }

        if (recording) {
            recording = false;
            recognition.stop();
            return;
        }

        startRecording();
        recognition.onresult = function (event) {
            let transcript = "";

            for (let i = event.resultIndex; i < event.results.length; i++) {
                if (event.results[i].isFinal) {
                    transcript += event.results[i][0].transcript;
                }
            }

            if (useTextFunc) {
                input.setData(input.getData().trim() + " " + changeValue(transcript.trim()));
            } else {
                input.val(input.val().trim() + " " + changeValue(transcript.trim()));
            }
        };
    });

    function changeValue(value) {

        if(value == " " || value == "\n" || value == "")
            return "";

        value = value.trim().toLowerCase();
        value = value.replace(/\./g, "");
        return value;
    }

});