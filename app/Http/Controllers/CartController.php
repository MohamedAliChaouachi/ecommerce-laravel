<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService
    ) {}

    /**
     * Display the cart page
     */
    public function index(): Response
    {
        $cartData = $this->cartService->getCartData();

        return Inertia::render('Cart/Index', [
            'cart' => $cartData,
        ]);
    }

    /**
     * Add product to cart
     */
    public function add(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        if ($product->stock < 1) {
            return back()->with('error', 'Ce produit est en rupture de stock.');
        }

        $quantity = $request->input('quantity', 1);
        
        if ($quantity > $product->stock) {
            return back()->with('error', 'Quantité demandée non disponible en stock.');
        }

        $this->cartService->add($product, $quantity);

        return back()->with('success', 'Produit ajouté au panier avec succès !');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, int $productId): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = $request->input('quantity');
        $updated = $this->cartService->update($productId, $quantity);

        if (!$updated) {
            return back()->with('error', 'Produit non trouvé dans le panier.');
        }

        return back()->with('success', 'Panier mis à jour avec succès !');
    }

    /**
     * Remove product from cart
     */
    public function remove(int $productId): RedirectResponse
    {
        $removed = $this->cartService->remove($productId);

        if (!$removed) {
            return back()->with('error', 'Produit non trouvé dans le panier.');
        }

        return back()->with('success', 'Produit retiré du panier.');
    }

    /**
     * Clear entire cart
     */
    public function clear(): RedirectResponse
    {
        $this->cartService->clear();

        return back()->with('success', 'Panier vidé avec succès.');
    }

    /**
     * Get cart count (for AJAX requests)
     */
    public function count(): array
    {
        return [
            'count' => $this->cartService->getCount(),
        ];
    }
}
