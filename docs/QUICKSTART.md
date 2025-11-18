# Quick Start - Development Workflow

## 🚀 Starting a New Task

### 1. Pick a Task
```bash
# Check GitHub Issues or Project Board
# Assign yourself to the issue
# Comment that you're starting work
```

### 2. Create Feature Branch
```bash
git checkout develop
git pull origin develop
git checkout -b feature/checkout/payment-form
```

### 3. Work & Commit
```bash
# Make changes
git add .
git commit -m "feat(checkout): add payment form component"

# Keep committing as you progress
```

### 4. Stay Updated
```bash
# At least once daily
git fetch origin
git rebase origin/develop

# Resolve conflicts if any
```

### 5. Create Pull Request
```bash
# Before creating PR:
npm run lint          # Check code style
composer test         # Run backend tests
npm run build         # Build frontend

# Push your branch
git push origin feature/checkout/payment-form

# Open PR on GitHub:
# - Base: develop
# - Title: "feat(checkout): add payment form component"
# - Fill out PR template
# - Request review from teammate
```

---

## 📋 PHASE 3 Task Assignments

### Developer A (Backend) - Initial Tasks

**Task 1: Order Models & Database**
```bash
git checkout -b feature/orders/models-migration

# Create:
php artisan make:model Order -m
php artisan make:model OrderItem -m

# Files to modify:
- database/migrations/xxx_create_orders_table.php
- database/migrations/xxx_create_order_items_table.php
- app/Models/Order.php
- app/Models/OrderItem.php

# Commit & push
git add .
git commit -m "feat(orders): add Order and OrderItem models with migrations"
git push origin feature/orders/models-migration
# Create PR → develop
```

**Task 2: Order Service**
```bash
git checkout develop
git pull origin develop
git checkout -b feature/orders/order-service

# Create:
- app/Services/OrderService.php

# Commit & push
git add .
git commit -m "feat(orders): create OrderService for order management"
git push origin feature/orders/order-service
# Create PR → develop
```

**Task 3: Controllers**
```bash
git checkout -b feature/orders/controllers

# Create:
php artisan make:controller CheckoutController
php artisan make:controller OrderController

# Commit & push
git commit -m "feat(orders): add CheckoutController and OrderController"
git push origin feature/orders/controllers
# Create PR → develop
```

### Developer B (Frontend) - Initial Tasks

**Task 1: Checkout Page**
```bash
git checkout -b feature/checkout/multi-step-ui

# Create:
- resources/js/Pages/Checkout/Index.vue
- resources/js/Components/Checkout/ShippingForm.vue
- resources/js/Components/Checkout/PaymentMethod.vue
- resources/js/Components/Checkout/OrderSummary.vue

# Commit & push
git add .
git commit -m "feat(checkout): create multi-step checkout UI"
git push origin feature/checkout/multi-step-ui
# Create PR → develop
```

**Task 2: Order Confirmation**
```bash
git checkout develop
git pull origin develop
git checkout -b feature/orders/confirmation-page

# Create:
- resources/js/Pages/Order/Confirmation.vue

# Commit & push
git commit -m "feat(orders): add order confirmation page"
git push origin feature/orders/confirmation-page
# Create PR → develop
```

**Task 3: Order History**
```bash
git checkout -b feature/orders/history-page

# Create:
- resources/js/Pages/Order/Index.vue
- resources/js/Pages/Order/Show.vue
- resources/js/Components/OrderCard.vue

# Commit & push
git commit -m "feat(orders): add order history and detail pages"
git push origin feature/orders/history-page
# Create PR → develop
```

---

## 🤝 Coordination Checkpoints

### Day 1 (Monday)
**Developer A:**
- [ ] Create Order & OrderItem models
- [ ] Define database schema

**Developer B:**
- [ ] Design checkout flow wireframe
- [ ] Create basic checkout page structure

**Meeting (15 min):**
- Agree on Order JSON structure
- Discuss checkout steps

### Day 3 (Wednesday)
**Developer A:**
- [ ] OrderService implementation complete
- [ ] Basic controller endpoints ready

**Developer B:**
- [ ] Shipping form complete
- [ ] Payment method selector ready

**Meeting (15 min):**
- Test API endpoints with Postman
- Ensure data structure matches frontend needs

### Day 5 (Friday)
**Both:**
- [ ] Merge all PRs to develop
- [ ] Test checkout flow end-to-end on local

**Meeting (30 min):**
- Demo working features
- Identify bugs
- Plan next week

