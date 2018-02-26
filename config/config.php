<?php

return [
    // shared models
    'models' => [ // alias => class name or object array
        'LoginForm'     => 'asb\yii2\modules\users_1_180221\models\LoginForm',
        'ProfileForm'   => 'asb\yii2\modules\users_1_180221\models\ProfileForm',
        'UserAttr'      => 'asb\yii2\modules\users_1_180221\models\UserAttr',
        'UserAttrQuery' => 'asb\yii2\modules\users_1_180221\models\UserAttrQuery',
        'UserWithAttrs' => 'asb\yii2\modules\users_1_180221\models\UserWithAttrs',
        'User'          => 'asb\yii2\modules\users_1_180221\models\UserWithAttrs', // !!
    ],
];
