<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * OrderItem model
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property string $product_name
 * @property string $unit_price
 * @property int $qty
 * @property string $line_total
 */
class OrderItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%order_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['order_id', 'product_id', 'product_name', 'unit_price', 'qty', 'line_total'], 'required'],
            [['order_id', 'product_id'], 'integer'],
            [['qty'], 'integer', 'min' => 1],
            [['unit_price', 'line_total'], 'number'],
            [['product_name'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'targetClass' => Order::class, 'targetAttribute' => 'id'],
            [['product_id'], 'exist', 'targetClass' => Product::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getOrder(): ActiveQuery
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProduct(): ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}
