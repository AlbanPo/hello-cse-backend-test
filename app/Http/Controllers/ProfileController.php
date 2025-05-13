<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['indexActive']);
    }

    public function indexActive(Request $request): JsonResponse
    {
        $profiles = Profile::where('status', 'active')
            ->select(['id', 'first_name', 'last_name', 'image_path', 'created_at'])
            ->get();

        return response()->json($profiles);
    }

    public function store(StoreProfileRequest $request): JsonResponse
    {
        /** @var UploadedFile $image */
        $image = $request->file('image');
        $imagePath = $image->store('profiles', 'public');

        /** @var Admin $admin */
        $admin = $request->user();

        $profile = Profile::create([
            'admin_id' => $admin->id,
            'first_name' => $request->validated('first_name'),
            'last_name' => $request->validated('last_name'),
            'image_path' => $imagePath,
            'status' => $request->validated('status'),
        ]);

        return response()->json($profile, 201);
    }

    public function show(Profile $profile): JsonResponse
    {
        return response()->json($profile);
    }

    public function update(UpdateProfileRequest $request, Profile $profile): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($profile->image_path);
            $imagePath = $request->file('image')->store('profiles', 'public');
            $validated['image_path'] = $imagePath;
        }

        $profile->update($validated);

        return response()->json($profile);
    }

    public function destroy(Profile $profile): JsonResponse
    {
        Storage::disk('public')->delete($profile->image_path);
        $profile->delete();

        return response()->json(null, 204);
    }
}
