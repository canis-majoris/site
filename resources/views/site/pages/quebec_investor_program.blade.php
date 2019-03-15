@extends('site.layout')

@section('title', 'Quebec Investor Program')

@section('content')

	<main>
		<section id="top">
			<div class="page-banner-container no-pseudo" style="background-image: url({{ asset('/images/bic_top.png') }});">
				<div class="banner-wrapper" data-aos="fade-up">
                    <div class="banner no-bgi no-pseudo">
                        <div class="banner-image" style="background-image: url({{ asset('/images/bic_i.png') }});"></div>
                        <div class="col">
                            <div class="banner-name justify-content-start px-5">Quebec Investor Program</div>
                            <!-- <div class="banner-name c-bgc-2 c-color-2 justify-content-end px-5 mr-3" style="margin-left: -3rem;">in Canada</div> -->
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
                                		<div class="header"><span>Quebec Investor Program</span></div>
                                		<div class="content">
                                			<p>To meet requirements for the Investor Program, you should:
                                            </p>

                                            <div class="container-fluid">
                                                <div class="c-v-list row text-center aos-init aos-animate" data-aos="fade-up">
                                                    <div class="c-list-item col-12">
                                                        Have, alone or with your common-law partner, legally obtained net assets of at least C$2,000,000, apart from any amounts received by donation less than six months prior to your date of application;
                                                    </div>
                                                    <div class="c-list-item col-12">
                                                        Have at least two years of management experience in the five years prior to the date of submitting the application. This also includes any responsibilities related to the planning, management and control of financial, human or material resources under your supervision before the application date for a selection certificate;
                                                    </div>
                                                    <div class="c-list-item col-12">
                                                        Prove that you plan to settle in Quebec;
                                                    </div>
                                                    <div class="c-list-item col-12">
                                                        Conclude an investment agreement with a financial intermediary (broker or trust company) legally allowed to participate in the Investor Program;  
                                                    </div>
                                                    <div class="c-list-item col-12">
                                                        Make a five-year term investment of C$1,200,000 with Investissement Quebec - Immigrants Investisseurs Inc.
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
	</main>

@endsection