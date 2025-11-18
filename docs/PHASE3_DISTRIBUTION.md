# PHASE 3: Checkout & Orders - Work Distribution

## 📊 Overview
This document outlines the specific task breakdown for implementing the Checkout & Orders module between two developers.

---

## 👤 Developer A - Backend Implementation

### Sprint 1: Foundation (Week 1)

#### Task 1.1: Database Schema
**Branch:** `feature/orders/models-migration`
**Estimate:** 4 hours

**Create migrations:**
```bash
php artisan make:migration create_orders_table
php artisan make:migration create_order_items_table
```

**Orders table schema:**
- id (bigIncrements)
- user_id (foreignId → users.id)
- order_number (string, unique)
- status (enum: pending, processing, shipped, delivered, cancelled)
- subtotal (decimal)
- tax (decimal)
- shipping_cost (decimal)
- total (decimal)
- payment_method (string)
- payment_status (enum: pending, paid, failed, refunded)
- shipping_address (json)
- billing_address (json)
- notes (text, nullable)
- timestamps

**Order Items table schema:**
- id (bigIncrements)
- order_id (foreignId → orders.id, onDelete cascade)
- product_id (foreignId → products.id)
- product_name (string)
- product_price (decimal)
- quantity (integer)
- subtotal (decimal)
- timestamps

**Acceptance Criteria:**
- [ ] Migrations created with up() and down() methods
- [ ] Foreign keys properly defined
- [ ] Indexes on frequently queried columns
- [ ] Test migration works: `php artisan migrate:fresh --seed`

---

#### Task 1.2: Order Models
**Branch:** `feature/orders/models`
**Estimate:** 3 hours

**Create models:**
```bash
php artisan make:model Order
php artisan make:model OrderItem
```

**Order model requirements:**
- Relationships: user(), orderItems()
- Casts: shipping_address → array, billing_address → array
- Appends: formatted_total, status_badge_color
- Scopes: recent(), byStatus(), byUser()
- Methods: calculateTotal(), markAsPaid(), markAsShipped()

**OrderItem model requirements:**
- Relationships: order(), product()
- Methods: calculateSubtotal()

**Acceptance Criteria:**
- [ ] All relationships defined
- [ ] Proper type casting
- [ ] Helper methods implemented
- [ ] PHPUnit tests for models

---

#### Task 1.3: Order Service
**Branch:** `feature/orders/order-service`
**Estimate:** 6 hours

**Create:** `app/Services/OrderService.php`

**Methods to implement:**
```php
createOrder(array $cartItems, array $shippingAddress, array $billingAddress, string $paymentMethod): Order
updateOrderStatus(Order $order, string $status): bool
calculateOrderTotal(array $cartItems): array
getOrdersByUser(User $user): Collection
getOrderDetails(Order $order): array
cancelOrder(Order $order): bool
```

**Business Logic:**
- Validate stock availability
- Calculate subtotal, tax (10%), shipping cost (free if > 100 DT)
- Generate unique order number (ORD-YYYYMMDD-XXXXX)
- Create order and order items in transaction
- Reduce product stock
- Clear cart after successful order

**Acceptance Criteria:**
- [ ] All methods implemented
- [ ] Transaction handling for order creation
- [ ] Stock validation
- [ ] Proper error handling
- [ ] Unit tests (minimum 80% coverage)

---

#### Task 1.4: Controllers
**Branch:** `feature/orders/controllers`
**Estimate:** 5 hours

**Create controllers:**
```bash
php artisan make:controller CheckoutController
php artisan make:controller OrderController
```

**CheckoutController methods:**
- `index()`: Show checkout page with cart summary
- `store()`: Process order creation
  - Validate: shipping address, billing address, payment method
  - Call OrderService::createOrder()
  - Redirect to order confirmation

**OrderController methods:**
- `index()`: List user's orders (paginated)
- `show(Order $order)`: Show order details
  - Authorize: user owns order
- `cancel(Order $order)`: Cancel pending order

