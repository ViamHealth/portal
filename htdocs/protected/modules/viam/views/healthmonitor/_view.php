<?php
$assetUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('viam.assets'));
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($assetUrl.'/js/d3.js');
$cs->registerScriptFile($assetUrl.'/js/xcharts.js');
$cs->registerCssFile($assetUrl.'/css/xcharts.css');
?>

<div class="view">
  <div class="span5" style="border-style: solid; padding: 20px; postion: relative; margin-left: 20px">

      <div>

<?php 
$variable=Yii::app()->db->createCommand("SELECT id,reading_date,weight,weight_measure FROM tbl_user_weight_readings WHERE user_weight_goal_id=$data->id")->queryall();
?>

<figure style=" height: 300px;" id="myChart"></figure>
<script type="text/javascript">
var o = <?php echo json_encode($variable); ?>;
var count = <?php echo count($variable); ?>;
var set = [];
for(i=0;i<count;i++)
{
  set.push({
  x : o[i].reading_date,
  y : o[i].weight
});
}
var data = {
xScale : "ordinal",
yScale : "linear",
yMin: 0,
yMax: 150,
type : "bar",
main :[{
className: ".pizza",
data: set
}],
};
var myChart = new xChart('bar', data, '#myChart');
</script>

    </div>

  </div>
</div>

