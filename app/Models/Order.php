<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Order model
 * @property int $id
 * @property string $full_name
 * @property integer $amount
 * @property string $address
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'amount',
        'address',
    ];

    protected $hidden = ['created_at'];
}
