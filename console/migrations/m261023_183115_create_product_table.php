<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 */
class m261023_183115_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()->unique(),
            'sku' => $this->string()->notNull()->unique(),
            'description' => $this->text(),
            'price' => $this->decimal(10, 2)->notNull(),
            'sale_price' => $this->decimal(10, 2),
            'stock' => $this->integer()->notNull()->defaultValue(0),
            'min_age' => $this->smallInteger()->notNull()->defaultValue(0),
            'max_age' => $this->smallInteger(),
            'is_featured' => $this->boolean()->notNull()->defaultValue(true),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),

            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Add foreign key for table `category`
        $this->addForeignKey(
            'fk-product-category',
            '{{%product}}',
            'category_id',
            '{{%category}}',
            'id',
            'RESTRICT', // on delete this will restrict deletion of a category if there are products associated with it
            'CASCADE'
        );

        // create index for column `product category status`
        $this->createIndex(
            'idx-product-category_status',
            '{{%product}}',
            ['category_id', 'status']
        );

        // create index for column `product category is_featured`
        $this->createIndex(
            'idx-product-category_is_featured',
            '{{%product}}',
            ['category_id', 'is_featured']
        );

        // database level rules for not letting app bugs from inserting wrong data into the product table
        $this->addCheck('chk-product-stock', '{{%product}}', 'stock >= 0');
        $this->addCheck('chk-product-price', '{{%product}}', 'price >= 0');
        

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%product}}');
    }
}
