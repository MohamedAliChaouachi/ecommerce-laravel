<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const CART_SESSION_KEY = 'shopping_cart';

    /**
     * Get all cart items
     */
    public function getItems(): array
    {
        return Session::get(self::CART_SESSION_KEY, []);
    }

    /**
     * Add product to cart
     */
    public function add(Product $product, int $quantity = 1): bool
    {
        $cart = $this->getItems();
        $productId = $product->id;

        if (isset($cart[$productId])) {
            // Update quantity if product already exists
            $cart[$productId]['quantity'] += $quantity;
        } else {
            // Add new product
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'image_path' => $product->image_path,
                'stock' => $product->stock,
                'quantity' => $quantity,
            ];
        }

        // Ensure quantity doesn't exceed stock
        if ($cart[$productId]['quantity'] > $product->stock) {
            $cart[$productId]['quantity'] = $product->stock;
        }

        Session::put(self::CART_SESSION_KEY, $cart);
        return true;
    }

    /**
     * Update product quantity in cart
     */
    public function update(int $productId, int $quantity): bool
    {
        $cart = $this->getItems();

        if (!isset($cart[$productId])) {
            return false;
        }

        if ($quantity <= 0) {
            return $this->remove($productId);
        }

        // Ensure quantity doesn't exceed stock
        if ($quantity > $cart[$productId]['stock']) {
            $quantity = $cart[$productId]['stock'];
        }

        $cart[$productId]['quantity'] = $quantity;
        Session::put(self::CART_SESSION_KEY, $cart);
        return true;
    }

    /**
     * Remove product from cart
     */
    public function remove(int $productId): bool
    {
        $cart = $this->getItems();

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put(self::CART_SESSION_KEY, $cart);
            return true;
        }

        return false;
    }

    /**
     * Clear entire cart
     */
    public function clear(): void
    {
        Session::forget(self::CART_SESSION_KEY);
    }

    /**
     * Get total number of items in cart
     */
    public function getCount(): int
    {
        $cart = $this->getItems();
        return array_sum(array_column($cart, 'quantity'));
    }

    /**
     * Get cart subtotal
     */
    public function getSubtotal(): float
    {
        $cart = $this->getItems();
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        return $subtotal;
    }

    /**
     * Get cart total (for now same as subtotal, can add tax/shipping later)
     */
    public function getTotal(): float
    {
        return $this->getSubtotal();
    }

    /**
     * Check if cart is empty
     */
    public function isEmpty(): bool
    {
        return empty($this->getItems());
    }

    /**
     * Get cart data with calculations
     */
    public function getCartData(): array
    {
        $items = $this->getItems();
        
        return [
            'items' => array_values($items),
            'count' => $this->getCount(),
            'subtotal' => $this->getSubtotal(),
            'total' => $this->getTotal(),
            'isEmpty' => $this->isEmpty(),
        ];
    }
}
