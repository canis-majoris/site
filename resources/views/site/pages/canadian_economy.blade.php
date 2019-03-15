@extends('site.layout')

@section('title', 'Canadian Economy')

@section('content')

	<main>
		<section id="top">
			<div class="page-banner-container no-pseudo" style="background-image: url({{ asset('/images/ce_top.png') }});">
				<div class="banner-wrapper" data-aos="fade-up">
                    <div class="banner no-bgi">
                        <div class="banner-image" style="background-image: url({{ asset('/images/ce_i.png') }});"></div>
                        <div class="col">
                            <div class="banner-name justify-content-start px-5">canadian</div>
                            <div class="banner-name c-bgc-2 c-color-2 justify-content-end px-5 mr-3" style="margin-left: -3rem;">economy</div>
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
                                		<div class="header"><span>Canadian Ecconomy</span></div>
                                		<div class="content">
                                			<p>Canada is the second largest country of the world by territory and has its 10 -largest economy. The country is divided into ten provinces and three territories. Canada is a member of the Organization for Economic Co-operation and Development and G7.

                                            <p>The economy of Canada is a highly developed mixed economy. The service industry has the highest influence on the country’s economy, as it employs about three quarters of Canadians. Canada takes the fourth place for natural resources all over the world. Natural resources play a vital role in generating income of Canada, Energy resources contain natural gas, crude oil, crude bitumen (oil sands) and coal. About mineral resources, they include gold-silver, nickel-copper, copper-zinc, lead0zinc, iron, molybdenum, uranium, potash and diamonds. The government Of Canada prioritizes green environmental policies, but it focuses on the fossil fuel industry, as it is crucial for the economy of the country.</p>

                                            <p>Canada possesses successful industries which continue to prosper from year to year and strengthen the economy of the country. In fact, Canada is one of the largest suppliers of agricultural products all over the world. The country is also one of the largest exporters in the world. The main export is the crop, which is mainly exported to the South America. The Agriculture sector is a very important sector, beside it contributes to the lives of all Canadians and the country’s economy, it employs lots of Canadians as well as immigrants.</p>

                                            <p>Canada is a world leader in energy resources. Canada has the third largest oil reserve in the world. Moreover, it also leads in hydroelectric power. Quebec, Ontario, and Saskatchewan all use large amount of hydroelectric energy. Canada’s oil export contributes to the country’s GDP a lot. In addition, Canada possesses solar and wind energy production as the next major energy industry.</p>

                                            <p>Canada’s technology industry is considered as one of the largest industries and this sector is growing continuously. The government of Canada has special programs which aim to attract professionals from all over the world to make the country more competitive. Technological achievements influence on other industries of Canada, for instance, the tech industry has already played the most significant role in transportation and how goods and people are moved.</p>

                                            <p>Manufacturing contributes a lot in Canada’s GDP as well. This industry accounts for more than $170 billion of the GDP and it represents over 10 percent of the GDP. Additionally, manufacturing industry contributes in increasing well-paying jobs across the country. The sector became more contemporary and innovative in the last years and it continues to contribute to the economic growth for the following years.</p>	
                                		</div>
                                	</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</section>
        @include('site.components.s4')
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