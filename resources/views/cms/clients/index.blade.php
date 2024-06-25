@extends('layouts.cms')
@section('content')
<section>
    @include('cms.clients.modal.edit')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between py-4">
            <h2 class="page-title">Clients</h2>
            <button class="btn btn-secondary" id="add-button">Add</button>
        </div>
        <div class="card p-3 table-card shadow-sm">
            <div class="table-responsive">
                <table class="table custom-table" id="myTable">
                    <thead>
                        <tr>
                            <th>Thumbnail</th>
                            <th style="width: 60%;">Title</th>
                            <th style="width: 20%;">Action</th>
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
            "ajax": '{{ route('clients.index') }}',
            "columns": [
                { "data": "thumbnail", "name": "thumbnail", "orderable": false, "searchable": false},
                { "data": "title", "name": "title", "render": renderWithDiv },
                { "data": "actions", "name": "actions", "orderable": false, "searchable": false,}
            ],
        });

        const myModal = $('#editModal');

        $('#add-button').click(e => {
            $('.modal-title').html('Add Clients');
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
            formData.append('title', $('#title').val());

            const thumbnail = $('#thumbnail')[0].files[0];
            if (thumbnail) {
                formData.append('thumbnail', thumbnail);
            }

            axios.post('clients', formData, {
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
                    $(`#type`).removeClass('is-invalid');

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
            axios.get(`clients/${id}/edit`)
            .then((response) => {
                const data = response.data.client;
                console.log(data)
                $('#imagePreview').attr('src', assetBaseUrl + data.thumbnail);
                $('#title').val(data.title);
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
            $(`#title`).removeClass('is-invalid');

            const formData = new FormData();
            formData.append('title', $('#title').val());
            formData.append('_method', 'PUT');

            const thumbnail = $('#thumbnail')[0].files[0];
            if (thumbnail) {
                formData.append('thumbnail', thumbnail);
            }

            axios.post(`clients/${id}`, formData, {
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
                    $(`#title`).removeClass('is-invalid');


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
                    axios.delete(`clients/${id}`)
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