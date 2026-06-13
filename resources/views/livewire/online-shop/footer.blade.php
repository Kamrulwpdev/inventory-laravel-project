<footer class="bg-black text-white pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">
            {{-- Column 1: Logo and About --}}
            <div class="col-lg-3 col-md-6">
                <div class="mb-3">
                    @php
                        $logoPath = 'image/logo.png'; // Update with your logo path
                    @endphp
                    @if(file_exists(public_path($logoPath)))
                        <img src="{{ asset($logoPath) }}" height="60" alt="QuickSpace">
                    @else
                        <h2 class="fw-bold text-white mb-0">Quick<span class="text-primary">Space</span></h2>
                    @endif
                </div>
                <p class="text-secondary small pe-lg-4" style="line-height: 1.8;">
                    QuickSpace Provide world class quality cosmetics, clothing, electronics, gadgets, general items and more.
                </p>
                {{-- Social Icons --}}
                <div class="d-flex gap-2 mt-4">
                    <a href="#" class="social-icon-btn"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon-btn"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon-btn"><i class="bi bi-youtube"></i></a>
                    <a href="#" class="social-icon-btn"><i class="bi bi-whatsapp"></i></a>
                    <a href="#" class="social-icon-btn"><i class="bi bi-tiktok"></i></a>
                </div>
                {{-- Payment Partners --}}
                <div class="mt-4 pt-2">
                     <img src="https://shopilax.com/wp-content/uploads/2024/10/Payment-Method-e1765731443463.png" 
                     alt="Payment Methods" 
                     class="img-fluid" 
                     style="max-height: 40px; width: auto;">
            
                </div>
            </div>

            {{-- Column 2: Resources --}}
            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-4 footer-title text-uppercase small">Resources</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="#">Latest Product</a></li>
                    <li><a href="#">Latest News</a></li>
                    <li><a href="#">Affiliate Partner</a></li>
                    <li><a href="#">Referral Program</a></li>
                </ul>
            </div>

            {{-- Column 3: Useful Links --}}
            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-4 footer-title text-uppercase small">Useful Links</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="#">Track My Orders</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                    <li><a href="#">Refund & Return Policy</a></li>
                </ul>
            </div>

            {{-- Column 4: Company --}}
            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-4 footer-title text-uppercase small">Company</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Career</a></li>
                </ul>
            </div>

            {{-- Column 5: Google Reviews --}}
            <div class="col-lg-3 col-md-6 text-center text-lg-start border-start border-secondary ps-lg-5">
                <div class="mb-2">
                    <span class="display-5 fw-bold">4.9<span class="fs-4">/5</span></span>
                </div>
                <div class="text-warning fs-5 mb-2">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-half"></i>
                </div>
                <p class="small text-secondary mb-4">Based on QuickSpace Google reviews</p>
                <a href="https://g.page/r/CXqRtgAIWfwWEAE/review" target="_blank" class="btn btn-outline-light rounded-pill px-4 py-2">Write A Review</a>
            </div>
        </div>

        {{-- Bottom Copyright --}}
        <hr class="border-secondary mt-5 mb-4">
        <div class="text-center text-secondary small">
            <p class="mb-0">© {{ date('Y') }} QuickSpace. All rights reserved.</p>
        </div>
    </div>

    {{-- Floating Back to Top Button --}}
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="back-to-top">
        <i class="bi bi-chevron-up"></i>
    </button>
</footer>
<div class="footer-bottom">
        <p><strong>QuickSpace POS</strong> - Developed By <strong>Kamrul Hasan</strong></p>
    </div>

<style>
/*footer fbottom*/
.footer-bottom {
    text-align: center; 
        padding: 15px; 
        color: #333; 
        font-size: 14px;
}
.footer-bottom p {
    font-size: 14px; opacity: 0.8;
    margin: 0px;
}
        
    .footer-title {
        letter-spacing: 1px;
        position: relative;
    }
    .footer-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -8px;
        width: 30px;
        height: 2px;
        background-color: #ff4d8d; /* Pink accent */
    }
    .footer-links li {
        margin-bottom: 12px;
    }
    .footer-links a {
        color: #adb5bd;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .footer-links a:hover {
        color: #fff;
        padding-left: 5px;
    }
    .social-icon-btn {
        width: 35px;
        height: 35px;
        background: #222;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        text-decoration: none;
        transition: 0.3s;
        border: 1px solid #333;
    }
    .social-icon-btn:hover {
        background: #ff4d8d;
        color: white;
        transform: translateY(-3px);
    }
    .back-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 45px;
        height: 45px;
        background: #fff;
        color: #000;
        border: none;
        border-radius: 50%;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 1000;
    }
</style>