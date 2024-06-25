<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Http\Requests\StoreAboutRequest;
use App\Http\Requests\UpdateAboutRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    public function index()
    {
        $about = About::first();
        return view('cms.about-us.index', compact('about'));
    }

    public function update(UpdateAboutRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $payload = $request->validated();

            // Handle file upload
            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail');
            }

            $service = About::first();
            if (isset($payload['thumbnail']) && $payload['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                if ($service->thumbnail && $service->thumbnail != 'img/default.jpg') {
                    Storage::disk('public')->delete($service->thumbnail);
                }
                $path = $payload['thumbnail']->store('thumbnails', 'public');
                $payload['thumbnail'] = $path;
    
                $service->update([
                    'thumbnail' => 'storage/' . $payload['thumbnail'],
                    'name' => $payload['name'],
                    'position' => $payload['position'],
                    'description' => $payload['description'],
                ]);
            } else {
                $service->update([
                    'name' => $payload['name'],
                    'position' => $payload['position'],
                    'description' => $payload['description'],
                ]);
            }
            
            if (isset($payload['cover_path']) && $payload['cover_path'] instanceof \Illuminate\Http\UploadedFile) {
                if ($service->cover_path && $service->cover_path != 'img/default.jpg') {
                    Storage::disk('public')->delete($service->cover_path);
                }
                $path = $payload['cover_path']->store('cover_paths', 'public');
                $payload['cover_path'] = $path;
    
                $service->update([
                    'cover_path' => 'storage/' . $payload['cover_path'],
                ]);
            }

            DB::commit();
            return response(['data' => $service, 'status' => 'success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['message' => $e->getMessage(), 'status' => 'update failed'], 500);
        }
    }

    public function updateCover(Request $request)
    {

    }

}
