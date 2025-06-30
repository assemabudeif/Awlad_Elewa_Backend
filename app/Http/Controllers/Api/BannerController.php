<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Http\Requests\BannerRequest;
use App\Http\Resources\BannerResource;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::paginate(15);
        return BannerResource::collection($banners);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BannerRequest $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|string',
        ]);
        $banner = Banner::create([
            'image' => $request->image->store('banners', 'public') ?? null,
            'link' => $request->link,
        ]);
        return new BannerResource($banner);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $banner = Banner::findOrFail($id);
        return new BannerResource($banner);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BannerRequest $request, $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->update($request->validated());
        return new BannerResource($banner);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();
        return response()->json(['message' => 'Banner deleted successfully']);
    }
}
