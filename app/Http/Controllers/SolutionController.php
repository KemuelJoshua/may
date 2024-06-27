<?php

namespace App\Http\Controllers;

use App\Models\Solution;
use App\Http\Requests\StoreSolutionRequest;
use App\Http\Requests\UpdateSolutionRequest;
use App\Services\ServicesService;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SolutionController extends Controller
{
    protected $solutionService;
    protected $model = 'solutions';
    public function __construct(ServicesService $solutionService)
    {
        $this->solutionService = $solutionService;
        $this->solutionService->setModel($this->model);
    }

    public function index()
    {
        $query = Solution::where('isActive', 1)
                ->orderBy('created_at', 'desc')
                ->get();

        if (request()->ajax()) {
            return $this->datatable($query);
        }

        return view('cms.solution.index');
    }

    public function store(StoreSolutionRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $path = $file->store('images', 'public');
                $data['thumbnail'] = $path;
            } else {
                $data['thumbnail'] = 'img/default.jpg';
            }

            $solution = $this->solutionService->addService($data);
            DB::commit();
            return response(['data' => $solution, 'status' => 'success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['message' => $e->getMessage(), 'status' => 'store failed'], 500);
        }
    }

    public function edit($id)
    {
        try {
            $solution = $this->solutionService->getService($id);
            return response(['solution' => $solution, 'status' => 'success']);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage(), 'status' => 'edit failed'], 500);
        }
    }

    public function update(UpdateSolutionRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            // Handle file upload
            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail');
            }

            $award = $this->solutionService->updateService($data, $id);
            DB::commit();
            return response(['data' => $award, 'status' => 'success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['message' => $e->getMessage(), 'status' => 'update failed'], 500);
        }
    }

    public function destroy($id)
    {
        $solution = $this->solutionService->getService($id);
        DB::beginTransaction();
        try {
            $solution->isActive = false;
            $solution->save();
            DB::commit();
            return response()->json(['data' => $solution, 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage(), 'status' => 'delete failed'], 500);
        }
    }

    public function datatable($query)
    {
        return DataTables::of($query)
        ->addColumn('thumbnail', function ($row) {
            return '
                <img src="' . asset($row->thumbnail) . '" alt="" class="thumbnail-image">
            ';
        })
        ->addColumn('actions', function ($row) {
            return '
                    <div class="d-flex">
                        <button data-id="' . $row->id . '" class="btn btn-warning btn-sm ms-1 text-white edit-button">Edit</button>
                        <button data-id="' . $row->id . '" class="btn btn-danger btn-sm ms-1 text-white delete-button">Delete</button>
                    </div>
                ';
     
        })
        ->rawColumns(['thumbnail', 'actions'])
        ->make(true);
    }
}
