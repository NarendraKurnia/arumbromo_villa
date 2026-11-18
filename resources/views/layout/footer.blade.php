<footer class="bg-brand-black text-white">
  <div class="mx-auto px-4 pt-16 sm:px-6 lg:px-10 lg:pt-24">
    <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-12 lg:gap-8">
      <div class="lg:col-span-4">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2">
          <span class="grid h-9 w-9 place-items-center rounded-full bg-brand-red font-heading font-bold">V</span>
          <span class="font-heading text-lg font-semibold">Villa Asri</span>
        </a>
        <p class="mt-4 max-w-xs text-sm text-white/50">
          Villa privat dengan suasana tenang, layanan hangat, dan pengalaman menginap yang tak terlupakan.
        </p>
      </div>

      <div class="lg:col-span-3">
        <h4 class="font-mono text-xs text-white/30">Navigasi</h4>
        <nav class="mt-4 flex flex-col gap-3 text-xs uppercase tracking-widest text-white/60">
          <a href="{{ url('/') }}" class="hover:text-brand-red">Beranda</a>
          <a href="{{ url('/villas') }}" class="hover:text-brand-red">Villa</a>
          <a href="{{ url('/gallery') }}" class="hover:text-brand-red">Galeri</a>
          <a href="{{ url('/contact') }}" class="hover:text-brand-red">Kontak</a>
        </nav>
      </div>

      <div class="lg:col-span-3">
        <h4 class="font-mono text-xs text-white/30">Kontak</h4>
        <div class="mt-4 space-y-4 text-sm text-white/60">
          <div><span class="font-mono text-[11px] text-white/40">Email</span><p>halo@villaasri.id</p></div>
          <div><span class="font-mono text-[11px] text-white/40">WhatsApp</span><p>+62 812-0000-0000</p></div>
          <div><span class="font-mono text-[11px] text-white/40">Alamat</span>
            <p class="leading-relaxed">Jl. Raya Ubud No. 88, Gianyar, Bali 80571</p></div>
        </div>
      </div>

      <div class="lg:col-span-2">
        <h4 class="font-mono text-xs text-white/30">Ikuti Kami</h4>
        <div class="mt-4 flex flex-col gap-3 text-xs uppercase tracking-widest text-white/60">
          <a href="#" class="hover:text-brand-red">Instagram</a>
          <a href="#" class="hover:text-brand-red">TikTok</a>
          <a href="#" class="hover:text-brand-red">YouTube</a>
        </div>
      </div>
    </div>

    <div class="mt-12 border-t border-white/10 py-6 flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
      <p class="font-mono text-xs text-white/30">© {{ date('Y') }} Villa Asri. Hak cipta dilindungi.</p>
      <button type="button" onclick="window.scrollTo({top:0,behavior:'smooth'})"
              class="font-mono text-xs text-white/30 hover:text-white/60">Kembali ke atas ↑</button>
    </div>
  </div>

  {{-- Marquee teks besar --}}
  <div class="overflow-hidden pb-10 [mask-image:linear-gradient(to_right,transparent,black_12%,black_88%,transparent)]">
    <div class="flex w-max animate-[marquee_30s_linear_infinite] whitespace-nowrap">
      @for ($i = 0; $i < 4; $i++)
        <span class="shrink-0 pr-12 font-heading text-6xl font-semibold leading-none tracking-tighter text-white/10 sm:text-8xl lg:text-[9rem]">
          Rest. Relax. Repeat.
        </span>
      @endfor
    </div>
  </div>
  <style>@keyframes marquee { to { transform: translateX(-50%); } }</style>

