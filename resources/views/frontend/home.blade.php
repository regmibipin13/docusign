@extends('frontend.layouts.app')

@section('title', 'QuikSign - Digital Document Signing Made Easy')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title">Digital Document Signing Made Simple</h1>
                    <p class="hero-subtitle">
                        Sign, share, and manage documents securely online. No more printing, scanning, or mailing. Get
                        documents signed in minutes.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        @auth
                            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('customer.dashboard') }}"
                                class="btn btn-primary btn-hero">
                                <i class='bx bx-home'></i> Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary btn-hero">
                                <i class='bx bx-user-plus'></i> Get Started Free
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-hero">
                                <i class='bx bx-log-in'></i> Sign In
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="hero-illustration">
                        <i class='bx bx-file-blank'></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Everything you need</h2>
                <p class="section-subtitle">Powerful features to streamline your document signing workflow</p>
            </div>
            <div class="row g-3">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class='bx bx-edit'></i>
                        </div>
                        <h3 class="feature-title">Digital Signatures</h3>
                        <p class="feature-description">
                            Create and manage multiple signatures. Upload images or draw directly on documents.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class='bx bx-share'></i>
                        </div>
                        <h3 class="feature-title">Easy Sharing</h3>
                        <p class="feature-description">
                            Share via link, email, or with registered users. Create groups for batch sharing.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class='bx bx-lock'></i>
                        </div>
                        <h3 class="feature-title">Secure & Private</h3>
                        <p class="feature-description">
                            Encrypted storage with access controls and expiration dates for complete security.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class='bx bx-group'></i>
                        </div>
                        <h3 class="feature-title">Receiver Groups</h3>
                        <p class="feature-description">
                            Create recipient groups and share documents with multiple people instantly.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class='bx bx-mobile'></i>
                        </div>
                        <h3 class="feature-title">Mobile Friendly</h3>
                        <p class="feature-description">
                            Sign documents on any device with our fully responsive interface.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class='bx bx-history'></i>
                        </div>
                        <h3 class="feature-title">Audit Trail</h3>
                        <p class="feature-description">
                            Complete activity logs tracking all document interactions and changes.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">How it works</h2>
                <p class="section-subtitle">Get started in three simple steps</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h4 class="step-title">Upload Document</h4>
                        <p class="step-description">
                            Upload your PDF document. Drag and drop or browse from your device.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h4 class="step-title">Add Signatures</h4>
                        <p class="step-description">
                            Place signatures anywhere on the document. Resize and position as needed.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h4 class="step-title">Share & Download</h4>
                        <p class="step-description">
                            Share via link or email. Download the signed PDF with all signatures.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">10K+</div>
                        <div class="stat-label">Documents Signed</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">5K+</div>
                        <div class="stat-label">Active Users</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">99.9%</div>
                        <div class="stat-label">Uptime</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Support</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Ready to get started?</h2>
                <p class="section-subtitle">
                    Join thousands of users who trust QuikSign for their document signing needs.
                </p>
            </div>
            @auth
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('customer.dashboard') }}"
                    class="btn btn-primary btn-lg btn-hero">
                    <i class='bx bx-home'></i> Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg btn-hero">
                    <i class='bx bx-user-plus'></i> Create Free Account
                </a>
            @endauth
        </div>
    </section>
@endsection
