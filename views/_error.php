<?php
/** @var $exception \Exception */
/** @var $this \abiz\phpmvc\View */

$this->title = 'Error';
?>
<h3><?= $exception->getCode() . ' - ' . $exception->getMessage() ?></h3>