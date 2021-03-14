<?php

use yii\db\Migration;

/**
 * Handles the creation for table `{{%currency_exchange_rate}}`.
 */
class m210314_130619_create_table_currency_exchange_rate extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%currency_exchange_rate}}', [

            'id' => $this->primaryKey()->notNull(),
            'exchange_date' => $this->date()->notNull(),
            'currency_id_from' => $this->integer()->notNull(),
            'currency_id_to' => $this->integer(),
            'h00' => $this->decimal(12,6),
            'h01' => $this->decimal(12,6),
            'h02' => $this->decimal(12,6),
            'h03' => $this->decimal(12,6),
            'h04' => $this->decimal(12,6),
            'h05' => $this->decimal(12,6),
            'h06' => $this->decimal(12,6),
            'h07' => $this->decimal(12,6),
            'h08' => $this->decimal(12,6),
            'h09' => $this->decimal(12,6),
            'h10' => $this->decimal(12,6),
            'h11' => $this->decimal(12,6),
            'h12' => $this->decimal(12,6),
            'h13' => $this->decimal(12,6),
            'h14' => $this->decimal(12,6),
            'h15' => $this->decimal(12,6),
            'h16' => $this->decimal(12,6),
            'h17' => $this->decimal(12,6),
            'h18' => $this->decimal(12,6),
            'h19' => $this->decimal(12,6),
            'h20' => $this->decimal(12,6),
            'h21' => $this->decimal(12,6),
            'h22' => $this->decimal(12,6),
            'h23' => $this->decimal(12,6),

        ]);
     }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%currency_exchange_rate}}');
    }
}
