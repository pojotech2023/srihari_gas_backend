@extends('layouts.app')

@section('title', 'Connect My LPG - Safe. Fast. Doorstep LPG Delivery.')
@section('meta_description', 'Book Domestic and Commercial LPG Gas cylinders online with Connect My LPG. Instant booking, transparent distance-based delivery charges, and verified doorstep delivery.')

@section('content')

<!-- ==========================================================================
     1. HERO SECTION
     ========================================================================== -->
<section class="hero-section" id="hero">
  <!-- Ambient floating sparks background -->
  <div class="hero-sparks-bg"></div>

  <div class="container hero-grid">
    <!-- Left Hero Column: Copy & CTAs -->
    <div class="hero-content" data-aos="fade-right">
      <div class="badge-tag flame">
        <i class="fa-solid fa-bolt"></i> On-Demand LPG Delivery
      </div>
      
      <h1 class="hero-headline">
        Your LPG Cylinder, <br>
        <span class="gradient-text-flame">Delivered to Your Doorstep.</span>
      </h1>

      <p class="hero-subtext">
        Book certified domestic & commercial LPG cylinders in 30 seconds. Experience live GPS tracking, background-checked delivery partners, and transparent fair distance-based delivery pricing.
      </p>

      <div class="hero-cta-buttons">
        <a href="{{ route('site.services') }}#pricing" class="btn btn-flame">
          <i class="fa-solid fa-fire"></i> Book Now
        </a>
        <a href="#how-it-works" class="btn btn-outline-light">
          <i class="fa-solid fa-circle-play"></i> How It Works
        </a>
      </div>

      <div class="hero-highlights-strip">
        <div class="hero-highlight-item">
          <i class="fa-solid fa-circle-check"></i>
          <span>100% Safety Tested</span>
        </div>
        <div class="hero-highlight-item">
          <i class="fa-solid fa-clock"></i>
          <span>Under 45 Mins Delivery</span>
        </div>
        <div class="hero-highlight-item">
          <i class="fa-solid fa-route"></i>
          <span>Fair Distance Rate</span>
        </div>
      </div>
    </div>

    <!-- Right Hero Column: Animated Floating Cylinder & Delivery Scooter -->
    <div class="hero-visual-wrapper" data-aos="fade-left">
      <div class="hero-visual-canvas">
        <!-- Dashed Orbit Ring -->
        <div class="visual-orbit-ring"></div>

        <!-- Real LPG Cylinder Banner Image -->
        <div class="hero-cylinder-image-box">
          <img src="{{ asset('images/cylinder.png') }}" alt="Connect My LPG Cylinder" class="hero-cylinder-img">
          <div class="cylinder-shadow-base"></div>
        </div>

        <!-- Floating Live Order Card -->
        <div class="floating-status-badge">
          <div class="status-badge-icon">
            <i class="fa-solid fa-motorcycle"></i>
          </div>
          <div class="status-badge-text">
            <h5>Partner En Route</h5>
            <p><i class="fa-solid fa-location-arrow"></i> 1.8 km away &bull; ETA 8 mins</p>
          </div>
        </div>

        <!-- Road Path SVG & Animated Moving Scooter -->
        <svg class="hero-road-svg" viewBox="0 0 400 60" fill="none">
          <path d="M 10 40 Q 200 10 390 40" stroke="rgba(255, 255, 255, 0.15)" stroke-width="2" stroke-dasharray="6 6"/>
        </svg>
        <div class="hero-scooter-mover">
          <i class="fa-solid fa-truck-fast" style="color: var(--flame-orange);"></i>
          <span>Express Delivery</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     2. STATS COUNTER STRIP
     ========================================================================== -->
