<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#282829">
<title>@yield('title', 'Arum Bromo Villas')</title>
<meta name="description" content="@yield('description', 'Pesan villa dengan pemandangan indah, dan suasana yang sejuk. Cek ketersediaan dan booking langsung.')">
<link rel="canonical" href="{{ url()->current() }}">

<meta property="og:title" content="@yield('title', 'Arum Bromo Villas')">
<meta property="og:type" content="website">
<meta property="og:locale" content="id_ID">
<meta property="og:url" content="{{ url()->current() }}">

<link rel="icon" href="{{ asset('favicon.ico') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

{{-- Dev: Tailwind CDN. Produksi: ganti dengan @vite(['resources/css/app.css','resources/js/app.js']) --}}
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          brand: { red: '#ED1C24', black: '#282829', silver: '#BFBFBF', stroke: '#E5E5E5', 'stroke-dark': 'rgba(255,255,255,.12)' },
        },
        fontFamily: { heading: ['Geist', 'sans-serif'], sans: ['Roboto', 'sans-serif'] },
      },
    },
  }
</script>
<style>
  html { scroll-behavior: smooth; }
  :root { --header-h: 64px; --subnav-h: 56px; }
  @media (min-width: 1024px) { :root { --header-h: 80px; } }
  .scroll-mt-nav { scroll-margin-top: calc(var(--header-h) + var(--subnav-h)); }
</style>
@stack('head')
</head>

<body class="bg-white antialiased">