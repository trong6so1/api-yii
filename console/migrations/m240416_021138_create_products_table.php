<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products}}`.
 */
class m240416_021138_create_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => 'LONGTEXT',
            'price' => $this->double()->notNull(),
            'discountPercentage' => $this->double()->notNull(),
            'rating' => $this->double()->notNull(),
            'stock' => $this->integer()->notNull()->defaultValue(0),
            'brand' => $this->string()->notNull(),
            'category' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);
        $this->addForeignKey(
            'fk-products-created_by',
            'products',
            'created_by',
            'users',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-products-created_by', 'products');
        $this->dropTable('{{%products}}');
    }
}
