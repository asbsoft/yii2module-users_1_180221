<?php

namespace asb\yii2\modules\users_1_180221\controllers;

use project\modules\sys\modules\user\models\UserWithAttrs;

use asb\yii2\modules\users_0_170112\controllers\AdminController as BaseAdminController;

/**
 * Backend controller.
 *
 * @author Alexandr Belogolovsky <ab2014box@gmail.com>
 */
class AdminController extends BaseAdminController
{
    protected function findModel($id)
    {
        $userModel = $this->module->model('UserWithAttrs');
        if (($model = $userModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
