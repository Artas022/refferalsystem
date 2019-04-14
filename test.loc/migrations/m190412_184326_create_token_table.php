<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%token}}`.
 */
class m190412_184326_create_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%token}}', [
            'id' => $this->primaryKey(),
            // кто получил приглашение
            'ref_id' => $this->integer()->unique(),
            // кто отправил приглашение
            'user_id' => $this->integer(),
        ]);
        $this->addForeignKey(
          'user_token',
            'token',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }




    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%token}}');
    }
}
