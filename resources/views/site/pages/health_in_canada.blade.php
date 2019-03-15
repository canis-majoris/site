@extends('site.layout')

@section('title', 'Health in Canada')

@section('content')

	<main>
		<section id="top">
			<div class="page-banner-container no-pseudo" style="background-image: url({{ asset('/images/hic_top.png') }});">
				<div class="banner-wrapper" data-aos="fade-up">
                    <div class="banner no-bgi">
                        <div class="banner-image" style="background-image: url({{ asset('/images/hic_i.png') }});"></div>
                        <div class="col">
                            <div class="banner-name justify-content-start px-5">Health in</div>
                            <div class="banner-name c-bgc-2 c-color-2 justify-content-end px-5 mr-3" style="margin-left: -3rem;">canada</div>
                        </div>
                    </div>    
                </div>
			</div>
		</section>
		<section id="page-content">
			<div class="c-wrapper-1 b-0">
                <div class="container-c">
                    <div class="inner-block" data-aos="fade-up"> 
                        <div class="row">
                            <div class="col-12">
                                <div class="c-list-1">
                                	<div class="c-list-item" data-aos="fade-right">
                                		<div class="header"><span>Healthcare in Canada/span></div>
                                		<div class="content">
                                            <h3>Basic Health Insurance</h3>
                                			<p>Healthcare in Canada is mostly free at the time of use, as it is paid for through the taxes. All Canadian citizens and permanent residents are eligible to public health insurance. Each province and territory has its own health insurance plan. It is important to know what your plan covers.</p>

                                            <p>Emergency medical services will be provided for free in all provinces and territories, even if you do not have a government health card. Restrictions may apply, depending upon individual’s immigration status. In case of emergency, you should go to nearest hospital. If you go to a walk-in clinic, you may be charged a fee.
                                            </p>	
                                		</div>
                                	</div>
                                    <div class="c-list-item" data-aos="fade-right">
                                        <div class="header"><span>Getting a Health Care Card</span></div>
                                        <div class="content">
                                            <p>To receive health care in Canada you will need to obtain a health insurance card from the region where you live. This card must be presented each time you get medical services. The health Card is issued by the provincial or territorial government and allows access to insured health care services.</p>

                                            <p>Visit your provincial or territorial website for details about application procedures.
                                            </p>    
                                        </div>
                                    </div>
                                    <div class="c-list-item divided" data-aos="fade-right">
                                        <div class="header"><span>Extra Health Insurance</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="inner-img parallax-1" style="background-image: url({{ asset('/images/hic_fixed_1.png') }});"></div>
            <div class="c-wrapper-1 anchored">
                <div class="container-c">
                    <div class="inner-block" data-aos="fade-up"> 
                        <div class="row">
                            <div class="col-12 position-static">
                                <div class="c-list-1">
                                    <div class="c-list-item divided" data-aos="fade-right">
                                        <div class="content">
                                            <p>Although government health plans cover all the basic medical services, not everything is included. You may opt for extra care insurance that covers the costs of:</p>   

                                             <div class="container-fluid">
                                                <div class="c-v-list row text-center aos-init aos-animate" data-aos="fade-up">
                                                    <div class="c-list-item col-12">
                                                        Dental treatments
                                                    </div>
                                                    <div class="c-list-item col-12">
                                                        Physiotherapy
                                                    </div>
                                                    <div class="c-list-item col-12">
                                                        Ambulance services
                                                    </div>
                                                    <div class="c-list-item col-12">
                                                        Certain prescription medications 
                                                    </div>
                                                    <div class="c-list-item col-12">
                                                        Prescription eyeglasses 
                                                    </div>
                                                </div>  
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
        <section class="c-gallery-1 anchored pt-5">
            <div class="container-c bg-white">
                <div class="inner-block py-0 c-anchor b-0"  data-type="b-0">
                    <div class="row ">
                        <div class="col-12 col-lg-4">
                            <div class="outer-wrapper" data-aos="fade-up">
                                <span class="icon c-icon family-2"></span>
                                <a class="item" href="{{ route('page', 'rising_children') }}">
                                    <div class="img" style="background-image: url({{ asset('/images/family_img.png') }});"></div>
                                    <div class="content">
                                        <h5 class="title">RISING CHILDREN IN CANADA</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="outer-wrapper" data-aos="fade-up">
                                <span class="icon c-icon rate"></span>
                                <a class="item" href="{{ route('page', 'canadian_experience_program') }}">
                                    <div class="img" style="background-image: url({{ asset('/images/experience_img.png') }});"></div>
                                    <div class="content">
                                        <h5 class="title">CANADA EXPERIENCE PROGRAM</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="outer-wrapper" data-aos="fade-up">
                                <span class="icon c-icon banking"></span>
                                <a class="item" href="{{ route('page', 'banking_in_canada') }}">
                                    <div class="img" style="background-image: url({{ asset('/images/banking_img.png') }});"></div>
                                    <div class="content">
                                        <h5 class="title">BANKING IN CANADA</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 d-none d-lg-block c-hr-1"></div>
                        <div class="col-12 col-lg-4">
                            <div class="outer-wrapper" data-aos="fade-up">
                                <span class="icon c-icon leaf"></span>
                                <a class="item" href="{{ route('page', 'canadian_economy') }}">
                                    <div class="img" style="background-image: url({{ asset('/images/economy_img.png') }});"></div>
                                    <div class="content">
                                        <h5 class="title">CANADIAN ECONOMY</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="outer-wrapper" data-aos="fade-up">
                                <span class="icon c-icon healthcare"></span>
                                <a class="item" href="#">
                                    <div class="img" style="background-image: url({{ asset('/images/healthcare_img.png') }});"></div>
                                    <div class="content">
                                        <h5 class="title">HEALTHCARE</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="outer-wrapper" data-aos="fade-up">
                                <span class="icon c-icon citizen"></span>
                                <a class="item" href="#">
                                    <div class="img" style="background-image: url({{ asset('/images/citizen_img.png') }});"></div>
                                    <div class="content">
                                        <h5 class="title">CANADIAN CITIZENSHIP</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </section>
	</main>

@endsection