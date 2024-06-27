<?php

namespace App\Http\Controllers;

use App\Models\OrganizationMember;
use App\Http\Requests\StoreOrganizationMemberRequest;
use App\Http\Requests\UpdateOrganizationMemberRequest;
use App\Services\MemberService;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrganizationMemberController extends Controller
{
    protected $member_service;
    public function __construct(MemberService $member_service)
    {
        $this->member_service = $member_service;
    }
    
    public function index()
    {
        $parents = OrganizationMember::orderBy('created_at', 'desc')
        ->get();;

        if (request()->ajax()) {
            return $this->datatable($parents);
        }

        return view('cms.organizational-chart.index');
    }

    public function getMembers()
    {
        $parents = OrganizationMember::orderBy('created_at', 'desc')
        ->get();;

        return response(['members' => $parents, 'status' => 'success']);
    }

    public function store(StoreOrganizationMemberRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('members', 'public');
                $data['image'] = $path;
            } else {
                $data['image'] = 'img/default.jpg';
            }

            $member = $this->member_service->addMember($data);
            DB::commit();
            return response(['data' => $member, 'status' => 'success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['message' => $e->getMessage(), 'status' => 'store failed'], 500);
        }
    }

    public function edit($id)
    {
        try {
            $member = $this->member_service->getMember($id);
            return response(['member' => $member, 'status' => 'success']);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage(), 'status' => 'edit failed'], 500);
        }
    }

    public function update(UpdateOrganizationMemberRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            // Handle file upload
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }

            $member = $this->member_service->updateMember($data, $id);
            DB::commit();
            return response(['data' => $member, 'status' => 'success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['message' => $e->getMessage(), 'status' => 'update failed'], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $member = $this->member_service->deleteMember($id);
            DB::commit();
            return response()->json(['data' => $member, 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage(), 'status' => 'delete failed'], 500);
        }
    }

    public function datatable($query)
    {
        return DataTables::of($query)
        ->addColumn('parent', function ($row) {
            if ($row->parent) {
                $parent = $row->parent;
                $parent_info = "{$parent->name} {$parent->lastname} <br> ( {$parent->position} )";
                return $parent_info;
            }
            return 'No Parent';
        })
        ->addColumn('name', function ($row) {
            $name = $row->name . ' ' . $row->lastname;
            return $name;
        })
        ->addColumn('image', function ($row) {
            return '
                <img src="' . asset($row->image) . '" alt="" class="thumbnail-image">
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
        ->rawColumns(['image', 'parent', 'name', 'actions'])
        ->make(true);
    }
}
