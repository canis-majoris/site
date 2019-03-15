@extends('site.layout')

@section('title', 'Quebec Self-Employed Worker Program')

@section('content')

	<main>
		<section id="top">
			<div class="page-banner-container no-pseudo" style="background-image: url({{ asset('/images/bic_top.png') }});">
				<div class="banner-wrapper" data-aos="fade-up">
                    <div class="banner no-bgi no-pseudo">
                        <div class="banner-image" style="background-image: url({{ asset('/images/bic_i.png') }});"></div>
                        <div class="col">
                            <div class="banner-name justify-content-start px-5">Quebec Self-Employed Worker Program</div>
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
                                		<div class="header"><span>Quebec Self-Employed Worker Program</span></div>
                                		<div class="content">
                                			<p>To qualify for the program, you must:</p>

                                            <div class="container-fluid">
                                                <div class="c-v-list row text-center aos-init aos-animate" data-aos="fade-up">
                                                    <div class="c-list-item col-12">
                                                        Come to Quebec to create your own job by practicing a profession or running business activities, alone or with other immigration candidates, with or without paid help;
                                                    </div>
                                                    <div class="c-list-item col-12">
                                                        Make a start-up deposit at a financial institution in the region where you plan to practice your trade or profession:

                                                        <ul class="c-ul-1">
                                                            <li>C$25,000 start-up deposit if you intend to practice your trade or profession outside the Montreal Metropolitan Community (MMC) area;</li>
                                                            <li>C$50,000 start-up deposit if you plan to practice your trade or profession in the MMC area.</li>
                                                        </ul>
                                                    </div>
                                                     <div class="c-list-item col-12">
                                                        Have minimum net assets of C$100,000 in conjunction, if applicable, with your accompanying spouse or de facto spouse, and evidence that you acquired these funds legally; 
                                                    </div>
                                                     <div class="c-list-item col-12">
                                                        Have at least two years of experience as a self-employed worker in the profession or trade you intend to practice in Quebec.
                                                    </div>
                                                </div>  
                                            </div>	

                                            <p>To qualify for the program, you must:</p>

                                            <ul class="c-ul-1">
                                                <li>Your age and the age of your spouse or de facto spouse, if applicable;</li>
                                                <li>Your level of education and that of your spouse or de facto spouse, if applicable;</li>
                                                <li>Your language skills in French or English;</li>
                                                <li>Your financial self-sufficiency;</li>
                                                <li>Your visits to Quebec;</li>
                                                <li>Your family in Quebec.</li>
                                            </ul>
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