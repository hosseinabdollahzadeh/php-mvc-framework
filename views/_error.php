<?php
/** @var $exception \Exception */
/** @var $this \app\core\View */

$this->title = 'Error';
?>
<h3><?= $exception->getCode() . ' - ' . $exception->getMessage() ?></h3>