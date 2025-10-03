<style>
    .floating-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }

    .shortcut-menu {
        position: fixed;
        bottom: 90px;
        right: 22px;
        display: none;
        flex-direction: column;
        gap: 10px;
        z-index: 1000;

    }

    .shortcut-menu a {

        text-decoration: none;
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .shortcut-menu a.show {
        opacity: 1;
        transform: translateY(0);
    }

    .iconBtn {
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0;
    }

    .floatingBtn {
        width: 55px;
        height: 55px;
    }

    .floatingBtn i{
        font-size: 18px;
    }
</style>

<div class="floating-btn">
    <button class="btn btn-primary shadow-lg rounded-circle floatingBtn d-flex justify-content-center align-items-center" id="shortcutBtn" onclick="toggleShortcutBtn()">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </button>
</div>

<div class="shortcut-menu" id="shortcutMenu">

    <a href="{{route("post.create")}}" class="d-flex align-items-center justify-content-end ">
        <button class="btn btn-primary  shadow-lg">
            Haber Ekle
        </button>
        <button class="btn btn-primary rounded-circle ms-2 iconBtn  shadow-lg">
            <i data-feather="plus-square"></i>
        </button>
    </a>
    <a href="{{route("post.index")}}" class="d-flex align-items-center justify-content-end ">
        <button class="btn btn-primary  shadow-lg">
            Haberler
        </button>
        <button class="btn btn-primary rounded-circle ms-2 iconBtn  shadow-lg">
            <i data-feather="file-text"></i>
        </button>
    </a>
    <a href="{{route("category.create")}}" class="d-flex align-items-center  justify-content-end ">
        <button class="btn btn-primary  shadow-lg">
            Kategori Ekle
        </button>
        <button class="btn btn-primary rounded-circle ms-2 iconBtn  shadow-lg">
            <i data-feather="folder-plus"></i>
        </button>
    </a>
    <a href="{{route("category.index")}}" class="d-flex align-items-center justify-content-end ">
        <button class="btn btn-primary  shadow-lg">
            Kategoriler
        </button>
        <button class="btn btn-primary rounded-circle ms-2 iconBtn  shadow-lg">
            <i data-feather="folder"></i>
        </button>
    </a>
    <a href="{{route("menus")}}" class="d-flex align-items-center justify-content-end ">
        <button class="btn btn-primary  shadow-lg">
            Menüler
        </button>
        <button class="btn btn-primary rounded-circle ms-2 iconBtn  shadow-lg">
            <i data-feather="menu"></i>
        </button>
    </a>
    <a href="{{ route('article.index') }}" class="d-flex align-items-center justify-content-end ">
        <button class="btn btn-primary  shadow-lg">
            Makaleler
        </button>
        <button class="btn btn-primary rounded-circle ms-2 iconBtn shadow-lg">
            <i data-feather="book-open"></i>
        </button>
    </a>
    <a href="{{ route('sortableSlide') }}" class="d-flex align-items-center justify-content-end ">
        <button class="btn btn-primary  shadow-lg">
            Manşet Sıralama
        </button>
        <button class="btn btn-primary rounded-circle ms-2 iconBtn  shadow-lg" >
            <i data-feather="sliders"></i>
        </button>
    </a>
    <a href="{{ route('sortableHomePage') }}" class="d-flex align-items-center justify-content-end ">
        <button class="btn btn-primary  shadow-lg">
            Ana Sayfa Sıralama
        </button>
        <button class="btn btn-primary rounded-circle ms-2 iconBtn  shadow-lg">
            <i data-feather="layout"></i>
        </button>
    </a>
    <a href="{{ route('googleAnalytic') }}" class="d-flex align-items-center justify-content-end ">
        <button class="btn btn-primary  shadow-lg">
            Google Analitik
        </button>
        <button class="btn btn-primary rounded-circle ms-2 iconBtn  shadow-lg">
            <i data-feather="bar-chart-2"></i>
        </button>
    </a>
    <a href="#" class="d-flex align-items-center justify-content-end ">
        <button class="btn btn-primary  shadow-lg">
            Google Gmail
        </button>
        <button class="btn btn-primary rounded-circle ms-2 iconBtn  shadow-lg">
            <i data-feather="mail"></i>
        </button>
    </a>

</div>

<script>

    <!-- Kısayol Scripti -->
    function toggleShortcutBtn() {
        const menu = document.getElementById('shortcutMenu');
        const links = menu.querySelectorAll('a');

        if (menu.style.display === 'flex') {
            links.forEach((link, index) => {
                setTimeout(() => {
                    link.classList.remove('show');
                }, index * 50);
            });
            setTimeout(() => {
                menu.style.display = 'none';
            }, links.length * 50);
        } else {
            menu.style.display = 'flex';
            links.forEach((link, index) => {
                setTimeout(() => {
                    link.classList.add('show');
                }, index * 50);
            });
        }
    }
</script>
