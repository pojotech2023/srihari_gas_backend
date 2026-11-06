@extends('layouts.app')

@section('title', 'About Us - Connect My LPG')
@section('meta_description', 'Discover the story, mission, and strict safety standards behind Connect My LPG, providing certified doorstep LPG cylinder delivery.')

@section('content')

<!-- ==========================================================================
     PAGE HEADER & BREADCRUMB
     ========================================================================== -->
<section class="page-hero-banner">
  <div class="container" data-aos="fade-down">
    <div class="badge-tag flame">
      <i class="fa-solid fa-shield-heart"></i> Who We Are
    </div>
    <h1 class="page-banner-title" style="color: #FFFFFF !important; font-size: clamp(2.2rem, 4vw, 3.2rem); margin-bottom: 12px; font-weight: 800;">Pioneering Safe & Transparent LPG Logistics</h1>
    <p class="page-banner-desc" style="color: #E2E8F0 !important; max-width: 680px; margin: 0 auto; font-size: 1.15rem; line-height: 1.7;">
      Redefining energy access for modern families and commercial culinary businesses with smart logistics and unwavering safety standards.
    </p>

    <div class="breadcrumb-nav">
      <a href="{{ route('site.home') }}" style="color: #E2E8F0;">Home</a>
      <i class="fa-solid fa-chevron-right text-xs" style="color: #94A3B8;"></i>
      <span style="color: var(--flame-orange); font-weight: 600;">About Us</span>
    </div>
  </div>
</section>

<!-- ==========================================================================
     OUR STORY & VISION (SPLIT LAYOUT)
     ========================================================================== -->
<section class="section">
  <div class="container">
    <div class="split-layout-grid">
      <!-- Left: Story Narrative -->
      <div data-aos="fade-right">
        <div class="badge-tag">
          <i class="fa-solid fa-book-open"></i> Our Story
        </div>
        <h2 class="section-title">From Endless Queues to On-Demand Doorstep Comfort</h2>
        <p class="section-desc" style="margin-bottom: 20px;">
          For decades, getting an LPG cylinder refill meant dealing with unpredictable booking wait times, carrying heavy 30-kilogram cylinders up staircases, and opaque delivery fees. 
        </p>
        <p style="color: var(--text-muted); line-height: 1.7; margin-bottom: 24px;">
          <strong>Connect My LPG</strong> was born out of a mission to transform LPG delivery into a safe, instant, and transparent utility experience. We connected central high-safety bottling godowns directly with certified delivery professionals through smart GPS routing technology, eliminating middleman delays and unfair charges.
        </p>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; border-top: 1px solid var(--light-border); padding-top: 24px;">
          <div>
            <h4 style="font-size: 1.1rem; color: var(--primary-deep); margin-bottom: 6px;">
              <i class="fa-solid fa-bullseye me-2"></i> Our Mission
            </h4>
            <p style="font-size: 0.9rem; color: var(--text-muted);">
              To provide every household and commercial kitchen with guaranteed safe, doorstep LPG access within 45 minutes of booking.
            </p>
          </div>
          <div>
            <h4 style="font-size: 1.1rem; color: var(--flame-orange); margin-bottom: 6px;">
              <i class="fa-solid fa-eye me-2"></i> Our Vision
            </h4>
            <p style="font-size: 0.9rem; color: var(--text-muted);">
              To build South Asia’s most trusted, digitally-monitored, zero-accident energy distribution network.
            </p>
          </div>
        </div>
      </div>

      <!-- Right: Visual Cards & Image -->
      <div data-aos="fade-left">
        <div style="position: relative;">
          <img src="https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=800&auto=format&fit=crop&q=80" alt="LPG Safety Inspection" style="border-radius: var(--radius-xl); box-shadow: var(--shadow-lg); width: 100%; height: 420px; object-fit: cover;">
          
          <div style="position: absolute; bottom: -20px; left: -20px; background: var(--dark-base); color: white; padding: 20px 28px; border-radius: var(--radius-lg); border: 1px solid var(--dark-border); box-shadow: var(--shadow-lg);">
            <div style="font-family: var(--font-heading); font-size: 2rem; font-weight: 800; color: var(--flame-yellow);">
              100%
            </div>
            <div style="font-size: 0.85rem; color: var(--text-light-muted);">Tare Weight & Leak Tested</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     CORE VALUES (3D FLIP CARDS)
     ========================================================================== -->
