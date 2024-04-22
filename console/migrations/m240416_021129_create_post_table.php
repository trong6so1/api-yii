<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%posts}}`.
 */
class m240416_021129_create_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'body' => 'LONGTEXT',
            'tags' => $this->string(),
            'reactions' => $this->integer()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime()->notNull(),
            'isDeleted' => $this->boolean(),
        ]);
        $this->addForeignKey(
            'fk-post-created_by',
            '{{%post}}',
            'created_by',
            '{{%user}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-post-userID', '{{%post}}');
        $this->dropTable('{{%post}}');
    }
}
