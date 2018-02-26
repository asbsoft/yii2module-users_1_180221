<?php

namespace asb\yii2\modules\users_1_180221\models;

use asb\yii2\modules\users_0_170112\models\ProfileForm as BaseProfileForm;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model as YiiBaseModel;

use ReflectionClass;
use ReflectionProperty;
use Exception;

/**
 * Profile form model.
 *
 * @author Alexandr Belogolovsky <ab2014box@gmail.com>
 */
class ProfileForm extends BaseProfileForm
{
    // additional user's attributes
    public $user_id;
    public $lastname;
    public $firstname;
    public $middlename;
    public $phone;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!empty($this->user->attr)) {
            $this->lastname   = $this->user->attr->lastname;
            $this->firstname  = $this->user->attr->firstname;
            $this->middlename = $this->user->attr->middlename;
            $this->phone      = $this->user->attr->phone;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = ArrayHelper::merge(parent::attributeLabels(), [
            'lastname'   => Yii::t($this->tcModule, 'Surname'),
            'firstname'  => Yii::t($this->tcModule, 'Name'),
            'middlename' => Yii::t($this->tcModule, 'Patronim'),
            'phone'      => Yii::t($this->tcModule, 'Phone'),
        ]);
        return $labels;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $userAttrModel = $this->module->model('UserAttr');
        $addRules = $userAttrModel->rules(); // rules for additional attributes
        $rules = parent::rules();
        $rules = ArrayHelper::merge($rules, $addRules);
        return $rules;
    }
    
    /**
     * @inheritdoc
     */
    public function save($isNewRecord)
    {
        $failMsg = Yii::t($this->tcModule, 'Saving unsuccessfull');
        if ($this->validate()) {
            $transaction = Yii::$app->getDb()->beginTransaction();
            try {
                $user = $this->user;
                $userAttrModel = $this->module->model('UserAttr');

                $data = $this->prepareUserData();
                $loaded = $user->load($data, '');
                if ($loaded) {
                    if ($isNewRecord) {
                        $user->scenario = $user::SCENARIO_CREATE;
                        $user->status = $user::STATUS_REGISTERED; // initial status
                        $saved = $user->insert();
                    } else {
                        $saved = $user->update();
                    }
                    if ($saved !== false) { // process additional attributes
                        $userAttr = $userAttrModel::findOne(['user_id' => $user->id]);
                        if (empty($userAttr)) {
                            $userAttr = $userAttrModel;
                        }
                        $data['user_id'] = $user->id;
                        $loadedAttr = $userAttr->load($data, '');
                        if ($loadedAttr) {
                            if ($userAttr->isNewRecord) {
                                $savedAttr = $userAttr->insert();
                            } else {
                                $savedAttr = $userAttr->update();
                            }
                            if ($savedAttr !== false) {
                                $this->auth_key = $user->auth_key;
                                $transaction->commit();
                                return $saved;
                            }
                        }
                        $this->addErrors($userAttr->errors);
                    }
                }
                $this->addErrors($user->errors);
                $transaction->rollBack();
            } catch (Exception $e) {
                $transaction->rollBack();
                $failMsg = Yii::t($this->tcModule, 'Saving unsuccessfull by the reason') . ': ' . $e->getMessage();
            }
        }
        Yii::$app->session->setFlash('error', $failMsg);
        return false;
    }

    /**
     * Prepare data for load to user and additional attributes models.
     * @return array
     */
    protected function prepareUserData()
    {
        $class = new ReflectionClass($this);
        $data = [];
        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $name = $property->getName();
                $data[$property->getName()] = $this->$name;
            }
        }
        $data['password'] = $this->password_new;
        return $data;
    }

}
