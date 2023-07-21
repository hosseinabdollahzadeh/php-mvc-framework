<?php
/** @var $model User */

/** @var $this \app\core\View */

use app\models\User;

$this->title = 'Register';
?>
    <h1>Create an account</h1>
<?php $form = \app\core\form\Form::begin('', "post") ?>
    <div class="row">
        <div class="col">
            <?= $form->field($model, 'first_name') ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'last_name') ?>
        </div>
    </div>

<?= $form->field($model, 'email') ?>
<?= $form->field($model, 'password')->passwordField() ?>
<?= $form->field($model, 'confirmPassword')->passwordField() ?>
    <br>
    <button type="submit" class="btn btn-primary">Submit</button>
<?php \app\core\form\Form::end() ?>