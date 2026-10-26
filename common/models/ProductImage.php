<?php

declare(strict_types=1);

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * ProductImage model
 *
 * @property int $id
 * @property int $product_id
 * @property string $path
 * @property string|null $alt
 * @property int $sort_order
 * @property string $created_at
 */
class ProductImage extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%product_image}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['product_id', 'path'], 'required'],
            [['product_id', 'sort_order'], 'integer'],
            [['path', 'alt'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'targetClass' => Product::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getProduct(): ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public function getUrl(): string
    {
        return Yii::getAlias('@web/' . ltrim($this->path, '/'));
    }
}
