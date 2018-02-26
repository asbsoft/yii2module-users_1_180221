<?php
    // This view-template inherited from vendor/asbsoft/yii2module-users_0_170112/views/admin/_form.php

    /* @var $this yii\web\View */
    /* @var $model asb\yii2\modules\users_1_180221\models\UserWithAttrs */
    /* @var $rolesModels empty|asb\yii2\modules\users_0_170112\models\AuthAssignment[] */
    /* @var $form yii\widgets\ActiveForm */

    use yii\widgets\ActiveForm;
    use yii\base\Widget;


    $enableClientValidation = false; // disable JS-validation

    $modelAttr = $model->attr;
    if (!$modelAttr) {
        $modelAttr = $this->context->module->model('UserAttr');
    }
    $fakeForm = ActiveForm::begin(); // new fake form - only for generate HTML for additional fields
    $_lastname   = $fakeForm->field($modelAttr, 'lastname')->textInput(['maxlength' => true]);
    $_firstname  = $fakeForm->field($modelAttr, 'firstname')->textInput(['maxlength' => true]);
    $_middlename = $fakeForm->field($modelAttr, 'middlename')->textInput(['maxlength' => true]);
    $_phone      = $fakeForm->field($modelAttr, 'phone')->textInput(['maxlength' => true]);
    //$fakeForm::end(); -- will render empty form
    // deactivate fake form:
    $widget = array_pop(Widget::$stack); // == $fakeForm
    unset($fakeForm);

?>
<?php $this->startParent([ // send variable(s) to parent view-template
          'enableClientValidation' => $enableClientValidation,
      ]); ?>

    <?php $this->startBlock('fields/after-main-fields') ?>
        <div class="col-md-4"><?= $_lastname ?></div>
        <div class="col-md-4"><?= $_firstname ?></div>
        <div class="col-md-4"><?= $_middlename ?></div>
        <?= $_phone ?>
    <?php $this->stopBlock('fields/after-main-fields') ?>

<?php $this->stopParent(); ?>
