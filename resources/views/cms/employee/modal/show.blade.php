<!-- Employee Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeModalLabel">Employee Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 d-flex flex-column align-items-center">
                    <label for="picture_url" class="form-label">Picture</label>
                    <img id="picture_url_show" class="img-fluid mb-2 profile-pic" src="" alt="Employee Picture">
                </div>
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <span id="first_name_show" class="form-control" readonly></span>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <span id="last_name_show" class="form-control" readonly></span>
                </div>
                <div class="mb-3">
                    <label for="position" class="form-label">Position</label>
                    <span id="position_show" class="form-control" readonly></span>
                </div>
                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <span id="gender_show" class="form-control" readonly></span>
                </div>
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <span id="phone_number_show" class="form-control" readonly></span>
                </div>
                <div class="mb-3">
                    <label for="employee_number" class="form-label">Employee Number</label>
                    <span id="employee_number_show" class="form-control" readonly></span>
                </div>
                <div class="mb-3">
                    <label for="date_started" class="form-label">Date Started</label>
                    <span id="date_started_show" class="form-control" readonly></span>
                </div>
                <div class="mb-3">
                    <label for="date_stop" class="form-label">Date Stop</label>
                    <span id="date_stop_show" class="form-control" readonly></span>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <span id="status_show" class="form-control" readonly></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
