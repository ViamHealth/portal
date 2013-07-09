<div class="view">
 <div class="span5" style="border-style: solid; padding: 20px; postion: relative; margin-left: 20px">
<div>
      
  <?php
  $this->widget('bootstrap.widgets.TbJsonGridView', array(
  'dataProvider' => UserWeightReading::model()->search(array('user_weight_goal_id'=>$data->id)),
 
  'columns' => array(
    array(
      'name'=>'weight',
      'header'=>'weight',
      'value'=>'$data->weight',
    ),
    array(
      'name'=>'weight_measure',
      'header'=>'weight_measure',
      'type'=>'text',
      'value'=>'$data->weight_measure',
    ),
    array(
      'name'=>'reading_date',
      'header'=>'Date',
      'type'=>'datetime',
      'value'=>'$data->reading_date',
    ),

  ),
  ));
  ?>
</div>

 </div>
</div>

<?php
/*
$assetUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('viam.assets'));
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($assetUrl.'/js/d3.js');
$cs->registerScriptFile($assetUrl.'/js/xcharts.js');
$cs->registerCssFile($assetUrl.'/css/xcharts.css');
?>

<figure style=" height: 300px;" id="myChart"></figure>
<script>
var data_gr = {
  xScale : "ordinal",
  yScale : "linear",
  type : "bar",
  main :[{
    className: ".pizza",
    data: [
    {
      x: $data->reading_date,
      y: 12
    },
    {
      x: "Cheese",
      y: 8
    }
    ]
  }],
  comp :[{
    className: ".pizza",
    type: "line-dotted",
    data: [
    {
      x: "Pepperoni",
      y: 10
    },
    {
      x: "Cheese",
      y: 7
    }
    ]
  }],
};

var myChart = new xChart('bar', data_gr, '#myChart');
</script>*/
?>