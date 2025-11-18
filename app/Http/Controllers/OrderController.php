<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Display user's orders
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        
        $orders = $this->orderService->getOrdersByUser(
            auth()->user(),
            $status
        );

        return Inertia::render('Order/Index', [
            'orders' => $orders,
            'filters' => [
                'status' => $status,
            ],
        ]);
    }

    /**
     * Display a specific order
     */
    public function show(Order $order)
    {
        // Authorize - user can only view their own orders
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to order.');
        }

        $orderDetails = $this->orderService->getOrderDetails($order);

        return Inertia::render('Order/Show', $orderDetails);
    }

    /**
     * Show order confirmation page
     */
    public function confirmation(Order $order)
    {
        // Authorize
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $orderDetails = $this->orderService->getOrderDetails($order);

        return Inertia::render('Order/Confirmation', $orderDetails);
    }

    /**
     * Cancel an order
     */
    public function cancel(Order $order)
    {
        // Authorize
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to order.');
        }

        try {
            $this->orderService->cancelOrder($order);

            return back()->with('success', 'Votre commande a été annulée.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
