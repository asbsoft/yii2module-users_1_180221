<?php

namespace asb\yii2\modules\users_1_180221\controllers;

use asb\yii2\modules\users_0_170112\controllers\MainController as BaseMainController;
use asb\yii2\modules\users_0_170112\models\ProfileForm as BaseProfileForm;

use Yii;

/**
 * Frontend controller.
 *
 * @author Alexandr Belogolovsky <ab2014box@gmail.com>
 */
class MainController extends BaseMainController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if (isset($behaviors['verbs']['actions']['profile'])) {
            unset($behaviors['verbs']['actions']['profile']); // remove POST-verb for profile link
        }
        return $behaviors;
    }

    protected function findModel($condition)
    {
        $model = $this->module->model('UserWithAttrs');
        return $model::findOne($condition);
    }

}
