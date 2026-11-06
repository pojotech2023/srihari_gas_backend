<!-- Animated Flame / Wave Divider -->
<div class="footer-wave-divider">
  <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
    <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.08,130.83,121.31,192,109.13,236.4,100.27,279.4,76.54,321.39,56.44Z" fill="#070A12"></path>
  </svg>
</div>

<!-- Main Footer -->
<footer class="site-footer">
  <div class="container">
    <div class="footer-main-grid">
      <!-- Col 1: Brand & Bio -->
      <div class="footer-brand-column">
        <a href="{{ route('site.home') }}" class="navbar-brand" style="margin-bottom: 16px;">
          <img src="{{ asset('images/logo.png') }}" alt="Connect My LPG Logo" class="navbar-logo-img">
          <div>
            <span class="navbar-brand-name">{{ config('site.brand.name') }}</span>
            <span class="navbar-brand-tagline">Safe. Fast. Doorstep Delivery.</span>
          </div>
        </a>
        <p>{{ config('site.brand.short_desc') }}</p>
        
        <div class="footer-social-row">
          <a href="{{ config('site.socials.facebook') }}" target="_blank" rel="noopener" class="footer-social-icon" aria-label="Facebook">
            <i class="fa-brands fa-facebook-f"></i>
          </a>
          <a href="{{ config('site.socials.instagram') }}" target="_blank" rel="noopener" class="footer-social-icon" aria-label="Instagram">
            <i class="fa-brands fa-instagram"></i>
          </a>
          <a href="{{ config('site.socials.twitter') }}" target="_blank" rel="noopener" class="footer-social-icon" aria-label="Twitter">
            <i class="fa-brands fa-x-twitter"></i>
          </a>
          <a href="{{ config('site.socials.youtube') }}" target="_blank" rel="noopener" class="footer-social-icon" aria-label="YouTube">
            <i class="fa-brands fa-youtube"></i>
          </a>
          <a href="{{ config('site.socials.whatsapp') }}" target="_blank" rel="noopener" class="footer-social-icon" aria-label="WhatsApp">
            <i class="fa-brands fa-whatsapp"></i>
          </a>
        </div>
      </div>

      <!-- Col 2: Quick Links -->
      <div>
        <h4 class="footer-col-title">Quick Links</h4>
        <ul class="footer-links-list">
          <li class="footer-link-item"><a href="{{ route('site.home') }}"><i class="fa-solid fa-angle-right"></i> Home</a></li>
          <li class="footer-link-item"><a href="{{ route('site.about') }}"><i class="fa-solid fa-angle-right"></i> About Us</a></li>
          <li class="footer-link-item"><a href="{{ route('site.services') }}"><i class="fa-solid fa-angle-right"></i> Cylinder Services</a></li>
          <li class="footer-link-item"><a href="{{ route('site.services') }}#pricing"><i class="fa-solid fa-angle-right"></i> Cylinder Pricing</a></li>
          <li class="footer-link-item"><a href="{{ route('site.contact') }}"><i class="fa-solid fa-angle-right"></i> Contact & Help</a></li>
        </ul>
      </div>

      <!-- Col 3: Cylinders & Types -->
      <div>
        <h4 class="footer-col-title">LPG Cylinders</h4>
        <ul class="footer-links-list">
          <li class="footer-link-item"><a href="{{ route('site.services') }}#domestic-14"><i class="fa-solid fa-fire"></i> Domestic 14.2 kg</a></li>
          <li class="footer-link-item"><a href="{{ route('site.services') }}#domestic-5"><i class="fa-solid fa-fire"></i> Chhotu Mini 5 kg</a></li>
          <li class="footer-link-item"><a href="{{ route('site.services') }}#commercial-19"><i class="fa-solid fa-fire"></i> Commercial 19 kg</a></li>
          <li class="footer-link-item"><a href="{{ route('site.services') }}#commercial-47"><i class="fa-solid fa-fire"></i> Industrial 47.5 kg</a></li>
          <li class="footer-link-item"><a href="{{ route('site.services') }}#distance-calc"><i class="fa-solid fa-calculator"></i> Distance Fee Estimator</a></li>
        </ul>
      </div>

      <!-- Col 4: Reach Us & Mobile App -->
      <div>
        <h4 class="footer-col-title">Get In Touch</h4>
        <ul class="footer-links-list" style="margin-bottom: 24px;">
          <li class="footer-link-item">
            <span style="color: var(--flame-orange);"><i class="fa-solid fa-phone me-2"></i></span>
            <span>{{ config('site.brand.phone') }}</span>
          </li>
          <li class="footer-link-item">
            <span style="color: var(--primary-light);"><i class="fa-solid fa-envelope me-2"></i></span>
            <span>{{ config('site.brand.email') }}</span>
          </li>
          <li class="footer-link-item">
            <span style="color: #10B981;"><i class="fa-solid fa-clock me-2"></i></span>
            <span>{{ config('site.brand.operating_hours') }}</span>
          </li>
          <li class="footer-link-item">
            <span style="color: var(--flame-yellow);"><i class="fa-solid fa-location-dot me-2"></i></span>
            <span style="font-size: 0.85rem;">{{ config('site.brand.address') }}</span>
          </li>
        </ul>

        <div style="display: flex; gap: 10px; flex-direction: column;">
          <a href="#" class="store-badge-btn" style="padding: 8px 14px; font-size: 0.8rem;">
            <i class="fa-brands fa-google-play" style="font-size: 1.3rem; color: #34D399;"></i>
            <div class="badge-text">
              <span style="font-size: 0.65rem;">GET IT ON</span>
              <strong>Google Play</strong>
            </div>
          </a>
          <a href="#" class="store-badge-btn" style="padding: 8px 14px; font-size: 0.8rem;">
            <i class="fa-brands fa-apple" style="font-size: 1.5rem; color: #FFFFFF;"></i>
            <div class="badge-text">
              <span style="font-size: 0.65rem;">Download on the</span>
              <strong>App Store</strong>
            </div>
          </a>
        </div>
      </div>
    </div>

    <!-- Copyright Bar -->
    <div class="footer-bottom-bar">
      <p>&copy; {{ date('Y') }} {{ config('site.brand.name') }}. All rights reserved. Complies with PESO & BIS safety standards.</p>
      <div style="display: flex; gap: 20px;">
        <a href="{{ route('site.about') }}" style="color: var(--text-light-muted);">Privacy Policy</a>
        <a href="{{ route('site.about') }}" style="color: var(--text-light-muted);">Terms & Conditions</a>
        <a href="{{ route('site.contact') }}" style="color: var(--text-light-muted);">Emergency Support</a>
      </div>
    </div>
  </div>
</footer>
