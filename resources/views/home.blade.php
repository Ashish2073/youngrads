@extends('layouts.beta_layout')
@section('head_script')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <style>
        #advance-search-box {}

        .india-card {
            margin-left: 23px;
            padding: 20px 25px;
            width: 352px;
            height: 229px;
            background: rgba(153, 153, 153, 0.19);
            box-shadow: 4px 4px 4px 0px rgba(0, 0, 0, 0.25);
        }

        .india-card .img-txt-row {
            gap: 35px;
            display: flex;
        }

        .india-card .img-txt-row img {
            width: 167px;
            height: 184px;
        }

        .india-card .img-txt-row h3 {
            margin-top: 43px;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .india-card p {
            margin-top: 20px;
            font-size: 0.8rem;
            text-align: left;
        }

        .india-card .media-row {
            display: flex;
            gap: 40px;
        }

        .india-card .media-row img {
            height: 17px;
            width: 17px;
        }

        .founding-cards {
            display: flex;
            justify-content: space-around;
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            .india-card {

                padding: 23px 30px;
                margin-bottom: 27px;
                width: 94%;
                height: 285px
            }


        }


        @media (min-width: 430px) and (max-width: 932px) {
            .india-card {

                padding: 23px 30px;
                margin-bottom: 27px;
                width: 94%;
                height: 285px
            }

            .about-youngrads {
                display: flex;
                flex-direction: column;
            }

        }


        @media (min-width: 360px) and (max-width: 740px) {
            .india-card {

                padding: 23px 30px;
                margin-bottom: 27px;
                width: 83%;
                height: 285px
            }

            .about-youngrads {
                display: flex;
                flex-direction: column;
            }

            @media screen and (max-width: 992px) {
                .heading {
                    font-size: 2.5rem !important;
                }
            }



            /* .india-card .img-txt-row img {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            width: 167px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            height: 184px;

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        } */


        }

        @media screen and (max-width: 992px) {
            .ceoimage {
                padding: unset !important;
            }
        }

        @media screen and (min-width: 992px) {
            .ceoimage {
                padding: 106px !important;
            }
        }
    </style>
@section('content')

    @if (session('success'))
        <div class="alert alert-success fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif



    {{-- <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/homebanner1.jpeg') }}" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/homebanner2.jpeg') }}" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/banner/banner-3.jpg') }}" class="d-block w-100" alt="...">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleFade" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleFade" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div> --}}
















    <div class="hero-banner-sec">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="banner-search-block home-select-box">

                        <div id="headingbanner" style="text-align: 
                        left">
                            {{-- <h2 class="site-title">Explore Over 55,000+ Courses</h2> --}}
                        </div>

                        <div class="col-md-6">
                            <a href="#about_youngrads" class="btn btn-info p-2 btn btn-warning"
                                style="font-size: 25px;"><strong> What is
                                    Youngrads
                                </strong></a>
                        </div>


                        {{-- <h4>Use Our Course Finder to Search</h4> --}}

                        @php
                            $route = Auth::check() ? route('course-finder') : route('course-finder-guest');

                        @endphp
                        {{-- <form action="{{ $route }}" method="get">
                            <div class="row mt-2">
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-12"> --}}
                        {{-- <select name="program" class="form-control program-typehead" placeholder="What you want to study ?" data-url="{{ route('program-auto')}}">
                                      </select> --}}
                        {{-- <input type="text" name="what" id="what"
                                                placeholder="What you want to study ?"
                                                class="form-control form-control-lg rounded-0">
                                        </div> --}}
                        {{-- <div class="col-1">
                                      <i class="fa fa-spin fa-spinner program-loader d-none"></i>
                                    </div> --}}
                        {{-- </div>
                                </div> --}}
                        {{-- <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <input type="text" name="where" id="where"
                                                placeholder="Where do you want to study ?"
                                                class="form-control form-control-lg rounded-0"> --}}
                        {{-- <select name="country_id" class="form-control country" placeholder="Where do yo want to study ?" data-url="{{ route('country-auto') }}"></select> --}}
                        {{-- </div> --}}
                        {{-- <div class="col-1">
                                      <i class="fa fa-spin fa-spinner country-loader d-none"></i>
                                     </div> --}}
                        {{-- </div>
                                </div>
                            </div>
                            <div class="ygd-search-btn">
                                <div class="form-inline">
                                    <button class="search-btn" type="submit"><i class="search-icon"></i> Search</button>
                                </div>
                            </div>
                        </form> --}}


                    </div>
                </div>
                <div class="col-md-6">
                    <div class="banner-img">
                        <img src="{{ asset('images/banner-graphic.png') }}" alt="Banner Graphic">
                    </div>
                </div>
            </div>

        </div>
    </div>


    <section class="about-sec mt-5 mb-3" id="about_youngrads">
        <div class="container">
            <div class="">
                <h2 class="site-title">About Youngrads</h2>
            </div>
            <div class="d-flex about-youngrads">
                <img src="{{ asset('images/site-logo.png') }}" alt="">
                <p class="ml-5" style="color:black;font-weight:500;font-size:1.3em">Youngrads features a distinctive
                    profile-based
                    program search
                    comprising over 55000
                    courses from global
                    universities. Youngrads’ Recommendation
                    Engine helps students identify ideal institutions and career options as per their individual profile. We
                    further
                    help with the enrollment process and travel arrangements, as your partner and guide on the journey to
                    study
                    abroad.</p>

            </div>
        </div>
    </section>






    <section class="work-sec">
        <div class="container">
            <div class="">
                <h2 class="site-title">How it Works</h2>
            </div>

            <div class="row">
                <div class="col">
                    <div class="work-block">
                        <i class="work-icon-1"></i>
                        <h4>Find the Right <strong>School & Program</strong></h4>
                    </div>
                </div>

                <div class="col">
                    <div class="work-block">
                        <i class="work-icon-2"></i>
                        <h4>Submit Your <strong>Application </strong></h4>
                    </div>
                </div>

                <div class="col">
                    <div class="work-block">
                        <i class="work-icon-3"></i>
                        <h4>Get Your Letter <strong>of Acceptance </strong></h4>
                    </div>
                </div>

                <div class="col">
                    <div class="work-block">
                        <i class="work-icon-4"></i>
                        <h4>Start the Visa <strong>Process</strong></h4>
                    </div>
                </div>

                <div class="col">
                    <div class="work-block">
                        <i class="work-icon-5"></i>
                        <h4>Book Your <strong>Flight</strong></h4>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <section class="our-process-sec">
        <div class="container">

            <div class="row align-items-center">
                <div class="col-md-5">
                    <div class="our-process-heading">
                        <h2 class="site-title">Watch How Our <span>Process Works</span></h2>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="our-process-video">
                        <video width="320" height="240" poster={{ asset('images/thumbnail.png') }} controls>
                            <source src="{{ asset('videos/YounGrads-Explainer.mp4') }}" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="work-sec">
        <div class="container">
            <div class="">
                <h2 class="site-title"> Youngrads in the Media</h2>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <div class="work-block">
                        <a target="blank"
                            href="https://www.careerindia.com/study-abroad/want-to-study-in-australia-heres-your-guide-from-youngrads-039337.html"><img
                                style="height: 80px;width:100%" src="{{ asset('images/carrerindia.png') }}"
                                alt="carrerindia" /></a>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="work-block">
                        <a target="blank"
                            href="https://www.moneycontrol.com/news/business/australian-universities-retract-enrolments-amid-visa-crackdown-12264951.html"><img
                                style="height: 80px; width:100%" src="{{ asset('images/moneycontrol.jpg') }}"
                                alt="moneycontrol" /></a>

                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="work-block">

                        <a target="blank"
                            href="https://www.opportunityindia.com/hindi/article/विदेशों-में-शैक्षिक-अवसर-तलाशते-भारतीय-छात्र-16565"><img
                                style="height: 100px; width:100%" src="{{ asset('images/opportunity.png') }}"
                                alt="opportunity" /><a>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="work-block">
                        <a target="blank"
                            href="https://www.financialexpress.com/business/investing-abroad-tips-to-build-an-international-career-by-studying-in-a-foreign-university-3357294/"><img
                                style="height: 80px; width:100%" src="{{ asset('images/finanicail_express.png') }}"
                                alt="opportunity" /><a>
                    </div>
                </div>


            </div>
        </div>
    </section>


    <section class="work-sec">
        {{-- <div class="container">
            <div class="">
                <h2 class="site-title"> Founding Team</h2>
            </div>
            <div class="row my-5 founding-cards ">
                <div class="col-lg-4">
                    <div class="india-card">
                        <div class="img-txt-row">
                            <img src="{{ asset('foundingmember/founder1.jpeg') }}" alt="" />
                            <div class="founding-details">
                                <h3>Anuj Gupta</h3>
                                <p>
                                    CEO of Youngrads1
                                </p>
                                <div class="media-row">
                                    <img src="https://images.hindustantimes.com/tech/img/2023/09/21/960x540/fb_1695273515215_1695273522698.jpg"
                                        alt="" />
                                    <img src="images/envelope.png" alt="" />
                                    <img src="images/linkedin-in.png" alt="" />
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div> --}}
        <div class="">
            <h2 class="site-title">Founding Member</h2>
        </div>

        <div class="container">


            <div class="row align-items-center">
                <div class="col-md-5">
                    <div class="our-process-heading">
                        <h2 class="site-title">Anuj Gupta <span>CEO of Youngrads</span></h2>
                        <span><a target="blank" href="https://www.linkedin.com/in/anuj-gupta-962169a"><img width="50"
                                    class="mb-2" height="50" src="{{ asset('media/linkedin.png') }}"
                                    alt="linkedin"></a></span>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="our-process-video ceoimage">
                        <img class="img-fluid" src={{ asset('foundingmember/founder1.jpeg') }} alt="" />
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="about-sec mt-5 mb-3">
        <div class="container">
            <div class="">
                <h2 class="site-title">Our Vision</h2>
            </div>
            <div class="d-flex about-youngrads">
                <img src="{{ asset('images/vision.jpg') }}" alt="">
                <p class="ml-5 mt-3" style="color:black;font-weight:500;font-size:1.3em">Youngrads features a distinctive
                    profile-based
                    At Youngrads, our vision is crystal clear: to empower students worldwide to seamlessly explore, pursue,
                    and thrive in international universities. .</p>

            </div>
            {{-- <div class="ml-md-12" id="access_form">
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <form>
                            <div class="form-group">
                                <label for="inputEmail" class="mb-2" style="font-size: 20px"><strong> Early
                                        Access</strong></label>
                                <input style="15%;" type="email" class="form-control mb-2" id="inputEmail"
                                    placeholder="Enter your email">
                                <button type="submit" class="btn btn-primary">Access</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div> --}}
        </div>


    </section>



    <section class="work-sec" id="access_form">

        <div class="">
            <h2 class="site-title">Early Access</h2>
        </div>

        <div class="container shadow">

            <form class="p-2" action="{{ route('earlyaccess') }}" method="post">
                @csrf
                <div class="">
                    <div class="mb-3">
                        <label for="name" class="form-label"><strong>Name</strong></label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Example input placeholder">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label"><strong>Email</strong></label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Another input placeholder">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label"><strong>Mobile Number</strong></label>
                        <input type="number" class="form-control" id="number" name="personal_number"
                            placeholder="Another input placeholder">
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </section>









@endsection
@section('foot_script')
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <script type="text/javascript">
        var path = "{{ route('autocompletecourse') }}";
        $('#what').typeahead({
            autoSelect: false,
            source: function(query, process) {
                return $.get(path, {
                    query: query
                }, function(data) {
                    return process(data);
                });
            }
        });
    </script>

    <script type="text/javascript">
        var path1 = "{{ route('autocompletecountry') }}";
        $('#where').typeahead({
            autoSelect: false,
            source: function(query, process) {
                return $.get(path1, {
                    query: query
                }, function(data) {
                    return process(data);
                });
            }
        });
    </script>


    <script>
        // JavaScript for changing the content of the banner every 5 seconds with random content
        const banner = document.getElementById('headingbanner');
        const contents = [
            '<div class="left-text" style="margin-left:50px" ><h3 class="heading" style="font-weight: 400 ;font-size:3.5rem "> <span style="color: orange; font-weight: 700; font-size: 4rem" >55,000+</span>Cources<span style="color: orange; font-weight: 700; font-size: 4rem"></span></h3><p style="text-transform: capitalize;font-weight:500;font-size:1.5rem; color:#000"><br /></p></div>',
            '<div class="left-text" style="margin-left:50px" ><h3 class="heading" style="font-weight: 400 ;font-size:3.5rem "><br />  <span style="color: orange; font-weight: 700; font-size: 6rem;margin-top:80px">200+</span><br/>Universities!</h3></div>',
            '<div class="left-text" style="margin-left:50px" ><h3 class="heading" style="font-weight: 400 ;font-size:3.5rem "> <br /><span style="color: orange; font-weight: 700; font-size: 6rem;margin-top:80px">20+</span><br/>Destination!</h3></div>'
        ]; // Array of contents

        function getRandomContent() {
            const randomIndex = Math.floor(Math.random() * contents.length);
            return contents[randomIndex];
        }

        setInterval(() => {
            banner.innerHTML = getRandomContent();
        }, 2000); // Change content every 5 seconds (5000 milliseconds)
    </script>





@endsection
