<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RepairOrder;
use App\Http\Requests\RepairOrderRequest;
use App\Http\Resources\RepairOrderResource;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RepairOrderController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $repairs = RepairOrder::where('user_id', Auth::id())
            ->with('user')
            ->paginate(15);
        return RepairOrderResource::collection($repairs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RepairOrderRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';
        $data['photo'] = $request->photo->store('repairs', 'public') ?? null;
        $data['audio'] = $request->audio->store('repairs', 'public') ?? null;
        $repair = RepairOrder::create($data);
        $repair->load('user');

        // إرسال إشعار إنشاء طلب الصيانة
        $this->notificationService->sendRepairOrderCreatedNotification($repair);

        return new RepairOrderResource($repair);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $repair = RepairOrder::where('user_id', Auth::id())
            ->with('user')
            ->findOrFail($id);
        return new RepairOrderResource($repair);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RepairOrderRequest $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);
        $repair = RepairOrder::where('user_id', Auth::id())->findOrFail($id);
        $repair->update([
            'status' => $request->status,
        ]);
        $repair->load('user');
        return new RepairOrderResource($repair);
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy($id)
    {
        $repair = RepairOrder::where('user_id', Auth::id())->findOrFail($id);
        if ($repair->photo) {
            Storage::disk('public')->delete($repair->photo);
        }
        if ($repair->audio) {
            Storage::disk('public')->delete($repair->audio);
        }
        $repair->delete();
        return response()->json(['message' => 'Repair order deleted successfully']);
    }
}
