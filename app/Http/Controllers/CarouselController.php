<?php

namespace App\Http\Controllers;

use App\Models\Carousel;
use App\Http\Requests\StoreCarouselRequest;
use App\Http\Requests\UpdateCarouselRequest;
use App\Services\ServicesService;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CarouselController extends Controller
{
    protected $service;
    protected $model = 'carousels';
    public function __construct(ServicesService $service)
    {
        $this->service = $service;
        $this->service->setModel($this->model);
    }

    public function index()
    {
        $query = Carousel::where('isActive', 1)
                ->orderBy('created_at', 'desc')
                ->get();

        if (request()->ajax()) {
            return $this->datatable($query);
        }

        return view('cms.carousels.index');
    }

    public function store(StoreCarouselRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $path = $file->store('carousels', 'public');
                $data['thumbnail'] = $path;
            } else {
                $data['thumbnail'] = 'img/default.jpg';
            }

            $carousel = $this->service->addService($data);
            DB::commit();
            return response(['data' => $carousel, 'status' => 'success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['message' => $e->getMessage(), 'status' => 'store failed'], 500);
        }
    }

    public function edit($id)
    {
        try {
            $carousel = $this->service->getService($id);
            return response(['carousel' => $carousel, 'status' => 'success']);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage(), 'status' => 'edit failed'], 500);
        }
    }

    public function update(UpdateCarouselRequest $request, $id)
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
        $carousel = $this->service->getService($id);
        DB::beginTransaction();
        try {
            $carousel->isActive = false;
            $carousel->save();
            DB::commit();
            return response()->json(['data' => $carousel, 'status' => 'success']);
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
