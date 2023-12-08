@extends('template.master')

@section('content')
    <div class="site-breadcrumb" style="background: url(img/breadcrumb/site-breadcrumb.jpg)">
        <div class="container">
            <h2 class="breadcrumb-title">Frequently Asked Questions</h2>
            <ul class="breadcrumb-menu">
                <li class="active">FAQs</li>
                <li><a href="{{url('/')}}">Home</a></li>
            </ul>
        </div>
    </div>
    <section id="faqs">
    <div class="faq-area py-120">
        <div class="container">
            <div class="accordion" id="accordionExample">
            <div class="row">
                <div class="col-lg-6 wow bounceInLeft" data-wow-duration="2.5s" data-wow-delay=".25s">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading1">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1"
                        aria-expanded="true" aria-controls="collapse1">
                        <span><i class="far fa-question"></i></span> What is {{ ucwords(config('app.name')) }} used for ?
                    </button>
                    </h2>
                    <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="heading1"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        It is a digital marketing platform that provides easy access to both digital and static boards for 
                        Out Of Home advertisement in Nigeria. Advertisers can easily access available advertising boards and book them. 
                    </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading2">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                        <span><i class="far fa-question"></i></span> How can I become a user of the Platform ?
                    </button>
                    </h2>
                    <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        Users of the software are classified into two and they are Advertisers and Operators. Advertisers can easily create their 
                        personified accounts on the platform by clicking <strong><a href="{{url("/signup/category")}}">here</a></strong>. 
                        While all operators' accounts are created by the Admin users. All Operators must be {{ ucwords(config('app.name')) }} members to have active accounts on the platform.
                    </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading3">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                        <span><i class="far fa-question"></i></span> Can I make payment on {{ ucwords(config('app.name')) }} ?
                    </button>
                    </h2>
                    <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        Yes, Advertisers can make payment using any of the payment gateway provided on the platform.
                    </div>
                    </div>
                </div>
                </div>
                <div class="col-lg-6 wow bounceInRight" data-wow-duration="2.5s" data-wow-delay=".25s">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading4">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                        <span><i class="far fa-question"></i></span> What Payment Gateway You Support ?
                    </button>
                    </h2>
                    <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading4"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        The payment gateways are provided on the platform.
                    </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading5">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                        <span><i class="far fa-question"></i></span> Can I book multiple boards at a time ?
                    </button>
                    </h2>
                    <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="heading5"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        Yes, {{ ucwords(config('app.name')) }} is not limited to a one-board-at-a-time feature. Advertisers can book multiple boards at a time provided the boards are available.
                    </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading1">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6"
                        aria-expanded="false" aria-controls="collapse6">
                        <span><i class="far fa-question"></i></span> How I Can Reset My Password ?
                    </button>
                    </h2>
                    <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="heading1"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        To reset your password, click <strong><a href="#">here</a></strong>.
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </section>
@endsection