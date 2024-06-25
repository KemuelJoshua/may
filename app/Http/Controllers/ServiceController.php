<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Services\ServicesService;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    protected $serviceService;
    protected $model = 'services';
    public function __construct(ServicesService $serviceService)
    {
        $this->serviceService = $serviceService;
        $this->serviceService->setModel($this->model);
    }

    public function index()
    {
        $query = Service::where('isActive', 1)
                ->orderBy('created_at', 'desc')
                ->get();

        if (request()->ajax()) {
            return $this->datatable($query);
        }

        return view('cms.services.index');
    }

    public function store(StoreServiceRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $path = $file->store('services', 'public');
                $data['thumbnail'] = $path;
            } else {
                $data['thumbnail'] = 'img/default.jpg';
            }

            $service = $this->serviceService->addService($data);
            DB::commit();
            return response(['data' => $service, 'status' => 'success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['message' => $e->getMessage(), 'status' => 'store failed'], 500);
        }
    }

    public function edit($id)
    {
        try {
            $service = $this->serviceService->getService($id);
            return response(['service' => $service, 'status' => 'success']);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage(), 'status' => 'edit failed'], 500);
        }
    }

    public function update(UpdateServiceRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            // Handle file upload
            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail');
            }

            $award = $this->serviceService->updateService($data, $id);
            DB::commit();
            return response(['data' => $award, 'status' => 'success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['message' => $e->getMessage(), 'status' => 'update failed'], 500);
        }
    }

    public function destroy($id)
    {
        $service = $this->serviceService->getService($id);
        DB::beginTransaction();
        try {
            $service->isActive = false;
            $service->save();
            DB::commit();
            return response()->json(['data' => $service, 'status' => 'success']);
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
