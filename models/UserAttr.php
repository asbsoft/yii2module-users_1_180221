<?php

namespace asb\yii2\modules\users_1_180221\models;

use asb\yii2\modules\users_1_180221\Module;

use asb\yii2\common_2_170212\models\DataModel;

use Yii;

/**
 * This is the model class for table "{{%user_attr}}".
 *
 * @property integer $user_id
 * @property string $lastname
 * @property string $firstname
 * @property string $middlename
 * @property string $phone
 */
class UserAttr extends DataModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_attr}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lastname', 'firstname', 'middlename', 'phone'], 'string', 'max' => 255],
            [['lastname', 'firstname', 'middlename'], 'match',
                'pattern' => '/^[\pL][\pL\.\-\ \'\`]+$/ui',
            ],
            [['lastname', 'firstname', 'phone'], 'required'],

            ['user_id', 'integer'],
            ['phone', 'string', 'min' => 7, 'max' => 255],
            ['phone',  'match', 'pattern' => '/^[0-9#\(\)\+\-\*\ ]+$/',
                'message' => Yii::t($this->tcModule, 'Illegal symbol(s) for phone number')
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t($this->tcModule, 'ID'),
            'lastname' => Yii::t($this->tcModule, 'Surname'),
            'firstname' => Yii::t($this->tcModule, 'Name'),
            'middlename' => Yii::t($this->tcModule, 'Patronim'),
            'phone' => Yii::t($this->tcModule, 'Phone'),
        ];
    }

    /**
     * @inheritdoc
     * @return UserAttrQuery the active query used by this AR class.
     */
    public static function find()
    {
        $module = Module::getModuleByClassname(Module::className());
        $queryModel = $module->model('UserAttrQuery', [get_called_class()]);
        return $queryModel;
    }
}
