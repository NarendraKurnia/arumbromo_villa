<!-- Footer -->
<footer class="footer mt-5">
    <div class="container">
        <div class="row">
            <!-- Hubungi Kami -->
            <div class="col-md-6 text-center text-md-start">
                 <h5 class="mb-3">Hubungi Kami</h5>
                <div class="d-flex align-items-start">
                    <!-- Ikon Call Center (Kiri) -->
                    <div class="me-3">
                        <img src="images/icon-call-center.png" alt="Contact Center PLN 123" width="120">
                    </div>

                    <!-- Daftar Kontak (Kanan) -->
                    <div class="d-flex flex-column gap-2" style="margin-left: 15px;">
                        <div class="d-flex align-items-center">
                            <img src="images/contact-icon.png" alt="Phone Icon" width="25">
                            <span class="ms-2">123</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <img src="images/phone-icon.png" alt="Phone Area Icon" width="25">
                            <span class="ms-2">(kode area) 123</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <img src="images/twiter-icon.png" alt="Twitter Icon" width="25">
                            <span class="ms-2">@pln_123</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <img src="images/facebook-icon.png" alt="Facebook Icon" width="25">
                            <span class="ms-2">PLN 123</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <img src="images/email-icon.png" alt="Email Icon" width="25">
                            <span class="ms-2">pln123@pln.co.id</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <img src="images/instagram-icon.png" alt="Instagram Icon" width="25">
                            <span class="ms-2">pln123_official</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sitemap -->
            <div class="col-md-3 text-center text-md-start">
                <h5>Sitemap</h5>
                <ul class="list-unstyled">
                    <li><a href="#">Pelanggan</a></li>
                    <li><a href="#">Media</a></li>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Stakeholder</a></li>
                    <li><a href="#">PLN Peduli</a></li>
                    <li><a href="#">KIP</a></li>
                    <li><a href="#">Karier</a></li>
                    <li><a href="#">Webmail</a></li>
                </ul>
            </div>

            <!-- Ikuti Kami -->
            <div class="col-md-3 text-center text-md-start">
                <h5>Ikuti Kami</h5>
                <div class="footer-icons">
                    <a href="#"><img src="images/facebook-icon.png" alt="Facebook"></a>
                    <a href="#"><img src="images/twiter-icon.png" alt="Twitter"></a>
                    <a href="#"><img src="images/youtube-icon.png" alt="YouTube"></a>
                    <a href="#"><img src="images/instagram-icon.png" alt="Instagram"></a>
                </div>
            </div>
        </div>

        <hr class="text-white">

        <!-- Copyright -->
        <div class="text-center">
            <p>Copyright © 2025 PT PLN (Persero) All Rights Reserved</p>
        </div>
    </div>
</footer>
<!-- Instalasi Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    let navbar = document.getElementById("stickyNavbar");

    window.addEventListener("scroll", function () {
        if (window.scrollY > 100) {
            navbar.classList.add("visible-navbar");
            navbar.classList.remove("hidden-navbar");
        } else {
            navbar.classList.remove("visible-navbar");
            navbar.classList.add("hidden-navbar");
        }
    });
    var myCarousel = new bootstrap.Carousel(document.getElementById('carouselExampleCaptions'), {
        interval: 2000,  // Ganti angka ini untuk mengatur durasi antar slide
        ride: 'carousel'
    });
    document.querySelectorAll(".toggle-content").forEach(button => {
        button.addEventListener("click", function () {
            let targetId = this.getAttribute("data-target");
            
            // Sembunyikan semua konten terlebih dahulu
            document.querySelectorAll(".content-section").forEach(section => {
                section.style.display = "none";
            });

            // Tampilkan konten yang sesuai dengan tombol yang diklik
            document.getElementById(targetId).style.display = "block";
        });
    });
</script>

</body>
</html>