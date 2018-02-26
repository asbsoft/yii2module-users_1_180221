<?php

use asb\yii2\modules\users_1_180221\models\UserAttr as Model;

use yii\db\Migration;

class m180221_145300_create_user_attr_table extends Migration
{
    protected $tableName;
    protected $idxNamePrefix;

    public function init()
    {
        parent::init();

        $this->tableName = Model::tableName();
        $this->idxNamePrefix = 'idx-' . Model::baseTableName();
    }

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'user_id'    => $this->primaryKey(),
            'firstname'  => $this->string()->notNull(),
            'middlename' => $this->string()->notNull(),
            'lastname'   => $this->string()->notNull(),
            'phone'      => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex("{$this->idxNamePrefix}-user_id",  $this->tableName, 'user_id');
        $this->createIndex("{$this->idxNamePrefix}-lastname", $this->tableName, 'lastname');
        $this->createIndex("{$this->idxNamePrefix}-phone",    $this->tableName, 'phone');
    }

    public function safeDown()
    {
        $this->dropIndex("{$this->idxNamePrefix}-phone",    $this->tableName);
        $this->dropIndex("{$this->idxNamePrefix}-lastname", $this->tableName);
        $this->dropIndex("{$this->idxNamePrefix}-user_id",  $this->tableName);
        $this->dropTable($this->tableName);
    }
}
