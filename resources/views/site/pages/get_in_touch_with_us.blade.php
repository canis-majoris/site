@extends('site.layout')

@section('title', 'Home')

@section('content')

	<main>
		<section id="top">
			<div class="page-banner-container parallax-1" style="background-image: url({{ asset('/images/gintwu_top.png') }});" >
				<div class="banner-wrapper justify-content-start container-c" data-aos="fade-up">
                    <div class="banner bn-2">
                        <div class="banner-image" style="background-image: url({{ asset('/images/gintwu_i.png') }});"></div>
                        <div class="banner-name">Terms & Conditions</div>
                    </div>    
                </div>
			</div>
		</section>
		<section id="page-content">
			<div class="c-wrapper-1 b-0">
                <div class="container-c">
                    <div class="inner-block" data-aos="fade-up" style="margin-bottom: -5.4rem;"> 
                        <div class="row">
                            <div class="col-12">
                                <div class="c-list-1">
                                	<div class="c-list-item" data-aos="fade-right">
                                		<div class="header"><span>Location</span></div>
                                		<div class="content">
                                            <div class="row">
                                                <div class="col-12 col-lg-6">
                                                    <h3 class="d-flex align-items-center"><span class="c-icon pin-dark mr-3"></span> Canada</h3>
                                                    <div class="p-3">
                                                        <p>116 Albert Street,  Suite 200&300, Ottawa</p>
                                                        <p>K1P 5G3, Ontario</p>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-6">
                                                    <h3 class="d-flex align-items-center"><span class="c-icon pin-dark mr-3"></span> Estonia</h3>
                                                    <div class="p-3">
                                                        <p>Tallinn</p>
                                                        <p>Kesklinna linnaosa Narva mnt 5 Harju maakond,10117</p>
                                                        <p>K1P 5G3, Ontario</p>
                                                    </div>
                                                </div>
                                            </div>
                                			
                                		</div>
                                	</div>
                                    <div class="c-list-item" data-aos="fade-right">
                                        <div class="header"><span>Phone Number</span></div>
                                        <div class="content">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h5 class="d-flex align-items-center"><span class="c-icon phone-dark mr-3"></span> +1-613-416-8600   (Press to call button)</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="c-list-item" data-aos="fade-right">
                                        <div class="header"><span>WhatsApp:</span></div>
                                        <div class="content">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h5 class="d-flex align-items-center"><span class="c-icon whatsapp-dark mr-3"></span> +1-613-416-8600</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="c-list-item" data-aos="fade-right">
                                        <div class="header"><span>E-mail:</span></div>
                                        <div class="content">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h5 class="d-flex align-items-center"><span class="c-icon mail-dark mr-3"></span> Support@solidvisa.com</h5>
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
        <section class="form-section c-bgc-1 pt-5" data-aos="fade-up">
            <div class="container-c pt-5">
                <div class="row">
                    <div class="col-12 col-lg-8 offset-lg-2">
                        <form class="row py-5 px-2 p-lg-5 inline-form">
                            <div class="form-group mb-4 col-12 col-lg-6">
                                <input type="text" class="form-control" placeholder="Full Name" name="name">
                            </div>
                            <div class="form-group mb-4 col-12 col-lg-6">
                                <input type="email" class="form-control" aria-describedby="emailHelp" placeholder="E-Mail">
                            </div>
                            <div class="form-group mb-4 col-12">
                                <input type="text" class="form-control" placeholder="Subject" name="subject">
                            </div>
                            <div class="form-group mb-4 col-12">
                                <textarea class="form-control" placeholder="Text" name="text" rows="5"></textarea>
                            </div>
                            <div class="form-group mb-4 col-12">
                                <button type="submit" class="c-button c-color-1 float-right hvr-shutter-out-vertical hover-c-color-1"><span>Send</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
	</main>

@endsection