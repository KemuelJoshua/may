<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Http\Requests\StorepartnerRequest;
use App\Http\Requests\UpdatepartnerRequest;
use App\Services\PartnerService;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PartnerController extends Controller
{
    protected $service;
    protected $model = 'partners';
    public function __construct(PartnerService $service)
    {
        $this->service = $service;
        $this->service->setModel($this->model);
    }

    public function index()
    {
        $query = Partner::where('isActive', 1)
                ->orderBy('created_at', 'desc')
                ->get();

        if (request()->ajax()) {
            return $this->datatable($query);
        }

        return view('cms.partners.index');
    }

    public function store(StorepartnerRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $path = $file->store('partners', 'public');
                $data['thumbnail'] = $path;
            } else {
                $data['thumbnail'] = 'img/default.jpg';
            }

            $partner = $this->service->addService($data);
            DB::commit();
            return response(['data' => $partner, 'status' => 'success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['message' => $e->getMessage(), 'status' => 'store failed'], 500);
        }
    }

    public function edit($id)
    {
        try {
            $partner = $this->service->getService($id);
            return response(['partner' => $partner, 'status' => 'success']);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage(), 'status' => 'edit failed'], 500);
        }
    }

    public function update(UpdatepartnerRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            // Handle file upload
            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail');
            }

            $award = $this->service->updateService($data, $id);
            DB::commit();
            return response(['data' => $award, 'status' => 'success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['message' => $e->getMessage(), 'status' => 'update failed'], 500);
        }
    }

    public function destroy($id)
    {
        $partner = $this->service->getService($id);
        DB::beginTransaction();
        try {
            $partner->isActive = false;
            $partner->save();
            DB::commit();
            return response()->json(['data' => $partner, 'status' => 'success']);
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
