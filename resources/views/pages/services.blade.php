@extends('layouts.app')

@section('title', 'LPG Cylinders & Services - Connect My LPG')
@section('meta_description', 'Explore domestic and commercial LPG cylinders, transparent distance-based delivery rates, and emergency refill services from Connect My LPG.')

@section('content')

<!-- ==========================================================================
     PAGE HEADER & BREADCRUMB
     ========================================================================== -->
<section class="page-hero-banner">
  <div class="container" data-aos="fade-down">
    <div class="badge-tag flame">
      <i class="fa-solid fa-fire-burner"></i> Products & Logistics
    </div>
    <h1 class="page-banner-title" style="color: #FFFFFF !important; font-size: clamp(2.2rem, 4vw, 3.2rem); margin-bottom: 12px; font-weight: 800;">Certified LPG Cylinders & Fast Doorstep Refills</h1>
    <p class="page-banner-desc" style="color: #E2E8F0 !important; max-width: 680px; margin: 0 auto; font-size: 1.15rem; line-height: 1.7;">
      From compact 5kg portable cylinders to industrial 47.5kg setups, we provide certified energy supplies with real-time tracking and fair distance-based charges.
    </p>

    <div class="breadcrumb-nav">
      <a href="{{ route('site.home') }}" style="color: #E2E8F0;">Home</a>
      <i class="fa-solid fa-chevron-right text-xs" style="color: #94A3B8;"></i>
      <span style="color: var(--flame-orange); font-weight: 600;">Services & Pricing</span>
    </div>
  </div>
</section>

<!-- ==========================================================================
     CYLINDERS & PRICING GRID
     ========================================================================== -->
<section class="section" id="pricing">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <div class="badge-tag">
        <i class="fa-solid fa-tags"></i> Transparent Pricing
      </div>
      <h2 class="section-title">Select Your LPG Cylinder</h2>
      <p class="section-desc">Choose between domestic refill exchanges, bachelor packs, and commercial bulk orders.</p>
    </div>

    <div class="pricing-cylinders-grid">
      @foreach($cylinders as $cylinder)
      <div class="pricing-card {{ $cylinder['popular'] ? 'popular' : '' }}" id="{{ $cylinder['id'] }}" data-aos="fade-up">
        @if($cylinder['popular'])
        <div class="popular-badge">Most Popular</div>
        @endif

        <span style="font-size: 0.82rem; font-weight: 700; color: var(--flame-orange); text-transform: uppercase;">
          {{ $cylinder['category'] }}
        </span>

        <h3 style="font-size: 1.25rem; margin-top: 6px;">{{ $cylinder['name'] }}</h3>
        
        <div class="pricing-price-tag">
          {{ $cylinder['price'] }}
          <span style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">/ Refill</span>
        </div>

        <div style="font-size: 0.88rem; color: var(--primary-deep); font-weight: 600; margin-bottom: 12px;">
          <i class="fa-solid fa-weight-hanging me-1"></i> Net Weight: {{ $cylinder['weight'] }}
        </div>

        <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 16px;">
          {{ $cylinder['description'] }}
        </p>

        <ul class="pricing-features-list">
          @foreach($cylinder['features'] as $f)
          <li>
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ $f }}</span>
          </li>
          @endforeach
        </ul>

        <div style="margin-top: 20px;">
          <a href="{{ route('site.contact') }}?service={{ urlencode($cylinder['name']) }}" class="btn {{ $cylinder['popular'] ? 'btn-flame' : 'btn-primary' }}" style="width: 100%;">
            <i class="fa-solid fa-cart-shopping"></i> Book Refill
          </a>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ==========================================================================
     DELIVERY CHARGE CALCULATION EXPLAINER & INTERACTIVE WIDGET
     ========================================================================== -->
