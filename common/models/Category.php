<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Category model
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $sort_order
 * @property bool $is_active
 * @property int $created_at
 * @property int $updated_at
 */
class Category extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['sort_order'], 'integer'],
            [['is_active'], 'boolean'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getProducts(): ActiveQuery
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public static function findActive(): ActiveQuery
    {
        return static::find()->where(['is_active' => true])->orderBy(['sort_order' => SORT_ASC]);
    }

    /**
     * Active categories for the navigation menu.
     *
     * @return Category[]
     */
    public static function findMenu(): array
    {
        return static::findActive()->all();
    }
}