<section class="stats-strip" id="stats">
  <div class="container">
    <div class="stats-grid">
      @foreach($stats as $stat)
      <div class="stat-item" data-aos="fade-up">
        <div class="stat-icon-wrapper">
          <i class="fa-solid {{ $stat['icon'] }}"></i>
        </div>
        <div>
          <div class="stat-number-box">
            <span class="stat-count" data-target="{{ $stat['number'] }}" data-suffix="{{ $stat['suffix'] }}">0{{ $stat['suffix'] }}</span>
          </div>
          <div class="stat-label">{{ $stat['label'] }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ==========================================================================
     3. SERVICES PREVIEW (3 ANIMATED CARDS)
     ========================================================================== -->
<section class="section" id="services-preview">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <div class="badge-tag">
        <i class="fa-solid fa-gas-pump"></i> Tailored Solutions
      </div>
      <h2 class="section-title">Built for Homes, Kitchens & Businesses</h2>
      <p class="section-desc">From compact bachelor homes to large restaurant kitchens, we supply certified cylinders backed by real-time logistics.</p>
    </div>

    <div class="services-grid-3">
      <!-- Card 1: Domestic -->
      <div class="service-card" data-aos="fade-up" data-aos-delay="100">
        <div class="service-icon-box">
          <i class="fa-solid fa-house-chimney"></i>
        </div>
        <h3 class="service-title">Domestic LPG Cylinders</h3>
        <p class="service-desc">
          Standard 14.2 kg & 5 kg refill options designed for domestic cooking. Delivered with complete digital tare-weight verification and valve safety checks.
        </p>
        <a href="{{ route('site.services') }}#domestic-14" class="service-link">
          Explore Domestic Cylinders <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>

      <!-- Card 2: Commercial -->
      <div class="service-card" data-aos="fade-up" data-aos-delay="200">
        <div class="service-icon-box">
          <i class="fa-solid fa-utensils"></i>
        </div>
        <h3 class="service-title">Commercial & Industrial</h3>
        <p class="service-desc">
          Heavy-duty 19 kg & 47.5 kg cylinders with priority morning refill slots for restaurants, catering units, hotels, and manufacturing hubs.
        </p>
        <a href="{{ route('site.services') }}#commercial-19" class="service-link">
          Explore Commercial Supply <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>

      <!-- Card 3: Doorstep Delivery -->
      <div class="service-card" data-aos="fade-up" data-aos-delay="300">
        <div class="service-icon-box">
          <i class="fa-solid fa-person-shelter"></i>
        </div>
        <h3 class="service-title">Express Doorstep Setup</h3>
        <p class="service-desc">
          Our verified delivery agents safely carry the cylinder to your kitchen, safely replace the regulator, and verify zero leakage with soap-solution tests.
        </p>
        <a href="{{ route('site.services') }}#doorstep" class="service-link">
          Learn About Safety Delivery <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     4. HOW IT WORKS (4-STEP ANIMATED TIMELINE)
     ========================================================================== -->
<section class="section section-dark" id="how-it-works">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <div class="badge-tag flame">
        <i class="fa-solid fa-clock-rotate-left"></i> Simple 4 Steps
      </div>
      <h2 class="section-title">How Connect My LPG Works</h2>
      <p class="section-desc">Experience a hassle-free booking experience right from your phone to your kitchen stove.</p>
    </div>

    <div class="how-it-works-timeline">
      <!-- Connector Bar -->
      <div class="timeline-connector-bar">
        <div class="timeline-connector-progress"></div>
      </div>

      <!-- Step 1 -->
      <div class="timeline-step" data-aos="zoom-in" data-aos-delay="100">
        <div class="step-num-bubble">01</div>
        <h3 class="step-title">Register & Set Address</h3>
        <p class="step-desc">Enter your mobile number and set your exact delivery location on the map.</p>
      </div>

      <!-- Step 2 -->
      <div class="timeline-step" data-aos="zoom-in" data-aos-delay="200">
        <div class="step-num-bubble">02</div>
        <h3 class="step-title">Choose Cylinder Type</h3>
        <p class="step-desc">Pick domestic 14.2kg, 5kg mini, or commercial 19kg refill according to your need.</p>
      </div>

      <!-- Step 3 -->
      <div class="timeline-step" data-aos="zoom-in" data-aos-delay="300">
        <div class="step-num-bubble">03</div>
        <h3 class="step-title">Book & Pay Fair Fee</h3>
        <p class="step-desc">Transparent distance-based fee calculated automatically. Pay via UPI, Card, or COD.</p>
      </div>

      <!-- Step 4 -->
      <div class="timeline-step" data-aos="zoom-in" data-aos-delay="400">
        <div class="step-num-bubble">04</div>
        <h3 class="step-title">Doorstep Delivery</h3>
        <p class="step-desc">Your verified delivery partner brings and installs the cylinder with complete safety checks.</p>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     5. WHY CHOOSE US (6 FEATURE CARDS GRID)
     ========================================================================== -->
<section class="section" id="why-choose-us">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <div class="badge-tag">
        <i class="fa-solid fa-award"></i> The Connect Advantage
      </div>
      <h2 class="section-title">Why Customers Trust Connect My LPG</h2>
      <p class="section-desc">Engineered for absolute safety, speed, and fairness. No hidden charges, no endless waits.</p>
    </div>

    <div class="features-grid-6">
      @foreach($why_choose_us as $index => $item)
      <div class="feature-card" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
        <div class="feature-icon-wrapper">
          <i class="fa-solid {{ $item['icon'] }}"></i>
        </div>
        <div class="feature-body">
          <h4>{{ $item['title'] }}</h4>
          <p>{{ $item['desc'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ==========================================================================
     6. MOBILE APP SHOWCASE
     ========================================================================== -->
<section class="section section-dark" id="app-showcase" style="background: linear-gradient(180deg, var(--dark-base) 0%, #0d1527 100%);">
  <div class="container app-showcase-grid">
    <!-- Left: Text & Badges -->
    <div data-aos="fade-right">
      <div class="badge-tag flame">
        <i class="fa-solid fa-mobile-screen-button"></i> Mobile First
      </div>
      <h2 class="section-title">Book & Track In Seconds with Our Mobile App</h2>
      <p class="section-desc">
        Enjoy intuitive 1-click cylinder refills, live GPS routing, digital payment receipts, and automated refill reminders right on your Android or iOS smartphone.
      </p>

      <ul style="display: flex; flex-direction: column; gap: 14px; margin: 28px 0; color: rgba(255,255,255,0.85);">
        <li style="display: flex; align-items: center; gap: 12px;">
          <i class="fa-solid fa-circle-check" style="color: var(--flame-orange);"></i>
          <span>Instant booking without standing in long distributor queues</span>
        </li>
        <li style="display: flex; align-items: center; gap: 12px;">
          <i class="fa-solid fa-circle-check" style="color: var(--flame-orange);"></i>
          <span>Real-time delivery partner contact & live route navigation</span>
        </li>
        <li style="display: flex; align-items: center; gap: 12px;">
          <i class="fa-solid fa-circle-check" style="color: var(--flame-orange);"></i>
          <span>Special recurring schedules for commercial businesses & caterers</span>
        </li>
      </ul>

      <div class="app-store-badges">
        <a href="#" class="store-badge-btn">
          <i class="fa-brands fa-google-play" style="color: #34D399;"></i>
          <div class="badge-text">
            <span>GET IT ON</span>
            <strong>Google Play</strong>
          </div>
        </a>
        <a href="#" class="store-badge-btn">
          <i class="fa-brands fa-apple"></i>
          <div class="badge-text">
            <span>Download on the</span>
            <strong>App Store</strong>
          </div>
        </a>
      </div>
    </div>

    <!-- Right: CSS Phone Mockup -->
    <div data-aos="fade-left">
      <div class="phone-mockup-frame">
        <div class="phone-notch"></div>
        <div class="phone-screen-content">
          <!-- App Bar -->
          <div class="phone-app-header">
            <div style="font-weight: 700; font-size: 0.85rem; color: #1E293B;">Connect My LPG</div>
            <i class="fa-solid fa-bell text-xs" style="color: var(--primary-deep);"></i>
          </div>

          <!-- Product Card in App -->
          <div class="phone-cylinder-item">
            <img src="{{ asset('images/logo.png') }}" alt="Cylinder">
            <div style="flex-grow: 1;">
              <div style="font-weight: 700; font-size: 0.8rem; color: #0F172A;">Domestic 14.2 kg</div>
              <div style="font-size: 0.7rem; color: #64748B;">Standard Refill</div>
              <div style="font-weight: 800; font-size: 0.85rem; color: var(--primary-deep);">₹890</div>
            </div>
            <button style="background: var(--flame-orange); color: white; border: none; padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 700;">Reorder</button>
          </div>

          <!-- Live Tracking Map in Mockup -->
          <div class="phone-map-tracker">
            <div class="phone-tracker-pin">
              <i class="fa-solid fa-location-dot"></i> Arriving in 12m
            </div>
          </div>

          <!-- Notification Toast in App -->
          <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 8px; font-size: 0.72rem; color: #1E40AF;">
            <i class="fa-solid fa-circle-info me-1"></i> Delivery partner Suresh Kumar assigned to your order.
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     7. TESTIMONIALS CAROUSEL
     ========================================================================== -->
<section class="section" id="testimonials">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <div class="badge-tag">
        <i class="fa-solid fa-comments"></i> Real Stories
      </div>
      <h2 class="section-title">Loved by Thousands of Households & Chefs</h2>
      <p class="section-desc">See what our customers say about our doorstep speed, safety precautions, and transparent pricing.</p>
    </div>

    <div class="testimonials-slider-container" data-aos="zoom-in">
      <div class="testimonials-track" id="testimonialsTrack">
        @foreach($testimonials as $t)
        <div class="testimonial-card-slide">
          <div class="testimonial-stars">
            @for($i = 0; $i < $t['rating']; $i++)
              <i class="fa-solid fa-star"></i>
            @endfor
          </div>
          <p class="testimonial-quote-text">“{{ $t['quote'] }}”</p>
          <div class="testimonial-author-box">
            <img src="{{ $t['avatar'] }}" alt="{{ $t['name'] }}" class="testimonial-author-img">
            <div class="testimonial-author-info">
              <h5>{{ $t['name'] }}</h5>
              <p>{{ $t['role'] }}</p>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      <!-- Navigation Dots -->
      <div class="testimonial-dots">
        @foreach($testimonials as $index => $t)
        <button class="testimonial-dot {{ $index === 0 ? 'active' : '' }}" aria-label="Go to slide {{ $index + 1 }}"></button>
        @endforeach
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     8. CTA BANNER
     ========================================================================== -->
<section class="section" style="padding-top: 0;">
  <div class="container" data-aos="fade-up">
    <div class="cta-banner-wrapper">
      <div class="cta-banner-flame-glow"></div>
      <div class="badge-tag light" style="margin-bottom: 20px;">
        <i class="fa-solid fa-gauge-high"></i> Fast Order Hotline
      </div>
      <h2 class="cta-banner-title">Cylinder Over? Book in 30 Seconds.</h2>
      <p class="cta-banner-desc">
        Never let an empty cylinder disrupt your family dinner or restaurant rush. Call our priority dispatch or book instantly online.
      </p>
      <div style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
        <a href="{{ route('site.services') }}#pricing" class="btn btn-flame">
          <i class="fa-solid fa-fire"></i> Book a Cylinder Now
        </a>
        <a href="tel:{{ str_replace(' ', '', config('site.brand.phone')) }}" class="btn btn-outline-light">
          <i class="fa-solid fa-phone"></i> Call {{ config('site.brand.phone') }}
        </a>
      </div>
    </div>
  </div>
</section>

@endsection
