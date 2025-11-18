<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private OrderService $orderService
    ) {}

    /**
     * Show the checkout page
     */
    public function index()
    {
        // Check if cart is empty
        if ($this->cartService->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        $cartData = $this->cartService->getCartData();
        
        // Calculate totals with tax and shipping
        $totals = $this->orderService->calculateOrderTotal($cartData['items']);

        return Inertia::render('Checkout/Index', [
            'cart' => array_merge($cartData, $totals),
        ]);
    }

    /**
     * Process the order
     */
    public function store(Request $request)
    {
        // Check if cart is empty
        if ($this->cartService->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        // Validate request
        $validated = $request->validate([
            'shipping_address.name' => 'required|string|max:255',
            'shipping_address.address' => 'required|string|max:500',
            'shipping_address.city' => 'required|string|max:100',
            'shipping_address.postal_code' => 'required|string|max:20',
            'shipping_address.phone' => 'required|string|max:20',
            'billing_address.name' => 'required|string|max:255',
            'billing_address.address' => 'required|string|max:500',
            'billing_address.city' => 'required|string|max:100',
            'billing_address.postal_code' => 'required|string|max:20',
            'billing_address.phone' => 'required|string|max:20',
            'payment_method' => 'required|in:cash_on_delivery,credit_card,paypal',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $cartItems = $this->cartService->getItems();

            // Create order
            $order = $this->orderService->createOrder(
                cartItems: array_values($cartItems),
                shippingAddress: $validated['shipping_address'],
                billingAddress: $validated['billing_address'],
                paymentMethod: $validated['payment_method'],
                userId: auth()->id(),
                notes: $validated['notes'] ?? null
            );

            // Redirect to order confirmation
            return redirect()->route('orders.confirmation', $order)
                ->with('success', 'Votre commande a été créée avec succès!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de la commande: ' . $e->getMessage());
        }
    }
}