<section class="section section-dark" id="distance-calc">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <div class="badge-tag flame">
        <i class="fa-solid fa-calculator"></i> Fair Pricing Technology
      </div>
      <h2 class="section-title">Transparent Road-Distance Billing</h2>
      <p class="section-desc">
        Unlike other platforms that charge unfair flat fees, our system calculates delivery charges based on actual driving kilometers between our central bottling godown and your delivery address.
      </p>
    </div>

    <!-- Distance Calculation Infographic & Interactive Simulator -->
    <div class="distance-calculator-box" data-aos="zoom-in">
      <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid var(--dark-border); padding-bottom: 20px;">
        <div>
          <h3 style="font-size: 1.4rem; color: #FFFFFF;">Interactive Delivery Fee Estimator</h3>
          <p style="color: var(--text-light-muted); font-size: 0.9rem;">Test how your distance affects the delivery charge.</p>
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
          <label for="calcTripType" style="font-size: 0.88rem; color: var(--text-light-muted);">Trip Type:</label>
          <select id="calcTripType" style="background: var(--dark-surface); color: white; border: 1px solid var(--dark-border); padding: 8px 14px; border-radius: 8px; outline: none; cursor: pointer;">
            <option value="two_way">Two-Way (Refill Exchange)</option>
            <option value="one_way">One-Way (New Cylinder / Single Drop)</option>
          </select>
        </div>
      </div>

      <!-- Route Visual Line -->
      <div class="distance-route-visual">
        <div class="route-node">
          <div class="route-node-icon">
            <i class="fa-solid fa-warehouse"></i>
          </div>
          <span style="font-size: 0.85rem; font-weight: 600; color: #FFFFFF;">Central Godown</span>
          <span style="font-size: 0.72rem; color: var(--flame-yellow);">Dispatch Depot</span>
        </div>

        <div class="route-line-dynamic"></div>

        <div class="route-node">
          <div class="route-node-icon" style="background: var(--cylinder-red);">
            <i class="fa-solid fa-house-chimney-user"></i>
          </div>
          <span style="font-size: 0.85rem; font-weight: 600; color: #FFFFFF;">Your Address</span>
          <span style="font-size: 0.72rem; color: #34D399;">Doorstep Drop</span>
        </div>
      </div>

      <!-- Distance Range Slider -->
      <div class="distance-slider-control">
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-weight: 600;">
          <span style="color: var(--text-light-muted);">Driving Road Distance:</span>
          <span style="color: var(--flame-orange); font-size: 1.25rem;" id="calcDistanceValue">5 km</span>
        </div>
        <input type="range" min="1" max="30" value="5" step="0.5" class="distance-range-input" id="calcDistanceSlider">
        <div style="display: flex; justify-content: space-between; margin-top: 6px; font-size: 0.75rem; color: var(--text-light-muted);">
          <span>1 km (Local Radius)</span>
          <span>15 km (Suburbs)</span>
          <span>30 km (Outer Perimeter)</span>
        </div>
      </div>

      <!-- Live Calculated Output -->
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; background: var(--dark-surface); padding: 20px; border-radius: var(--radius-lg); border: 1px solid var(--dark-border); text-align: center; margin-top: 24px;">
        <div>
          <div style="font-size: 0.8rem; color: var(--text-light-muted);">Estimated Delivery Fee</div>
          <div style="font-family: var(--font-heading); font-size: 1.8rem; font-weight: 800; color: var(--flame-orange);" id="calcDeliveryCharge">₹130</div>
        </div>
        <div>
          <div style="font-size: 0.8rem; color: var(--text-light-muted);">Estimated Delivery Time</div>
          <div style="font-family: var(--font-heading); font-size: 1.8rem; font-weight: 800; color: #60A5FA;" id="calcEstTime">28 mins</div>
        </div>
        <div>
          <div style="font-size: 0.8rem; color: var(--text-light-muted);">GPS Routing Engine</div>
          <div style="font-family: var(--font-heading); font-size: 1.1rem; font-weight: 700; color: #34D399; margin-top: 8px;">
            <i class="fa-solid fa-route me-1"></i> Live Road Routes
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     COMPREHENSIVE SERVICES LISTING
     ========================================================================== -->
