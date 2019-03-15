@extends('site.layout')

@section('title', 'Skilled Worker Program')

@section('content')

	<main>
		<section id="top">
			<div class="page-banner-container no-pseudo" style="background-image: url({{ asset('/images/bic_top.png') }});">
				<div class="banner-wrapper" data-aos="fade-up">
                    <div class="banner no-bgi no-pseudo">
                        <div class="banner-image" style="background-image: url({{ asset('/images/bic_i.png') }});"></div>
                        <div class="col">
                            <div class="banner-name justify-content-start px-5">Skilled Worker Program</div>
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
                                		<div class="header"><span>Skilled Worker Program</span></div>
                                		<div class="content">
                                			<p>If you are interested to get a Canadian permanent residence, you can consider applying through the Quebec Skilled Worker Program (QSWP). You have to create your profile in the Arrima which is a new selection system. 
                                            Other words, you need to submit an Expression of Interest (EOI) through the Arrima system. Since August 2018, the Quebec government have been processing all applications through this system. </p>
                                            <p>In the EOI you have to provide information about your education, the area of training, proficiency in French and other languages and work experience, including other details. </p>
                                            <p>The Quebec Skilled Worker program is designed for candidates who intend to settle in Quebec. You don’t need to have a job offer or any specific skill level. But you need to meet education, age, and language proficiency requirements. 
                                            </p>

                                            <h3 class="my-5">Quebec Skilled Worker General Requirements and Perks</h3>

                                            <div class="container-fluid">
                                                <div class="c-v-list row text-center aos-init aos-animate" data-aos="fade-up">
                                                    <div class="c-list-item col-12">
                                                        Level of education and field of training (maximum 26 points): Master’s and doctorate degree holders are awarded higher points in terms of education. 
                                                    </div>
                                                    <div class="c-list-item col-12">
                                                        Work experience (8 points): Points are awarded based on the length of work experience.
                                                    </div>
                                                    <div class="c-list-item col-12">
                                                        Knowledge of English and or/French (22 points): A priority is given to French-speaking applicants.
                                                    </div>
                                                    <div class="c-list-item col-12">
                                                        Visits to Quebec and family relationships with a Canadian citizen or a permanent resident (8 points): You might get additional points for working or living previously in Quebec, including having family members in the province.  
                                                    </div>
                                                    <div class="c-list-item col-12">
                                                        Accompanying spouse or common-law partner (maximum 17 points): Depending on your spouse education, field of training, age, and language proficiency, you might get some extra points.
                                                    </div>
                                                    <div class="c-list-item col-12">
                                                        Employment offer (10 points): While job offer is not a mandatory requirement, having one will give you extra points.
                                                    </div>
                                                    <div class="c-list-item col-12">
                                                        Accompanying children (8 points): Children under 12 years of old get maximum points, while older children (from 13 to 21) are normally awarded fewer points.  
                                                    </div>
                                                    <div class="c-list-item col-12">
                                                        Proof of financial feasibility (maximum 1 point): You have to present proof of having sufficient funds to settle in Quebec.  
                                                    </div>
                                                </div>  
                                            </div>	
                                		</div>
                                	</div>
                                    <div class="c-list-item" data-aos="fade-right">
                                        <div class="header"><span>How Arrima Works</span></div>
                                        <div class="content">
                                            <p>There’s an online waiting room you need to enter. Then you wait until you are notified you can access the portal. </p>
                                            <p>After that, you start filling out your application. Keep in mind, that each session lasts 90 minutes. Therefore, save your application frequently to avoid losing information. In total, you have 30 days to complete the profile. The system saves and keeps them for one year. </p>
                                            <p>During that period, you can review and update your account. 
                                            Once you get an invitation from Quebec to apply for a Quebec Selection Certificate, you’ll have 90 days to submit the application. 
                                            </p>
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