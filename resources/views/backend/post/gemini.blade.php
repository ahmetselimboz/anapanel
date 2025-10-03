<style>
    .chat-message {
        max-width: 75%;
        padding: 10px 15px;
        border-radius: 10px;
        margin-bottom: 8px;
        display: inline-block;
        font-size: 14px;
    }

    .user-message {
        background-color: #007bff;
        color: white;
        align-self: flex-end;
        text-align: right;
    }

    .bot-message {
        background-color: #e9ecef;
        color: black;
        align-self: flex-start;
        text-align: left;
    }

    .loading {
        font-size: 14px;
        font-style: italic;
        color: #6c757d;
    }
</style>

<div class="modal fade" id="geminiChatModal" tabindex="-1" aria-labelledby="geminiChatLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-lg">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-primary text-white">
                <h4 id="modal-title" class="m-2">Gemini ile Özelleştir</h4>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <div id="chat-box" class="border p-3 mb-3 rounded"
                    style="height: 400px; overflow-y: auto; background-color: #f8f9fa;overflow-x: hidden;">
                    <p class="text-muted text-center">Sohbeti başlatmak için bir mesaj yazın.</p>
                </div>
                <div class="d-flex align-items-center">
                    <textarea id="message" class="form-control" rows="1" placeholder="Mesajınızı yazın..."
                        style="resize: none; overflow-y: auto; max-height: 150px;"></textarea>
                    <button id="send" class="btn btn-primary ms-2" style="height: 40px;">Gönder</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        let prompt = "";
        let type = "";
        let images = [];
        let title = "";
        let small_description = "";

        if (type == 'agencies') {
            prompt = `Sen bir haber sitesi asistanısın. Görevin şu şekilde:

Sana HTML formatında bir haber metni verilecek.

Senin görevin, bu haberin cümlelerini anlamı bozulmadan yeniden yazarak farklılaştırmak.

Cümleler birebir aynı kalmamalı. Farklı kelimeler, yapılar, eş anlamlılar kullan. Gerekirse içerik ekle ama haberin özü değişmesin.

HTML içeriğindeki etiket yapısını bozmadan ve etiket isimlerini değiştirmeden sadece metinleri düzenle.

Görseller varsa, onlara dokunma. Sadece yazılı içeriklerle ilgilen.

Etiketlerin içindeki style="..." gibi stil ayarlarını değiştirme.

Kurallar:
✅ Yalnızca cümleleri farklılaştır.
❌ Etiket isimlerini değiştirme.
❌ Style ayarlarını silme veya değiştirme.
✅ HTML formatında gönder, sadece değişmiş halini ver.
✅ Haberin anlamını ve bağlamını koru.

📝 Kısaca:
Cümleleri yeniden yaz, anlamı bozulmasın. Etiketlere dokunma. HTML yapısını koruyarak sadece özgünleştirilmiş haber metnini geri ver.`

        } else {
            prompt = `Sen bir haber sitesinin SEO asistanısın. Görevin, WYSIWYG editörden gelen HTML formatındaki haber metinlerini SEO uyumlu hale getirmek. 

❗❗ Önemli Kurallar:
- Gönderilen metin HTML formatında gelecek ve **cevabını da aynı HTML formatında** vereceksin.
- HTML taglerindeki style özelliklerini değiştirme.

Senin görevin, **gelen HTML içeriğini analiz etmek, SEO kurallarına göre optimize etmek ve HTML olarak geri vermektir.** Cevap verirken **HTML formatını koru** ve sadece optimize edilmiş metni döndür.`
        }


        // Modal açılınca CKEditor içeriğini textarea'ya al
        $("#geminiChatModal").on("show.bs.modal", function (event) {
            // Tıklanan butonu al
            let button = $(event.relatedTarget);
            // data-type değerini al
            type = button.data('type') || '';

            if (type === 'agencies') {
                // IHA değişkenlerini güncelle
                images = window.parent.images || [];
                title = window.parent.title || '';
                small_description = window.parent.small_description || '';
            }

            if (typeof CKEDITOR !== "undefined" && CKEDITOR.instances.detail_editor) {
                var editorContent = CKEDITOR.instances.detail_editor.getData();
                $("#message").val(editorContent);
                if (editorContent !== "") {
                    $("#message").css('height', '150px');
                }
            }
        });

        // Textarea dinamik olarak büyüsün (1-6 satır arası)
        $("#message").on("input", function () {
            this.style.height = "auto";
            var lineHeight = 24; // Ortalama satır yüksekliği
            var maxRows = 6;
            var maxHeight = maxRows * lineHeight;

            this.style.height = Math.min(this.scrollHeight, maxHeight) + "px";
            this.style.overflowY = this.scrollHeight > maxHeight ? "scroll" : "hidden";
        });

        $("#send").click(function () {
            var message = $("#message").val().trim();
            if (message === "") {
                alert("Lütfen bir mesaj girin!");
                return;
            }

            var chatBox = $("#chat-box");

            // Kullanıcı mesajını güvenli şekilde göster
            var escapedMessage = $("<div>").text(message).html();
            chatBox.append(`
            <div class="text-end">
                <div class="chat-message user-message p-2 bg-primary text-white rounded px-3 py-2" style="overflow: hidden;">${escapedMessage}</div>
            </div>
        `);

            $("#message").val("").css({ height: "auto", overflowY: "hidden" });

            // Loading göstergesi ekle
            var loadingIndicator = $('<div class="text-start"><p class="loading text-muted">Yanıt bekleniyor...</p></div>');
            chatBox.append(loadingIndicator);
            chatBox.scrollTop(chatBox[0].scrollHeight);

            // Sunucuya istek gönder
            $.post("{{ route('gemini.chat') }}",
                { message: message, prompt: prompt, _token: "{{ csrf_token() }}" },
                function (response) {
                    loadingIndicator.remove();

                    if (response && response.candidates) {
                        var geminiResponse = response.candidates[0].content.parts[0].text;
                        var cleanedText = geminiResponse.replace(/```html|```/g, "").trim();
                        // **HTML içeriğini düz metin (TEXT) haline getir**
                        var plainText = $("<div>").text(cleanedText).html();

                        var botMessage = $(`
                        <div class="chat-message text-start">
                            <div class="bot-message p-2 bg-light text-dark rounded px-3 py-2" >
                                ${plainText}

                            </div>
                            <div class="d-flex justify-content-center mt-2">
                                <button class="btn  btn-success add-to-editor">Ekle</button>
                            </div>
                        </div>
                    `);
                        chatBox.append(botMessage);
                    } else {
          
                        if (response.error.message == "API key not valid. Please pass a valid API key.") {
                            chatBox.append(`
                        <div class="text-start">
                            <p class="chat-message bot-message text-danger">${"Google Gemini API'ye bağlanılamadı! API Key'inizin doğru olduğundan emin olunuz."}</p>
                        </div>
                    `);
                        } else {
                            chatBox.append(`
                        <div class="text-start">
                            <p class="chat-message bot-message text-danger">${"Yanıt Alınamadı!"}</p>
                        </div>
                    `);
                        }

                    }
                    chatBox.scrollTop(chatBox[0].scrollHeight);
                }
            ).fail(function () {
                loadingIndicator.remove();
                chatBox.append(`
                <div class="text-start">
                    <p class="chat-message bot-message text-danger">Bağlantı hatası!</p>
                </div>
            `);
            });
        });

        // "Ekle" butonuna tıklayınca CKEditor'ü temizleyip yeni içeriği ekle
        $(document).on("click", ".add-to-editor", function () {
            var rawHTML = $(this).closest(".text-start").find(".bot-message").text(); // HTML olarak al

            if (!rawHTML) {
                console.log("Hata: .bot-message içeriği alınamadı.");
                return;
            }

            if (type == 'agencies') {



                // IHA form verilerini hazırla ve gönder
                let imagesHtml = images.map(img => `<img src="${img}" style="width:50%;"><br/>`).join('') +
                    "<br/><br/><br/><br/><br/>" +
                    rawHTML.replace(/&lt;br\/&gt;/g, '<br/>');

                document.getElementById("detail").value = imagesHtml;
                document.getElementById("title").value = title;
                document.getElementById("description").value = small_description;
                document.getElementById("imageInput").value = $("#mainImage").attr("src");

                // Formu submit et
                document.getElementById("newsForm").submit();
            } else {
                if (typeof CKEDITOR !== "undefined" && CKEDITOR.instances.detail_editor) {
                    //CKEDITOR.instances.detail_editor.setData("");
                    CKEDITOR.instances.detail_editor.setData(rawHTML); // CKEditor'e **tam HTML** olarak ekle
                } else {
                    console.log("CKEditor bulunamadı, textarea'ya ekleniyor.");
                    $('#detail_editor').val($('#detail_editor').val() + "\n\n" + rawHTML);
                }
            }



            $("#geminiChatModal").modal("hide"); // Modal'ı kapat
        });
    });


</script>