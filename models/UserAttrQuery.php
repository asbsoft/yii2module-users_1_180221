<?php

namespace asb\yii2\modules\users_1_180221\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[UserAttr]].
 *
 * @see UserAttr
 */
class UserAttrQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserAttr[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserAttr|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
