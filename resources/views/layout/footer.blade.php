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
    // Menu mobile
    const btn = document.getElementById('menu-btn');
    const menu = document.getElementById('mobile-menu');
    btn?.addEventListener('click', () => {
        const open = menu.classList.toggle('hidden') === false;
        btn.setAttribute('aria-expanded', open);
    });

    // Header jadi solid saat discroll (kecuali sudah solid dari awal)
    const header = document.getElementById('site-header');
    const onScroll = () => {
        if (header.dataset.solid === '1') return;
        header.classList.toggle('bg-brand-black', window.scrollY > 40);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
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
</footer>