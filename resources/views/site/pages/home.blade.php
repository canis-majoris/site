@extends('site.layout')

@section('title', 'Home')

@section('head')
    <link rel="stylesheet" href="{{ asset('../node_modules/owl.carousel/dist/assets/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}" />
@endsection

@section('content')
    <main>
        <section id="slider">
            <div class="owl-carousel owl-theme">
              <div class="item" style="background-image: url({{ asset('/images/img-1.jpg') }});"></div>
              <div class="item" style="background-image: url({{ asset('/images/img-2.jpg') }});"></div>
              <div class="item" style="background-image: url({{ asset('/images/img-3.jpg') }});"></div>
              <div class="item" style="background-image: url({{ asset('/images/img-4.jpg') }});"></div>
            </div>
        </section>
        <section id="s-2">
            <div class="c-wrapper-1">
                <div class="container-c">
                    <div class="inner-block" data-aos="fade-up"> 
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <a href="#" class="c-banner-1" data-aos="fade-down-right">
                                    <div class="img" style="background-image: url({{ asset('/images/img-1.jpg') }});">
                                        
                                    </div>
                                    <div class="content">
                                        <div class="icon-wrapper">
                                            <span class="c-icon work"></span>
                                        </div>
                                        <div class="title text-white">work permit</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-12 col-lg-6">
                                <a href="#" class="c-banner-1" data-aos="fade-down-left"> 
                                    <div class="img" style="background-image: url({{ asset('/images/img-2.jpg') }});">
                                        
                                    </div>
                                    <div class="content">
                                        <div class="icon-wrapper">
                                            <span class="c-icon handshake"></span>
                                        </div>
                                        <div class="title text-white">business visit program</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-12 col-lg-6">
                                <a href="#" class="c-banner-1" data-aos="fade-up-right">
                                    <div class="img" style="background-image: url({{ asset('/images/img-3.jpg') }});">
                                        
                                    </div>
                                    <div class="content">
                                        <div class="icon-wrapper">
                                            <span class="c-icon study"></span>
                                        </div>
                                        <div class="title text-white">study in canada</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-12 col-lg-6">
                                <a href="#" class="c-banner-1" data-aos="fade-up-left">
                                    <div class="img" style="background-image: url({{ asset('/images/img-4.jpg') }});">
                                        
                                    </div>
                                    <div class="content">
                                        <div class="icon-wrapper">
                                            <span class="c-icon family"></span>
                                        </div>
                                        <div class="title text-white">family sponsorship program</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="s-3" class="parallax-1">
            <div class="container-c">
                 <h2 class="title text-white" data-aos="fade-up">canada will welcome an increased number</h2>
                 <div class="subtitle text-white" data-aos="fade-up" data-aos-delay="50">Of the immigration through the economy program in 2019</div>
                 <div class="c-wrapper-2">
                     <div class="inner-block container-fluid">
                        <div class="row w-100 m-0">
                            <div class="col-12 col-lg-4">
                                <div class="c-banner-2">
                                    <div class="content">
                                        <div class="icon-wrapper" data-aos="fade-down">
                                            <span class="c-icon consultant"></span>
                                        </div>
                                        <div class="inner" data-aos="fade-up">
                                            <div class="title text-white mb-2">71.600</div>
                                            <div class="title c-color-1 mt-0 text-capitalize mb-0">98%</div>
                                            <div class="subtitle text-white">Guaranteed result</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="c-banner-2">
                                    <div class="content">
                                        <div class="icon-wrapper" data-aos="fade-down">
                                            <span class="c-icon handshake"></span>
                                        </div>
                                        <div class="inner" data-aos="fade-up">
                                             <div class="title text-white mb-2">51.700</div>
                                            <div class="title c-color-1 mt-0 text-capitalize mb-0">Over 20</div>
                                            <div class="subtitle text-white">Years of experience</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="c-banner-2">
                                    <div class="content">
                                        <div class="icon-wrapper" data-aos="fade-down">
                                            <span class="c-icon guaranteed"></span>
                                        </div>
                                        <div class="inner" data-aos="fade-up">
                                            <div class="title text-white mb-2">172.700</div>
                                            <div class="title c-color-1 mt-0 text-capitalize mb-0">8 out of 10</div>
                                            <div class="subtitle text-white">From our customers</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
            </div>
        </section>
        <section id="s-4">
            <div class="container-c">
                <h2 class="title" data-aos="fade-up">
                    where to go 
                    <div class="icon-wrapper ml-3">
                        <span class="c-icon airplane"></span>
                    </div>
                </h2>
            </div>
            <div class="wrapper container-fluid">
                <div class="container-c">
                    <div class="row">
                        <div class="col-12 col-lg-1">
                            <img src="" id="c-img-1">
                        </div>
                        <div class="col-12 col-lg-6 wtg-l" data-aos="fade-right">
                            <a class="c-card" href="#">
                                <div class="img" style="background-image: url({{ asset('/images/img-1.jpg') }});"></div>
                                <div class="content">
                                    <div class="title text-white">british columbia</div>
                                </div>
                            </a>
                            <div class="desc-1">
                                <p> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                            </div>
                        </div>
                        <div class="col-12 col-lg-5 map" data-aos="fade-up">
                            asd
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('js')
    <script src="{{ asset('../node_modules/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/homepage.js') }}"></script>
@endsection