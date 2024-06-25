<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Services\PartnerService;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    protected $service;
    protected $model = 'clients';
    public function __construct(PartnerService $service)
    {
        $this->service = $service;
        $this->service->setModel($this->model);
    }

    public function index()
    {
        $query = Client::where('isActive', 1)
                ->orderBy('created_at', 'desc')
                ->get();

        if (request()->ajax()) {
            return $this->datatable($query);
        }

        return view('cms.clients.index');
    }

    public function store(StoreClientRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $path = $file->store('clients', 'public');
                $data['thumbnail'] = $path;
            } else {
                $data['thumbnail'] = 'img/default.jpg';
            }

            $client = $this->service->addService($data);
            DB::commit();
            return response(['data' => $client, 'status' => 'success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['message' => $e->getMessage(), 'status' => 'store failed'], 500);
        }
    }

    public function edit($id)
    {
        try {
            $client = $this->service->getService($id);
            return response(['client' => $client, 'status' => 'success']);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage(), 'status' => 'edit failed'], 500);
        }
    }

    public function update(UpdateClientRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            // Handle file upload
            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail');
            }

            $client = $this->service->updateService($data, $id);
            DB::commit();
            return response(['data' => $client, 'status' => 'success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['message' => $e->getMessage(), 'status' => 'update failed'], 500);
        }
    }

    public function destroy($id)
    {
        $client = $this->service->getService($id);
        DB::beginTransaction();
        try {
            $client->isActive = false;
            $client->save();
            DB::commit();
            return response()->json(['data' => $client, 'status' => 'success']);
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
