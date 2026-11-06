@extends('layouts.app')

@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag;
@endphp

@section('title', 'Contact Us & Help Center - Connect My LPG')
@section('meta_description', 'Contact Connect My LPG for customer support, cylinder refills, emergency assistance, or partner inquiries.')

@section('content')

<!-- ==========================================================================
     PAGE HEADER & BREADCRUMB
     ========================================================================== -->
<section class="page-hero-banner">
  <div class="container" data-aos="fade-down">
    <div class="badge-tag flame">
      <i class="fa-solid fa-headset"></i> We're Here to Help
    </div>
    <h1 class="page-banner-title" style="color: #FFFFFF !important; font-size: clamp(2.2rem, 4vw, 3.2rem); margin-bottom: 12px; font-weight: 800;">Get in Touch with Our Team</h1>
    <p class="page-banner-desc" style="color: #E2E8F0 !important; max-width: 680px; margin: 0 auto; font-size: 1.15rem; line-height: 1.7;">
      Have questions about LPG booking, commercial manifold connections, or becoming a verified delivery partner? Send us a message or call directly.
    </p>

    <div class="breadcrumb-nav">
      <a href="{{ route('site.home') }}" style="color: #E2E8F0;">Home</a>
      <i class="fa-solid fa-chevron-right text-xs" style="color: #94A3B8;"></i>
      <span style="color: var(--flame-orange); font-weight: 600;">Contact</span>
    </div>
  </div>
</section>

<!-- ==========================================================================
     CONTACT CONTENT (2 COLUMNS: INFO & FORM)
     ========================================================================== -->
