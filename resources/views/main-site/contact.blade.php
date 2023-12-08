@extends('template.master')

@section('content')

<div class="site-breadcrumb" style="background: url(img/breadcrumb/01.jpg)">
    <div class="container">
        <h2 class="breadcrumb-title">Contact Us</h2>
        <ul class="breadcrumb-menu">
            <li class="active">Contact</li>
            <li><a href="{{url('/')}}">Home</a></li>
        </ul>
    </div>
</div>
<section id="contact-us">
    <div class="contact-area py-120">
        <div class="container">
            <div class="contact-wrapper">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="contact-content wow slideInLeft" data-wow-duration="2.5s" data-wow-delay=".25s">
                            <div class="contact-info">
                                <div class="contact-info-icon">
                                    <i class="fal fa-map-marker-alt"></i>
                                </div>
                                <div class="contact-info-content">
                                    <h5>Office Address</h5>
                                    <p>{{env('APP_ADDRESS')}}</p>
                                </div>
                            </div>
                            <div class="contact-info">
                                <div class="contact-info-icon">
                                    <i class="fal fa-phone"></i>
                                </div>
                                <div class="contact-info-content">
                                    <h5>Call Us</h5>
                                    <p>{{env('APP_CUSTOMER_CARE_PHONE', '+234-813-2614337')}}</p>
                                </div>
                            </div>
                            <div class="contact-info">
                                <div class="contact-info-icon">
                                    <i class="fal fa-envelope"></i>
                                </div>
                                <div class="contact-info-content">
                                    <h5>Email Us</h5>
                                    <p><a href=""
                                            class="__cf_email__"> {{env('APP_CUSTOMER_CARE_EMAIL', 'info@globetrotter.com.ng')}}</a>
                                    </p>
                                </div>
                            </div>
                            <div class="contact-info">
                                <div class="contact-info-icon">
                                    <i class="fal fa-clock"></i>
                                </div>
                                <div class="contact-info-content">
                                    <h5>Open Time</h5>
                                    <p>24 / 7</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 align-self-center">
                        <div class="contact-form wow slideInRight" data-wow-duration="2.5s" data-wow-delay=".25s">
                            <div class="contact-form-header">
                                <h2>Get In Touch</h2>
                                <p>Drop us a Note to Get Started</p>
                                @if ( $errors->any() )
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach( $errors->all() as $key => $error )
                                            <li>
                                                {{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                @if (session()->has('flash_message'))
                                <div class="alert alert-success">{{ session()->get('flash_message') }}</div>
                                @endif
                            </div>
                            <form id="contact-form" action="{{url('/contact')}}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="name"
                                                placeholder="Your Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="email" class="form-control" name="email"
                                                placeholder="Your Email" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="subject"
                                        placeholder="Your Subject" required>
                                </div>
                                <div class="form-group">
                                    <textarea name="message" cols="30" rows="5" class="form-control"
                                        placeholder="Write Your Message"></textarea>
                                </div>
                                <button type="submit" class="theme-btn">Send
                                    Message <i class="far fa-paper-plane"></i></button>
                                <div class="col-md-12 mt-3">
                                    <div class="form-messege text-success"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="contact-map">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.6106747597764!2d3.4162067144032906!3d6.44400892587102!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103b8b2b46c71c6b%3A0xb05cb9b254ad0107!2s12%20Ribadu%20Rd%2C%20Ikoyi%20106104%2C%20Lagos!5e0!3m2!1sen!2sng!4v1677858809771!5m2!1sen!2sng"
            style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
</section>

@endsection