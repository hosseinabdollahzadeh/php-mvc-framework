<?php
/** @var $model User */

/** @var $this \app\core\View */

use app\models\User;

$this->title = 'Login';
?>
    <h1>Login</h1>
<?php $form = \app\core\form\Form::begin('', "post") ?>
<?= $form->field($model, 'email') ?>
<?= $form->field($model, 'password')->passwordField() ?>
    <br>
    <button type="submit" class="btn btn-primary">Submit</button>
<?php \app\core\form\Form::end() ?>