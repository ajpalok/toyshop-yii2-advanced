<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Order model
 *
 * @property int $id
 * @property string $number
 * @property int $user_id
 * @property string $status
 * @property string $customer_name
 * @property string $phone
 * @property string $address
 * @property string $city
 * @property string $delivery_zone
 * @property string $payment_method
 * @property string $subtotal
 * @property string $delivery_charge
 * @property string $total
 * @property string|null $note
 * @property string $created_at
 * @property string $updated_at
 */
class Order extends ActiveRecord
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Allowed status -> status transitions.
     */
    public const TRANSITIONS = [
        self::STATUS_PENDING => [self::STATUS_CONFIRMED, self::STATUS_CANCELLED],
        self::STATUS_CONFIRMED => [self::STATUS_SHIPPED, self::STATUS_CANCELLED],
        self::STATUS_SHIPPED => [self::STATUS_DELIVERED],
        self::STATUS_DELIVERED => [],
        self::STATUS_CANCELLED => [],
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['number', 'user_id', 'customer_name', 'phone', 'address', 'city', 'delivery_zone', 'subtotal', 'delivery_charge', 'total'], 'required'],
            [['user_id'], 'integer'],
            [['subtotal', 'delivery_charge', 'total'], 'number'],
            [['note'], 'string'],
            [['number', 'customer_name', 'phone', 'address', 'city', 'delivery_zone', 'payment_method'], 'string', 'max' => 255],
            [['number'], 'unique'],
            [['status'], 'in', 'range' => array_keys(self::TRANSITIONS)],
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
        ];
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_SHIPPED => 'Shipped',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public function canMoveTo(string $status): bool
    {
        return in_array($status, self::TRANSITIONS[$this->status] ?? [], true);
    }

    /**
     * @return ActiveQuery
     */
    public function getItems(): ActiveQuery
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
