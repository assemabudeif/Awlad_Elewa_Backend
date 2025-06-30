<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Http\Requests\SettingRequest;
use App\Http\Resources\SettingResource;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::paginate(15);
        return SettingResource::collection($settings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SettingRequest $request)
    {
        $setting = Setting::create($request->validated());
        return new SettingResource($setting);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $setting = Setting::findOrFail($id);
        return new SettingResource($setting);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SettingRequest $request, $id)
    {
        $setting = Setting::findOrFail($id);
        $setting->update($request->validated());
        return new SettingResource($setting);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();
        return response()->json(['message' => 'Setting deleted successfully']);
    }
}
