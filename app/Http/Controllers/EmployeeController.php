<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Employee::where('isActive', 1)
        ->orderBy('created_at', 'desc')
        ->get();

        if (request()->ajax()) {
            return $this->datatable($query);
        }

        return view('cms.employee.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        DB::beginTransaction();
        try {
            // Validate the incoming request data
            $data = $request->validated();

            // Handle picture upload if provided
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $path = $file->store('images', 'public');
                $data['picture_url'] = $path;
            } else {
                $data['picture_url'] = 'img/default.jpg'; // Default picture URL
            }

            // Create the employee record in the database
            $employee = Employee::create([
                'picture_url' => 'storage/' . $data['picture_url'], // Adjust as needed
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'middlename' => $data['middlename'],
                'position' => $data['position'],
                'gender' => $data['gender'],
                'phone_number' => $data['phone_number'],
                'employee_number' => $data['employee_number'],
                'date_started' => $data['date_started'],
                'date_stop' => $data['date_stop'],
                'status' => $data['status'],
                'address' => $data['address'],
            ]);

            // Commit the transaction
            DB::commit();

            return response()->json(['message' => 'Employee created successfully', 'employee' => $employee], 201);
        } catch (\Exception $e) {
            // Rollback the transaction on exception
            DB::rollBack();
            return response()->json(['message' => $e->getMessage(), 'status' => 'store failed'], 500);
        }
    }


    public function show($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            return response(['employee' => $employee, 'status' => 'success']);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage(), 'status' => 'edit failed'], 500);
        }
    }

    public function edit($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            return response(['employee' => $employee, 'status' => 'success']);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage(), 'status' => 'edit failed'], 500);
        }
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            // Handle file upload if new picture is provided
            if ($request->hasFile('picture_url')) {
                $file = $request->file('picture_url');
                $path = $file->store('images', 'public');

                // Delete old picture if it exists and is not default
                $employee = Employee::findOrFail($id);
                if ($employee->picture_url && $employee->picture_url != 'img/default.jpg') {
                    $adjustedPath = substr($employee->picture_url, 8); // remove 'storage/' prefix
                    Storage::disk('public')->delete($adjustedPath);
                }

                $data['picture_url'] = $path;
            }

            // Find the employee record to update
            $employee = Employee::findOrFail($id);

            // Update the employee record with validated data
            $employee->update([
                'picture_url' => isset($data['picture_url']) ? 'storage/' . $data['picture_url'] : $employee->picture_url,
                'firstname' => $data['firstname'] ?? $employee->firstname,
                'lastname' => $data['lastname'] ?? $employee->lastname,
                'middlename' => $data['middlename'] ?? $employee->middlename,
                'position' => $data['position'] ?? $employee->position,
                'gender' => $data['gender'] ?? $employee->gender,
                'phone_number' => $data['phone_number'] ?? $employee->phone_number,
                'employee_number' => $data['employee_number'] ?? $employee->employee_number,
                'date_started' => $data['date_started'] ?? $employee->date_started,
                'date_stop' => $data['date_stop'] ?? $employee->date_stop,
                'status' => $data['status'] ?? $employee->status,
                'address' => $data['address'] ?? $employee->address,
            ]);

            // Commit the transaction
            DB::commit();

            return response()->json(['message' => 'Employee updated successfully', 'employee' => $employee], 200);
        } catch (\Exception $e) {
            // Rollback the transaction on exception
            DB::rollBack();
            return response()->json(['message' => $e->getMessage(), 'status' => 'update failed'], 500);
        }
    }


    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        DB::beginTransaction();
        try {
            $employee->isActive = false;
            $employee->save();
            DB::commit();
            return response()->json(['data' => $employee, 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage(), 'status' => 'delete failed'], 500);
        }
    }

    public function datatable($query)
    {
        return DataTables::of($query)
        ->addColumn('picture_url', function ($row) {
            return '
                <img src="' . asset($row->picture_url) . '" alt="' . $row->lastname . '" class="thumbnail-image">
            ';
        })        
        ->addColumn('actions', function ($row) {
            return '
                    <div class="d-flex">
                        <button data-id="' . $row->id . '" class="btn btn-info btn-sm ms-1 text-white show-button">Show</button>
                        <button data-id="' . $row->id . '" class="btn btn-warning btn-sm ms-1 text-white edit-button">Edit</button>
                        <button data-id="' . $row->id . '" class="btn btn-danger btn-sm ms-1 text-white delete-button">Delete</button>
                    </div>
                ';
     
        })
        ->rawColumns(['picture_url', 'actions'])
        ->make(true);
    }
}
