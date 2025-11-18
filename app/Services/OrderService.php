<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderService
{
    const TAX_RATE = 0.10; // 10% tax
    const FREE_SHIPPING_THRESHOLD = 100; // Free shipping over 100 DT
    const SHIPPING_COST = 7.00; // 7 DT shipping cost

    /**
     * Create a new order from cart items
     */
    public function createOrder(
        array $cartItems,
        array $shippingAddress,
        array $billingAddress,
        string $paymentMethod,
        ?int $userId = null,
        ?string $notes = null
    ): Order {
        return DB::transaction(function () use ($cartItems, $shippingAddress, $billingAddress, $paymentMethod, $userId, $notes) {
            // Validate stock availability
            $this->validateStock($cartItems);

            // Calculate totals
            $totals = $this->calculateOrderTotal($cartItems);

            // Generate unique order number
            $orderNumber = $this->generateOrderNumber();

            // Create order
            $order = Order::create([
                'user_id' => $userId ?? auth()->id(),
                'order_number' => $orderNumber,
                'status' => 'pending',
                'payment_status' => $paymentMethod === 'cash_on_delivery' ? 'pending' : 'pending',
                'payment_method' => $paymentMethod,
                'subtotal' => $totals['subtotal'],
                'tax' => $totals['tax'],
                'shipping_cost' => $totals['shipping_cost'],
                'total' => $totals['total'],
                'shipping_address' => $shippingAddress,
                'billing_address' => $billingAddress,
                'notes' => $notes,
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                $product = Product::findOrFail($item['id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->price * $item['quantity'],
                ]);

                // Reduce product stock
                $product->decrement('stock', $item['quantity']);
            }

            // Clear cart
            Session::forget('shopping_cart');

            return $order->load('orderItems');
        });
    }

    /**
     * Validate that all products have sufficient stock
     */
    protected function validateStock(array $cartItems): void
    {
        foreach ($cartItems as $item) {
            $product = Product::findOrFail($item['id']);
            
            if ($product->stock < $item['quantity']) {
                throw new \Exception("Insufficient stock for product: {$product->name}. Only {$product->stock} available.");
            }
        }
    }

    /**
     * Calculate order total with tax and shipping
     */
    public function calculateOrderTotal(array $cartItems): array
    {
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                $subtotal += $product->price * $item['quantity'];
            }
        }

        $tax = $subtotal * self::TAX_RATE;
        $shippingCost = $subtotal >= self::FREE_SHIPPING_THRESHOLD ? 0 : self::SHIPPING_COST;
        $total = $subtotal + $tax + $shippingCost;

        return [
            'subtotal' => round($subtotal, 2),
            'tax' => round($tax, 2),
            'shipping_cost' => round($shippingCost, 2),
            'total' => round($total, 2),
        ];
    }

    /**
     * Generate unique order number
     */
    protected function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $random = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        
        $orderNumber = "ORD-{$date}-{$random}";

        // Ensure uniqueness
        while (Order::where('order_number', $orderNumber)->exists()) {
            $random = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            $orderNumber = "ORD-{$date}-{$random}";
        }

        return $orderNumber;
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Order $order, string $status): bool
    {
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        $order->status = $status;
        return $order->save();
    }

    /**
     * Get orders by user
     */
    public function getOrdersByUser(User $user, ?string $status = null)
    {
        $query = $user->orders()->with('orderItems.product')->recent();

        if ($status) {
            $query->byStatus($status);
        }

        return $query->paginate(10);
    }

    /**
     * Get order details with items
     */
    public function getOrderDetails(Order $order): array
    {
        $order->load(['orderItems.product', 'user']);

        return [
            'order' => $order,
            'items' => $order->orderItems,
            'user' => $order->user,
            'can_cancel' => $order->canBeCancelled(),
        ];
    }

    /**
     * Cancel an order
     */
    public function cancelOrder(Order $order): bool
    {
        if (!$order->canBeCancelled()) {
            throw new \Exception("This order cannot be cancelled. Current status: {$order->status}");
        }

        return DB::transaction(function () use ($order) {
            // Restore product stock
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }

            // Update order status
            $order->cancel();

            return true;
        });
    }

    /**
     * Mark order as paid
     */
    public function markOrderAsPaid(Order $order): bool
    {
        $order->markAsPaid();
        $order->markAsProcessing();
        return true;
    }
}
