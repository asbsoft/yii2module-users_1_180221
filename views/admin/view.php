<?php
    // This view-template inherited from vendor/asbsoft/yii2module-users_0_170112/views/admin/view.php 

    /* @var $this yii\web\View */
    /* @var $model User */

    use yii\widgets\DetailView;


    $tc = $this->context->module->tcModule;

?>
<?php $this->startParent(); ?>

    <?php $this->startBlock('after-view') ?>

        <?= DetailView::widget([
            'id' => 'view-user-attr',
            'model' => $model->attr,
            'attributes' => [
                [
                    'label' => Yii::t($tc, 'Additional attribute'),
                    'value' => '',
                    'captionOptions' => ['class' => 'col-md-3'],
                    'contentOptions' => ['class' => 'col-md-9', 'style' => 'font-weight: bold'],
                ],
                'lastname',
                'firstname',
                'middlename',
                'phone',
            ],
        ]) ?>

    <?php $this->stopBlock('after-view') ?>

<?php $this->stopParent(); ?>
