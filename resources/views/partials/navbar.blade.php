<!-- Sticky Glassmorphism Navbar -->
<header class="site-navbar">
  <div class="container navbar-container">
    <!-- Brand Logo -->
    <a href="{{ route('site.home') }}" class="navbar-brand" aria-label="Connect My LPG Home">
      <img src="{{ asset('images/logo.png') }}" alt="Connect My LPG Logo" class="navbar-logo-img">
      <div>
        <span class="navbar-brand-name">{{ config('site.brand.name', 'Connect My LPG') }}</span>
        <span class="navbar-brand-tagline">Safe. Fast. Doorstep Delivery.</span>
      </div>
    </a>

    <!-- Desktop Navigation Links -->
    <nav class="navbar-nav" aria-label="Primary Navigation">
      <a href="{{ route('site.home') }}" class="nav-link {{ request()->routeIs('site.home') ? 'active' : '' }}">Home</a>
      <a href="{{ route('site.about') }}" class="nav-link {{ request()->routeIs('site.about') ? 'active' : '' }}">About</a>
      <a href="{{ route('site.services') }}" class="nav-link {{ request()->routeIs('site.services') ? 'active' : '' }}">Services</a>
      <a href="{{ route('site.contact') }}" class="nav-link {{ request()->routeIs('site.contact') ? 'active' : '' }}">Contact</a>
    </nav>

    <!-- Navbar CTAs -->
    <div class="navbar-cta-group">
      <a href="{{ route('site.services') }}#pricing" class="btn btn-flame btn-sm">
        <i class="fa-solid fa-fire-flame-curved"></i> Book Now
      </a>
    </div>

    <!-- Mobile Hamburger Toggle Button -->
    <button type="button" class="hamburger-btn" id="hamburgerBtn" aria-label="Open Navigation Menu">
      <i class="fa-solid fa-bars"></i>
    </button>
  </div>
</header>

<!-- Mobile Slide-in Drawer -->
<div class="mobile-drawer-backdrop" id="mobileDrawerBackdrop"></div>
<aside class="mobile-nav-drawer" id="mobileNavDrawer" aria-label="Mobile Navigation">
  <div>
    <div class="mobile-drawer-header">
      <div class="navbar-brand">
        <img src="{{ asset('images/logo.png') }}" alt="Connect My LPG Logo" class="navbar-logo-img" style="width: 36px; height: 36px;">
        <span class="navbar-brand-name" style="font-size: 1.1rem;">Connect My LPG</span>
      </div>
      <button type="button" class="mobile-drawer-close" id="closeDrawerBtn" aria-label="Close Navigation Menu">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <nav class="mobile-nav-links">
      <a href="{{ route('site.home') }}" class="mobile-nav-link {{ request()->routeIs('site.home') ? 'active' : '' }}">
        <span>Home</span>
        <i class="fa-solid fa-chevron-right text-xs"></i>
      </a>
      <a href="{{ route('site.about') }}" class="mobile-nav-link {{ request()->routeIs('site.about') ? 'active' : '' }}">
        <span>About Us</span>
        <i class="fa-solid fa-chevron-right text-xs"></i>
      </a>
      <a href="{{ route('site.services') }}" class="mobile-nav-link {{ request()->routeIs('site.services') ? 'active' : '' }}">
        <span>LPG Cylinders & Services</span>
        <i class="fa-solid fa-chevron-right text-xs"></i>
      </a>
      <a href="{{ route('site.contact') }}" class="mobile-nav-link {{ request()->routeIs('site.contact') ? 'active' : '' }}">
        <span>Contact & Support</span>
        <i class="fa-solid fa-chevron-right text-xs"></i>
      </a>
    </nav>
  </div>

  <div style="border-top: 1px solid var(--dark-border); padding-top: 24px;">
    <a href="{{ route('site.services') }}#pricing" class="btn btn-flame" style="width: 100%; margin-bottom: 12px;">
      <i class="fa-solid fa-fire"></i> Book a Cylinder
    </a>
    <p style="font-size: 0.8rem; color: var(--text-light-muted); text-align: center;">
      <i class="fa-solid fa-phone me-1"></i> Helpline: {{ config('site.brand.phone') }}
    </p>
  </div>
</aside>
