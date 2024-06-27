@extends('layouts.cms')
@section('content')
<section>
    @include('cms.organizational-chart.modal.edit')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between py-4">
            <h2 class="page-title">Members</h2>
            <button class="btn btn-secondary" id="add-button">Add</button>
        </div>
        <div class="card p-3 table-card shadow-sm">
            <div class="table-responsive">
                <table class="table custom-table" id="myTable">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th style="width: 30%;">Name</th>
                            <th style="width: 30%;">position</th>
                            <th style="width: 20%;">Parent</th>
                            <th style="width: 10%;">Action</th>
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

    $(document).ready(function () {
        var renderWithDiv = function(data, type, full, meta) {
            return '<div>' + data + '</div>';
        };
        let dataTable = $('#myTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": '{{ route('organizational-chart.index') }}',
            "columns": [
                { "data": "image", "name": "image", "orderable": false, "searchable": false},
                { "data": "name", "name": "name", "render": renderWithDiv },
                { "data": "position", "name": "position", "render": renderWithDiv },
                { "data": "parent", "name": "parent", "render": renderWithDiv, },
                { "data": "actions", "name": "actions", "orderable": false, "searchable": false,}
            ],
        });

        const myModal = $('#editModal');

        $('#add-button').click(e => {
            $('.modal-title').html('Add Member');
            $('#myForm')[0].reset();
            $('#submit-button').show();
            $('#update-button').hide();
            $('#imagePreview').attr('src', assetBaseUrl + 'img/default.jpg');

            // Remove the previous error if there is
            $('.text-danger').addClass('d-none');
            $('.form-control').removeClass('is-invalid');

            myModal.modal('show');
        })

        // Create
        $('#submit-button').click(e => {
            e.preventDefault(); 

            const formData = new FormData();
            formData.append('name', $('#name').val());
            formData.append('lastname', $('#lastname').val());
            formData.append('position', $('#position').val());
            formData.append('parentId', $('#parentId').val());


            const image = $('#image')[0].files[0];
            if (image) {
                formData.append('image', image);
            }

            console.log(formData);

            axios.post('organizational-chart', formData, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                dataTable.ajax.reload();
                myModal.modal('hide');
                console.log(response)
                Swal.fire({
                    title: "Success!",
                    text: "Your data has been saved.",
                    icon: "success"
                });
            })
            .catch(error => {
                if (error.response && error.response.status === 422) {
                    $('.text-danger').addClass('d-none');
                    $(`#name`).removeClass('is-invalid');
                    $(`#lastname`).removeClass('is-invalid');
                    $(`#position`).removeClass('is-invalid');
                    $(`#parentId`).removeClass('is-invalid');
                    $(`#image`).removeClass('is-invalid');

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

        // Edit show
        let id = null;
        const assetBaseUrl = "{{ asset('') }}";

        $(document).on('click', '.edit-button', function() {
            id = $(this).attr('data-id');
            $('.modal-title').html('Edit Award');
            $('#submit-button').hide();
            $('#update-button').show();
            $('#imagePreview').attr('src', assetBaseUrl + 'img/default.jpg');

             // Remove the previous error if there is
            $('.text-danger').addClass('d-none');
            $('.form-control').removeClass('is-invalid');
            axios.get(`organizational-chart/${id}/edit`)
            .then((response) => {
                const data = response.data.member;
                console.log(data)
                $('#imagePreview').attr('src', assetBaseUrl + data.image);
                $('#name').val(data.name);
                $('#position').val(data.position);
                $('#lastname').val(data.lastname);
                $('#parentId').val(data.parentId);
                myModal.modal('show');
            })
            .catch(error => {
                Swal.fire({
                    title: "Oops!",
                    text: "Something went wrong, try again later!",
                    icon: "error"
                });
            })
        })

        // Update
        $('#update-button').click(e => {
            e.preventDefault();

            $('.text-danger').addClass('d-none');
            $(`#name`).removeClass('is-invalid');
            $(`#lastname`).removeClass('is-invalid');
            $(`#position`).removeClass('is-invalid');
            $(`#parentId`).removeClass('is-invalid');
            $(`#image`).removeClass('is-invalid');

            const formData = new FormData();
            formData.append('name', $('#name').val());
            formData.append('lastname', $('#lastname').val());
            formData.append('position', $('#position').val());
            formData.append('parentId', $('#parentId').val());
            formData.append('_method', 'PUT');

            const image = $('#image')[0].files[0];
            if (image) {
                formData.append('image', image);
            }

            axios.post(`organizational-chart/${id}`, formData, {
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
                    text: "Your changes have been saved.",
                    icon: "success"
                });
            })
            .catch(error => {
                if(error.response && error.response.status === 422) {
                    // Clear previous error messages
                    $('.text-danger').addClass('d-none');
                    $(`#name`).removeClass('is-invalid');
                    $(`#lastname`).removeClass('is-invalid');
                    $(`#position`).removeClass('is-invalid');
                    $(`#parentId`).removeClass('is-invalid');
                    $(`#image`).removeClass('is-invalid');


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

         // Delete
         $(document).on('click', '.delete-button', function() {
            id = $(this).attr('data-id');

            swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if(result.isConfirmed) {
                    axios.delete(`organizational-chart/${id}`)
                    .then(response => {
                        dataTable.ajax.reload();
                        console.log(response.data.data)
                        Swal.fire({
                            title: "Deleted!",
                            text: "Your data has been deleted.",
                            icon: "success"
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            title: "Oops!",
                            text: "Something went wrong, try again later!",
                            icon: "error"
                        });
                    })
                }
            })
        })
    });
</script>
@endsection