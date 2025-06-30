<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.product'])->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled,shipped',
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        $order->update($validated);

        // إرسال إشعار تغيير حالة الطلب
        if ($oldStatus !== $newStatus) {
            $this->notificationService->sendOrderStatusChangedNotification($order, $oldStatus, $newStatus);
        }

        return redirect()->route('admin.orders.index')
            ->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')
            ->with('success', 'تم حذف الطلب بنجاح');
    }
}
