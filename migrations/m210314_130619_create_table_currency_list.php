<?php

use yii\db\Migration;

/**
 * Handles the creation for table `{{%currency_list}}`.
 */
class m210314_130619_create_table_currency_list extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%currency_list}}', [

            'id' => $this->primaryKey()->notNull(),
            'short_name' => $this->string(3)->notNull(),
            'name' => $this->text()->notNull(),
            'is_using' => $this->tinyInteger(1),

        ]);
     }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%currency_list}}');
    }
}
