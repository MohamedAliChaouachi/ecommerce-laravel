<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Calculate statistics
        $totalRevenue = Order::whereIn('status', ['processing', 'shipped', 'delivered'])
            ->sum('total');

        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<=', 10)->count();
        
        $totalUsers = User::where('is_admin', false)->count();
        $newUsersToday = User::whereDate('created_at', today())->count();

        // Calculate revenue growth (compare this month to last month)
        $currentMonthRevenue = Order::whereIn('status', ['processing', 'shipped', 'delivered'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $lastMonthRevenue = Order::whereIn('status', ['processing', 'shipped', 'delivered'])
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total');

        $revenueGrowth = $lastMonthRevenue > 0 
            ? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        // Get recent orders
        $recentOrders = Order::with('user:id,name,email')
            ->latest()
            ->take(10)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalRevenue' => $totalRevenue,
                'revenueGrowth' => $revenueGrowth,
                'totalOrders' => $totalOrders,
                'pendingOrders' => $pendingOrders,
                'totalProducts' => $totalProducts,
                'lowStockProducts' => $lowStockProducts,
                'totalUsers' => $totalUsers,
                'newUsersToday' => $newUsersToday,
            ],
            'recentOrders' => $recentOrders,
        ]);
    }
}
