<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RepairOrder;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class RepairOrderController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $repairOrders = RepairOrder::with('user')->latest()->paginate(15);
        return view('admin.repair-orders.index', compact('repairOrders'));
    }

    public function show(RepairOrder $repairOrder)
    {
        $repairOrder->load('user');
        return view('admin.repair-orders.show', compact('repairOrder'));
    }

    public function edit(RepairOrder $repairOrder)
    {
        $repairOrder->load('user');
        return view('admin.repair-orders.edit', compact('repairOrder'));
    }

    public function update(Request $request, RepairOrder $repairOrder)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'estimated_cost' => 'nullable|numeric|min:0|max:999999.99',
            'final_cost' => 'nullable|numeric|min:0|max:999999.99',
            'notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $repairOrder->status;
        $newStatus = $validated['status'];

        $repairOrder->update($validated);

        // إرسال إشعار تغيير حالة طلب الإصلاح
        if ($oldStatus !== $newStatus) {
            $this->notificationService->sendRepairOrderStatusChangedNotification($repairOrder, $oldStatus, $newStatus);
        }

        return redirect()->route('admin.repair-orders.index')
            ->with('success', 'تم تحديث طلب الإصلاح بنجاح');
    }

    public function destroy(RepairOrder $repairOrder)
    {
        $repairOrder->delete();
        return redirect()->route('admin.repair-orders.index')
            ->with('success', 'تم حذف طلب الإصلاح بنجاح');
    }
}
