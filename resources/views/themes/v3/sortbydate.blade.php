@extends($theme_path . '.frontend_layout')

@section("custom_css")
    <style>
        .header {
            background-color: #0000ff;
            color: white;
            padding: 10px 20px;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }

        .badge-custom {
            background-color: yellow;
            color: black;
            font-size: 12px;
            font-weight: bold;
            padding: 3px 6px;
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .search-section {
            background: linear-gradient(to right, #ffe742, #ff8d43); /* Sarıdan turuncuya geçiş */
            padding: 15px;
            border-radius: 5px;
        }


        /* Mobil uyumluluk */
        @media (max-width: 768px) {
            .search-section {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
@endsection

@section("content")

    <div class="container my-4">
        <div class="row g-3">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card position-relative">
                    <span class="badge-custom">RESMİ İLANDIR</span>
                    <img src="https://gazetebunet.teimg.com/crop/640x375/gazetebu-net/uploads/2025/02/x-565-424-is-yeri-kiralarken-kira-sozlesmesinde-dikkat-edilecekler.png"
                         class="card-img-top" alt="Nakil Hizmeti">
                    <div class="card-body text-center">
                        <h5 class="card-title">NAKİL HİZMETİ ALINACAKTIR</h5>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <div class="card position-relative">
                    <span class="badge-custom">RESMİ İLANDIR</span>
                    <img src="https://gazetebunet.teimg.com/crop/640x375/gazetebu-net/uploads/2025/02/x-565-424-is-yeri-kiralarken-kira-sozlesmesinde-dikkat-edilecekler.png"
                         class="card-img-top" alt="Çimento Satın Alınacak">
                    <div class="card-body text-center">
                        <h5 class="card-title">ÇİMENTO SATIN ALINACAKTIR</h5>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <div class="card position-relative">
                    <span class="badge-custom">RESMİ İLANDIR</span>
                    <img src="https://gazetebunet.teimg.com/crop/640x375/gazetebu-net/uploads/2025/02/x-565-424-is-yeri-kiralarken-kira-sozlesmesinde-dikkat-edilecekler.png"
                         class="card-img-top" alt="TOKİ Konutları">
                    <div class="card-body text-center">
                        <h5 class="card-title">TOKİ KONUTLARI SATIŞI YAPILACAKTIR</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="search-section mt-4 p-3 d-flex align-items-center">
            <input type="date" class="form-control me-2" value="2024-12-02">
            <input type="text" class="form-control me-2" placeholder="İlan numarasına göre filtrele">
            <button class="btn btn-primary">ARA</button>
        </div>
    </div>

@endsection