<section class="section">
  <div class="container">
    <div class="contact-layout-grid">
      <!-- Left Column: Contact Cards -->
      <div data-aos="fade-right">
        <div class="badge-tag">
          <i class="fa-solid fa-address-book"></i> Reach Out
        </div>
        <h2 class="section-title" style="margin-bottom: 24px;">Always Available for Your Cooking Energy Needs</h2>
        <p class="section-desc" style="margin-bottom: 32px;">
          Our customer support lines operate 7 days a week. For emergencies, our quick response team is stationed at our central bottling and storage godowns.
        </p>

        <div class="contact-info-cards">
          <!-- Card: Phone -->
          <div class="contact-info-card">
            <div class="contact-card-icon" style="color: var(--primary-deep); background: rgba(21, 101, 192, 0.1);">
              <i class="fa-solid fa-phone-volume"></i>
            </div>
            <div class="contact-card-content">
              <h5>Customer Support Helpline</h5>
              <a href="tel:{{ str_replace(' ', '', $brand['phone']) }}" style="font-weight: 700; color: var(--primary-deep);">
                {{ $brand['phone'] }}
              </a>
              <p>Mon - Sun: 06:00 AM - 09:00 PM</p>
            </div>
          </div>

          <!-- Card: WhatsApp -->
          <div class="contact-info-card">
            <div class="contact-card-icon" style="color: #10B981; background: rgba(16, 185, 129, 0.1);">
              <i class="fa-brands fa-whatsapp"></i>
            </div>
            <div class="contact-card-content">
              <h5>Instant WhatsApp Booking</h5>
              <a href="{{ config('site.socials.whatsapp') }}" target="_blank" rel="noopener" style="font-weight: 700; color: #10B981;">
                Chat on WhatsApp (+91 98765 43210)
              </a>
              <p>Send 'HI' to book your cylinder in seconds</p>
            </div>
          </div>

          <!-- Card: Email -->
          <div class="contact-info-card">
            <div class="contact-card-icon" style="color: var(--flame-orange); background: rgba(255, 143, 0, 0.1);">
              <i class="fa-solid fa-envelope-open-text"></i>
            </div>
            <div class="contact-card-content">
              <h5>Official Email Inquiries</h5>
              <a href="mailto:{{ $brand['email'] }}" style="font-weight: 600; color: var(--text-dark);">
                {{ $brand['email'] }}
              </a>
              <p>For corporate bulk contracts & inquiries</p>
            </div>
          </div>

          <!-- Card: Godown Address -->
          <div class="contact-info-card">
            <div class="contact-card-icon" style="color: var(--cylinder-red); background: rgba(211, 47, 47, 0.1);">
              <i class="fa-solid fa-warehouse"></i>
            </div>
            <div class="contact-card-content">
              <h5>Central Godown & Dispatch Depot</h5>
              <p style="font-weight: 500; color: var(--text-dark);">{{ $brand['address'] }}</p>
              <p>Authorized LPG Distribution Terminal</p>
            </div>
          </div>
        </div>

        <!-- Social Media Follow -->
        <div style="margin-top: 32px;">
          <h5 style="font-size: 0.95rem; margin-bottom: 12px; color: var(--text-dark);">Follow Us on Social Media:</h5>
          <div class="footer-social-row" style="margin-top: 0;">
            <a href="{{ config('site.socials.facebook') }}" target="_blank" rel="noopener" class="footer-social-icon" style="background: #E2E8F0; color: var(--text-dark);" aria-label="Facebook">
              <i class="fa-brands fa-facebook-f"></i>
            </a>
            <a href="{{ config('site.socials.instagram') }}" target="_blank" rel="noopener" class="footer-social-icon" style="background: #E2E8F0; color: var(--text-dark);" aria-label="Instagram">
              <i class="fa-brands fa-instagram"></i>
            </a>
            <a href="{{ config('site.socials.twitter') }}" target="_blank" rel="noopener" class="footer-social-icon" style="background: #E2E8F0; color: var(--text-dark);" aria-label="Twitter">
              <i class="fa-brands fa-x-twitter"></i>
            </a>
            <a href="{{ config('site.socials.youtube') }}" target="_blank" rel="noopener" class="footer-social-icon" style="background: #E2E8F0; color: var(--text-dark);" aria-label="YouTube">
              <i class="fa-brands fa-youtube"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Right Column: Interactive Form -->
      <div data-aos="fade-left">
        <div class="contact-form-box">
          <div style="margin-bottom: 28px;">
            <h3 style="font-size: 1.5rem; margin-bottom: 8px;">Send a Direct Message</h3>
            <p style="color: var(--text-muted); font-size: 0.92rem;">
              Fill in your details and our team will get back to you promptly.
            </p>
          </div>

          <!-- Server Flash Message Banner (if not submitted via AJAX) -->
          @if(session('success'))
          <div style="background: #ECFDF5; border: 1.5px solid #10B981; border-radius: var(--radius-md); padding: 16px 20px; color: #065F46; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
            <i class="fa-solid fa-circle-check" style="font-size: 1.4rem; color: #10B981;"></i>
            <span>{{ session('success') }}</span>
          </div>
          @endif

          <!-- Success Card (AJAX State) -->
          <div class="contact-submit-success-card" id="contactSuccessCard">
            <div class="success-animated-checkmark">
              <i class="fa-solid fa-check"></i>
            </div>
            <h3 style="font-size: 1.4rem; margin-bottom: 10px;">Message Sent Successfully!</h3>
            <p class="success-msg-text" style="color: var(--text-muted); max-width: 400px; margin: 0 auto 24px auto;">
              Thank you for contacting Connect My LPG. One of our support managers will reach out to you shortly.
            </p>
            <button type="button" class="btn btn-outline-dark" onclick="window.location.reload();">
              <i class="fa-solid fa-arrow-rotate-left"></i> Send Another Message
            </button>
          </div>

          <!-- Main Contact Form -->
          <form action="{{ route('site.contact.submit') }}" method="POST" id="contactForm" novalidate>
            @csrf

            <!-- Name Field -->
            <div class="floating-input-group">
              <input type="text" name="name" id="name" class="floating-input" placeholder=" " value="{{ old('name') }}" required>
              <label for="name" class="floating-label">Full Name *</label>
              <span class="form-feedback-error" id="error-name">
                @error('name') {{ $message }} @enderror
              </span>
            </div>

            <!-- Phone & Email Row -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
              <div class="floating-input-group">
                <input type="tel" name="phone" id="phone" class="floating-input" placeholder=" " value="{{ old('phone') }}" required>
                <label for="phone" class="floating-label">Mobile Number *</label>
                <span class="form-feedback-error" id="error-phone">
                  @error('phone') {{ $message }} @enderror
                </span>
              </div>

              <div class="floating-input-group">
                <input type="email" name="email" id="email" class="floating-input" placeholder=" " value="{{ old('email') }}" required>
                <label for="email" class="floating-label">Email Address *</label>
                <span class="form-feedback-error" id="error-email">
                  @error('email') {{ $message }} @enderror
                </span>
              </div>
            </div>

            <!-- Subject Field -->
            <div class="floating-input-group">
              <input type="text" name="subject" id="subject" class="floating-input" placeholder=" " value="{{ request('service') ? 'Inquiry for ' . request('service') : old('subject') }}">
              <label for="subject" class="floating-label">Subject / Purpose (Optional)</label>
              <span class="form-feedback-error" id="error-subject">
                @error('subject') {{ $message }} @enderror
              </span>
            </div>

            <!-- Message Field -->
            <div class="floating-input-group">
              <textarea name="message" id="message" rows="4" class="floating-input" placeholder=" " style="resize: vertical; min-height: 120px;" required>{{ old('message') }}</textarea>
              <label for="message" class="floating-label">Your Message or Order Request *</label>
              <span class="form-feedback-error" id="error-message">
                @error('message') {{ $message }} @enderror
              </span>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-flame" id="contactSubmitBtn" style="width: 100%; padding: 16px;">
              <i class="fa-solid fa-paper-plane"></i> Send Message
            </button>
            <p style="font-size: 0.78rem; color: var(--text-muted); text-align: center; margin-top: 14px;">
              <i class="fa-solid fa-lock me-1"></i> We respect your privacy. Your information is 100% confidential.
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     EMBEDDED GOOGLE MAP LOCATION (IFRAME)
     ========================================================================== -->
<section style="line-height: 0; background: var(--dark-base);">
  <iframe 
    title="Connect My LPG Godown Location Map"
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d124414.28828236166!2d80.12658882570025!3d13.003975765715975!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a5265ea4f7d3361%3A0x6e61a70b6863d433!2sChennai%2C%20Tamil%20Nadu!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin" 
    width="100%" 
    height="380" 
    style="border:0; filter: grayscale(30%) contrast(1.1);" 
    allowfullscreen="" 
    loading="lazy" 
    referrerpolicy="no-referrer-when-downgrade">
  </iframe>
</section>

@endsection
