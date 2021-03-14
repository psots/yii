<?php

use yii\db\Migration;

/**
 * Handles the creation for table `{{%currency_exchange_rate_for_day}}`.
 */
class m210314_130619_create_table_currency_exchange_rate_for_day extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%currency_exchange_rate_for_day}}', [

            'id' => $this->primaryKey()->notNull(),
            'exchange_date' => $this->date()->notNull(),
            'currency_id_from' => $this->integer()->notNull(),
            'currency_id_to' => $this->integer()->notNull(),
            'rate' => $this->decimal(11,6)->notNull(),

        ]);
     }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%currency_exchange_rate_for_day}}');
    }
}
