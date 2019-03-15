@extends('site.layout')

@section('title', 'Canadian Experience Program')

@section('content')

	<main>
		<section id="top">
			<div class="page-banner-container no-pseudo" style="background-image: url({{ asset('/images/ce_top.png') }});">
				<div class="banner-wrapper" data-aos="fade-up">
                    <div class="banner no-bgi">
                        <div class="banner-image" style="background-image: url({{ asset('/images/ce_i.png') }});"></div>
                        <div class="col">
                            <div class="banner-name justify-content-start px-5">canadian</div>
                            <div class="banner-name c-bgc-2 c-color-2 justify-content-end px-5 mr-3" style="margin-left: -3rem;">Experience Program</div>
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
                                		<div class="header"><span>Canadian Experience Program</span></div>
                                		<div class="content">
                                			<p>In order to qualify for the Canadian Experience Immigration Program, a candidate must have a minimum of one year of paid full-time work experience in Canada (or an equal amount of paid part-time work experience) in an occupation listed by National Occupational Classification (NOC) as Skill Type A or B within the previous three years, with legal authorization.</p>
                                            <p>The Skill Types by the NOC are as follows:</p>	

                                            <div class="container-fluid p-0">
                                                <div class="c-v-list row text-center aos-init aos-animate" data-aos="fade-up">
                                                    <div class="c-list-pre col-5 justify-content-center px-4"><span class="c-style-2 text-capitalize">skill type</span><span class="c-color-1 c-font-size-lg font-weight-bold ml-3">0</span></div>
                                                    <div class="c-list-item col-7">
                                                        Saving accounts – intended for keeping the funds. A bank will pay you interest on your deposit.
                                                    </div>
                                                     <div class="c-list-pre col-5 justify-content-center px-4"><span class="c-style-2 text-capitalize">skill type</span><span class="c-color-1 c-font-size-lg font-weight-bold ml-3">A</span></div>
                                                    <div class="c-list-item col-7">
                                                        Professional Occupations
                                                    </div>
                                                     <div class="c-list-pre col-5 justify-content-center px-4"><span class="c-style-2 text-capitalize">skill type</span><span class="c-color-1 c-font-size-lg font-weight-bold ml-3">B</span></div>
                                                    <div class="c-list-item col-7">
                                                        Technical, Skilled Trades and Paraprofessional Occupants
                                                    </div>
                                                     <div class="c-list-pre col-5 justify-content-center px-4"><span class="c-style-2 text-capitalize">skill type</span><span class="c-color-1 c-font-size-lg font-weight-bold ml-3">C</span></div>
                                                    <div class="c-list-item col-7">
                                                        Intermediate level occupations, clerical or supportive functions
                                                    </div>
                                                     <div class="c-list-pre col-5 justify-content-center px-4"><span class="c-style-2 text-capitalize">skill type</span><span class="c-color-1 c-font-size-lg font-weight-bold ml-3">D</span></div>
                                                    <div class="c-list-item col-7">
                                                        Elemental sales or service and primary laborer occupations
                                                    </div>
                                                </div>  
                                            </div>

                                            <p>Applicants who gained their experience in Canada in category 0 or A jobs, must meet the minimum level of Canadian Language Benchmark (CLB) 7 in either English or French, for all four language abilities. Those who gained their experience in a category B job, must meet the minimum level of CLB 5 in either English or French for all four language abilities.</p>
                                		</div>
                                	</div>
                                    <div class="c-list-item" data-aos="fade-right">
                                        <div class="header"><span>Applying Under the Express Entry</span></div>
                                        <div class="content">
                                            <p>As of January 2015, the Canadian Government has implemented a new method of processing candidates in Federal Skilled Worker Class for immigration to Canada known as Express Entry. The main aim is to speed up the process, as candidates who apply under the Express Entry will receive a response within 6 months only after submitting the application. </p> 
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
                                <a class="item" href="{{ route('page', 'canadian_dream') }}">
                                    <div class="img" style="background-image: url({{ asset('/images/canadian_dream_img.png') }});"></div>
                                    <div class="content">
                                        <h5 class="title">CANADIAN DREAM</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="outer-wrapper" data-aos="fade-up">
                                <span class="icon c-icon healthcare"></span>
                                <a class="item" href="{{ route('page', 'banking_in_canada') }}">
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