<?php
/* @var $this ResourcesController */
/* @var $dataProvider CActiveDataProvider */

?>

<h1>Resources</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