**Validation rules:**
```php
'shipping_address.name' => 'required|string|max:255',
'shipping_address.address' => 'required|string|max:500',
'shipping_address.city' => 'required|string|max:100',
'shipping_address.postal_code' => 'required|string|max:20',
'shipping_address.phone' => 'required|string|max:20',
'payment_method' => 'required|in:cash_on_delivery,credit_card,paypal',
```

**Acceptance Criteria:**
- [ ] Controllers created
- [ ] Validation rules defined
- [ ] Authorization policies
- [ ] Flash messages for success/error
- [ ] Feature tests for all endpoints

---

#### Task 1.5: Routes
**Branch:** `feature/orders/routes`
**Estimate:** 1 hour

**Add to routes/web.php:**
```php
Route::middleware(['auth'])->group(function () {
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});
```

**Acceptance Criteria:**
- [ ] Routes defined with auth middleware
- [ ] Named routes
- [ ] Route model binding for Order
- [ ] Test routes with: `php artisan route:list`

---

### Sprint 2: Advanced Features (Week 2)

#### Task 2.1: Payment Gateway (Stripe)
**Branch:** `feature/payment/stripe-integration`
**Estimate:** 8 hours

**Install Stripe:**
```bash
composer require stripe/stripe-php
```

**Create:** `app/Services/PaymentService.php`

**Methods:**
```php
createPaymentIntent(Order $order): array
processPayment(Order $order, string $paymentMethodId): bool
handleWebhook(array $payload): void
```

**Webhook endpoint:**
```php
Route::post('/webhook/stripe', [WebhookController::class, 'stripe'])->name('webhook.stripe');
```

**Acceptance Criteria:**
- [ ] Stripe SDK configured
- [ ] Payment intent creation
- [ ] Webhook handling (payment.succeeded, payment.failed)
- [ ] Test mode working
- [ ] Error handling for failed payments

---

#### Task 2.2: Email Notifications
**Branch:** `feature/orders/email-notifications`
**Estimate:** 4 hours

**Create mailables:**
```bash
php artisan make:mail OrderConfirmation
php artisan make:mail OrderStatusChanged
```

**Send emails on:**
- Order created → OrderConfirmation
- Order status changed → OrderStatusChanged

**Email content:**
- Order details (items, total, shipping address)
- Order tracking link
- Support contact

**Acceptance Criteria:**
- [ ] Mailables created
- [ ] HTML email templates
- [ ] Queue jobs for sending
- [ ] Test emails sent successfully

---

#### Task 2.3: Order Status Management
**Branch:** `feature/orders/status-management`
**Estimate:** 3 hours

**Add admin controller:**
```bash
php artisan make:controller Admin/OrderController
```

**Methods:**
- `index()`: List all orders
- `updateStatus(Order $order, Request $request)`: Change order status

**Status transitions:**
- pending → processing → shipped → delivered
- Any status → cancelled

**Acceptance Criteria:**
- [ ] Admin can update order status
- [ ] Status validation (prevent invalid transitions)
- [ ] Email sent on status change
- [ ] Audit log of status changes

---

## 👤 Developer B - Frontend Implementation

### Sprint 1: Checkout UI (Week 1)

#### Task 1.1: Checkout Page Structure
**Branch:** `feature/checkout/multi-step-ui`
**Estimate:** 6 hours

**Create:** `resources/js/Pages/Checkout/Index.vue`

**Multi-step checkout:**
1. **Step 1: Shipping Address**
   - Form fields: name, address, city, postal code, phone
   - Validation
   - "Same as billing" checkbox

2. **Step 2: Payment Method**
   - Radio buttons: Cash on Delivery, Credit Card, PayPal
   - Payment method icons

3. **Step 3: Review Order**
   - Cart summary
   - Shipping address display
   - Payment method display
   - Total breakdown (subtotal, tax, shipping, total)
   - "Place Order" button

**Components to create:**
- `resources/js/Components/Checkout/StepIndicator.vue`
- `resources/js/Components/Checkout/ShippingForm.vue`
- `resources/js/Components/Checkout/PaymentMethod.vue`
- `resources/js/Components/Checkout/OrderReview.vue`

**State management:**
```javascript
const currentStep = ref(1);
const shippingAddress = ref({});
const billingAddress = ref({});
const paymentMethod = ref('cash_on_delivery');
```

