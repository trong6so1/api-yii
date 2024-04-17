<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%posts}}`.
 */
class m240416_021129_create_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%posts}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'body' => 'LONGTEXT',
            'tags' => $this->string()->notNull(),
            'reactions' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'isDeleted' => $this->boolean(),
        ]);
        $this->addForeignKey(
            'fk-posts-created_by',
            '{{%posts}}',
            'created_by',
            '{{%users}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-posts-userID', '{{%posts}}');
        $this->dropTable('{{%posts}}');
    }
}
