<?php

use yii\db\Migration;

class m210519_094336_apples extends Migration
{

    public function up()
    {
        $this->createTable('apples', [
            'id' => $this->primaryKey(),
            'color' => $this->string(50)->notNull(),
            'size' => $this->float()->defaultValue(1),
            'status' => $this->integer()->defaultValue(1),
            'data_create' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'data_drop' => $this->timestamp()->defaultValue(NULL),
        ]);
    }

    public function down()
    {
        $this->dropTable('apples');
    }

}
