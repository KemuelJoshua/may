@extends('layouts.cms')
@section('content')
<section>
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between py-4">
            <h2 class="page-title">About Us</h2>
            <button class="btn btn-secondary" id="add-button">Add</button>
        </div>
        <div class="card p-3 table-card shadow-sm mb-3">
            <form id="myForm">
                @csrf
                <div class="mb-3">
                    <div class="mb-1 box-picture">
                        <img id="imagePreview" src="{{ $about->thumbnail ? asset($about->thumbnail) : asset('img/default.jpg') }}" alt="">
                        <input onchange="previewImage(event)" id="thumbnail" type="file" name="thumbnail" accept="image/jpeg, image/png, image/jpg, image/gif">
                    </div>
                    <span id="thumbnail_error" class="text-danger d-none"></span>
                </div>
                
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input value="{{ $about->name }}" type="text" name="name" class="form-control" id="name">
                    <span id="name_error" class="text-danger d-none"></span>
                </div>

                <div class="mb-3">
                    <label for="position" class="form-label">Position</label>
                    <input value="{{ $about->position }}" type="text" name="position" class="form-control" id="position">
                    <span id="position_error" class="text-danger d-none"></span>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea type="text" name="description" rows="10" class="form-control" id="description">{{ $about->description }}</textarea>
                    <span id="description_error" class="text-danger d-none"></span>
                </div>

                <small>Cover photo</small>
                <div class="mb-3">
                    <div class="mb-1 box-picture">
                        <img id="imagePreviewCover" src="{{ $about->cover_path ? asset($about->cover_path) : asset('img/default.jpg') }}" alt="">
                        <input onchange="previewImageCover(event)" id="cover_path" type="file" name="cover_path" accept="image/jpeg, image/png, image/jpg, image/gif">
                    </div>
                    <span id="cover_path_error" class="text-danger d-none"></span>
                </div>

                <button id="update-button" type="button" class="btn btn-secondary">Update</button>
            </form>                
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Preview for thumbnail
    function previewImage(event) {
        const input = event.target;
        const preview = $('#imagePreview')[0];

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                $(preview).attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Preview for cover_path
    function previewImageCover(event) {
        const input = event.target;
        const preview = $('#imagePreviewCover')[0];

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                $(preview).attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function () {
        // Update
        $('#update-button').click(e => {
            e.preventDefault();

            $('.text-danger').addClass('d-none');
            $(`#name`).removeClass('is-invalid');
            $(`#position`).removeClass('is-invalid');
            $(`#description`).removeClass('is-invalid');

            const formData = new FormData();
            formData.append('name', $('#name').val());
            formData.append('position', $('#position').val());
            formData.append('description', $('#description').val());
            formData.append('_method', 'PUT');

            const thumbnail = $('#thumbnail')[0].files[0];
            if (thumbnail) {
                formData.append('thumbnail', thumbnail);
            }

            const cover = $('#cover_path')[0].files[0];
            if (cover) {
                formData.append('cover_path', cover);
            }

            axios.post(`about-us/{{ $about->id }}`, formData, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                Swal.fire({
                    title: "Success!",
                    text: "Your changes have been saved.",
                    icon: "success"
                });
            })
            .catch(error => {
                if (error.response && error.response.status === 422) {
                    // Clear previous error messages
                    $('.text-danger').addClass('d-none');
                    $(`#name`).removeClass('is-invalid');
                    $(`#position`).removeClass('is-invalid');
                    $(`#description`).removeClass('is-invalid');

                    // Display validation errors
                    $.each(error.response.data.errors, function(field, errorMessage) {
                        var errorSpanId = `#${field}_error`;
                        $(`#${field}`).addClass('is-invalid');
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