**Acceptance Criteria:**
- [ ] 3-step wizard UI
- [ ] Step indicator (visual progress)
- [ ] Form validation on each step
- [ ] Can't proceed without completing current step
- [ ] Responsive design (mobile-friendly)
- [ ] Loading state on submit

---

#### Task 1.2: Order Confirmation Page
**Branch:** `feature/orders/confirmation-page`
**Estimate:** 3 hours

**Create:** `resources/js/Pages/Order/Confirmation.vue`

**Display:**
- Success message with checkmark icon
- Order number
- Order details (items, quantities, prices)
- Shipping address
- Payment method
- Total amount
- Estimated delivery date
- "Print Receipt" button
- "View Order Details" button
- "Continue Shopping" button

**Acceptance Criteria:**
- [ ] Clean, celebratory design
- [ ] All order info displayed
- [ ] Print functionality works
- [ ] Links to order details and products page

---

#### Task 1.3: Order History Page
**Branch:** `feature/orders/history-page`
**Estimate:** 4 hours

**Create:** `resources/js/Pages/Order/Index.vue`

**Features:**
- List of user's orders (paginated)
- Each order shows:
  - Order number
  - Order date
  - Status badge (colored by status)
  - Total amount
  - Number of items
  - "View Details" button

**Filters:**
- Status filter dropdown (all, pending, processing, shipped, delivered, cancelled)
- Date range picker

**Empty state:**
- "No orders yet" message
- "Start Shopping" button

**Acceptance Criteria:**
- [ ] Orders displayed in reverse chronological order
- [ ] Pagination works
- [ ] Status badges color-coded
- [ ] Filters functional
- [ ] Responsive design

---

#### Task 1.4: Order Detail Page
**Branch:** `feature/orders/detail-page`
**Estimate:** 4 hours

**Create:** `resources/js/Pages/Order/Show.vue`

**Sections:**
1. **Order Header**
   - Order number
   - Order date
   - Status badge
   - "Cancel Order" button (if pending)

2. **Order Timeline**
   - Visual timeline of status changes
   - Timestamps for each status

3. **Order Items**
   - Product image, name, price, quantity, subtotal
   - Link to product page

4. **Order Summary**
   - Subtotal
   - Tax
   - Shipping
   - Total

5. **Shipping Address**
   - Address display

6. **Payment Info**
   - Payment method
   - Payment status

**Acceptance Criteria:**
- [ ] All order info displayed
- [ ] Timeline shows status progression
- [ ] Cancel button works (with confirmation)
- [ ] Responsive layout

---

### Sprint 2: Payment & Polish (Week 2)

#### Task 2.1: Payment Form (Stripe)
**Branch:** `feature/checkout/payment-forms`
**Estimate:** 6 hours

**Create:** `resources/js/Components/Checkout/CreditCardForm.vue`

**Install Stripe.js:**
```bash
npm install @stripe/stripe-js
```

**Features:**
- Stripe Elements integration
- Card number, expiry, CVC fields
- Real-time validation
- Error messages
- Loading state
- Secure payment processing

**Acceptance Criteria:**
- [ ] Stripe Elements embedded
- [ ] Form validation
- [ ] Error handling
- [ ] Payment intent creation
- [ ] Success/failure feedback

---

#### Task 2.2: UX Improvements
**Branch:** `feature/checkout/ux-improvements`
**Estimate:** 4 hours

**Enhancements:**
- Loading states on all async actions
- Error handling with toast notifications
- Form validation feedback (inline errors)
- Disable buttons during processing
- Success animations
- Auto-save shipping address for next time
- Guest checkout option

**Accessibility:**
- ARIA labels
- Keyboard navigation
- Screen reader support
- Focus management

**Acceptance Criteria:**
- [ ] All loading states implemented
- [ ] Error messages user-friendly
- [ ] Forms accessible
- [ ] Smooth animations

---

#### Task 2.3: Responsive Design Polish
**Branch:** `feature/checkout/responsive-design`
**Estimate:** 3 hours

**Test and fix on:**
- Mobile (320px - 768px)
- Tablet (768px - 1024px)
- Desktop (1024px+)

