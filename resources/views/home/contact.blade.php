@extends('home.layout.master')

@section('content')

<!-- Hero Banner -->
<section class="heading-banner text-white text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 250px;">
    <h2 class="mb-2 text-uppercase fw-bold">Contact Us</h2>
    <nav class="breadcrumbs">
        <ul class="list-inline">
            <li class="list-inline-item"><a href="/" class="text-white-50">Home</a></li>
            <li class="list-inline-item text-white">Contact</li>
        </ul>
    </nav>
</section>

<!-- Contact Info & Map -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <!-- Contact Info -->
            <div class="col-lg-4">
                <div class="bg-white p-4 shadow-sm rounded h-100">
                    <h4 class="text-uppercase text-primary fw-bold mb-4">Contact Details</h4>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fas fa-map-marker-alt fs-4 me-3 text-primary"></i>
                            <div>
                                <strong>Kp. Kelapa</strong><br>Depok, Indonesia
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fas fa-phone fs-4 me-3 text-primary"></i>
                            <span>085766464443</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Google Map -->
            <div class="col-lg-8">
                <div class="map-area h-100">
                    <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15853.144050839498!2d106.8002011!3d-6.4593356!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zQ2l0YXlhbSBrcCBrZWxhcGE!5e0!3m2!1sid!2sid!4v1683187000000!5m2!1sid!2sid"                        width="100%" height="100%" style="border:0; border-radius: 10px;" allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form -->
<section class="py-5 contact-form-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="bg-white p-5 shadow rounded">
                    <h4 class="text-center text-primary fw-bold mb-4">Send Us a Message</h4>
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" placeholder="Your Email" required>
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label">Your Message</label>
                                <textarea class="form-control" id="message" rows="4" placeholder="Your Message" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100 fw-bold">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    .heading-banner {
        background: linear-gradient(to right, #6a11cb, #2575fc);
        padding: 60px 0;
    }

    .breadcrumbs ul {
        margin: 0;
        padding: 0;
    }

    .breadcrumbs ul li {
        font-size: 16px;
        display: inline;
        margin-right: 8px;
    }

    .contact-form input,
    .contact-form textarea {
        font-size: 16px;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
    }

    .contact-form button {
        background: linear-gradient(to right, #2575fc, #6a11cb);
        color: white;
        padding: 14px;
        font-size: 17px;
        border: none;
        border-radius: 6px;
        transition: 0.3s ease;
    }

    .contact-form button:hover {
        opacity: 0.9;
    }

    .map-area iframe {
        min-height: 400px;
        width: 100%;
    }
</style>
@endpush
