@extends('layouts.app')

@section('content')
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
            <div class="">
                <h2 class="site-title">Watch a Quick Video Explaining
                    <span>How Our Process Works</span></h2>
            </div>

            <div class="our-process-video">
                {{-- <iframe width="560" height="315" src="https://www.youtube.com/embed/nWwpyclIEu4" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> --}}
                <video width="320" height="240" controls>
                    <source src="{{ asset('videos/YounGrads-Explainer.mp4') }}" type="video/mp4">
                </video>
            </div>


        </div>
    </section>
@endsection