<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order}}`.
 */
class m261026_110737_create_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'number' => $this->string(20)->notNull()->unique(), // Unique order number like: T261000000001
            'user_id' => $this->integer()->notNull(),
            'status' => $this->string(20)->notNull()->defaultValue('pending'),
            'customer_name' => $this->string()->notNull(),
            'phone' => $this->string()->notNull(),
            'address' => $this->string()->notNull(),
            'city' => $this->string()->notNull(),
            'delivery_zone' => $this->string()->notNull(), // e.g., "inside_dhaka", "outside_dhaka"
            'payment_method' => $this->string()->notNull()->defaultValue('cod'), // e.g., "cod", "online"
            'subtotal' => $this->decimal(10, 2)->notNull(),
            'delivery_charge' => $this->decimal(10, 2)->notNull(),
            'total' => $this->decimal(10, 2)->notNull(),
            'note' => $this->text(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Add foreign key for table `user`
        $this->addForeignKey(
            'fk-order-user',
            '{{%order}}',
            'user_id',
            '{{%user}}',
            'id',
            'RESTRICT', // on delete this will restrict deletion of a user if there are orders associated with it
            'CASCADE' // on update this will update all orders associated with the user if the user id changes
        );

        // create index for column `user_id`
        $this->createIndex(
            'idx-order-user_created',
            '{{%order}}',
            ['user_id', 'created_at']
        );
        $this->createIndex(
            'idx-order-status',
            '{{%order}}',
            'status'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order}}');
    }
}
