<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  @include('layout.head')
</head>
<body class="min-h-screen bg-white font-sans text-brand-black antialiased">
  @include('layout.header')

  <main id="content">
    @yield('content')
  </main>

  @include('layout.footer')

  <script>
    // Toggle menu mobile
    const btn = document.getElementById('menu-btn');
    const menu = document.getElementById('mobile-menu');
    btn?.addEventListener('click', () => {
      const open = menu.classList.toggle('hidden') === false;
      btn.setAttribute('aria-expanded', open);
    });

    // Header solid saat discroll
    const header = document.getElementById('site-header');
    const onScroll = () => header.classList.toggle('bg-brand-black', window.scrollY > 40);
    onScroll(); window.addEventListener('scroll', onScroll, { passive: true });
  </script>
  @stack('scripts')
</body>
</html>