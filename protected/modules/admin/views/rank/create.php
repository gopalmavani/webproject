<?php
/* @var $this RankController */
/* @var $model Rank */
$this->pageTitle = 'Create Rank';
?>
<?php $this->renderPartial('_form', array('model'=>$model, 'rules'=>$rules, 'rulesModel'=>$rulesModel,)); ?>