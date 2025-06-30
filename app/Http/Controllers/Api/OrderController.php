<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
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
        $orders = Order::where('user_id', Auth::id())
            ->with(['user', 'orderItems.product'])
            ->paginate(15);
        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        $data = $request->all();
        $items = OrderItem::where('user_id', Auth::id())
            ->where('order_id', null)
            ->get();

        if (count($items) == 0) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $data['user_id'] = Auth::id();
        $data['total_price'] = $items->sum('price');

        $order = Order::create($data);
        foreach ($items as $item) {
            $item->order_id = $order->id;
            $item->save();
        }

        // إرسال إشعار إنشاء الطلب
        $this->notificationService->sendOrderCreatedNotification($order);

        return new OrderResource($order);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->with(['user', 'orderItems.product'])
            ->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled,shipped,processing',
        ]);

        $order = Order::where('user_id', Auth::id())->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->update([
            'status' => $request->status,
        ]);

        return new OrderResource($order);
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy($id)
    {
        $order = Order::where('user_id', Auth::id())->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->orderItems()->delete();
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully']);
    }
}
