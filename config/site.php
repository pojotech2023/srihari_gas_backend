<?php

return [
    'brand' => [
        'name' => 'Connect My LPG',
        'tagline' => 'Safe. Fast. Doorstep LPG Delivery.',
        'short_desc' => 'Your trusted on-demand LPG cylinder delivery partner. Book domestic and commercial cylinders in 30 seconds with doorstep delivery and fair, transparent distance-based charges.',
        'godown_location' => 'Main LPG Bottling & Distribution Godown, Industrial Area, Chennai, TN',
        'operating_hours' => 'Mon - Sun: 06:00 AM - 09:00 PM',
        'phone' => '+91 98765 43210',
        'whatsapp' => '+91 98765 43210',
        'email' => 'support@connectmylpg.com',
        'address' => '42/A, Energy Boulevard, Near Central Hub, Chennai, Tamil Nadu - 600001',
    ],

    'stats' => [
        [
            'number' => 25000,
            'suffix' => '+',
            'label' => 'Happy Customers',
            'icon' => 'fa-users',
        ],
        [
            'number' => 85000,
            'suffix' => '+',
            'label' => 'Cylinders Delivered',
            'icon' => 'fa-fire-flame-curved',
        ],
        [
            'number' => 120,
            'suffix' => '+',
            'label' => 'Verified Delivery Partners',
            'icon' => 'fa-motorcycle',
        ],
        [
            'number' => 45,
            'suffix' => '+',
            'label' => 'Service Areas Covered',
            'icon' => 'fa-map-location-dot',
        ],
    ],

    'cylinders' => [
        [
            'id' => 'domestic-14',
            'name' => 'Domestic LPG Cylinder',
            'weight' => '14.2 kg',
            'price' => '₹890',
            'category' => 'Home & Kitchen',
            'trip_type' => 'Two-Way (Refill / Exchange)',
            'description' => 'Ideal for household cooking needs. Standard certified domestic LPG cylinder with tamper-proof seal and safety certificate.',
            'popular' => true,
            'features' => ['BIS Certified Safety Valve', 'Doorstep Weight Check', 'Leakage Pre-Inspection', 'Same-day Priority Delivery'],
        ],
        [
            'id' => 'domestic-5',
            'name' => 'Chhotu Mini Cylinder',
            'weight' => '5.0 kg',
            'price' => '₹360',
            'category' => 'Students & Small Homes',
            'trip_type' => 'Two-Way (Refill / Exchange)',
            'description' => 'Compact, lightweight, and easy to carry. Specially designed for students, bachelors, and quick emergency back-up.',
            'popular' => false,
            'features' => ['Ultra-portable Design', 'Zero Documentation Needed', 'Instant Booking', 'Direct Handover'],
        ],
        [
            'id' => 'commercial-19',
            'name' => 'Commercial LPG Cylinder',
            'weight' => '19.0 kg',
            'price' => '₹1,850',
            'category' => 'Hotels & Restaurants',
            'trip_type' => 'Commercial Delivery',
            'description' => 'Heavy-duty commercial blue-band cylinders crafted for restaurants, cloud kitchens, hotels, and food trucks.',
            'popular' => true,
            'features' => ['High-pressure Output', 'Bulk Order Discounts', 'Dedicated Account Manager', 'Scheduled Morning Refills'],
        ],
        [
            'id' => 'commercial-47',
            'name' => 'Industrial Jumbo Cylinder',
            'weight' => '47.5 kg',
            'price' => '₹4,620',
            'category' => 'Manufacturing & Industry',
            'trip_type' => 'Industrial Transport',
            'description' => 'High-capacity industrial cylinder engineered for severe thermal requirements, industrial manufacturing, and large bakeries.',
            'popular' => false,
            'features' => ['Heavy Industrial Grade', 'Multi-Manifold Compatible', 'Hydraulic Lift Delivery', '24/7 Priority Supply Line'],
        ],
    ],

    'why_choose_us' => [
        [
            'icon' => 'fa-route',
            'title' => 'Fair Distance-Based Charges',
            'desc' => 'No flat or unfair delivery fees. Distance is calculated live from our godown via real road routes, giving you the fairest price every time.',
        ],
        [
            'icon' => 'fa-location-crosshairs',
            'title' => 'Live GPS Order Tracking',
            'desc' => 'Track your assigned delivery partner in real-time right from pickup at the godown until arrival at your doorstep.',
        ],
        [
            'icon' => 'fa-shield-halved',
            'title' => '100% Safety Inspected',
            'desc' => 'Every single cylinder undergoes thorough valve integrity, tare-weight verification, and digital leak checks before dispatch.',
        ],
        [
            'icon' => 'fa-id-card-clip',
            'title' => 'KYC Verified Partners',
            'desc' => 'All delivery agents are background-verified with Aadhaar, driving license, and trained in hazardous material handling protocols.',
        ],
        [
            'icon' => 'fa-bell',
            'title' => 'Smart Push Notifications',
            'desc' => 'Get instant updates at every step—when your order is confirmed, assigned, out for delivery, and safely delivered.',
        ],
        [
            'icon' => 'fa-wallet',
            'title' => 'Cash or Digital Payments',
            'desc' => 'Pay seamlessly with UPI, Credit/Debit cards, Net Banking, or pay by Cash on Delivery (COD) upon receiving your cylinder.',
        ],
    ],

    'testimonials' => [
        [
            'name' => 'Rajesh Sundaram',
            'role' => 'Restaurant Owner, Spice Garden',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
            'rating' => 5,
            'quote' => 'In the restaurant business, running out of gas during dinner rush is a disaster. Connect My LPG delivers 19kg commercial cylinders within 45 minutes. The transparent distance billing saves us thousands each month.',
        ],
        [
            'name' => 'Priya Jayakumar',
            'role' => 'Homemaker, T. Nagar',
            'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80',
            'rating' => 5,
            'quote' => 'Booking our domestic cylinder was so effortless! The delivery partner checked the weight and safety seal right in front of me before installing. Very polite and completely trustworthy.',
        ],
        [
            'name' => 'Karthik Narayanan',
            'role' => 'Software Engineer, Velachery',
            'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80',
            'rating' => 5,
            'quote' => 'The live map tracking and instant notifications are a game-changer. I knew exactly when the delivery agent entered my street. No more taking half-day leaves waiting for the cylinder truck!',
        ],
    ],

    'faqs' => [
        [
            'q' => 'How is the delivery charge calculated for my order?',
            'a' => 'Our system calculates the exact driving road distance in kilometers between our central bottling godown and your delivery address using GPS routing. Charges are calculated at a fair, regulated per-kilometer rate (either for one-way or round-trip cylinder collection), ensuring 100% transparency.',
        ],
        [
            'q' => 'Can I book a cylinder if I do not have an empty cylinder to exchange?',
            'a' => 'Yes! We provide both refill exchanges (two-way trip) and new connection package options (one-way trip) where you can purchase a new cylinder along with regulator and security deposit.',
        ],
        [
            'q' => 'How do I know my cylinder is safe and has the right weight?',
            'a' => 'Every delivery partner carries a calibrated digital weighing scale and an O-ring leak test kit. You can request a live weight check and seal verification right at your doorstep before accepting delivery.',
        ],
        [
            'q' => 'What are the delivery operating hours?',
            'a' => 'We deliver 7 days a week from 06:00 AM to 09:00 PM. Orders placed after 08:30 PM are automatically scheduled for early morning priority delivery starting at 06:30 AM.',
        ],
        [
            'q' => 'How can I become a verified delivery partner?',
            'a' => 'Download our Delivery Partner App, submit your KYC documents (Aadhaar, Driving License, Vehicle RC Book, and Insurance), and complete a short safety training module. Our team verifies accounts within 24 hours.',
        ],
    ],

    'socials' => [
        'facebook' => 'https://facebook.com',
        'instagram' => 'https://instagram.com',
        'twitter' => 'https://twitter.com',
        'youtube' => 'https://youtube.com',
        'whatsapp' => 'https://wa.me/919876543210',
    ],
];
