@extends('site.layout')

@section('title', 'Study in Quebec')

@section('content')

	<main>
		<section id="top">
			<div class="page-banner-container no-pseudo" style="background-image: url({{ asset('/images/bic_top.png') }});">
				<div class="banner-wrapper" data-aos="fade-up">
                    <div class="banner no-bgi no-pseudo">
                        <div class="banner-image" style="background-image: url({{ asset('/images/bic_i.png') }});"></div>
                        <div class="col">
                            <div class="banner-name justify-content-start px-5">Study in Quebec</div>
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
                                		<div class="header"><span>Why Study in Quebec?</span></div>
                                		<div class="content">
                                			<p>Quebec boasts a high quality of life. Students from different parts of the world are attracted to the province because of its excellent benefits. Each year, more than 50 000 international choose to study in Quebec.</p>
                                            <p>So what makes Quebec the place you want to be?</p>
                                            <p>First of all, it is Quebec’s education system, which stands out due to its many internationally recognized programs. In addition, post-secondary institutions across Quebec are equipped with high-tech laboratories, computer labs, sports facilities and performance halls. And tuition fees and the cost of living in Quebec are the most affordable in North America. No less is safety as Quebec is known as an inclusive and democratic society. The crime rate is one of the lowest in North America.</p>
                                            <p>Every year, a large variety of cultural and international events—festivals, carnivals, art exhibitions, and sports events—take place all across Quebec.</p>
                                            <p>Quebec’s main cities offer a unique mix of North American modernity and European charm.
                                            There are impressive views as well as numerous lakes and rivers, a vast boreal forest, a majestic river; visitors can enjoy beautiful landscapes and engage in sporting and leisure activities that vary with the seasons.</p>
                                            <p>If you are a vocational, technical training or university student in Quebec, you can apply for a Merit Scholarship which is financial assistance during the studies. Priority is also given to international students from China, India, Japan, Brazil, and Mexico and other developing countries. </p>
                                            <p>In addition, foreign students who are already selected for studies for a vocational training, technical training, or university program, including recipients of a Quebec Merit Scholarship can qualify for the same tuition fee as Canadian students, called “Exemptions from foreign student differential tuition fees”. <p>Similarly, a spouse or child of certain temporary workers and a person with a Quebec Selection Certificate (QSC) are viewed as eligible candidates. </p>
                                            <p>Individuals authorized for permanent residence can register online for French courses. Foreign students in Quebec with a valid study permit are allowed to work part-time (20 hours per week) and they don’t need a work permit for that. </p>
                                            <p>You can find very good job opportunities after graduation and stay permanently in Canada. 
                                            For more information, contact one of our immigration agents. 
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