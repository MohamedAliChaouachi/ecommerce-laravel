# Quick Test Guide - PHASE 3

## Prerequisites ✅
- [x] Vite dev server running (npm run dev) - Port 5174
- [x] Laragon running - Site at http://ecommerce-laravel.test
- [x] Database seeded with products

---

## 🧪 Test Scenario: Complete Order Flow

### Step 1: Register/Login (2 minutes)

1. Open browser: **http://ecommerce-laravel.test**
2. Click "Register" or "Login"
3. Create account or use existing credentials

### Step 2: Browse & Add to Cart (3 minutes)

1. Go to **Products** page
2. Add **3-4 different products** to cart
   - Click "Add to Cart" on each product
   - Vary quantities (1, 2, 3, etc.)
3. Click **Cart icon** in navigation
4. Verify cart shows all items correctly
5. Try:
   - ✅ Update quantity
   - ✅ Remove item
   - ✅ Clear cart (then re-add items)

### Step 3: Checkout - Step 1 (Shipping Address)

1. Click **"Proceed to Checkout"**
2. Fill shipping address:
   ```
   Name: John Doe
   Address: 123 Test Street, Apartment 4B
   City: Tunis
   Postal Code: 1000
   Phone: +216 12345678
   ```
3. Try clicking "Continue" without filling all fields
   - ✅ Validation should prevent proceeding
4. Fill all fields and click **"Continue to Payment"**

### Step 4: Checkout - Step 2 (Payment)

1. Select **"Cash on Delivery"** (or try others)
2. Check **"Same as shipping"** for billing address
   - ✅ Billing fields should auto-fill
3. Uncheck it and manually fill billing address
4. Add optional note:
   ```
   Please call before delivery
   ```
5. Click **"Continue to Review"**

### Step 5: Checkout - Step 3 (Review)

1. **Verify everything displays correctly:**
   - ✅ Shipping address
   - ✅ Billing address  
   - ✅ Payment method
   - ✅ All cart items with images
   - ✅ Subtotal calculation
   - ✅ Tax (10% of subtotal)
   - ✅ Shipping cost:
     - FREE if subtotal ≥ 100 DT
     - 7 DT if subtotal < 100 DT
   - ✅ Total = Subtotal + Tax + Shipping

2. Click **"Confirm Order"**
3. Watch for loading state ("Processing order...")

### Step 6: Order Confirmation (2 minutes)

**Expected:** Redirect to confirmation page

**Verify:**
- ✅ Green checkmark icon
- ✅ "Order confirmed!" message
- ✅ Order number displayed (format: ORD-20251118-XXXXX)
- ✅ Shipping address shown
- ✅ Payment method shown
- ✅ All items listed with images
- ✅ Order summary with correct totals
- ✅ "What's Next?" checklist

**Test buttons:**
- Click **"Print Receipt"** → Print dialog opens
- Click **"View Details"** → Go to order detail page
- Click **"Continue Shopping"** → Go to products page

### Step 7: Order History (2 minutes)

1. Go to **"My Orders"** (navigation link)
2. **Verify:**
   - ✅ Order appears in list
   - ✅ Status badge shows "Pending" (yellow)
   - ✅ Order date is today
   - ✅ Item count correct
   - ✅ Total amount correct
   - ✅ Product thumbnails shown

3. **Test filter:**
   - Select "Pending" → Shows only pending orders
   - Select "All" → Shows all orders
   - Select "Delivered" → Shows empty (no delivered yet)

4. Click **"View Details"** button

### Step 8: Order Details (3 minutes)

**Verify display:**
- ✅ Order number + date/time in header
- ✅ Status badge (yellow "Pending")
- ✅ **Order Timeline** shows:
  - ✅ Step 1 "Ordered" - completed (green)
  - ✅ Steps 2-4 - not completed (gray)
  - ✅ Progress line partially filled
- ✅ All items shown with images (clickable to product page)
- ✅ Order summary sidebar: subtotal, tax, shipping, total
- ✅ Shipping address card
- ✅ Payment info card
- ✅ Notes shown (if you added any)
- ✅ **"Cancel Order" button** visible (red border)

**Test navigation:**
- Click **"Back to orders"** → Returns to order list

### Step 9: Cancel Order (2 minutes)

1. From order details page, click **"Cancel Order"**
2. Confirm cancellation in dialog
3. **Verify:**
   - ✅ Status changes to "Cancelled" (red)
   - ✅ "Cancel Order" button disappears
   - ✅ Success message: "Your order has been cancelled"
   - ✅ Timeline disappears (replaced with cancelled badge)

4. **Check stock restoration:**
   ```
   Option 1: Check database
   - Go to Adminer/phpMyAdmin
   - Check `products` table
   - Stock should be restored

   Option 2: Try re-ordering same products
   - Should work without "insufficient stock" error
   ```

---

## 🧮 Calculation Tests

### Test Case 1: Small Order (Shipping = 7 DT)
**Cart:**
- Product A: 25 DT × 1 = 25 DT
- Product B: 15 DT × 1 = 15 DT

**Expected Totals:**
- Subtotal: **40 DT**
- Tax (10%): **4 DT**
- Shipping: **7 DT** (subtotal < 100 DT)
- **Total: 51 DT**

