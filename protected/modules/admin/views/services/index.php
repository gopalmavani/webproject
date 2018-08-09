<?php
/* @var $this ServicesController */
/* @var $dataProvider CActiveDataProvider */

?>

<h1>Services</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
