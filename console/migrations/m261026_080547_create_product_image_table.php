<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_image}}`.
 */
class m261026_080547_create_product_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_image}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'path' => $this->string()->notNull(),
            'alt' => $this->string(),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),

            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Add foreign key for table `product`
        $this->addForeignKey(
            'fk-product_image-product',
            '{{%product_image}}',
            'product_id',
            '{{%product}}',
            'id',
            'CASCADE', // on delete this will delete all images associated with the product
            'CASCADE' // on update this will update all images associated with the product if the product id changes
        );

        // Add index for column `product_id`
        $this->createIndex(
            'idx-product_image-product',
            '{{%product_image}}',
            ['product_id', 'sort_order']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%product_image}}');
    }
}
