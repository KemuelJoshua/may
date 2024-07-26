@extends('layouts.cms')

@section('content')
<section>
    @include('cms.employee.modal.edit')
    @include('cms.employee.modal.show')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between py-4">
            <h2 class="page-title">students</h2>
            <button class="btn btn-secondary" id="add-button">Add</button>
        </div>
        <div class="row">
            <div class="col-md-6">
              <div class="search-box">
                <input type="text" placeholder="Student Name">
              </div>
            </div>
            <div class="col-md-6">
              <div class="search-box">
                <input type="text" placeholder="Student Number">
              </div>
            </div>
          </div>
        </div>
        <div>
            <!-- Personal Information -->
            <div class="card mb-3">
              <div class="card-header bg-primary text-white">
                Personal Information
              </div>
              <div class="card-body">
                <p><strong>Email:</strong> example@example.com</p>
                <p><strong>Full name:</strong> John Doe</p>
                <p><strong>Grade and Section:</strong> 10-A</p>
                <p><strong>Age:</strong> 16</p>
                <p><strong>Address:</strong> 123 Main St, Anytown</p>
                <p><strong>Birthdate:</strong> January 1, 2008</p>
                <p><strong>Cellphone number:</strong> 09171234567</p>
                <p><strong>Landline number:</strong> (02) 123-4567</p>
                <p><strong>Religion:</strong> Christianity</p>
              </div>
            </div>
      
            <!-- Educational Background -->
            <div class="card mb-3">
              <div class="card-header bg-primary text-white">
                Educational Background
              </div>
              <div class="card-body">
                <p><strong>Elementary School Attended:</strong> ABC Elementary School</p>
                <p><strong>G10 School Attended:</strong> XYZ High School</p>
                <p><strong>Easiest Subject:</strong> Mathematics</p>
                <p><strong>Most Difficult Subject:</strong> Science</p>
                <p><strong>Subject/s with lowest grade:</strong> History</p>
                <p><strong>Subject/s with highest grade:</strong> English</p>
                <p><strong>Awards & Recognition:</strong> Honor Roll</p>
                <p><strong>Landline number:</strong> (02) 234-5678</p>
                <p><strong>Religion:</strong> Christianity</p>
              </div>
            </div>
      
            <!-- Family Background -->
            <div class="card mb-3">
              <div class="card-header bg-primary text-white">
                Family Background
              </div>
              <div class="card-body">
                <p><strong>Father's Name:</strong> Mr. Doe</p>
                <p><strong>Father's Age:</strong> 45</p>
                <p><strong>Educational Attainment:</strong> College Graduate</p>
                <p><strong>Occupation:</strong> Engineer</p>
                <p><strong>Contact Number:</strong> 09172345678</p>
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
        // let dataTable = $('#myTable').DataTable({
        //     "processing": true,
        //     "serverSide": true,
        //     "ajax": '{{ route('students.index') }}', // Replace with actual route for students index
        //     "columns": [
        //         { "data": "picture_url", "name": "picture_url", "orderable": false, "searchable": false,},
        //         { "data": "firstname", "name": "firstname", "render": renderWithDiv },
        //         { "data": "position", "name": "position", "render": renderWithDiv },
        //         { "data": "actions", "name": "actions", "orderable": false, "searchable": false }
        //     ],
        // });

        const myModal = $('#editModal');

        // Add Button Click Event
        $('#add-button').click(e => {
            $('.modal-title').html('Add student');
            $('#myForm')[0].reset();
            $('#submit-button').show();
            $('#update-button').hide();
            $('#imagePreview').attr('src', '{{ asset('img/default.jpg') }}');

            // Remove previous errors if any
            $('.text-danger').addClass('d-none');
            $('.form-control').removeClass('is-invalid');

            myModal.modal('show');
        });

        // Submit Form (Create)
        $('#submit-button').click(e => {
            e.preventDefault();

            const formData = new FormData();
            formData.append('firstname', $('#firstname').val());
            formData.append('lastname', $('#lastname').val());
            formData.append('middlename', $('#middlename').val());
            formData.append('position', $('#position').val());
            formData.append('gender', $('#gender').val());
            formData.append('phone_number', $('#phone_number').val());
            formData.append('student_number', $('#student_number').val());
            formData.append('date_started', $('#date_started').val());
            formData.append('date_stop', $('#date_stop').val());
            formData.append('status', $('#status').val());
            formData.append('address', $('#address').val());

            const picture = $('#picture')[0].files[0];
            if (picture) {
                formData.append('picture', picture);
            }

            axios.post('{{ route('students.store') }}', formData, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                dataTable.ajax.reload();
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
            formData.append('middlename', $('#middlename').val());
            formData.append('position', $('#position').val());
            formData.append('gender', $('#gender').val());
            formData.append('phone_number', $('#phone_number').val());
            formData.append('student_number', $('#student_number').val());
            formData.append('date_started', $('#date_started').val());
            formData.append('date_stop', $('#date_stop').val());
            formData.append('status', $('#status').val());
            formData.append('address', $('#address').val());
            formData.append('_method', 'PUT');

            const picture = $('#picture')[0].files[0];
            if (picture) {
                formData.append('picture', picture);
            }

            axios.post(`{{ route('students.update', ['student' => ':id']) }}`.replace(':id', id), formData, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                dataTable.ajax.reload();
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
