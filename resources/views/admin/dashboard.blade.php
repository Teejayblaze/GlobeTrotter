@extends('template.master')

@section('content')

<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Dashboard</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li class="active-nav-dashboard">Dashboard</li>
        </ul>
    </div>
</div>
<section id="advertiser-dashboard">
    <div class="user-profile pt-40 pb-40 dashboard-bg-color">
        <div class="container">
            @if (session()->has('session-mismatch'))
                <div class="alert alert-warning">
                    <span>{{session()->get('session-mismatch')}}</span>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-3">
                    <div class="user-profile-sidebar operator-profile-sidebar wow slideInLeft" data-wow-duration="2.5s" data-wow-delay=".25s">
                        <div class="user-profile-sidebar-top mt-20">
                        <img class="operator-image" src="{{ $user->profile_img ? Storage::url($user->profile_img) : asset('/img/operators/e-motion.png') }}" alt="Operator Image">
                        <p class="welcome-text fs-14 mt-10">Welcome</p>
                            <h3 class="profile-name operator-profile-name">{{ $user->firstname?$user->lastname:$user->corporate_name }}</h3>
                        </div>

                        <div class="edit-profile-div text-center mt-20 mb-30">
                            {{ $department }} DEPARTMENT
                        </div>
                        <div class="profile-stats text-center">
                            <a href="{{ url('/admin/logout') }}" class="log-out-btn color-bg">Log Out <i class="far fa-sign-out"></i></a>
                        </div>
                        
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="user-profile-wrapper">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form wow fadeIn" data-wow-duration="2.5s" data-wow-delay="1.75s">
                                    <div class="user-profile-sidebar-top">
                                        <h4 class="profile-name dashboard-search-title">Dashboard</h4>
                                    </div>
                                    <div class="listing-features fl-wrap">
                                        <h2 style="text-align:left" class="fs-15">What is Lorem Ipsum?</h2> 
                                        <p class="fs-13">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>  
                                            
                                        <h2 style="text-align:left" class="fs-15 mt-20">Why do we use it?</h2>
                                        <p class="fs-13">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
                                            
                                            
                                        <h2 style="text-align:left" class="fs-15 mt-20">Where does it come from?</h2>
                                        <p class="fs-13">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.</p>
                                            
                                        <p class="fs-13">The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
                                            
                                        <h2 style="text-align:left" class="fs-15 mt-20">Where can I get some?</h2>
                                        <p class="fs-13">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p> 
                                            
                                        <span class="fw-separator" style="margin-top:3px; margin-bottom:25px;"></span>
                                        
                                    </div>
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