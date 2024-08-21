@extends('layouts.cms')

@section('content')
<section>
    <div class="container-fluid mt-4">
        <div>
            <!-- Personal Information -->
            <div class="card mb-3">
              <div class="card-header bg-primary text-white">
                Student Profile
              </div>
              <div class="card-body">
                <form id="myForm">
                    @csrf
                    <div class="modal-body">
                        {{-- <div class="mb-3">
                            <div class="mb-1 box-picture">
                                <img id="imagePreview" src="{{ asset('img/default.jpg') }}" alt="">
                                <input onchange="previewImage(event)" id="picture" type="file" name="picture" accept="image/jpeg, image/png, image/jpg, image/gif">
                            </div>
                            <span id="picture_error" class="text-danger d-none"></span>
                        </div> --}}
                        
                        <div class="row mb-3 g-2">
                            <div class="col-md-6">
                                <label for="firstname" class="form-label">First Name</label>
                                <input value="{{ $student_profile->firstname }}" type="text" name="firstname" class="form-control" id="firstname">
                                <span id="firstname_error" class="text-danger d-none"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="lastname" class="form-label">Last Name</label>
                                <input value="{{ $student_profile->lastname }}" type="text" name="lastname" class="form-control" id="lastname">
                                <span id="lastname_error" class="text-danger d-none"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="mi" class="form-label">Middle Name</label>
                                <input value="{{ $student_profile->mi }}" type="text" name="mi" class="form-control" id="mi">
                                <span id="mi_error" class="text-danger d-none"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="age" class="form-label">Age</label>
                                <input value="{{ $student_profile->age }}" type="text" name="age" class="form-control" id="age">
                                <span id="age_error" class="text-danger d-none"></span>
                            </div>
                            <div class="col-md-12">
                                <label for="birthday" class="form-label">Birthday</label>
                                <input value="{{ $student_profile->birthday }}" type="date" class="form-control" name="birthday" id="birthday">
                                <span id="birthday_error" class="text-danger d-none"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex gap-2">
                        @isset($student_profile)
                            <button id="update-button" type="button" class="btn btn-secondary">Update</button>
                        @else
                            <button id="submit-button" type="button" class="btn btn-secondary">Add</button>
                        @endisset
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Function to render HTML with div wrapper
    var renderWithDiv = function(data, type, full, meta) {
        return '<div>' + data + '</div>';
    };

    $(document).ready(function () {
        const myModal = $('#editModal');
        // Submit Form (Create)
        $('#submit-button').click(e => {
            e.preventDefault();

            const formData = new FormData();
            formData.append('firstname', $('#firstname').val());
            formData.append('lastname', $('#lastname').val());
            formData.append('mi', $('#mi').val());
            formData.append('age', $('#age').val());
            formData.append('birthday', $('#birthday').val());

            axios.post('{{ route('students.store') }}', formData, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                myModal.modal('hide');
                Swal.fire({
                    title: "Success!",
                    text: "student added successfully.",
                    icon: "success"
                });
            })
            .catch(error => {
                if (error.response && error.response.status === 422) {
                    $('.text-danger').addClass('d-none');
                    $('.form-control').removeClass('is-invalid');

                    $.each(error.response.data.errors, function(field, errorMessage) {
                        $(`#${field}`).addClass('is-invalid');
                        $(`#${field}_error`).removeClass('d-none').text(errorMessage[0]);
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

        // Edit Button Click Event
        let id = null;
        $(document).on('click', '.edit-button', function() {
            id = $(this).attr('data-id');
            $('.modal-title').html('Edit student');
            $('#submit-button').hide();
            $('#update-button').show();
            $('#imagePreview').attr('src', '{{ asset('img/default.jpg') }}');

            // Remove previous errors if any
            $('.text-danger').addClass('d-none');
            $('.form-control').removeClass('is-invalid');

            axios.get(`{{ route('students.edit', ['student' => ':id']) }}`.replace(':id', id))
            .then((response) => {
                const data = response.data.student;
                $('#imagePreview').attr('src', '{{ asset('storage/') }}' + data.picture_url);
                $('#firstname').val(data.firstname);
                $('#lastname').val(data.lastname);
                $('#middlename').val(data.middlename);
                $('#position').val(data.position);
                $('#gender').val(data.gender);
                $('#phone_number').val(data.phone_number);
                $('#student_number').val(data.student_number);
                $('#date_started').val(data.date_started);
                $('#date_stop').val(data.date_stop);
                $('#status').val(data.status);
                $('#address').val(data.address);

                myModal.modal('show');
            })
            .catch(error => {
                Swal.fire({
                    title: "Oops!",
                    text: "Something went wrong, try again later!",
                    icon: "error"
                });
            });
        });

        // Update Button Click Event
        $('#update-button').click(e => {
            e.preventDefault();

            const formData = new FormData();
            formData.append('firstname', $('#firstname').val());
            formData.append('lastname', $('#lastname').val());
            formData.append('mi', $('#mi').val());
            formData.append('age', $('#age').val());
            formData.append('birthday', $('#birthday').val());
            formData.append('_method', 'PUT');

            axios.post(`{{ route('students.update', ['student' => ':id']) }}`.replace(':id', 1), formData, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                myModal.modal('hide');
                Swal.fire({
                    title: "Success!",
                    text: "student updated successfully.",
                    icon: "success"
                });
            })
            .catch(error => {
                if (error.response && error.response.status === 422) {
                    $('.text-danger').addClass('d-none');
                    $('.form-control').removeClass('is-invalid');

                    $.each(error.response.data.errors, function(field, errorMessage) {
                        $(`#${field}`).addClass('is-invalid');
                        $(`#${field}_error`).removeClass('d-none').text(errorMessage[0]);
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

        // show modal
        const showModal = $('#studentModal');

        $(document).on('click', '.show-button', function() {
            let id = $(this).attr('data-id');

            axios.get(`students/${id}`)
                .then(function(response) {
                    const student = response.data.student;

                    // Update modal content with fetched data
                    $('#studentModalLabel').text('student Details: ' + student.firstname + ' ' + student.lastname);
                    $('#picture_url_show').attr('src', '{{ asset('') }}' + student.picture_url);
                    $('#first_name_show').text(student.firstname);
                    $('#last_name_show').text(student.lastname);
                    $('#position_show').text(student.position);
                    $('#gender_show').text(student.gender);
                    $('#phone_number_show').text(student.phone_number);
                    $('#student_number_show').text(student.student_number);
                    $('#date_started_show').text(student.date_started);
                    $('#date_stop_show').text(student.date_stop);
                    $('#status_show').text(student.status);

                    $('#studentModal').modal('show'); // Show the modal
                })
                .catch(function(error) {
                    console.error('Error fetching student data:', error);
                });
        });





        // Delete Button Click Event
        $(document).on('click', '.delete-button', function() {
            id = $(this).attr('data-id');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if(result.isConfirmed) {
                    axios.delete(`{{ route('students.destroy', ['student' => ':id']) }}`.replace(':id', id))
                    .then(response => {
                        dataTable.ajax.reload();
                        Swal.fire({
                            title: "Deleted!",
                            text: "student data has been deleted.",
                            icon: "success"
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            title: "Oops!",
                            text: "Something went wrong, try again later!",
                            icon: "error"
                        });
                    });
                }
            });
        });
    });
</script>
@endsection
