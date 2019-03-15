@extends('site.layout')

@section('title', 'Home')

@section('content')

	<main>
		<section id="top">
			<div class="page-banner-container parallax-1" style="background-image: url({{ asset('/images/pp_top.png') }});" >
				<div class="banner-wrapper justify-content-start container-c" data-aos="fade-up">
                    <div class="banner bn-2">
                        <div class="banner-image" style="background-image: url({{ asset('/images/pp_i.png') }});"></div>
                        <div class="banner-name">Privacy Policy</div>
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
                                		<div class="header"><span>Privacy Policy</span></div>
                                		<div class="content">
                                			<p>It is the policy of SolidVisa.com is to protect and preserve the privacy of its users and customers, and the confidentiality of the information they provide, subject to conditions described below.</p><p>To demonstrate our commitment to privacy we encourage all of our current and prospective users to read this Privacy Statement carefully before using the system.</p><p>This privacy statement discloses what information we gather, how we use it, and how to correct or change it. It is our intention to give you as much control as possible over your privacy in regard to your Information. Be assured that we will not disclose personal Information to third parties without your consent. By using our site, you agree to the terms of the Terms and Conditions and our Privacy Statement. SolidVisa.com reserves the right to expand and/or modify this Statement at any time.</p>

                                            <ol class="c-ol-1">
                                                <li data-aos="fade-right">
                                                    <p>Security. SolidVisa.com is committed to providing the highest level of security and privacy. All transactions of user authentication including credit cards processing are conducted using SSL (Secure Socket Layer) technology, supported by your browser, which encrypts all information that is sent to us. Our security certificate has been verified; We are providing the best commercially available encryption on the Internet. We take every precaution to protect personal information from loss, misuse, unauthorized access, disclosure, alteration or destruction by implementing policies and procedures to ensure that personal information is kept only for the purposes for which it has been gathered.</p>
                                                </li>
                                                <li data-aos="fade-right">
                                                    <p>Information. We take measures to ensure that the information you provide is recorded accurately and completely. We provide you with access to your personal information at all times for correcting or modifying that information where appropriate.</p>
                                                    <div class="container-fluid">
                                                        <div class="c-v-list row text-center aos-init aos-animate" data-aos="fade-up">
                                                            <div class="c-list-item col-12" data-aos="fade-up">
                                                                <p><span class="c-type-1">a)</span> Information Collected from/about Customers is used exclusively for the purpose of providing better service. It is never released to third parties except when expressly permitted by the customer. Any information you provide is completely confidential and will be protected from unauthorized use.</p>
                                                            </div>
                                                            <div class="c-list-item col-12" data-aos="fade-up">
                                                                <p><span class="c-type-1">b)</span> Financial Transactions. Credit card transactions are processed through one of the major third-party credit card processing companies. All your information is encrypted and is used only to complete the appropriate transactions.</p>
                                                            </div>
                                                            <div class="c-list-item col-12" data-aos="fade-up">
                                                                <p><span class="c-type-1">c)</span>  Cookies. SolidVisa.com uses cookies to recognize our clients when they visit our site. That allows us to customize their experience on our web site. You may need to have cookies “turned on” or enabled in the browser you use in order to register. But if you choose to disable cookies you still will be able to navigate the site. Cookies do not store any personal information. All the information is entirely confidential and is never sold or seen outside of the company. SolidVisa.com may display links or advertisement of/to other sites and companies that may also use cookies. In such cases, SolidVisa.com cannot be held responsible for any and all information that these parties collect through the use of cookies. You are hereby advised to familiarize yourself with privacy policies and information sharing standards of these sites as they may be different from SolidVisa.com’s policies and standards.</p>
                                                            </div>
                                                            <div class="c-list-item col-12" data-aos="fade-up">
                                                                <p><span class="c-type-1">d)</span> System information. SolidVisa.com reserves the right to collect and store such information as IP address, browser type or operating system type. All the information is highly confidential and will be used exclusively for system administration purposes. This information helps diagnose problems, monitor traffic and site usage.</p> 
                                                            </div>
                                                            <div class="c-list-item col-12" data-aos="fade-up">
                                                                <p><span class="c-type-1">e)</span> Email. SolidVisa.com uses email to notify our members or clients about changes in the status of their applications, changes in the database, on our web site, or when new services or features are added. As part of the service, SolidVisa.com may send emails notifications whenever there are special discounts, new products added, or new features developed. Our email list is confidential and is never sold or given to third parties.</p> 
                                                            </div>
                                                            <div class="c-list-item col-12" data-aos="fade-up">
                                                                <p><span class="c-type-1">f)</span> Change or Modify Your Information. You can change or modify your profile at any time by using your customer ID (login) and password. This approach guaranties safety of your information. Please immediately report any unauthorized use of your customer ID (login) / password or computer equipment. SolidVisa.com is not responsible for any damage, loss or change of information resulting from an unauthorized use of your customer ID (login) /password or computer equipment.</p>
                                                            </div>
                                                        </div>  
                                                    </div>
                                                </li>
                                                <li data-aos="fade-right">
                                                    <p>Disclaimer. Communication over the Internet as well as applications used to provide services over the Internet are subject to various security risks. In no event shall SolidVisa.com be responsible for any damages or losses whatsoever, direct or indirect, incidental or consequential, special or punitive, arising from or relating to the unauthorized use, change, deletion or exposure of any information, confidential or not, resulting from unauthorized breaking into the system or any other breach of security, or system failure. SolidVisa.com hereby disclaims all warranties with regard to the hardware and software used to provide security and support this web site including all implied warranties, fitness for a particular purpose and incidental, special, direct or consequential damages. Accordingly, SolidVisa.com, its officers and employees, partners, affiliates, subsidiaries, successors and assigns, and its third-party agents shall not, directly or indirectly, be liable, in any way, to you or any other person for any inaccuracies, misuse, errors, third party interceptions, viruses, or hacker attacks resulting in loss of data or services including, but not limited to errors or interruptions in the transmission or delivery of services. SolidVisa.com may contain links to other sites. These links are provided exclusively for information purposes and to assist in locating other Internet resources. Therefore, we are not responsible for the privacy practices or the content of such web sites.</p>
                                                </li>
                                                <li data-aos="fade-right">
                                                    <p>Summary. By accessing this site and using its services, you unconditionally agree with the terms of this Privacy Statement and our Terms & Conditions. You agree to comply with the terms that govern the use of this site and its services and that also govern all information provided by you and other users of SolidVisa.com. If you do not agree to all or any of the terms of this Privacy Statement, please do not use this site.</p>
                                                </li>
                                            </ol>
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