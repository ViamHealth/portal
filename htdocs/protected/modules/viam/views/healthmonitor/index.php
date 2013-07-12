<?php
/*$assetUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('viam.assets'));
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($assetUrl.'/js/d3.js');
$cs->registerScriptFile($assetUrl.'/js/xcharts.js');
$cs->registerCssFile($assetUrl.'/css/xcharts.css');*/?>

<h1>Goals</h1>

<?php //echo $profile_id;?>
	<div class="toolbar pull-right">
	  <i class="icon-calendar"></i>
      year
      <div class="btn-group">
        <button class="btn">Today</button>
      </div>
      <div class="btn-group">
       <button class="btn">Day</button>
       <button class="btn">Week</button>
       <button class="btn">Month</button>
      </div>
	</div>

<div class="row-fluid">
<?php $this->widget('bootstrap.widgets.TbListView',array(
 'dataProvider'=>UserWeightGoal::model()->search(array('user_id'=>2)),
 //'dataProvider'=>UserWeightReading::model()->search(3),
 'itemView'=>'_view',
)); ?>
</div>