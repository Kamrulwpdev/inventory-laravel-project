
<div class="shopilax-home" style="font-family: 'Inter', sans-serif; background-color: #fff;">
        <div class="container-fluid">
       <section class="hero-slider mb-5">
            <div id="heroCarousel" class="carousel slide carousel-fade" 
                 data-bs-ride="carousel" 
                 data-bs-interval="5000" 
                 data-bs-wrap="true">
                
                {{-- Carousel Indicators --}}
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                </div>
        
                <div class="carousel-inner">
                    {{-- Slide 1 --}}
                    <div class="carousel-item active" style="height: 410px; background: url('{{ asset('image/imagesx/PRODUCT-banner-image2.jpg') }}') center/cover;">
                        {{-- Content removed for image-only view --}}
                    </div>
        
                    {{-- Slide 2 --}}
                    <div class="carousel-item" style="height: 410px; background: url('{{ asset('image/imagesx/PRODUCT-banner-image4.jpg') }}') center/cover;">
                        {{-- Content removed for image-only view --}}
                    </div>
                </div>
        
                {{-- Navigation Arrows --}}
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-3"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide-to="next">
                    <span class="carousel-control-next-icon bg-dark rounded-circle p-3"></span>
                </button>
            </div>
        </section>
        
        <!--Bannner Promotional-->
        <section class="promotional-banner my-5">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="banner-wrapper overflow-hidden rounded-4 shadow-sm">
                            <a href="/shop" wire:navigate>
                                <img src="{{ asset('image/imagesx/promotional-banner-1.gif') }}" 
                                     alt="Promotional Offer" 
                                     class="img-fluid w-100 banner-img">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!--Featurws box-->
        <section class="qs-funfaq py-5 bg-white">
            <div class="container text-center">
                <h2 class="fw-bold mb-5">Why Choose Shopilax Online Shopping?</h2>
                
                <div class="row g-4">
                    {{-- Cash On Delivery --}}
                    <div class="col-lg-3 col-md-6">
                        <div class="feature-item">
                            <div class="feature-icon mb-3">
                                <i class="bi bi-card-checklist text-pink fs-1"></i>
                            </div>
                            <h6 class="fw-bold mb-2">Cash On Delivery</h6>
                            <p class="text-muted small px-3">See before you Pay and Check the Quality, Then Pay the Cash.</p>
                        </div>
                    </div>
        
                    {{-- Exchange & Return --}}
                    <div class="col-lg-3 col-md-6">
                        <div class="feature-item">
                            <div class="feature-icon mb-3">
                                <i class="bi bi-arrow-left-right text-pink fs-1"></i>
                            </div>
                            <h6 class="fw-bold mb-2">Exchange & Return</h6>
                            <p class="text-muted small px-3">We offer hassle-free returns and exchanges within <strong>7 Day</strong></p>
                        </div>
                    </div>
        
                    {{-- Free Shipping --}}
                    <div class="col-lg-3 col-md-6">
                        <div class="feature-item">
                            <div class="feature-icon mb-3">
                                <i class="bi bi-truck text-pink fs-1"></i>
                            </div>
                            <h6 class="fw-bold mb-2">Free Shipping</h6>
                            <p class="text-muted small px-3">We're offering FREE SHIPPING on all orders over <strong>৳5000</strong>.</p>
                        </div>
                    </div>
        
                    {{-- Customer Service --}}
                    <div class="col-lg-3 col-md-6">
                        <div class="feature-item">
                            <div class="feature-icon mb-3">
                                <div class="position-relative d-inline-block">
                                     <i class="bi bi-headphones text-pink fs-1"></i>
                                     <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-pink p-1 small" style="font-size: 0.6rem;">24</span>
                                </div>
                            </div>
                            <h6 class="fw-bold mb-2">Customer Service</h6>
                            <p class="text-muted small px-3">Our Customer Service team is available on <strong>24/7</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!--Category section -->
        <section class="py-5 bg-light rounded-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Popular Categories</h3>
            <a href="/shop" wire:navigate class="text-pink fw-bold text-decoration-none small">View All <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="row g-4">
            {{-- Use ->take(8) to show only the first 8 categories --}}
            @foreach($categories->take(8) as $category)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ $category->slug ? route('category.show', $category->slug) : '#' }}" 
                   wire:navigate 
                   class="text-decoration-none group">
                    <div class="category-card text-center p-4 bg-white rounded-4 shadow-sm transition-all border border-transparent">
                        <div class="category-icon-wrapper mb-3 mx-auto d-flex align-items-center justify-content-center">
                            @if($category->image)
                                <img src="{{ asset($category->image) }}" class="img-fluid rounded-circle" alt="{{ $category->name }}" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="bi bi-grid text-pink fs-2"></i>
                                </div>
                            @endif
                        </div>
                        <h6 class="category-name fw-bold text-dark mb-0 transition-all">{{ $category->name }}</h6>
                        <span class="text-muted tiny-text">{{ $category->products_count ?? $category->products()->count() }} Products</span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

    </div>
        
        <!--Featured Products section-->
    <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4 mt-4">
            <h3 class="fw-bold mb-0">Featured Products</h3>
            <a href="/shop" class="text-primary fw-bold text-decoration-none">View All <i class="fas fa-chevron-right ms-1 small"></i></a>
        </div>
    
        <div class="row g-4">
            {{-- Use take(4) to limit the products displayed in this row --}}
            @foreach($featuredProducts->take(4) as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    @include('livewire.online-shop.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </div>
    
    <!--cosmetics oroduct section-->
    <div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <h3 class="fw-bold mb-0">Beauty & Cosmetics</h3>
        {{-- Link directly to the cosmetics category slug to avoid 404s --}}
        <a href="{{ url('/category/cosmetics') }}" class="text-primary fw-bold text-decoration-none">
            Explore Beauty <i class="fas fa-chevron-right ms-1 small"></i>
        </a>
    </div>

    <div class="row g-4">
        @forelse($cosmeticProducts as $product)
            <div class="col-6 col-md-4 col-lg-3">
                {{-- Reusing your professional card design --}}
                @include('livewire.online-shop.product-card', ['product' => $product])
            </div>
        @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted">No cosmetics products found.</p>
            </div>
        @endforelse
    </div>
</div>
</div>

<style>
    /* Shopilax Style Theme Colors */
    :root {
        --primary-color: #007bff;
        --border-color: #ebebeb;
    }
    .product-card img {
        height: 350px;
    }
    .title-divider {
        width: 60px;
        height: 3px;
        background-color: var(--primary-color);
        margin-top: 10px;
    }

    /* Product Card Styling */
    .product-card {
        background: #fff;
        transition: 0.3s ease;
    }
    .product-card:hover {
        border-color: var(--primary-color) !important;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }

    .category-img-wrapper {
        transition: 0.3s ease;
        border: 2px solid transparent;
    }
    .category-img-wrapper:hover {
        border-color: var(--primary-color);
        transform: scale(1.05);
    }

    .product-actions {
        position: absolute;
        right: 15px;
        top: 15px;
        display: flex;
        flex-direction: column;
        opacity: 0;
        transition: 0.3s ease;
    }
    .product-card:hover .product-actions {
        opacity: 1;
    }

    .btn-white {
        background: #fff;
        border: none;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .shadow-sm-hover:hover {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.05)!important;
    }
    
    /*hero slider area*/
    
    /* Ensure the images stay responsive */
.carousel-item {
    transition: transform 0.6s ease-in-out, opacity 0.5s ease-in-out;
}

/* Add a subtle animation to the text when the slide enters */
.carousel-item.active .slider-content {
    animation: fadeInUp 0.8s ease-out forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Styling for the control icons to make them visible against busy backgrounds */
.carousel-control-prev-icon, .carousel-control-next-icon {
    width: 2rem;
    height: 2rem;
}

/*4 featureBox for fun faq*/
 .text-pink {
        color: #ff4d8d !important;
    }
    .bg-pink {
        background-color: #ff4d8d !important;
    }
    .feature-item p, .feature-item h6 {
        line-height: 1.5;
        font-size: 18px;
    }
    .feature-icon i {
        display: inline-block;
        transition: transform 0.3s ease;
    }
    .feature-icon i.fs-1 {
        font-size: 60px!important;
    }
    .feature-item:hover .feature-icon i {
        transform: translateY(-5px);
    }
    
    /*popular category*/
     .text-pink {
        color: #ff4d8d !important;
    }
    
    .category-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .category-card:hover {
        transform: translateY(-10px);
        border-color: #ff4d8d;
        box-shadow: 0 10px 25px rgba(255, 77, 141, 0.1) !important;
    }

    .category-card:hover .category-name {
        color: #ff4d8d !important;
    }

    .category-icon-wrapper {
        transition: transform 0.5s ease;
    }

    .category-card:hover .category-icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }

    .tiny-text {
        font-size: 0.75rem;
        opacity: 0.7;
    }

    /* Soft glass effect on the icon background if no image exists */
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>

<script>
    document.addEventListener('livewire:navigated', () => {
        const carouselEl = document.querySelector('#heroCarousel');
        if (carouselEl) {
            // Destroy any old instance and create a fresh one
            const carousel = new bootstrap.Carousel(carouselEl, {
                interval: 5000,
                ride: 'carousel',
                wrap: true // Crucial for 2-slide looping
            });
            carousel.cycle();
        }
    });
</script>