**Focus areas:**
- Checkout form stacks properly on mobile
- Order cards responsive
- Images scale correctly
- Buttons full-width on mobile
- Text sizes readable on all devices

**Acceptance Criteria:**
- [ ] Tested on Chrome, Firefox, Safari
- [ ] Mobile-first approach
- [ ] No horizontal scrolling
- [ ] Touch targets minimum 44px

---

## 🤝 Integration Points

### Day 2: API Contract Agreement
**Meeting (30 min)**

**Agree on:**
1. Order creation endpoint structure:
```json
POST /checkout
{
  "shipping_address": {
    "name": "string",
    "address": "string",
    "city": "string",
    "postal_code": "string",
    "phone": "string"
  },
  "billing_address": { /* same structure */ },
  "payment_method": "string"
}

Response:
{
  "success": true,
  "order": {
    "id": 1,
    "order_number": "ORD-20251118-00001",
    "total": 150.50,
    "status": "pending"
  }
}
```

2. Order status enum values
3. Error response format

---

### Day 5: Integration Testing
**Meeting (1 hour)**

**Test together:**
- [ ] Create order from cart
- [ ] View order confirmation
- [ ] Check order appears in history
- [ ] View order details
- [ ] Cancel order
- [ ] Check stock reduced
- [ ] Check cart cleared

**Use Postman or Insomnia to test API endpoints**

---

### Day 8: Payment Testing
**Meeting (1 hour)**

**Test:**
- [ ] Stripe payment intent creation
- [ ] Process test payment
- [ ] Handle payment success
- [ ] Handle payment failure
- [ ] Verify order status updates
- [ ] Check email notifications

---

## 📊 Progress Tracking

### Week 1 Checklist
**Backend:**
- [ ] Models & migrations
- [ ] OrderService
- [ ] Controllers
- [ ] Routes
- [ ] Basic tests

**Frontend:**
- [ ] Checkout page (3 steps)
- [ ] Order confirmation
- [ ] Order history
- [ ] Order details

### Week 2 Checklist
**Backend:**
- [ ] Stripe integration
- [ ] Email notifications
- [ ] Status management
- [ ] Admin endpoints

**Frontend:**
- [ ] Payment form
- [ ] UX improvements
- [ ] Responsive polish
- [ ] E2E tests

---

## 🧪 Testing Requirements

### Backend Tests (Developer A)
```bash
# Unit tests
tests/Unit/Services/OrderServiceTest.php
tests/Unit/Models/OrderTest.php
tests/Unit/Models/OrderItemTest.php

# Feature tests
tests/Feature/CheckoutTest.php
tests/Feature/OrderTest.php

# Run tests
composer test
```

**Minimum coverage:** 80%

### Frontend Tests (Developer B)
```bash
# Component tests
tests/js/Components/Checkout/ShippingForm.test.js
tests/js/Components/Checkout/PaymentMethod.test.js

# E2E tests
cypress/e2e/checkout.cy.js
cypress/e2e/order-history.cy.js

# Run tests
npm run test
npm run test:e2e
```

---

## 🚀 Definition of Done

**A task is complete when:**
- [ ] Code implemented and working
- [ ] Tests written and passing
- [ ] Code reviewed and approved
- [ ] Documentation updated
- [ ] No linting errors
- [ ] Merged to develop branch
- [ ] Tested in integrated environment

---

## 📞 Communication Cadence

- **Daily standup:** 9:00 AM (async via Slack)
- **Integration sync:** Every 2 days (30 min)
- **Weekly demo:** Friday 4:00 PM (1 hour)
- **Blocker discussions:** Immediate (Slack/call)

---

## 🎯 Success Metrics

**PHASE 3 is complete when:**
- [ ] User can checkout with cart items
- [ ] User can choose payment method
- [ ] Orders are created and stored
- [ ] User receives order confirmation
- [ ] User can view order history
- [ ] User can view order details
- [ ] User can cancel pending orders
- [ ] Payment processing works (Stripe test mode)
- [ ] Email notifications sent
- [ ] All tests pass
- [ ] Code coverage > 70%
- [ ] No critical bugs

---

**Next:** After PHASE 3, proceed to PHASE 4 (Admin Panel) with similar work distribution.