<section class="section section-dark" id="values">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <div class="badge-tag flame">
        <i class="fa-solid fa-compass"></i> Guiding Principles
      </div>
      <h2 class="section-title" style="color: #FFFFFF !important;">Our Core Pillars</h2>
      <p class="section-desc" style="color: #CBD5E1 !important;">Hover or tap each card to reveal our unwavering commitments to our customers and partners.</p>
    </div>

    <div class="values-grid-4">
      <!-- Value 1: Safety -->
      <div class="flip-card-container" data-aos="zoom-in" data-aos-delay="100">
        <div class="flip-card-inner">
          <div class="flip-card-front" style="background: #FFFFFF;">
            <i class="fa-solid fa-shield-virus" style="color: var(--cylinder-red); font-size: 2.75rem;"></i>
            <h3 style="font-size: 1.35rem; margin-top: 14px; color: #0F172A !important; font-weight: 700;">Absolute Safety</h3>
            <span class="flip-hint-badge" style="font-size: 0.8rem; font-weight: 600; color: #1565C0 !important; background: rgba(21, 101, 192, 0.08); padding: 5px 14px; border-radius: 9999px; margin-top: 14px; display: inline-flex; align-items: center; gap: 6px;">
              <i class="fa-solid fa-rotate"></i> Flip to explore
            </span>
          </div>
          <div class="flip-card-back" style="background: linear-gradient(135deg, #182234 0%, #0B0F1A 100%);">
            <h4 style="color: #FFB300 !important; margin-bottom: 12px; font-size: 1.2rem; font-weight: 700;">Zero Compromise</h4>
            <p style="color: #F1F5F9 !important; font-size: 0.95rem; line-height: 1.6;">Every cylinder undergoes mandatory statutory pressure tests, valve safety inspections, and O-ring leak verifications before dispatch.</p>
          </div>
        </div>
      </div>

      <!-- Value 2: Reliability -->
      <div class="flip-card-container" data-aos="zoom-in" data-aos-delay="200">
        <div class="flip-card-inner">
          <div class="flip-card-front" style="background: #FFFFFF;">
            <i class="fa-solid fa-clock-check" style="color: var(--primary-light); font-size: 2.75rem;"></i>
            <h3 style="font-size: 1.35rem; margin-top: 14px; color: #0F172A !important; font-weight: 700;">Reliability</h3>
            <span class="flip-hint-badge" style="font-size: 0.8rem; font-weight: 600; color: #1565C0 !important; background: rgba(21, 101, 192, 0.08); padding: 5px 14px; border-radius: 9999px; margin-top: 14px; display: inline-flex; align-items: center; gap: 6px;">
              <i class="fa-solid fa-rotate"></i> Flip to explore
            </span>
          </div>
          <div class="flip-card-back" style="background: linear-gradient(135deg, #182234 0%, #0B0F1A 100%);">
            <h4 style="color: #60A5FA !important; margin-bottom: 12px; font-size: 1.2rem; font-weight: 700;">Guaranteed Refills</h4>
            <p style="color: #F1F5F9 !important; font-size: 0.95rem; line-height: 1.6;">Rain or shine, our verified delivery network operates 7 days a week from 6:00 AM to 9:00 PM with automated stock replenishment.</p>
          </div>
        </div>
      </div>

      <!-- Value 3: Transparency -->
      <div class="flip-card-container" data-aos="zoom-in" data-aos-delay="300">
        <div class="flip-card-inner">
          <div class="flip-card-front" style="background: #FFFFFF;">
            <i class="fa-solid fa-scale-balanced" style="color: var(--flame-orange); font-size: 2.75rem;"></i>
            <h3 style="font-size: 1.35rem; margin-top: 14px; color: #0F172A !important; font-weight: 700;">Transparency</h3>
            <span class="flip-hint-badge" style="font-size: 0.8rem; font-weight: 600; color: #1565C0 !important; background: rgba(21, 101, 192, 0.08); padding: 5px 14px; border-radius: 9999px; margin-top: 14px; display: inline-flex; align-items: center; gap: 6px;">
              <i class="fa-solid fa-rotate"></i> Flip to explore
            </span>
          </div>
          <div class="flip-card-back" style="background: linear-gradient(135deg, #182234 0%, #0B0F1A 100%);">
            <h4 style="color: #FFB300 !important; margin-bottom: 12px; font-size: 1.2rem; font-weight: 700;">No Hidden Costs</h4>
            <p style="color: #F1F5F9 !important; font-size: 0.95rem; line-height: 1.6;">Transparent government-notified cylinder prices coupled with GPS road-distance delivery fees calculated directly from our bottling depot.</p>
          </div>
        </div>
      </div>

      <!-- Value 4: Speed -->
      <div class="flip-card-container" data-aos="zoom-in" data-aos-delay="400">
        <div class="flip-card-inner">
          <div class="flip-card-front" style="background: #FFFFFF;">
            <i class="fa-solid fa-bolt-lightning" style="color: #FBBF24; font-size: 2.75rem;"></i>
            <h3 style="font-size: 1.35rem; margin-top: 14px; color: #0F172A !important; font-weight: 700;">Speed</h3>
            <span class="flip-hint-badge" style="font-size: 0.8rem; font-weight: 600; color: #1565C0 !important; background: rgba(21, 101, 192, 0.08); padding: 5px 14px; border-radius: 9999px; margin-top: 14px; display: inline-flex; align-items: center; gap: 6px;">
              <i class="fa-solid fa-rotate"></i> Flip to explore
            </span>
          </div>
          <div class="flip-card-back" style="background: linear-gradient(135deg, #182234 0%, #0B0F1A 100%);">
            <h4 style="color: #FBBF24 !important; margin-bottom: 12px; font-size: 1.2rem; font-weight: 700;">Under 45 Minutes</h4>
            <p style="color: #F1F5F9 !important; font-size: 0.95rem; line-height: 1.6;">Smart geo-clustering matches incoming orders with the nearest active delivery partner, cutting down transit times significantly.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     HOW WE ENSURE SAFETY (CHECKLIST)
     ========================================================================== -->
