<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Product model
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $slug
 * @property string $sku
 * @property string|null $description
 * @property string $price
 * @property string|null $sale_price
 * @property int $stock
 * @property int $min_age
 * @property int|null $max_age
 * @property bool $is_featured
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Product extends ActiveRecord
{
    public const STATUS_DRAFT = 0;
    public const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%product}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['category_id', 'name', 'slug', 'sku', 'price'], 'required'],
            [['category_id', 'stock', 'min_age', 'max_age', 'status'], 'integer'],
            [['description'], 'string'],
            [['price', 'sale_price'], 'number'],
            [['is_featured'], 'boolean'],
            [['name', 'slug', 'sku'], 'string', 'max' => 255],
            [['slug', 'sku'], 'unique'],
            [['category_id'], 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category',
            'name' => 'Name',
            'slug' => 'Slug',
            'sku' => 'SKU',
            'description' => 'Description',
            'price' => 'Price',
            'sale_price' => 'Sale Price',
            'stock' => 'Stock',
            'min_age' => 'Min Age',
            'max_age' => 'Max Age',
            'is_featured' => 'Featured',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getImages(): ActiveQuery
    {
        return $this->hasMany(ProductImage::class, ['product_id' => 'id'])
            ->orderBy(['sort_order' => SORT_ASC]);
    }

    /**
     * @return ActiveQuery
     */
    public function getMainImage(): ActiveQuery
    {
        return $this->hasOne(ProductImage::class, ['product_id' => 'id'])
            ->orderBy(['sort_order' => SORT_ASC]);
    }

    /**
     * @return ActiveQuery
     */
    public static function findActive(): ActiveQuery
    {
        return static::find()->where(['status' => self::STATUS_ACTIVE]);
    }

    /**
     * Whether the product currently has a discounted price lower than the regular price.
     */
    public function isOnSale(): bool
    {
        return $this->sale_price !== null && (float) $this->sale_price < (float) $this->price;
    }

    /**
     * The price customers actually pay: the sale price when on sale, otherwise the regular price.
     */
    public function getEffectivePrice(): float
    {
        return $this->isOnSale() ? (float) $this->sale_price : (float) $this->price;
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
}
