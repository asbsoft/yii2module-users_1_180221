<?php
    // This view-template inherited from vendor/asbsoft/yii2module-users_0_170112/views/main/profile-form.php

    /* @var $this yii\web\View */
    /* @var $model ProfileForm */

    use yii\widgets\ActiveForm;
    use yii\base\Widget;


    $enableClientValidation = false; // disable JS-validation

    $fakeForm = ActiveForm::begin([ // new fake form - only for generate HTML for additional fields
         'enableClientValidation' => $enableClientValidation,
    ]);
    $_lastname   = $fakeForm->field($model, 'lastname')->textInput(['maxlength' => true]);
    $_firstname  = $fakeForm->field($model, 'firstname')->textInput(['maxlength' => true]);
    $_middlename = $fakeForm->field($model, 'middlename')->textInput(['maxlength' => true]);
    $_phone      = $fakeForm->field($model, 'phone')->textInput(['maxlength' => true]);
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
