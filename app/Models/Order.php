<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'payment_status',
        'payment_method',
        'subtotal',
        'tax',
        'shipping_cost',
        'total',
        'shipping_address',
        'billing_address',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'tax' => 'float',
        'shipping_cost' => 'float',
        'total' => 'float',
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['formatted_total', 'status_badge_color'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessors
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 2, ',', ' ') . ' DT';
    }

    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'processing' => 'blue',
            'shipped' => 'purple',
            'delivered' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    // Scopes
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Methods
    public function calculateTotal()
    {
        $this->total = $this->subtotal + $this->tax + $this->shipping_cost;
        return $this->total;
    }

    public function markAsPaid()
    {
        $this->payment_status = 'paid';
        $this->save();
    }

    public function markAsProcessing()
    {
        $this->status = 'processing';
        $this->save();
    }

    public function markAsShipped()
    {
        $this->status = 'shipped';
        $this->save();
    }

    public function markAsDelivered()
    {
        $this->status = 'delivered';
        $this->save();
    }

    public function cancel()
    {
        $this->status = 'cancelled';
        $this->save();
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'processing']);
    }
}
