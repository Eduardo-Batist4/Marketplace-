<?php

namespace App\Models;

use App\States\CanceledState;
use App\States\CompletedState;
use App\States\OrderStateInterface;
use App\States\PendingState;
use App\States\ProcessingState;
use App\States\ShippedState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'coupon_id',
        'status',
        'total_amount',
    ];

    private static array $stateMap = [
        'pending'    => PendingState::class,
        'processing' => ProcessingState::class,
        'shipped'    => ShippedState::class,
        'completed'  => CompletedState::class,
        'canceled'   => CanceledState::class,
    ];

    public function getStateInstance(): OrderStateInterface
    {
        $stateClass = self::$stateMap[$this->status]
            ?? throw new \RuntimeException("Status desconhecido: {$this->status}");

        return new $stateClass();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }
}