### Test Case 2: Large Order (Free Shipping)
**Cart:**
- Product A: 50 DT × 2 = 100 DT
- Product B: 30 DT × 1 = 30 DT

**Expected Totals:**
- Subtotal: **130 DT**
- Tax (10%): **13 DT**
- Shipping: **0 DT (FREE)** ✅ (subtotal ≥ 100 DT)
- **Total: 143 DT**

### Test Case 3: Exactly 100 DT
**Cart:**
- Product A: 100 DT × 1 = 100 DT

**Expected Totals:**
- Subtotal: **100 DT**
- Tax (10%): **10 DT**
- Shipping: **0 DT (FREE)** ✅
- **Total: 110 DT**

---

## 🔐 Security Tests

### Test 1: Authentication Required
1. Open incognito/private window
2. Try accessing:
   - http://ecommerce-laravel.test/checkout
   - http://ecommerce-laravel.test/orders
3. **Expected:** Redirect to login page ✅

### Test 2: Authorization (Need 2 accounts)
1. Login as **User A**, create order
2. Note the order URL (e.g., /orders/1)
3. Logout, login as **User B**
4. Try accessing User A's order URL
5. **Expected:** 403 Forbidden error ✅

---

## 📱 Responsive Design Tests

### Desktop (> 1024px)
- ✅ Checkout: Form on left, summary on right
- ✅ Order details: 2-column layout
- ✅ Order history: 2-3 cards per row

### Tablet (768-1024px)
- ✅ Checkout: Stacked layout
- ✅ Order details: Single column

### Mobile (< 768px)
- ✅ All forms full width
- ✅ Buttons stack vertically
- ✅ Product thumbnails scale down
- ✅ Timeline horizontal scroll or simplify

**Test:** Resize browser window and verify layout adapts

---

## 🐛 Edge Cases to Test

### 1. Empty Cart Checkout
1. Clear cart completely
2. Try accessing: http://ecommerce-laravel.test/checkout
3. **Expected:** Redirect back or show "Cart is empty" message

### 2. Validation Errors
- Leave required fields empty
- Enter invalid phone number (too short)
- Enter invalid postal code
- **Expected:** Error messages shown, cannot proceed

### 3. Out of Stock
1. Check database: Find product with low stock (e.g., stock = 2)
2. Add 5 units to cart
3. Try checkout
4. **Expected:** Error message about insufficient stock

### 4. Form Persistence
1. Fill Step 1 (shipping)
2. Continue to Step 2
3. Click "Back"
4. **Expected:** Step 1 data still filled (not lost)

### 5. Multiple Orders
1. Create 3-4 orders quickly
2. Verify each has unique order number
3. Check no duplicate numbers exist

---

## ✅ Success Checklist

- [ ] Can register/login
- [ ] Can add products to cart
- [ ] Can update/remove cart items
- [ ] Checkout Step 1: Form validation works
- [ ] Checkout Step 2: Payment selection works
- [ ] Checkout Step 3: Review shows correct data
- [ ] Order confirmation page displays
- [ ] Order appears in history
- [ ] Order details show timeline
- [ ] Can cancel pending order
- [ ] Stock restored after cancellation
- [ ] Calculations correct (tax + shipping)
- [ ] Free shipping over 100 DT works
- [ ] Authorization prevents viewing others' orders
- [ ] Responsive design works on mobile
- [ ] Print receipt works

---

## 🎥 Quick Test (5 minutes)

**Minimal flow to verify everything works:**

1. **Login** → http://ecommerce-laravel.test/login
2. **Add to cart** → Pick 2-3 products
3. **Checkout** → Fill 3-step wizard quickly
4. **Confirm** → Verify confirmation page shows
5. **View orders** → Check order appears in history
6. **View details** → Check timeline and details
7. **Cancel** → Cancel the order, verify stock restored

**If all 7 steps work: ✅ PHASE 3 is working!**

---

## 🔍 Database Inspection (Optional)

```bash
# Open Laravel Tinker
php artisan tinker

# Check latest order
Order::with('orderItems.product')->latest()->first();

# Count orders by status
Order::selectRaw('status, count(*) as count')->groupBy('status')->get();

# Check order totals
Order::sum('total');

# Find your orders
Order::where('user_id', 1)->get();

# Check if stock was deducted
Product::find(1)->stock;
```

---

## 📞 Troubleshooting

### Issue: "404 Not Found" on checkout
**Solution:** Clear routes cache
```bash
php artisan route:clear
php artisan route:cache
```

### Issue: Styles not loading
**Solution:** Rebuild assets
```bash
npm run build
# Or keep dev server running
npm run dev
```

### Issue: "Session expired"
**Solution:** Clear application cache
```bash
php artisan cache:clear
php artisan config:clear
```

### Issue: Database errors
**Solution:** Re-run migrations
```bash
php artisan migrate:fresh --seed
```

---

## 🎯 Testing Time Estimate

- **Quick test:** 5 minutes
- **Full test:** 15-20 minutes
- **Thorough test (all edge cases):** 30-45 minutes

---

**Your site is ready at:** http://ecommerce-laravel.test  
**Vite is running on:** http://localhost:5174  

**Start testing now!** 🚀
