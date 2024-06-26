@extends('layouts.app')
@section('content')
<section class="page-section position-relative">
    <div class="page-cover">
        <img src="{{ asset($about->cover_path) }}" alt="">
        <div class="container-fluid">
            <div class="title">
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
                <div class="mapouter">
                    <div class="gmap_canvas">
                        <iframe style="width: 100%" height="500" id="gmap_canvas" src="https://maps.google.com/maps?q=eco%20tower&t=&z=19&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
                        </iframe>
                    </div>
                </div>
                
            </div>
            <div class="col-md-6">
                <div class="contact-form">
                    <h2>Get in Touch</h2>
                    <form id="myForm" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                            <span id="email_error" class="text-danger d-none"></span>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone">Mobile Number</label>
                            <input type="tel" id="phone" name="phone" required pattern="[0-9]{10}">
                            <span id="phone_error" class="text-danger d-none"></span>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" rows="4" required></textarea>
                            <span id="message_error" class="text-danger d-none"></span>
                        </div>
                        <button type="submit" class="btn send-message btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
       
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        // Create
        $('.send-message').click(e => {
            e.preventDefault(); 
            
            const formData = new FormData();
            formData.append('email', $('#email').val());
            formData.append('phone', $('#phone').val());
            formData.append('message', $('#message').val());

            axios.post('send-email', formData, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                console.log(response)
                Swal.fire({
                    title: "Success!",
                    text: "Your data has been saved.",
                    icon: "success"
                }).then(() => {
                    $('#myForm')[0].reset();
                });
            })
            .catch(error => {
                if (error.response && error.response.status === 422) {
                    $('.text-danger').addClass('d-none');
                    $(`#email`).removeClass('is-invalid');
                    $(`#phone`).removeClass('is-invalid');
                    $(`#message`).removeClass('is-invalid');

                    $.each(error.response.data.errors, function(field, errorMessage) {
                        var errorSpanId = '#' + field + '_error';
                        $(`#${field}`).addClass('is-invalid');

                        // Show the error message in the respective error span
                        $(errorSpanId).removeClass('d-none').text(errorMessage[0]);
                    });
                } else {
                    Swal.fire({
                        title: "Oops!",
                        text: "Something went wrong, try again later!",
                        icon: "error"
                    });
                }
            });
        });
    });
</script>

@endsection