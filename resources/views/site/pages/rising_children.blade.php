@extends('site.layout')

@section('title', 'Rising children in Canada')

@section('content')

	<main>
		<section id="top">
			<div class="page-banner-container no-pseudo" style="background-image: url({{ asset('/images/cd_top.png') }});">
				<div class="banner-wrapper" data-aos="fade-up">
                    <div class="banner no-bgi">
                        <div class="banner-image" style="background-image: url({{ asset('/images/cd_i.png') }});"></div>
                        <div class="col">
                            <div class="banner-name justify-content-start px-5">Rising children</div>
                            <div class="banner-name c-bgc-2 c-color-2 justify-content-end px-5 mr-3" style="margin-left: -3rem;">in Canada</div>
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
                                		<div class="header"><span>Rising children in Canada</span></div>
                                		<div class="content">
                                			<p>The second largest country is by right one of the best countries for raising children. According to the UN, Canada is considered the best country for life. The conclusion was made after studying factors, such as: general standard of living, ecology, safety, affordability and quality of education. People, appreciating qualities such as courtesy, self-restraint and calmness, will find the idea of raising children in Canada really appealing. It is these qualities that will be instilled in your children from an early age.
                                            </p>	
                                		</div>
                                	</div>
                                    <div class="c-list-item" data-aos="fade-right">
                                        <div class="header"><span>School education</span></div>
                                        <div class="content">
                                            <p>YOu will find a large number of schools in every province. Schools are divided into 3 main groups: private, state and church-parish. Schools are also distributed by districts; this is why you have to make sure which area your place of residence belongs to. Don’t forget to find out about ratings of schools, the teaching program and possible additional subjects that can depend on the type of school. In addition, schools might provide you with a list of extracurricular lessons for your child.</p>

                                            <p>There’s also a separate training program (IEP – Individual Education Program). This program allows you to get individual training for children with disabilities: problems with hearing, motor skills, vision, speech, autism, lack of attention, development delay. The program is aimed at special training for the subsequent successful integration of children with disabilities into society. It is worth noting that the Canadian program for handicapped children is considered the most successful and professional in the world.
                                            </p>    
                                        </div>
                                    </div>
                                    <div class="c-list-item divided" data-aos="fade-right">
                                        <div class="header"><span>Why Canada in particular?</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="inner-img parallax-1" style="background-image: url({{ asset('/images/rcic_fixed_1.png') }});"></div>
            <div class="c-wrapper-1 anchored">
                <div class="container-c">
                    <div class="inner-block" data-aos="fade-up"> 
                        <div class="row">
                            <div class="col-12 position-static">
                                <div class="c-list-1">
                                    <div class="c-list-item divided" data-aos="fade-right">
                                        <div class="content">
                                            <p>Canada is a country where each child is treated as an individual worthy of respect. This is part of the state policy which stipulated a large number of laws aimed at protecting the rights of children. The laws are aimed not only at protecting children from violence, but also at affirming their rights to express themselves. There is a special training approach for children in Canada’s educational institutions: a policy of mutual respect for children and teachers.</p>   
                                        </div>
                                    </div>
                                    <div class="c-list-item" data-aos="fade-right">
                                        <div class="header"><span>Safety in schools and on the streets</span></div>
                                        <div class="content">
                                            <p>For any country, one of the most significant indicators of the quality of life is the crime level. The lower the crime rate is, the better the country is developed in terms of spiritual, social and economic progress. Due to the country’s lowest crime rate, Canada remains the most attractive country for immigration. Much of the credit for what the country has achieved goes to active citizenship and professional work of police</p>   
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
                                <span class="icon c-icon healthcare"></span>
                                <a class="item" href="{{ route('page', 'health_in_canada') }}">
                                    <div class="img" style="background-image: url({{ asset('/images/healthcare_img.png') }});"></div>
                                    <div class="content">
                                        <h5 class="title">HEALTHCARE</h5>
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