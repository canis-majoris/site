@extends('site.layout')

@section('title', 'Canadian Economy')

@section('content')

	<main>
		<section id="top">
			<div class="page-banner-container no-pseudo" style="background-image: url({{ asset('/images/cd_top.png') }});">
				<div class="banner-wrapper" data-aos="fade-up">
                    <div class="banner no-bgi">
                        <div class="banner-image" style="background-image: url({{ asset('/images/cd_i.png') }});"></div>
                        <div class="col">
                            <div class="banner-name justify-content-start px-5">canadian</div>
                            <div class="banner-name c-bgc-2 c-color-2 justify-content-end px-5 mr-3" style="margin-left: -3rem;">dream</div>
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
                                		<div class="header"><span>The Canadian Dream</span></div>
                                		<div class="content">
                                			<p>The New York Times has recently speculated that “the American Dream has swapped citizenship, for now it is more likely to be found in Canada”.</p>

                                            <p>The NYT has a strong point, or rather – several points. The Canadians seemingly enjoy significantly higher standard of living and better life style than people in the U.S. According to data from the Organization for Economic Cooperation and Development, Canadians live longer and work on average 4.6 % fewer hours than their southern neighbors.</p>

                                            <p>Many people are now looking to build a new, happier life in Canada. So how good life really is in the land of a maple leaf?
                                            </p>	
                                		</div>
                                	</div>
                                    <div class="c-list-item" data-aos="fade-right">
                                        <div class="header"><span>Life in Canada</span></div>
                                        <div class="content">
                                            <p>The New York Times has recently speculated that “the American Dream has swapped citizenship, for now it is more likely to be found in Canada”.</p>

                                            <p>The NYT has a strong point, or rather – several points. The Canadians seemingly enjoy significantly higher standard of living and better life style than people in the U.S. According to data from the Organization for Economic Cooperation and Development, Canadians live longer and work on average 4.6 % fewer hours than their southern neighbors.</p>

                                            <p>Many people are now looking to build a new, happier life in Canada. So how good life really is in the land of a maple leaf?
                                            </p>    
                                        </div>
                                    </div>
                                    <div class="c-list-item divided" data-aos="fade-right">
                                        <div class="header"><span>Wealth</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="inner-img parallax-1" style="background-image: url({{ asset('/images/cd_fixed_1.png') }});"></div>
            <div class="c-wrapper-1 anchored">
                <div class="container-c">
                    <div class="inner-block" data-aos="fade-up"> 
                        <div class="row">
                            <div class="col-12 position-static">
                                <div class="c-list-1">
                                    <div class="c-list-item divided" data-aos="fade-right">
                                        <div class="content">
                                            <p>Money cannot buy happiness, but it’s an important means to achieving higher standard of living. Although there is a considerable gap between the richest and the poorest in Canada, the average household net-adjusted disposable income per capita is USD 30 474 a year, when the OECD average is USD 29 016 a year.</p>   
                                        </div>
                                    </div>
                                    <div class="c-list-item" data-aos="fade-right">
                                        <div class="header"><span>Employment</span></div>
                                        <div class="content">
                                            <p>Over 72% of people aged 15 to 64 in Canada have a paid job, higher than the OECD employment average of 66%. Only 4% of employees in Canada work long hours, comparing to the OECD average of 13%.</p>   
                                        </div>
                                    </div>
                                    <div class="c-list-item" data-aos="fade-right">
                                        <div class="header"><span>Education</span></div>
                                        <div class="content">
                                            <p>Canada is top-performing country in terms of educational system. 90% of adults aged 25-64 have completed upper secondary education, well above the OECD average of 76%. Compared to many countries, the cost of higher education in Canada is very affordable.</p>   
                                        </div>
                                    </div>
                                    <div class="c-list-item">
                                        <div class="image c-anchor b-0" data-type="b-0" style="background-image: url({{ asset('/images/cd_inner_1.png') }});" data-aos="fade-right"></div>
                                        <div class="header" data-aos="fade-right"><span>Health</span></div>
                                        <div class="content" data-aos="fade-right">
                                            <p>Canada is top-performing country in terms of educational system. 90% of adults aged 25-64 have completed upper secondary education, well above the OECD average of 76%. Compared to many countries, the cost of higher education in Canada is very affordable.</p>   
                                        </div>
                                    </div>
                                    <div class="c-list-item">
                                        <div class="header" data-aos="fade-right"><span>Conclusions</span></div>
                                        <div class="content" data-aos="fade-right">
                                            <p>In general, Canadians are very satisfied with their lives. When asked to rate their general satisfaction with life on a scale from 0 to 10, Canadians gave a 7.4 grade, higher than the OECD average of 6.5. Free healthcare, affordable education and plenty of employment opportunities attract people from all over the world who pursue their dreams. SolidVisa helps people to fulfill dreams and achieve goals, by providing a legal and professional guidance on immigration to Canada. We will examine your eligibility, help choose a suitable type of visa, assist with employment and advise on educational routes in Canada. Sign up to our website now to learn how you can make your dream of living in Canada come true.</p>   
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