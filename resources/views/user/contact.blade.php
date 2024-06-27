@extends('layouts.app')
@section('content')
<section class="page-section position-relative">
    <div class="page-cover">
        <img src="{{ asset($about->cover_path) }}" alt="">
        <div class="container-fluid">
            <div data-aos="fade-up" class="title">
                <h5><span>Contact</span>  us</h5>
            </div>
        </div>
        <div class="overlay"></div>
    </div>
</section>

<section class="py-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div data-aos="fade-up" class="mapouter">
                    <div class="gmap_canvas">
                        <iframe width="600" height="500" id="gmap_canvas" src="https://maps.google.com/maps?q=eco%20tower&t=&z=19&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
                        </iframe>
                        <a href="https://123movies-to.org">123movies</a>
                        <br>
                        <style>.mapouter{position:relative;text-align:right;height:500px;width:600px;}</style>
                        <a href="https://www.embedgooglemap.net"></a>
                        <style>.gmap_canvas {overflow:hidden;background:none!important;height:500px;width:600px;}</style>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div data-aos="fade-up" class="contact-form">
                    <h2>Get in Touch</h2>
                    <form action="/submit_form" method="post">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
            
                        <label for="phone">Mobile Number</label>
                        <input type="tel" id="phone" name="phone" required pattern="[0-9]{10}" placeholder="1234567890">
            
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="4" required></textarea>
            
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
       
    </div>
</section>
@endsection

@section('scripts')

@endsection