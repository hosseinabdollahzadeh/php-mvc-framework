<?php
/** @var $this \abiz\phpmvc\View */

/** @var $model \app\models\ContactForm */

use abiz\phpmvc\form\TextareaField;

$this->title = 'Contact';
?>

    <h1>Contact us</h1>

<?php $form = \abiz\phpmvc\form\Form::begin('', 'post') ?>
<?= $form->field($model, 'subject') ?>
<?= $form->field($model, 'email') ?>
<?= new TextareaField($model, 'body') ?>
    <br>
    <button type="submit" class="btn btn-primary">Submit</button>
<?php \abiz\phpmvc\form\Form::end(); ?>