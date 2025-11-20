# PHASE 4: Admin Panel - Implementation Plan

## 🎯 Overview
Build a comprehensive admin panel for managing products, orders, and users.

**Duration:** 1-2 weeks  
**Branch:** `feature/admin-panel`  
**Prerequisites:** PHASE 3 complete ✅

---

## 📋 Features Breakdown

### 1. Admin Authentication & Authorization (Day 1)
- [ ] Create admin middleware
- [ ] Add `is_admin` column to users table
- [ ] Admin login protection
- [ ] Redirect non-admins from admin routes

### 2. Admin Dashboard (Day 1-2)
- [ ] Dashboard layout with sidebar navigation
- [ ] Statistics cards:
  - Total revenue
  - Total orders (today, this week, this month)
  - Total products
  - Total users
- [ ] Recent orders table
- [ ] Quick actions menu
- [ ] Revenue chart (optional)

### 3. Product Management (Day 2-3)
- [ ] List all products with search/filter
- [ ] Create new product form
- [ ] Edit existing product
- [ ] Delete product (soft delete)
- [ ] Image upload functionality
- [ ] Bulk actions (delete, change category)
- [ ] Stock alerts for low inventory

### 4. Order Management (Day 3-4)
- [ ] List all orders with filters
  - Filter by status
  - Filter by date range
  - Search by order number or customer
- [ ] View order details
- [ ] Update order status workflow:
  - Pending → Processing → Shipped → Delivered
  - Cancel orders
- [ ] Print packing slip
- [ ] Export orders to CSV

### 5. Category Management (Day 4)
- [ ] List all categories
- [ ] Create/Edit/Delete categories
- [ ] Assign products to categories
- [ ] Category reordering

### 6. User Management (Day 5)
- [ ] List all users
- [ ] View user details & order history
- [ ] Ban/unban users
- [ ] Promote user to admin
- [ ] User search functionality

### 7. Reports & Analytics (Day 5-6, Optional)
- [ ] Sales report (daily, weekly, monthly)
- [ ] Best-selling products
- [ ] Customer insights
- [ ] Export reports to Excel/CSV

---

## 🗂️ File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Admin/
│   │       ├── DashboardController.php
│   │       ├── ProductController.php (admin version)
│   │       ├── OrderController.php (admin version)
│   │       ├── CategoryController.php
│   │       └── UserController.php
│   └── Middleware/
│       └── EnsureUserIsAdmin.php
│
resources/js/
├── Pages/
│   └── Admin/
│       ├── Dashboard.vue
│       ├── Products/
│       │   ├── Index.vue
│       │   ├── Create.vue
│       │   └── Edit.vue
│       ├── Orders/
│       │   ├── Index.vue
│       │   └── Show.vue
│       ├── Categories/
│       │   └── Index.vue
│       └── Users/
│           └── Index.vue
└── Layouts/
    └── AdminLayout.vue

routes/
└── web.php (add admin routes)

database/
└── migrations/
    └── xxxx_add_is_admin_to_users_table.php
