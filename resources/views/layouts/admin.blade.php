<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - BookMyLPG</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <!-- Chart.js CDN for visual metric diagrams -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar" id="adminSidebar">
            <div class="sidebar-logo">
                <img src="{{ asset('images/logo.png') }}" alt="BookMyLPG Logo" style="width: 42px; height: 42px; object-fit: contain; border-radius: 0.25rem;">
                <div>
                    <h1 class="logo-text">BOOKMYLPG</h1>
                    <span class="logo-subtext">Admin Panel</span>
                </div>
            </div>

            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="menu-item-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.customers.index') }}" class="menu-item-link {{ Request::routeIs('admin.customers.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Customers
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.products.index') }}" class="menu-item-link {{ Request::routeIs('admin.products.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Products Management
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders.index') }}" class="menu-item-link {{ Request::routeIs('admin.orders.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Orders Management
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.deliverymen.index') }}" class="menu-item-link {{ Request::routeIs('admin.deliverymen.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Deliveryman
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Body Wrapper -->
        <main class="main-wrapper">
            <header class="top-navbar">
                <button class="sidebar-toggle" id="sidebarToggleBtn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <div class="page-header">
                    <h1>@yield('header_title')</h1>
                    <p>@yield('header_subtitle', 'BookMyLPG Administration')</p>
                </div>
                <div class="admin-profile-badge">
                    <div class="profile-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <span>{{ Auth::user()->name ?? 'Admin User' }}</span>
                </div>
            </header>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Floating Notification Container -->
    <div id="notificationToast" class="notification-toast" style="display: none;">
        <div class="toast-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
        </div>
        <div class="toast-content">
            <h4 id="toastTitle">New Order Received!</h4>
            <p id="toastMessage">Order has been placed.</p>
            <a id="toastLink" href="#" class="toast-action-btn">View Order</a>
        </div>
        <button onclick="dismissNotification()" class="toast-close-btn">&times;</button>
    </div>

    <!-- Toggle sidebar & Real-time Notification script -->
    <script>
        document.getElementById('sidebarToggleBtn').addEventListener('click', function() {
            document.getElementById('adminSidebar').classList.toggle('open');
        });

        // Audio synthesis for notification chime (works out of the box without media assets)
        function playNotificationChime() {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                const audioCtx = new AudioContext();
                
                // First note (High pitch)
                const osc1 = audioCtx.createOscillator();
                const gain1 = audioCtx.createGain();
                osc1.connect(gain1);
                gain1.connect(audioCtx.destination);
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5 note
                gain1.gain.setValueAtTime(0.08, audioCtx.currentTime);
                gain1.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.35);
                osc1.start(audioCtx.currentTime);
                osc1.stop(audioCtx.currentTime + 0.35);

                // Second note (Higher harmonic)
                const osc2 = audioCtx.createOscillator();
                const gain2 = audioCtx.createGain();
                osc2.connect(gain2);
                gain2.connect(audioCtx.destination);
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(880, audioCtx.currentTime + 0.12); // A5 note
                gain2.gain.setValueAtTime(0.08, audioCtx.currentTime + 0.12);
                gain2.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.6);
                osc2.start(audioCtx.currentTime + 0.12);
                osc2.stop(audioCtx.currentTime + 0.6);
            } catch (e) {
                console.log("Audio notification blocked or failed: ", e);
            }
        }

        // Show floating notification
        function showNotification(orderId, customer, amount) {
            const toast = document.getElementById('notificationToast');
            document.getElementById('toastMessage').innerText = `Order #ORD-${orderId} placed by ${customer} for ₹${amount}`;
            document.getElementById('toastLink').href = `/admin/orders/${orderId}`;
            toast.style.display = 'flex';
            playNotificationChime();

            // Auto dismiss after 10 seconds
            setTimeout(() => {
                dismissNotification();
            }, 10000);
        }

        function dismissNotification() {
            const toast = document.getElementById('notificationToast');
            toast.style.animation = 'none';
            // Simple fade out
            toast.style.transition = 'opacity 0.3s ease';
            toast.style.opacity = '0';
            setTimeout(() => {
                toast.style.display = 'none';
                toast.style.opacity = '1';
                toast.style.animation = ''; // restore animation for next trigger
            }, 300);
        }

        // Background Check Polling
        function startOrderPolling() {
            let lastOrderId = sessionStorage.getItem('last_order_id');
            
            function checkNewOrders() {
                fetch('/admin/latest-order-id')
                    .then(response => {
                        if (!response.ok) throw new Error('Network error');
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.id > 0) {
                            // If it is the first load, just record the ID without notification
                            if (lastOrderId === null) {
                                lastOrderId = data.id;
                                sessionStorage.setItem('last_order_id', data.id);
                                return;
                            }

                            // If we find a newer ID than stored
                            if (data.id > lastOrderId) {
                                lastOrderId = data.id;
                                sessionStorage.setItem('last_order_id', data.id);
                                showNotification(data.id, data.customer, data.total);
                            }
                        }
                    })
                    .catch(error => console.log('Notification check failed:', error));
            }

            // Check immediately, then poll every 8 seconds
            checkNewOrders();
            setInterval(checkNewOrders, 8000);
        }

        // Initialize polling once page resources load
        window.addEventListener('load', startOrderPolling);
    </script>
    @yield('scripts')
</body>
</html>