<section class="section" id="safety-protocol">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <div class="badge-tag">
        <i class="fa-solid fa-certificate"></i> Safety First
      </div>
      <h2 class="section-title">Our 4-Point Doorstep Safety Protocol</h2>
      <p class="section-desc">We follow rigorous guidelines certified by petroleum and explosives safety standards.</p>
    </div>

    <div class="safety-checklist-grid">
      <!-- Check 1 -->
      <div class="safety-check-item" data-aos="fade-up" data-aos-delay="100">
        <i class="fa-solid fa-circle-check"></i>
        <div>
          <h4 style="font-size: 1.15rem; margin-bottom: 6px;">Digital Tare-Weight Inspection</h4>
          <p style="color: var(--text-muted); font-size: 0.92rem;">
            Every cylinder is weighed in front of you using a certified digital balance to ensure exact net LPG content (14.2 kg or 19 kg).
          </p>
        </div>
      </div>

      <!-- Check 2 -->
      <div class="safety-check-item" data-aos="fade-up" data-aos-delay="200">
        <i class="fa-solid fa-circle-check"></i>
        <div>
          <h4 style="font-size: 1.15rem; margin-bottom: 6px;">Tamper-Evident Safety Seal</h4>
          <p style="color: var(--text-muted); font-size: 0.92rem;">
            Unbroken hologram seal guarantees that the cylinder comes straight from the authorized bottling facility without any contamination.
          </p>
        </div>
      </div>

      <!-- Check 3 -->
      <div class="safety-check-item" data-aos="fade-up" data-aos-delay="300">
        <i class="fa-solid fa-circle-check"></i>
        <div>
          <h4 style="font-size: 1.15rem; margin-bottom: 6px;">Soap-Water Valve Leakage Test</h4>
          <p style="color: var(--text-muted); font-size: 0.92rem;">
            Before fixing your regulator, our delivery partner conducts an O-ring leak test using standard bubble detection solution.
          </p>
        </div>
      </div>

      <!-- Check 4 -->
      <div class="safety-check-item" data-aos="fade-up" data-aos-delay="400">
        <i class="fa-solid fa-circle-check"></i>
        <div>
          <h4 style="font-size: 1.15rem; margin-bottom: 6px;">Safe Regulator Installation & Emergency Guidance</h4>
          <p style="color: var(--text-muted); font-size: 0.92rem;">
            We safely connect your domestic regulator, check the rubber hose date, and guide elderly or new users on safety shut-off procedures.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     DELIVERY PARTNER HIGHLIGHT
     ========================================================================== -->
<section class="section" style="background: #FFFFFF; border-top: 1px solid var(--light-border);">
  <div class="container">
    <div class="split-layout-grid">
      <div data-aos="fade-right">
        <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?w=800&auto=format&fit=crop&q=80" alt="Verified Delivery Partner" style="border-radius: var(--radius-xl); box-shadow: var(--shadow-md); width: 100%; height: 380px; object-fit: cover;">
      </div>

      <div data-aos="fade-left">
        <div class="badge-tag flame">
          <i class="fa-solid fa-id-card"></i> Empowered Partners
        </div>
        <h2 class="section-title">Verified & Trained Delivery Champions</h2>
        <p class="section-desc" style="margin-bottom: 20px;">
          Our delivery partners are the heart of our service. Each partner undergoes biometric Aadhaar verification, valid driving license validation, police background checks, and specialized fire-safety training.
        </p>
        <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 24px;">
          By providing them with fair per-kilometer earnings, insurance coverage, and smart route optimization, we ensure courteous, prompt, and respectful service at your doorstep.
        </p>

        <a href="{{ route('site.contact') }}" class="btn btn-outline-dark">
          <i class="fa-solid fa-handshake"></i> Become a Delivery Partner
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     BOTTOM CTA
     ========================================================================== -->
<section class="section" style="padding-top: 0;">
  <div class="container" data-aos="fade-up">
    <div class="cta-banner-wrapper">
      <h2 class="cta-banner-title">Experience Safe LPG Delivery Today</h2>
      <p class="cta-banner-desc">Book your first refill in just 30 seconds or reach out to our customer care team.</p>
      <div style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
        <a href="{{ route('site.services') }}#pricing" class="btn btn-flame">
          <i class="fa-solid fa-fire"></i> Book a Cylinder
        </a>
        <a href="{{ route('site.contact') }}" class="btn btn-outline-light">
          <i class="fa-solid fa-envelope"></i> Contact Us
        </a>
      </div>
    </div>
  </div>
</section>

@endsection
