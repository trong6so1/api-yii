<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products}}`.
 */
class m240416_021138_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => 'LONGTEXT',
            'price' => $this->double()->notNull(),
            'stock' => $this->integer()->notNull()->defaultValue(0),
            'category' => $this->string()->notNull(),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'isDeleted' => $this->boolean()
        ]);
        $this->addForeignKey(
            'fk-product-created_by',
            'product',
            'created_by',
            'user',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-product-created_by', 'product');
        $this->dropTable('{{%product}}');
    }
}
