<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use DB;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    
    public function index()
    {
        $student_profile = Auth::user()->studentInformation;

        return view('cms.employee.index', compact('student_profile'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        DB::beginTransaction();
        try {
            // Validate the incoming request data
            $data = $request->validated();

            // Create the employee record in the database
            $employee = Student::create([
                'user_id' => Auth::user()->id,
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'middlename' => $data['mi'],
                'birthday' => $data['birthday'],
                'age' => $data['age'],
            ]);

            // Commit the transaction
            DB::commit();

            return response()->json(['message' => 'Employee created successfully', 'employee' => $employee], 200);
        } catch (\Exception $e) {
            // Rollback the transaction on exception
            DB::rollBack();
            return response()->json(['message' => $e->getMessage(), 'status' => 'store failed'], 500);
        }
    }

    public function update(StoreStudentRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            // Validate the incoming request data
            $data = $request->validated();

            $student = Student::findOrFail($id);

            // Create the employee record in the database
            $student->update([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'middlename' => $data['mi'],
                'birthday' => $data['birthday'],
                'age' => $data['age'],
            ]);

            // Commit the transaction
            DB::commit();
            return response()->json(['message' => 'updated successfully', 'employee' => $student], 200);
        } catch (\Exception $e) {
            // Rollback the transaction on exception
            DB::rollBack();
            return response()->json(['message' => $e->getMessage(), 'status' => 'store failed'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }
}