```

---

## 🎨 Design Approach

### Layout
- Sidebar navigation (fixed left)
- Top header with user dropdown
- Main content area (right)
- Responsive: sidebar collapses on mobile

### Color Scheme
- Primary: Indigo (admin theme)
- Success: Green (delivered, active)
- Warning: Yellow (pending, low stock)
- Danger: Red (cancelled, out of stock)
- Neutral: Gray (general UI)

### Navigation Structure
```
Admin Panel
├── Dashboard
├── Products
│   ├── All Products
│   ├── Add New
│   └── Categories
├── Orders
│   ├── All Orders
│   └── By Status
├── Users
│   └── All Users
└── Settings (future)
```

---

## 🔐 Security Requirements

1. **Admin Middleware**
   - Check `is_admin` flag on every admin route
   - Redirect non-admins to 403 page

2. **Authorization**
   - Only admins can access `/admin/*` routes
   - Regular users get 403 Forbidden

3. **Input Validation**
   - Validate all form inputs
   - Sanitize file uploads
   - CSRF protection (Laravel default)

4. **Audit Trail** (Optional)
   - Log admin actions (who changed what, when)

---

## 🚀 Implementation Steps

### Step 1: Setup Admin Authentication (30 mins)
```bash
# Create migration
php artisan make:migration add_is_admin_to_users_table

# Create middleware
php artisan make:middleware EnsureUserIsAdmin

# Create admin controllers
php artisan make:controller Admin/DashboardController
php artisan make:controller Admin/ProductController --resource
php artisan make:controller Admin/OrderController --resource
php artisan make:controller Admin/CategoryController --resource
php artisan make:controller Admin/UserController --resource
```

### Step 2: Create Admin Layout (1 hour)
- Design AdminLayout.vue with sidebar
- Add navigation links
- Style consistently

### Step 3: Build Dashboard (2-3 hours)
- Statistics cards
- Recent orders
- Quick actions

### Step 4: Product Management (1 day)
- List products (with pagination)
- Create/edit forms
- Image upload
- Delete functionality

### Step 5: Order Management (1 day)
- List orders (with filters)
- View details
- Update status
- Export to CSV

### Step 6: Category & User Management (1 day)
- Category CRUD
- User list with search
- User actions (ban, promote)

### Step 7: Testing & Polish (1 day)
- Test all CRUD operations
- Check permissions
- Mobile responsiveness
- Fix bugs

---

## 📊 Database Changes

### Migration: Add `is_admin` to users
```php
Schema::table('users', function (Blueprint $table) {
    $table->boolean('is_admin')->default(false)->after('email');
});
```

### Seeder Update
```php
// Create admin user
User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => Hash::make('password'),
    'is_admin' => true,
]);
```

---

## 🧪 Testing Checklist

### Admin Access
- [ ] Admin can access `/admin` routes
- [ ] Regular user gets 403 on `/admin`
- [ ] Guest redirected to login

### Product Management
- [ ] Can create product with image
- [ ] Can edit product details
- [ ] Can delete product
- [ ] Pagination works
- [ ] Search/filter works

### Order Management
- [ ] Can view all orders
- [ ] Can filter by status
- [ ] Can update order status
- [ ] Status changes reflect in customer view
- [ ] Can export orders

### User Management
- [ ] Can view all users
- [ ] Can search users
- [ ] Can ban/unban users
- [ ] Can promote to admin

---

## 📝 API Endpoints

```
Admin Routes (middleware: auth, admin)

GET    /admin/dashboard           - Dashboard stats
GET    /admin/products            - List products
GET    /admin/products/create     - Create form
POST   /admin/products            - Store product
GET    /admin/products/{id}/edit  - Edit form
PUT    /admin/products/{id}       - Update product
DELETE /admin/products/{id}       - Delete product

GET    /admin/orders              - List orders
GET    /admin/orders/{id}         - View order
PUT    /admin/orders/{id}/status  - Update status

GET    /admin/categories          - List categories
POST   /admin/categories          - Create category
PUT    /admin/categories/{id}     - Update category
DELETE /admin/categories/{id}     - Delete category

GET    /admin/users               - List users
GET    /admin/users/{id}          - View user
PUT    /admin/users/{id}/ban      - Ban user
PUT    /admin/users/{id}/admin    - Toggle admin
```

---

## 🎯 Success Criteria

PHASE 4 is complete when:
- ✅ Admin can log in and access admin panel
- ✅ Dashboard shows accurate statistics
- ✅ Products can be created, edited, deleted
- ✅ Orders can be viewed and status updated
- ✅ Categories can be managed
- ✅ Users can be viewed and moderated
- ✅ All features work on mobile
- ✅ Non-admins cannot access admin panel
- ✅ Code is tested and documented

---

## 🚦 Quick Start

```bash
# 1. Create new branch
git checkout -b feature/admin-panel

# 2. Create admin user in database
php artisan tinker
>>> User::find(1)->update(['is_admin' => true]);

# 3. Start development
npm run dev
php artisan serve

# 4. Access admin panel
http://ecommerce-laravel.test/admin
```

---

## 📚 Resources

- [Laravel Authorization](https://laravel.com/docs/authorization)
- [Laravel File Upload](https://laravel.com/docs/filesystem)
- [Inertia.js Forms](https://inertiajs.com/forms)
- [TailwindCSS Admin Templates](https://tailwindui.com/components/application-ui/application-shells/sidebar)

---

**Ready to start?** Let's begin with Step 1: Admin Authentication Setup! 🚀
