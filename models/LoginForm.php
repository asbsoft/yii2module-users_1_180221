<?php

namespace asb\yii2\modules\users_1_180221\models;

use asb\yii2\modules\users_0_170112\models\LoginForm as BaseLoginForm;

use yii\helpers\ArrayHelper;
use Yii;

/**
 * Login form model.
 *
 * @author Alexandr Belogolovsky <ab2014box@gmail.com>
 */
class LoginForm extends BaseLoginForm
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $user = $this->module->model('UserWithAttrs');
        $labels = ArrayHelper::merge($labels, $user->attributeLabels());
        $labels = ArrayHelper::merge($labels, [
            'username' => Yii::t($this->tcModule, 'Login or email'),
        ]);
        return $labels;
    }

    /**
     * @inheritdoc
     * Find user by [[username]] or [[email]]
     * @return UserIdentity|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $user = $this->module->model('UserIdentity');
            if (strstr($this->username, '@') !== false) {
                $this->_user = $user::findByEmail($this->username);
            } else {
                $this->_user = $user::findByUsername($this->username);
            }
        }
        return $this->_user;
    }

}
