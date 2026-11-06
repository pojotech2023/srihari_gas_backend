/**
 * CONNECT MY LPG - SITE INTERACTIONS & ANIMATIONS
 * Author: Connect My LPG Development Team
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  // 1. PRELOADER
  const preloader = document.getElementById('preloader');
  if (preloader) {
    const hidePreloader = () => {
      preloader.classList.add('loaded');
      setTimeout(() => {
        if (preloader.parentNode) preloader.parentNode.removeChild(preloader);
      }, 700);
    };

    window.addEventListener('load', hidePreloader);
    // Safety fallback: dismiss after 2.5 seconds regardless
    setTimeout(hidePreloader, 2500);
  }

  // 2. SCROLL PROGRESS BAR & STICKY NAVBAR
  const scrollProgressBar = document.getElementById('scrollProgressBar');
  const navbar = document.querySelector('.site-navbar');

  window.addEventListener('scroll', function () {
    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    
    if (scrollProgressBar && scrollHeight > 0) {
      const scrollPercent = (scrollTop / scrollHeight) * 100;
      scrollProgressBar.style.width = scrollPercent + '%';
    }

    if (navbar) {
      if (scrollTop > 40) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    }
  }, { passive: true });

  // 3. MOBILE DRAWER NAVIGATION
  const hamburgerBtn = document.getElementById('hamburgerBtn');
  const closeDrawerBtn = document.getElementById('closeDrawerBtn');
  const mobileDrawer = document.getElementById('mobileNavDrawer');
  const drawerBackdrop = document.getElementById('mobileDrawerBackdrop');

  function openMobileMenu() {
    if (mobileDrawer) mobileDrawer.classList.add('open');
    if (drawerBackdrop) drawerBackdrop.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeMobileMenu() {
    if (mobileDrawer) mobileDrawer.classList.remove('open');
    if (drawerBackdrop) drawerBackdrop.classList.remove('active');
    document.body.style.overflow = '';
  }

  if (hamburgerBtn) hamburgerBtn.addEventListener('click', openMobileMenu);
  if (closeDrawerBtn) closeDrawerBtn.addEventListener('click', closeMobileMenu);
  if (drawerBackdrop) drawerBackdrop.addEventListener('click', closeMobileMenu);

  document.querySelectorAll('.mobile-nav-link').forEach(link => {
    link.addEventListener('click', closeMobileMenu);
  });

  // 4. STATS COUNTER ANIMATION (Count-up on viewport enter)
  const statNumbers = document.querySelectorAll('.stat-count');
  if (statNumbers.length > 0 && 'IntersectionObserver' in window) {
    const statsObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const target = entry.target;
          const endValue = parseInt(target.getAttribute('data-target'), 10) || 0;
          const suffix = target.getAttribute('data-suffix') || '';
          const duration = 1800; // ms
          const startTime = performance.now();

          function updateCounter(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            // Ease-out cubic calculation
            const easeOutProgress = 1 - Math.pow(1 - progress, 3);
            const currentVal = Math.floor(easeOutProgress * endValue);
            
            target.textContent = currentVal.toLocaleString('en-IN') + suffix;

            if (progress < 1) {
              requestAnimationFrame(updateCounter);
            } else {
              target.textContent = endValue.toLocaleString('en-IN') + suffix;
            }
          }

          requestAnimationFrame(updateCounter);
          observer.unobserve(target);
        }
      });
    }, { threshold: 0.4 });

    statNumbers.forEach(stat => statsObserver.observe(stat));
  }

  // 5. TESTIMONIALS SLIDER CAROUSEL
  const testimonialsTrack = document.getElementById('testimonialsTrack');
  const testimonialDots = document.querySelectorAll('.testimonial-dot');
  let currentSlide = 0;
  let slideInterval = null;

  function goToSlide(index) {
    if (!testimonialsTrack) return;
    const slides = testimonialsTrack.querySelectorAll('.testimonial-card-slide');
    if (slides.length === 0) return;

    if (index >= slides.length) index = 0;
    if (index < 0) index = slides.length - 1;

    currentSlide = index;
    testimonialsTrack.style.transform = `translateX(-${currentSlide * 100}%)`;

    testimonialDots.forEach((dot, i) => {
      if (i === currentSlide) {
        dot.classList.add('active');
      } else {
        dot.classList.remove('active');
      }
    });
  }

  if (testimonialsTrack && testimonialDots.length > 0) {
    testimonialDots.forEach((dot, i) => {
      dot.addEventListener('click', () => {
        goToSlide(i);
        restartSlideTimer();
      });
    });

    function startSlideTimer() {
      slideInterval = setInterval(() => {
        goToSlide(currentSlide + 1);
      }, 5000);
    }

    function restartSlideTimer() {
      clearInterval(slideInterval);
      startSlideTimer();
    }

    startSlideTimer();

    // Pause on hover
    testimonialsTrack.addEventListener('mouseenter', () => clearInterval(slideInterval));
    testimonialsTrack.addEventListener('mouseleave', startSlideTimer);
  }

  // 6. FAQ ACCORDION
  const faqItems = document.querySelectorAll('.faq-item');
  faqItems.forEach(item => {
    const questionBtn = item.querySelector('.faq-question-btn');
    const answerPane = item.querySelector('.faq-answer-pane');

    if (questionBtn && answerPane) {
      questionBtn.addEventListener('click', function () {
        const isActive = item.classList.contains('active');

        // Close all other FAQs
        faqItems.forEach(otherItem => {
          if (otherItem !== item) {
            otherItem.classList.remove('active');
            const otherPane = otherItem.querySelector('.faq-answer-pane');
            if (otherPane) otherPane.style.maxHeight = null;
          }
        });

        // Toggle current FAQ
        if (isActive) {
          item.classList.remove('active');
          answerPane.style.maxHeight = null;
        } else {
          item.classList.add('active');
          answerPane.style.maxHeight = answerPane.scrollHeight + 32 + 'px';
        }
      });
    }
  });

  // Open the first FAQ item by default
  if (faqItems.length > 0) {
    const firstItem = faqItems[0];
    const firstPane = firstItem.querySelector('.faq-answer-pane');
    firstItem.classList.add('active');
    if (firstPane) firstPane.style.maxHeight = firstPane.scrollHeight + 32 + 'px';
  }

  // 7. INTERACTIVE DISTANCE DELIVERY CALCULATOR WIDGET (Services Page)
  const distanceSlider = document.getElementById('calcDistanceSlider');
  const distanceValueLabel = document.getElementById('calcDistanceValue');
  const tripTypeSelect = document.getElementById('calcTripType');
  const calcDeliveryCharge = document.getElementById('calcDeliveryCharge');
  const calcEstTime = document.getElementById('calcEstTime');

  function updateDistanceCalc() {
    if (!distanceSlider || !calcDeliveryCharge) return;
    const distanceKm = parseFloat(distanceSlider.value) || 5;
    const tripType = tripTypeSelect ? tripTypeSelect.value : 'two_way';

    if (distanceValueLabel) {
      distanceValueLabel.textContent = distanceKm + ' km';
    }

    // Calculation logic mirroring DeliveryChargeService:
    // Base fee: ₹40
    // Rate: ₹12/km (one_way) or ₹18/km (two_way refill exchange)
    const perKmRate = tripType === 'one_way' ? 12 : 18;
    const totalCharge = Math.round(40 + (distanceKm * perKmRate));

    calcDeliveryCharge.textContent = '₹' + totalCharge;

    if (calcEstTime) {
      const minutes = Math.round(15 + (distanceKm * 2.5));
      calcEstTime.textContent = minutes + ' mins';
    }
  }

  if (distanceSlider) {
    distanceSlider.addEventListener('input', updateDistanceCalc);
  }
  if (tripTypeSelect) {
    tripTypeSelect.addEventListener('change', updateDistanceCalc);
  }
  updateDistanceCalc();

  // 8. CONTACT FORM AJAX SUBMISSION WITH SUCCESS ANIMATION
  const contactForm = document.getElementById('contactForm');
  const contactSuccessCard = document.getElementById('contactSuccessCard');
  const submitBtn = document.getElementById('contactSubmitBtn');

  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();

      // Clear existing errors
      document.querySelectorAll('.form-feedback-error').forEach(el => el.textContent = '');

      // Loading state on button
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Submitting...';
      }

      const formData = new FormData(contactForm);

      fetch(contactForm.action, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
        body: formData,
      })
      .then(async response => {
        const data = await response.json().catch(() => ({}));

        if (response.ok && data.success) {
          // Success state
          contactForm.style.display = 'none';
          if (contactSuccessCard) {
            contactSuccessCard.style.display = 'block';
            const msgEl = contactSuccessCard.querySelector('.success-msg-text');
            if (msgEl) msgEl.textContent = data.message;
          }
        } else if (response.status === 422 && data.errors) {
          // Validation error
          Object.keys(data.errors).forEach(field => {
            const errSpan = document.getElementById('error-' + field);
            if (errSpan) {
              errSpan.textContent = data.errors[field][0];
            }
          });
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Send Message';
          }
        } else {
          // Server error or unexpected response
          alert(data.message || 'Something went wrong. Please check your connection and try again.');
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Send Message';
          }
        }
      })
      .catch(err => {
        console.error('Submission error:', err);
        // Fallback: regular form submit if AJAX is blocked
        contactForm.submit();
      });
    });
  }

  // 9. INITIALIZE AOS IF LOADED
  if (typeof AOS !== 'undefined') {
    AOS.init({
      once: true,
      duration: 800,
      easing: 'ease-out-cubic',
      offset: 50,
    });
  }

  // 10. GSAP HERO REVEAL ANIMATIONS (If GSAP is loaded)
  if (typeof gsap !== 'undefined') {
    gsap.from('.hero-headline', {
      duration: 1,
      y: 40,
      opacity: 0,
      ease: 'power3.out',
      delay: 0.2
    });

    gsap.from('.hero-subtext', {
      duration: 1,
      y: 30,
      opacity: 0,
      ease: 'power3.out',
      delay: 0.4
    });

    gsap.from('.hero-cta-buttons', {
      duration: 0.9,
      y: 20,
      opacity: 0,
      ease: 'power3.out',
      delay: 0.6
    });

    gsap.from('.hero-cylinder-image-box, .cylinder-3d-box', {
      duration: 1.2,
      scale: 0.7,
      opacity: 0,
      ease: 'back.out(1.7)',
      delay: 0.3
    });
  }

  // 11. 3D FLIP CARDS TAP TOGGLE (Mobile / Touch Devices)
  document.querySelectorAll('.flip-card-container').forEach(card => {
    card.addEventListener('click', function () {
      this.classList.toggle('flipped');
    });
  });
});
