<header>
    <div class="container-fluid d-none d-lg-block">
        <div class="sub-header row d-flex justify-content-between align-items-center px-4">
            <a class="header-logo-wrapper col-5 col-md-3 col-lg-3 pt-2" href="{{ route('page', '') }}">
                <img src="{{ asset('images/logo.svg') }}">
            </a>
            <div class="header-auth-wrapper justify-content-end">
                <button type="button" class="c-button c-color-1 flat mx-2 hvr-shutter-out-vertical login-trigger"><span>Log in</span></button>
                <button type="button" class="c-button c-color-1 mx-2 hvr-shutter-out-vertical hover-c-color-1"><span>Sign up</span></button>
            </div>
        </div>
    </div>
   
    <nav class="navbar navbar-expand-lg navbar-light justify-content-end">
        <a class="header-logo-wrapper col-5 col-md-3 col-lg-3 pt-2 d-block d-lg-none" href="">
            <img src="{{ asset('images/logo.svg') }}">
        </a>
        <button class="navbar-toggler hamburger hamburger--collapse" type="button" data-toggle="collapse" data-target="#main-navigation" aria-controls="main-navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="hamburger-box">
                <span class="hamburger-inner"></span>
            </span>
        </button>
        <div class="collapse navbar-collapse justify-content-center px-2" id="main-navigation">
            <div class="header-auth-wrapper justify-content-end d-flex d-lg-none mt-4">
              <div class="col-12 p-0">
                <button type="button" class="c-button c-color-1 flat mx-0 w-100 my-2 text-white login-trigger"><span>Log in</span></button>
                <button type="button" class="c-button c-color-1 mx-0 w-100 my-2"><span>Sign up</span></button>
              </div>
                
            </div>
            <ul class="navbar-nav">
              <li class="nav-item active">
                  <a class="nav-link" href="#">
                    <span>
                        <span>free assessments</span>
                    </span>
                  </a>
              </li>
              <li class="nav-item w-menu">
                  <a data-toggle="dropdown" class="nav-link dropdown-toggle" href="#">
                    <span>
                      <span>immigration</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu">
                    <li class="dropdown-item dropdown-submenu">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle">Express Entry</a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-item">
                                <a href="#">Federal skills workers</a>
                            </li>
                            <li class="dropdown-item dropdown-submenu">
                                <a href="#" data-toggle="dropdown" class="dropdown-toggle">Federal skills trades</a>
                                <ul class="dropdown-menu">
                                  <li class="dropdown-item">
                                      <div>
                                        banner 2
                                      </div>
                                  </li>
                                </ul>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">Canadian esperienced class</a>
                            </li>
                            <li class="dropdown-item">
                                <div>
                                  banner 1
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown-item dropdown-submenu">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle">Provincial nominee</a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-item">
                                <a href="{{ route('page', 'Provincial nominee program updates') }}">Provincial nominee program updates</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ route('page', 'Alberta') }}">Alberta</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ route('page', 'British Columbia') }}">British Columbia</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ route('page', 'Manitoba') }}">Manitoba</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ route('page', 'New Brunswick') }}">New Brunswick</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ route('page', 'New Foundland and Labrador') }}">New Foundland and Labrador</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ route('page', 'Northwest territories') }}">Northwest territories</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ route('page', 'Nova Scotia') }}">Nova Scotia</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ route('page', 'Ontario') }}">Ontario</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ route('page', 'Prince Edward Islands') }}">Prince Edward Islands</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ route('page', 'Saskatchewan') }}">Saskatchewan</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ route('page', 'Yukon') }}">Yukon</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown-item dropdown-submenu">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle">Quebec Immigration</a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-item">
                                <a href="#">Quebec Skilled Worker Program</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">Enterpreneur program</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">Quebec Experience class</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">Working in Quebec</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">Studying in Quebec</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">Quebec Enterpreneur  Program</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">Quebec Investor Program</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">Quebec Self-Employed Programs</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown-item dropdown-submenu">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle">Business</a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-item">
                                <a href="#">Canada Business Immigration</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">Provincial Enterpreneurs Programs</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">Self-Employed Programs</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">International Business imigration</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">Buy a Business</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">Quebec Enterpreneur  Program</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">Quebec Investor Program</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">Quebec Self-Employed Programs</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown-item dropdown-submenu">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle">Family Immigration</a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-item">
                                <a href="#">sponsorship - what you need to know</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">sponsoring a spouce and child</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">sponsoring your parents and grandparents</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown-item dropdown-submenu">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle">Temporary VISA</a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-item">
                                <a href="#">Canada Temporary Visa Overview</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">Canada Visit/Tourist Visa</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">Electronic Travel Autorization</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#">Canada Student Visa</a>
                            </li>
                        </ul>
                    </li>
                  </ul>
              </li>
              <li class="nav-item w-menu">
                  <a data-toggle="dropdown" class="nav-link dropdown-toggle" href="#">
                    <span>
                      <span>study in canada</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu">
                    <li class="dropdown-item">
                        <a href="#">Working while studying</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Beginning your carreer</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Study permit</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Canada Student Visa</a>
                    </li>
                  </ul>
              </li>
              <li class="nav-item w-menu">
                  <a data-toggle="dropdown" class="nav-link dropdown-toggle" href="#">
                    <span>
                      <span>work permit</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu">
                    <li class="dropdown-item">
                        <a href="#">LMIA-Based work Permits</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">LMIA-Exemptions</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Work Permit Exemptions</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Open work Permits</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Global Skill Streams</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">International Mobility Program</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">International Experience Canada Program</a>
                    </li>
                  </ul>
              </li>
              <li class="nav-item w-menu">
                  <a data-toggle="dropdown" class="nav-link dropdown-toggle" href="#">
                    <span>
                      <span>Employment</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu">
                    <li class="dropdown-item">
                        <a href="#">Jobs Board</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Finding jobs in Canada</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Jobs reports</a>
                    </li>
                  </ul>
              </li>
              <li class="nav-item w-menu">
                  <a data-toggle="dropdown" class="nav-link dropdown-toggle" href="#">
                    <span>
                      <span>Application Process</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu">
                    <li class="dropdown-item">
                        <a href="#">Creating an application</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Submition to CIC</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Receiving ITA</a>
                    </li>
                  </ul>
              </li>
              <li class="nav-item w-menu">
                  <a data-toggle="dropdown" class="nav-link dropdown-toggle" href="#">
                    <span>
                      <span>About Canada</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu">
                    <li class="dropdown-item">
                        <a href="#">Canadian Dream</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Canadian Economy</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Healthcare in Canada</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Raising children in Canada</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Banking in Canada</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Canadian citizenship</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Articles</a>
                    </li>
                  </ul>
              </li>
              <li class="nav-item w-menu">
                  <a data-toggle="dropdown" class="nav-link dropdown-toggle" href="#">
                    <span>
                      <span>About a company</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu">
                    <li class="dropdown-item">
                        <a href="#">Our compnay</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Our services</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Why hire an immigration lawyer?</a>
                    </li>
                    <li class="dropdown-item">
                        <a href="#">Testimonials</a>
                    </li>
                  </ul>
              </li>
            </ul>
        </div>
    </nav>
</header>