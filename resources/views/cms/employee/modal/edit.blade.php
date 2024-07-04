<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="updateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="updateLabel">Employee</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="myForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="mb-1 box-picture">
                            <img id="imagePreview" src="{{ asset('img/default.jpg') }}" alt="">
                            <input onchange="previewImage(event)" id="picture" type="file" name="picture" accept="image/jpeg, image/png, image/jpg, image/gif">
                        </div>
                        <span id="picture_error" class="text-danger d-none"></span>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="firstname" class="form-label">First Name</label>
                            <input type="text" name="firstname" class="form-control" id="firstname">
                            <span id="firstname_error" class="text-danger d-none"></span>
                        </div>
                        <div class="col-md-4">
                            <label for="lastname" class="form-label">Last Name</label>
                            <input type="text" name="lastname" class="form-control" id="lastname">
                            <span id="lastname_error" class="text-danger d-none"></span>
                        </div>
                        <div class="col-md-4">
                            <label for="middlename" class="form-label">Middle Name</label>
                            <input type="text" name="middlename" class="form-control" id="middlename">
                            <span id="middlename_error" class="text-danger d-none"></span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" name="position" class="form-control" id="position">
                            <span id="position_error" class="text-danger d-none"></span>
                        </div>
                        <div class="col-md-4">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" class="form-control" id="gender">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            <span id="gender_error" class="text-danger d-none"></span>
                        </div>
                        <div class="col-md-4">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" id="phone_number">
                            <span id="phone_number_error" class="text-danger d-none"></span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="employee_number" class="form-label">Employee Number</label>
                            <input type="text" name="employee_number" class="form-control" id="employee_number">
                            <span id="employee_number_error" class="text-danger d-none"></span>
                        </div>
                        <div class="col-md-4">
                            <label for="date_started" class="form-label">Date Started</label>
                            <input type="date" name="date_started" class="form-control" id="date_started">
                            <span id="date_started_error" class="text-danger d-none"></span>
                        </div>
                        <div class="col-md-4">
                            <label for="date_stop" class="form-label">Date Stop</label>
                            <input type="date" name="date_stop" class="form-control" id="date_stop">
                            <span id="date_stop_error" class="text-danger d-none"></span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-control" id="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <span id="status_error" class="text-danger d-none"></span>
                        </div>
                        <div class="col-md-8">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" id="address">
                            <span id="address_error" class="text-danger d-none"></span>
                        </div>
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
