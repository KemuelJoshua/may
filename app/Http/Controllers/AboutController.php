<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Http\Requests\StoreAboutRequest;
use App\Http\Requests\UpdateAboutRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $validatedData = $request->validated();

        // Retrieve the About record to update
        $service = About::findOrFail($id);

        // Handle file upload for thumbnail
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('images', 'public');
            // Delete old thumbnail if exists
            $this->deleteFileIfExists($service->thumbnail);
            $service->thumbnail = 'storage/' . $thumbnailPath;
        }

        // Update other fields
        $service->name = $validatedData['name'];
        $service->position = $validatedData['position'];
        $service->description = $validatedData['description'];

        // Handle file upload for cover_path
        if ($request->hasFile('cover_path')) {
            $coverPath = $request->file('cover_path')->store('images', 'public');
            // Delete old cover_path if exists
            $this->deleteFileIfExists($service->cover_path);
            $service->cover_path = 'storage/' . $coverPath;
        }

        // Save changes to the database
        $service->save();

        DB::commit();
        return response(['data' => $service, 'status' => 'success'], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response(['message' => $e->getMessage(), 'status' => 'update failed'], 500);
    }
}

// Helper function to delete file if it exists
private function deleteFileIfExists($filePath)
{
    if ($filePath && $filePath != 'img/default.jpg' && $filePath != 'img/landing.png') {
        $adjustedPath = substr($filePath, 8); // Remove 'storage/' prefix
        Storage::disk('public')->delete($adjustedPath);
    }
}


}
