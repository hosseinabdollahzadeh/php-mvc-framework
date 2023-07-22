<?php
/** @var $model User */

/** @var $this \abiz\phpmvc\View */

use app\models\User;

$this->title = 'Login';
?>
    <h1>Login</h1>
<?php $form = \abiz\phpmvc\form\Form::begin('', "post") ?>
<?= $form->field($model, 'email') ?>
<?= $form->field($model, 'password')->passwordField() ?>
    <br>
    <button type="submit" class="btn btn-primary">Submit</button>
<?php \abiz\phpmvc\form\Form::end() ?>