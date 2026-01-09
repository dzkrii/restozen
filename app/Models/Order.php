<?php

namespace App\Models;

use App\Models\Traits\BelongsToOutlet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes, BelongsToOutlet;

    protected $fillable = [
        'outlet_id',
        'table_id',
        'user_id',
        'order_number',
        'order_type',
        'status',
        'customer_name',
        'customer_phone',
        'guest_count',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'service_charge',
        'total_amount',
        'notes',
        'confirmed_at',
        'completed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'service_charge' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    const TYPE_DINE_IN = 'dine_in';
    const TYPE_TAKEAWAY = 'takeaway';
    const TYPE_DELIVERY = 'delivery';
    const TYPE_QR_ORDER = 'qr_order';

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PREPARING = 'preparing';
    const STATUS_READY = 'ready';
    const STATUS_SERVED = 'served';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = static::generateOrderNumber($order->outlet_id);
            }
        });
    }

    /**
     * Generate unique order number.
     */
    public static function generateOrderNumber(int $outletId): string
    {
        $date = now()->format('Ymd');
        $prefix = "ORD-{$date}-";

        $lastOrder = static::where('outlet_id', $outletId)
            ->where('order_number', 'like', "{$prefix}%")
            ->orderBy('order_number', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->order_number, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }

    /**
     * Get the outlet for this order.
     */
    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    /**
     * Get the table for this order.
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Get the cashier/waiter who created this order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all items in this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get payment for this order.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get all payments (for split payment).
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Calculate totals from items.
     */
    public function calculateTotals(): void
    {
        $subtotal = $this->items->sum('subtotal');

        $this->subtotal = $subtotal;
        $this->total_amount = $subtotal + $this->tax_amount + $this->service_charge - $this->discount_amount;
        $this->save();
    }

    /**
     * Check if order is paid.
     */
    public function isPaid(): bool
    {
        return $this->payments()
            ->where('status', 'completed')
            ->sum('amount') >= $this->total_amount;
    }

    /**
     * Mark order as confirmed.
     */
    public function confirm(): void
    {
        $this->update([
            'status' => self::STATUS_CONFIRMED,
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Mark order as completed.
     */
    public function complete(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        // Release table if dine-in
        if ($this->table_id && $this->order_type === self::TYPE_DINE_IN) {
            // Fetch the table fresh from the database to ensure it's a model instance
            $table = Table::find($this->table_id);
            
            // Call release only if table exists
            if ($table) {
                $table->release();
            }
        }
    }
}
