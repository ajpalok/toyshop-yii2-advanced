<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_item}}`.
 */
class m261026_111527_create_order_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order_item}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'product_name' => $this->string()->notNull(),
            'unit_price' => $this->decimal(10, 2)->notNull(),
            'qty' => $this->integer()->notNull(),
            'line_total' => $this->decimal(10, 2)->notNull(),
        ]);

        // Add foreign key for table `order`
        $this->addForeignKey(
            'fk-order_item-order',
            '{{%order_item}}',
            'order_id',
            '{{%order}}',
            'id',
            'CASCADE', // on delete this will delete all order items associated with the order
            'CASCADE' // on update this will update all order items associated with the order if the order id changes
        );
        $this->createForeignKey(
            'fk-order_item-product',
            '{{%order_item}}',
            'product_id',
            '{{%product}}',
            'id',
            'RESTRICT', // on delete this will restrict deletion of a product if there are order items associated with it
            'CASCADE' // on update this will update all order items associated with the product if the product id changes
        );

        // create index for column `order_id`
        $this->createIndex(
            'idx-order_item-order_product',
            '{{%order_item}}',
            ['order_id', 'product_id'],
            true // unique index to prevent duplicate order items for the same product in the same order
        );

        $this->execute('ALTER TABLE {{%order_item}} ADD CONSTRAINT chk_order_item_qty CHECK (qty > 0)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('ALTER TABLE {{%order_item}} DROP CONSTRAINT chk_order_item_qty');
        $this->dropTable('{{%order_item}}');
    }
}