---

## 🛠️ Daily Commands Reference

### Morning Routine
```bash
# Sync with team's work
git checkout develop
git pull origin develop

# Check what you were working on
git branch --list
git log --oneline -5
```

### During Work
```bash
# Save progress frequently
git add .
git commit -m "wip: working on payment form"

# Check status
git status
git diff

# Stash changes if needed
git stash save "work in progress"
git stash pop
```

### Before Lunch/End of Day
```bash
# Push your work
git push origin your-branch-name

# Update your feature branch
git fetch origin
git rebase origin/develop
```

### Code Quality Checks
```bash
# Backend
composer test
php artisan route:list
php artisan config:clear

# Frontend
npm run lint
npm run build
npm run dev  # Check hot reload works
```

---

## 🚨 When Things Go Wrong

### Merge Conflict
```bash
# Don't panic! Conflicts are normal

# Step 1: Fetch latest
git fetch origin

# Step 2: Try to rebase
git rebase origin/develop

# Step 3: Git will show conflict files
# Open them in your editor
# Look for <<<<<<< ======= >>>>>>>

# Step 4: Resolve each conflict
# Keep what you need, remove markers

# Step 5: Mark as resolved
git add <file>

# Step 6: Continue
git rebase --continue

# If you want to start over:
git rebase --abort
```

### Accidentally Committed to Wrong Branch
```bash
# Move commits to correct branch
git checkout correct-branch
git cherry-pick <commit-hash>

git checkout wrong-branch
git reset --hard HEAD~1
```

### Need to Undo Last Commit
```bash
# Keep changes, undo commit
git reset --soft HEAD~1

# Discard changes and commit
git reset --hard HEAD~1
```

### Pull Request Has Conflicts
```bash
# On your feature branch
git fetch origin
git rebase origin/develop

# Resolve conflicts
# Then force push (safe on your own branch)
git push --force-with-lease origin your-branch
```

---

## 📞 Who to Ask

### Backend Questions
- Database schema
- API endpoints
- Business logic
- Server errors
**→ Ask Developer A**

### Frontend Questions
- Component design
- Vue/Inertia issues
- CSS/styling
- User interactions
**→ Ask Developer B**

### Shared Questions
- Git/GitHub issues
- Deployment
- Architecture decisions
- Performance
**→ Discuss together**

---

## ✅ PR Merge Checklist

Before approving a PR:
- [ ] Title follows convention (feat/fix/chore)
- [ ] Description is clear
- [ ] All CI checks are green
- [ ] No merge conflicts
- [ ] Code review comments addressed
- [ ] Tested locally (if possible)
- [ ] Documentation updated (if needed)

After merging:
- [ ] Delete feature branch
- [ ] Close related issue
- [ ] Update project board
- [ ] Pull latest develop

---

## 🎯 Sprint Goals (Week by Week)

### Week 1: Checkout Foundation
- [ ] Order models & migrations
- [ ] OrderService implementation
- [ ] Checkout UI (3 steps)
- [ ] Basic order creation flow

### Week 2: Payment & Completion
- [ ] Payment gateway integration
- [ ] Order confirmation page
- [ ] Order history page
- [ ] Email notifications

### Week 3: Testing & Polish
- [ ] E2E tests for checkout
- [ ] Bug fixes
- [ ] UI polish & responsive design
- [ ] Code review & refactoring

### Week 4: Admin Panel Start
- [ ] Admin layout
- [ ] Order management
- [ ] Product management
- [ ] Dashboard stats

---

## 💡 Pro Tips

1. **Commit often:** Small commits are easier to review and revert
2. **Pull daily:** Reduces merge conflicts
3. **Test before PR:** Save reviewer time
4. **Descriptive commits:** Help future you understand changes
5. **Ask early:** Don't struggle alone for hours
6. **Review thoroughly:** Better to catch issues before merge
7. **Document decisions:** Add comments for complex logic
8. **Keep PRs small:** Target 100-300 lines changed

---

## 🔗 Quick Links

- **Repository:** https://github.com/MohamedAliChaouachi/ecommerce-laravel
- **Issues:** https://github.com/MohamedAliChaouachi/ecommerce-laravel/issues
- **Projects:** https://github.com/MohamedAliChaouachi/ecommerce-laravel/projects
- **Wiki:** https://github.com/MohamedAliChaouachi/ecommerce-laravel/wiki

---

**Need Help?** Check `docs/COLLABORATION.md` for detailed guidelines or ask your teammate!
