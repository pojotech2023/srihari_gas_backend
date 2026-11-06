<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Primary SEO Meta Tags -->
  <title>@yield('title', 'Connect My LPG - Safe. Fast. Doorstep LPG Delivery.')</title>
  <meta name="title" content="@yield('meta_title', 'Connect My LPG | On-Demand LPG Cylinder Doorstep Delivery')">
  <meta name="description" content="@yield('meta_description', 'Book Domestic and Commercial LPG Gas cylinders online. Fast doorstep delivery, live GPS tracking, and transparent distance-based delivery charges.')">
  <meta name="keywords" content="LPG gas delivery, gas cylinder booking, doorstep LPG delivery, domestic cylinder 14.2kg, commercial cylinder 19kg, Connect My LPG, BookMyLPG">
  <meta name="author" content="Connect My LPG">
  <meta name="theme-color" content="#1565C0">

  <!-- Open Graph / Facebook / WhatsApp -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:title" content="@yield('og_title', 'Connect My LPG - Safe. Fast. Doorstep LPG Delivery.')">
  <meta property="og:description" content="@yield('og_description', 'Instant LPG cylinder booking with live GPS tracking, KYC verified delivery partners, and transparent distance-based delivery pricing.')">
  <meta property="og:image" content="{{ asset('images/logo.png') }}">

  <!-- Twitter Meta Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('og_title', 'Connect My LPG - Safe. Fast. Doorstep LPG Delivery.')">
  <meta name="twitter:description" content="@yield('og_description', 'Instant LPG cylinder booking with live GPS tracking, KYC verified delivery partners, and transparent distance-based delivery pricing.')">
  <meta name="twitter:image" content="{{ asset('images/logo.png') }}">

  <!-- Favicon from brand logo -->
  <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

  <!-- Google Fonts: Poppins (Headings) & Inter (Body) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Font Awesome 6 Icons (CDN) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- AOS (Animate on Scroll) CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" integrity="sha512-1cK78a1o+ht2JcaW6g8OXYvp4vMQRgzh2bNThtYZMV9hChXYSRCoRRzsPowUXMQi2guVZ9lIId52TW4MB4xVqQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Main Website CSS -->
  <link rel="stylesheet" href="{{ asset('css/site.css') }}">
  @stack('styles')
</head>
<body>

  <!-- Preloader Screen -->
  <div id="preloader" aria-hidden="true">
    <div class="preloader-cylinder-wrapper">
      <img src="{{ asset('images/logo.png') }}" alt="Connect My LPG" class="preloader-logo">
    </div>
    <div class="preloader-text">CONNECT MY LPG</div>
  </div>

  <!-- Scroll Progress Indicator -->
  <div id="scrollProgressBar" aria-hidden="true"></div>

  <!-- Main Navigation Partial -->
  @include('partials.navbar')

  <!-- Main Content Wrapper -->
  <main id="mainContent">
    @yield('content')
  </main>

  <!-- Main Footer Partial -->
  @include('partials.footer')

  <!-- Floating WhatsApp & Helpline Quick Connect -->
  <a href="{{ config('site.socials.whatsapp') }}" target="_blank" rel="noopener" class="floating-whatsapp-btn" aria-label="Chat with Support on WhatsApp" title="Chat with us on WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
  </a>

  <!-- GSAP & ScrollTrigger Animation Libraries (CDN) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

  <!-- AOS Library (CDN) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

  <!-- Main Site Scripts -->
  <script src="{{ asset('js/site.js') }}"></script>
  @stack('scripts')
</body>
</html>
