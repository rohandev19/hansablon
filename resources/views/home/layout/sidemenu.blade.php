<!-- Mobile Menu Start -->
<div class="mobile-menu-area d-md-none position-fixed top-0 start-0 w-100 bg-white shadow-sm z-1050" style="z-index: 1050;">
    <div class="container-fluid px-4 py-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <strong class="fs-5 text-dark">Menu</strong>
            <button class="btn btn-sm btn-outline-secondary" onclick="toggleMobileMenu()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <nav class="mobile-menu px-4 pb-3">
        <ul class="list-unstyled mt-3">
            <li class="mb-3">
                <a href="/" class="d-flex align-items-center gap-2 text-dark fw-semibold">
                    <i class="fas fa-home"></i> Home
                </a>
            </li>
            {{-- <li class="mb-3">
                <a href="{{ Route('produk_non') }}" class="d-flex align-items-center gap-2 text-dark fw-semibold">
                    <i class="fas fa-store"></i> Produk (Satuan)
                </a>
            </li> --}}
            <li class="mb-3">
                <a href="{{ route('produk') }}" class="d-flex align-items-center gap-2 text-dark fw-semibold">
                    <i class="fas fa-shopping-cart"></i> Produk
                </a>
            </li>
            <li>
                <a href="{{ route('contact') }}" class="d-flex align-items-center gap-2 text-dark fw-semibold">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
            </li>
        </ul>
    </nav>
</div>

<!-- Toggle Button (for example placed in header) -->
<button class="btn btn-warning d-md-none position-fixed top-0 start-0 m-3 z-1055" onclick="toggleMobileMenu()" style="z-index:1055;">
    <i class="fas fa-bars"></i>
</button>

<!-- JavaScript to toggle menu -->
<script>
    function toggleMobileMenu() {
        const menu = document.querySelector('.mobile-menu-area');
        menu.classList.toggle('d-none');
    }
</script>

<!-- Optional Styling -->
<style>
    .mobile-menu a:hover {
        color: #ffc107;
        text-decoration: none;
    }

    .z-1050 {
        z-index: 1050;
    }
    .z-1055 {
        z-index: 1055;
    }
</style>