<section class="section" id="doorstep">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <div class="badge-tag">
        <i class="fa-solid fa-list-check"></i> What We Offer
      </div>
      <h2 class="section-title">Comprehensive Gas & Support Services</h2>
      <p class="section-desc">Beyond standard gas refills, we provide total kitchen fuel security.</p>
    </div>

    <div class="features-grid-6">
      <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
        <div class="feature-icon-wrapper">
          <i class="fa-solid fa-truck-ramp-box"></i>
        </div>
        <div class="feature-body">
          <h4>Heavy Lift Doorstep Delivery</h4>
          <p>No carrying cylinders upstairs. Our trained staff deliver safely right to your kitchen stove, whether floor 1 or floor 10.</p>
        </div>
      </div>

      <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
        <div class="feature-icon-wrapper">
          <i class="fa-solid fa-gauge"></i>
        </div>
        <div class="feature-body">
          <h4>Regulator & Hose Replacement</h4>
          <p>We supply BIS-approved Suraksha LPG safety hoses, automatic low-pressure regulators, and safety shut-off caps.</p>
        </div>
      </div>

      <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
        <div class="feature-icon-wrapper">
          <i class="fa-solid fa-utensils"></i>
        </div>
        <div class="feature-body">
          <h4>Bulk Commercial Manifolds</h4>
          <p>Custom multi-cylinder manifold connections for hotel kitchens, bakery ovens, and high-flame catering burners.</p>
        </div>
      </div>

      <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
        <div class="feature-icon-wrapper">
          <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div class="feature-body">
          <h4>24/7 Emergency Gas Leak Assist</h4>
          <p>Smelling gas? Call our immediate response hotline for rapid technician dispatch and safety evacuation protocols.</p>
        </div>
      </div>

      <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
        <div class="feature-icon-wrapper">
          <i class="fa-solid fa-handshake-angle"></i>
        </div>
        <div class="feature-body">
          <h4>Partner Onboarding (KYC Verified)</h4>
          <p>Turn your two-wheeler or light commercial vehicle into a dependable source of income as a verified delivery partner.</p>
        </div>
      </div>

      <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
        <div class="feature-icon-wrapper">
          <i class="fa-solid fa-receipt"></i>
        </div>
        <div class="feature-body">
          <h4>Corporate & GST Tax Invoicing</h4>
          <p>Direct GST compliant digital tax invoices for businesses, restaurant tax deductions, and audit accounting.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     FAQ ACCORDION SECTION
     ========================================================================== -->
<section class="section" style="background: var(--light-muted); border-top: 1px solid var(--light-border);" id="faqs">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <div class="badge-tag">
        <i class="fa-solid fa-circle-question"></i> Common Inquiries
      </div>
      <h2 class="section-title">Frequently Asked Questions</h2>
      <p class="section-desc">Everything you need to know about booking, weight checks, and doorstep delivery.</p>
    </div>

    <div class="faq-accordion-list" data-aos="fade-up">
      @foreach($faqs as $index => $faq)
      <div class="faq-item {{ $index === 0 ? 'active' : '' }}">
        <button type="button" class="faq-question-btn">
          <span>{{ $faq['q'] }}</span>
          <i class="fa-solid fa-chevron-down faq-icon-arrow"></i>
        </button>
        <div class="faq-answer-pane">
          <p>{{ $faq['a'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ==========================================================================
     BOTTOM CTA
     ========================================================================== -->
<section class="section" style="padding-top: 0;">
  <div class="container" data-aos="fade-up">
    <div class="cta-banner-wrapper">
      <h2 class="cta-banner-title">Ready to Book Your Cylinder?</h2>
      <p class="cta-banner-desc">Enjoy doorstep delivery with certified tare weight and live tracking.</p>
      <div style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
        <a href="#pricing" class="btn btn-flame">
          <i class="fa-solid fa-fire"></i> Select Cylinder & Book
        </a>
        <a href="{{ route('site.contact') }}" class="btn btn-outline-light">
          <i class="fa-solid fa-envelope"></i> Talk to Support
        </a>
      </div>
    </div>
  </div>
</section>

@endsection
