<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="updateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="updateLabel">Service</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="myForm" >
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="mb-1 box-picture">
                            <img id="imagePreview" src="{{ asset('img/default.jpg') }}" alt="">
                            <input onchange="previewImage(event)" id="thumbnail" type="file" name="logo_path" id="logo" accept="image/jpeg, image/png, image/jpg, image/gif">
                        </div>
                        <span id="thumbnail_error" class="text-danger d-none"></span>
                    </div>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" id="title">
                        <span id="title_error" class="text-danger d-none"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="update-button" type="button" class="btn btn-secondary">Update</button>
                    <button id="submit-button" type="button" class="btn btn-secondary">Add</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
