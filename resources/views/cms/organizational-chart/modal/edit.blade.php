<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="updateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="updateLabel">Add Member</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="myForm" >
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="mb-1 box-picture">
                            <img id="imagePreview" src="{{ asset('img/default.jpg') }}" alt="">
                            <input onchange="previewImage(event)" id="image" type="file" name="logo_path" id="logo" accept="image/jpeg, image/png, image/jpg, image/gif">
                        </div>
                        <span id="image_error" class="text-danger d-none"></span>
                    </div>

                    <div class="mb-3">
                        <label for="parentId" class="form-label">Parent</label>
                        <select class="form-select" name="parentId" id="parentId">
                            <option value="">-- please select --</option>
                            @foreach ($parents as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }} {{ $parent->lastname }} ({{ $parent->position }})</option>
                            @endforeach
                        </select>
                        <span id="parent_error" class="text-danger d-none"></span>
                    </div>
                        
                    <div class="mb-3">
                        <label for="name" class="form-label">Firstname</label>
                        <input type="text" name="name" class="form-control" id="name">
                        <span id="name_error" class="text-danger d-none"></span>
                    </div>

                    <div class="mb-3">
                        <label for="lastname" class="form-label">Lastname</label>
                        <input type="text" name="lastname" class="form-control" id="lastname">
                        <span id="lastname_error" class="text-danger d-none"></span>
                    </div>

                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" name="position" class="form-control" id="position">
                        <span id="position_error" class="text-danger d-none"></span>
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
