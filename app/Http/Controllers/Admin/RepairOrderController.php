<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RepairOrder;
use Illuminate\Http\Request;

class RepairOrderController extends Controller
{
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
        ]);

        $repairOrder->update($validated);

        return redirect()->route('admin.repair-orders.index')
            ->with('success', 'تم تحديث حالة طلب الإصلاح بنجاح');
    }

    public function destroy(RepairOrder $repairOrder)
    {
        $repairOrder->delete();
        return redirect()->route('admin.repair-orders.index')
            ->with('success', 'تم حذف طلب الإصلاح بنجاح');
    }
}