<script>
  function toggleMobileMenu(e) {
    if (e) e.stopPropagation();
    var m = document.getElementById('mobile-menu');
    var b = document.getElementById('menu-btn');
    var open = (m.style.display === 'none' || m.style.display === '');
    m.style.display = open ? 'block' : 'none';
    b.setAttribute('aria-expanded', open ? 'true' : 'false');
    var h = document.getElementById('site-header');
    h.setAttribute('data-menu', open ? '1' : '0');
    h.classList.toggle('bg-brand-black', open || window.scrollY > 40 || h.dataset.solid === '1');
  }

  function toggleUserMenu(e) {
    if (e) e.stopPropagation();
    var d = document.getElementById('userDropdown');
    if (d) d.classList.toggle('hidden');
  }

  function toggleUserMenuMobile() {
    var d = document.getElementById('userDropdownMobile');
    if (d) d.classList.toggle('hidden');
  }

  (function () {
    var h = document.getElementById('site-header');
    var m = document.getElementById('mobile-menu');

    // Header solid saat scroll
    function onScroll() {
      var forced = h.dataset.solid === '1' || h.getAttribute('data-menu') === '1';
      h.classList.toggle('bg-brand-black', forced || window.scrollY > 40);
    }
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    // Tutup menu mobile jika layar melebar atau link diklik
    window.addEventListener('resize', function () {
      if (window.innerWidth >= 1280) { m.style.display = 'none'; h.setAttribute('data-menu', '0'); }
    });
    m.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', function () { m.style.display = 'none'; h.setAttribute('data-menu', '0'); });
    });

    // Tutup dropdown user saat klik di luar
    document.addEventListener('click', function (e) {
      var d = document.getElementById('userDropdown');
      if (d && !d.classList.contains('hidden') && !d.contains(e.target)) d.classList.add('hidden');
    });
  })();
</script>
{{-- pencarian kamar --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkIn = document.getElementById('check_in');
        const checkOut = document.getElementById('check_out');

        if (!checkIn || !checkOut) return;

        checkIn.addEventListener('change', function () {
            if (!this.value) return;

            const nextDay = new Date(this.value + 'T00:00:00');
            nextDay.setDate(nextDay.getDate() + 1);

            const minimumCheckout = [
                nextDay.getFullYear(),
                String(nextDay.getMonth() + 1).padStart(2, '0'),
                String(nextDay.getDate()).padStart(2, '0')
            ].join('-');

            checkOut.min = minimumCheckout;

            if (checkOut.value && checkOut.value <= this.value) {
                checkOut.value = minimumCheckout;
            }
        });

        if (checkIn.value) {
            checkIn.dispatchEvent(new Event('change'));
        }
    });
</script>
{{-- search room in home --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkIn = document.getElementById('check_in');
        const checkOut = document.getElementById('check_out');
        if (!checkIn || !checkOut) return;

        checkIn.addEventListener('change', function () {
            if (!this.value) return;
            const nextDay = new Date(this.value + 'T00:00:00');
            nextDay.setDate(nextDay.getDate() + 1);
            const minimumCheckout = [
                nextDay.getFullYear(),
                String(nextDay.getMonth() + 1).padStart(2, '0'),
                String(nextDay.getDate()).padStart(2, '0')
            ].join('-');
            checkOut.min = minimumCheckout;
            if (checkOut.value && checkOut.value <= this.value) {
                checkOut.value = minimumCheckout;
            }
        });

        if (checkIn.value) checkIn.dispatchEvent(new Event('change'));
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const userMenuBtn = document.getElementById('user-menu-btn');
    const userDropdown = document.getElementById('user-dropdown');
    const userMenuArrow = document.getElementById('user-menu-arrow');

    if (userMenuBtn && userDropdown) {

        userMenuBtn.addEventListener('click', function (e) {
            e.stopPropagation();

            const isHidden = userDropdown.classList.contains('hidden');

            userDropdown.classList.toggle('hidden');

            userMenuBtn.setAttribute('aria-expanded', !isHidden);

            if (userMenuArrow) {
                userMenuArrow.classList.toggle('rotate-180', !isHidden);
            }
        });

        document.addEventListener('click', function (e) {
            if (
                !userMenuBtn.contains(e.target) &&
                !userDropdown.contains(e.target)
            ) {
                userDropdown.classList.add('hidden');
                userMenuBtn.setAttribute('aria-expanded', 'false');

                if (userMenuArrow) {
                    userMenuArrow.classList.remove('rotate-180');
                }
            }
        });
    }

});
</script>
</footer>