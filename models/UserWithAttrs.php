<?php

namespace asb\yii2\modules\users_1_180221\models;

use asb\yii2\modules\users_0_170112\models\UserWithRoles as BaseUser;

use Yii;
use yii\helpers\ArrayHelper;

use Exception;

/**
 * User model with additional attributes.
 *
 * @author Alexandr Belogolovsky <ab2014box@gmail.com>
 */
class UserWithAttrs extends BaseUser
{
    // fields from joined table
    public $lastname;
    public $firstname;
    public $middlename;
    public $phone;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'username' => Yii::t($this->tcModule, 'User login'),
        ]);
    }
    
    protected $_userAttr;
    /**
     * Get additional attributes.
     * @return UserAttr model
     */
    public function getAttr()
    {
        if (empty($this->_userAttr)) {
            $modelUserAttr = $this->module->model('UserAttr');
            $this->_userAttr = $this->hasOne($modelUserAttr::className(), ['user_id' => 'id'])->one();
            if (!$this->_userAttr) {
                $this->_userAttr = $modelUserAttr;
            }
        }
        return $this->_userAttr;
    }
    
    protected static $_usersList;
    /**
     * @return array 'id' => 'Surname (login)'
     */
    public static function usersList()
    {
        if (empty(static::$_usersList)) {
            $modelUserAttr = $this->module->model('UserAttr');
            $attrTable = $modelUserAttr::tableName();
            $models = static::find()
                ->alias('main')
                ->select('main.id, main.email, attr.lastname')
                ->leftJoin("{$attrTable} attr", 'attr.user_id = main.id')
                ->orderBy(['attr.lastname' => SORT_ASC])
                ->where(['not', ['attr.lastname' => null]]) // don't show admins here
                ->andWhere(['main.status' => static::STATUS_ACTIVE])
                ->all();
            $list = [];
            foreach ($models as $model) {
                $userinfo = "({$model->username})"; // login
                if (!empty($model->lastname)) { // lastname may be empty
                    $userinfo = "{$model->lastname} {$userinfo}";
                }
                $list[$model->id] = $userinfo;
            }
            static::$_usersList = $list;
        }
        return static::$_usersList;
    }

    /**
     * @inheritdoc
     * @return int|false the number of rows deleted, or `false` if the deletion is unsuccessful for some reason.
     * @throws Exception in case delete failed.
     */
    public function delete()
    {
        $transaction = static::getDb()->beginTransaction();
        try {
            $n = 0;
            $modelUserAttr = $this->module->model('UserAttr');
            $userAttr = $modelUserAttr::findOne(['user_id' => $this->id]);
            if ($userAttr) {
                $result = $userAttr->delete();

                if ($result === false) {
                    $transaction->rollBack();
                    return false;
                }
                $n += $result;
            }

            $result = parent::delete();
            if ($result === false) {
                $transaction->rollBack();
                return false;
            }
            $n += $result;

            $transaction->commit();
            return $n;
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @inheritdoc
     * @return boolean whether `load()` found the expected form in `$data`.
     */
    public function load($data, $formName = null)
    {
        $result = parent::load($data, $formName);
        if ($formName === '') { // mixed data for both models
            $resultAttr = $this->attr->load($data, '');
        } else {
            $post = Yii::$app->request->post();
            $resultAttr = $this->attr->load($post);
        }
        return ($result && $resultAttr);
    }

    /**
     * @inheritdoc
     * @return bool whether the validation is successful without any error.
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        $result = parent::validate($attributeNames, $clearErrors);
        $resultAttr = $this->attr->validate();
        return ($result && $resultAttr);
    }

    /**
     * @inheritdoc
     * @return boolean whether the saving succeeded (i.e. no validation errors occurred).
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $failMsg = Yii::t($this->tcModule, 'Saving unsuccessfull');
        if ($this->validate()) {
            $transaction = Yii::$app->getDb()->beginTransaction();
            try {
                $result = parent::save($runValidation, $attributeNames);
                if ($result) {
                    $this->attr->user_id = $this->id;
                    $result = $this->attr->save($runValidation);
                }
                if ($result) {
                    $transaction->commit();
                    return true;
                }
                $transaction->rollBack();
            } catch (Exception $e) {
                $transaction->rollBack();
                $failMsg = Yii::t($this->tcModule, 'Saving unsuccessfull by the reason') . ': ' . $e->getMessage();
            }
        }
        Yii::$app->session->setFlash('error', $failMsg);
        return false;
    }

}
