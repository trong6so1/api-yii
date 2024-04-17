<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cart}}`.
 */
class m240416_021153_create_cart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cart}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'product_id' => $this->integer(),
            'quantity' => $this->integer(),
        ]);
        $this->addForeignKey(
            'fk_cart_product_id',
            'cart',
            'product_id',
            'product',
            'id'
        );
        $this->addForeignKey(
            'fk_cart_user_id',
            'cart',
            'user_id',
            'user',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_cart_product_id', 'cart');
        $this->dropForeignKey('fk_cart_user_id', 'cart');
        $this->dropTable('{{%cart}}');
    }
}
