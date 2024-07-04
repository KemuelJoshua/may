@extends('layouts.cms')

@section('content')
<section>
    @include('cms.employee.modal.edit')
    @include('cms.employee.modal.show')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between py-4">
            <h2 class="page-title">Employees</h2>
            <button class="btn btn-secondary" id="add-button">Add</button>
        </div>
        <div class="card p-3 table-card shadow-sm">
            <div class="table-responsive">
                <table class="table custom-table" id="myTable">
                    <thead>
                        <tr>
                            <th>Picture</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
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
        let dataTable = $('#myTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": '{{ route('employees.index') }}', // Replace with actual route for employees index
            "columns": [
                { "data": "picture_url", "name": "picture_url", "orderable": false, "searchable": false,},
                { "data": "firstname", "name": "firstname", "render": renderWithDiv },
                { "data": "position", "name": "position", "render": renderWithDiv },
                { "data": "actions", "name": "actions", "orderable": false, "searchable": false }
            ],
        });

        const myModal = $('#editModal');

        // Add Button Click Event
        $('#add-button').click(e => {
            $('.modal-title').html('Add Employee');
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
            formData.append('employee_number', $('#employee_number').val());
            formData.append('date_started', $('#date_started').val());
            formData.append('date_stop', $('#date_stop').val());
            formData.append('status', $('#status').val());
            formData.append('address', $('#address').val());

            const picture = $('#picture')[0].files[0];
            if (picture) {
                formData.append('picture', picture);
            }

            axios.post('{{ route('employees.store') }}', formData, {
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
                    text: "Employee added successfully.",
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
            $('.modal-title').html('Edit Employee');
            $('#submit-button').hide();
            $('#update-button').show();
            $('#imagePreview').attr('src', '{{ asset('img/default.jpg') }}');

            // Remove previous errors if any
            $('.text-danger').addClass('d-none');
            $('.form-control').removeClass('is-invalid');

            axios.get(`{{ route('employees.edit', ['employee' => ':id']) }}`.replace(':id', id))
            .then((response) => {
                const data = response.data.employee;
                $('#imagePreview').attr('src', '{{ asset('storage/') }}' + data.picture_url);
                $('#firstname').val(data.firstname);
                $('#lastname').val(data.lastname);
                $('#middlename').val(data.middlename);
                $('#position').val(data.position);
                $('#gender').val(data.gender);
                $('#phone_number').val(data.phone_number);
                $('#employee_number').val(data.employee_number);
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
            formData.append('employee_number', $('#employee_number').val());
            formData.append('date_started', $('#date_started').val());
            formData.append('date_stop', $('#date_stop').val());
            formData.append('status', $('#status').val());
            formData.append('address', $('#address').val());
            formData.append('_method', 'PUT');

            const picture = $('#picture')[0].files[0];
            if (picture) {
                formData.append('picture', picture);
            }

            axios.post(`{{ route('employees.update', ['employee' => ':id']) }}`.replace(':id', id), formData, {
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
                    text: "Employee updated successfully.",
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
        const showModal = $('#employeeModal');

        $(document).on('click', '.show-button', function() {
            let id = $(this).attr('data-id');

            axios.get(`employees/${id}`)
                .then(function(response) {
                    const employee = response.data.employee;

                    // Update modal content with fetched data
                    $('#employeeModalLabel').text('Employee Details: ' + employee.firstname + ' ' + employee.lastname);
                    $('#picture_url_show').attr('src', '{{ asset('') }}' + employee.picture_url);
                    $('#first_name_show').text(employee.firstname);
                    $('#last_name_show').text(employee.lastname);
                    $('#position_show').text(employee.position);
                    $('#gender_show').text(employee.gender);
                    $('#phone_number_show').text(employee.phone_number);
                    $('#employee_number_show').text(employee.employee_number);
                    $('#date_started_show').text(employee.date_started);
                    $('#date_stop_show').text(employee.date_stop);
                    $('#status_show').text(employee.status);

                    $('#employeeModal').modal('show'); // Show the modal
                })
                .catch(function(error) {
                    console.error('Error fetching employee data:', error);
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
                    axios.delete(`{{ route('employees.destroy', ['employee' => ':id']) }}`.replace(':id', id))
                    .then(response => {
                        dataTable.ajax.reload();
                        Swal.fire({
                            title: "Deleted!",
                            text: "Employee data has been deleted.",
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
