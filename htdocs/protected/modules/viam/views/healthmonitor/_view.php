<div class="view">
 <div class="span5" style="border-style: solid; padding: 20px; postion: relative; margin-left: 20px">

      <?php /*
      <?php echo CHtml::encode($data->weight); ?>
        <div class="col" style="margin-top: 10px">
         <p><span>When</span><br />
           <?php //echo CHtml::encode($data->target_date);?></p>
         </div>
         <div class="col">
         <p><span>Unit</span><br />
        	<?php switch(CHtml::encode($data->weight_measure))
		{case 1: echo 'Pound';
       		    break;
 		 case 2: echo 'Kgs';
			    break;
 		 default: echo 'unidentifiable unit';
          break;} ?></p>
         </div>       

       <div class="b_row" style="overflow: hidden; position: relative">
       <p>
       <button class="btn btn-medium btn-primary" type="button">Update</button>
       <button class="btn btn-medium" type="button">Delete</button>
       </p>
       </div>
      */ ?>


<div>
  <?php
  $this->widget('bootstrap.widgets.TbJsonGridView', array(
  'dataProvider' => UserWeightReading::model()->search($data->id),
  //'filter' => $HealthfileModel,
  /*'template' => "{pager}\n{items}\n{pager}",
  'type' => 'striped  condensed',
  'summaryText' => false,
  'cacheTTL' => 10, // cache will be stored 10 seconds (see cacheTTLType)
  'cacheTTLType' => 's', // type can be of seconds, minutes or hours*/
  'columns' => array(
    array(
      'name'=>'weight',
      'header'=>'weight',
      //'type'=>'int',
      //TODO: Need template for Tags
      //TODO: Find better way to show children tags
      'value'=>'$data->weight',
    ),
    array(
      'name'=>'weight_measure',
      'header'=>'weight_measure',
      'type'=>'text',
      //TODO: Need a helper for formatting date
      'value'=>'$data->weight_measure',
    ),
    array(
      'name'=>'reading_date',
      'header'=>'Date',
      //'type'=>'datetime',
      'value'=>'$data->reading_date',
      //'htmlOptions'=>array('width'=>'1000'),
    ),
    /*
    array(
      'header' => Yii::t('ses', 'Actions'),
      'class'=>'bootstrap.widgets.TbJsonButtonColumn',
      'template' => '{download} {share} {update} {delete}',
      'buttons' => array(
        'download' => array(
            'label' => 'Download',
            'icon' => 'icon-download',
            'url' => '$data->stored_url'
            //'url' => 'Yii::app()->createUrl("controller/action", array("id"=>"$data->id"))',
        ),
        'share' => array(
            'label' => 'Share',
            'icon' => 'icon-share',
            'url' => '$data->stored_url'
            //'url' => 'Yii::app()->createUrl("controller/action", array("id"=>"$data->id"))',
        ),
      ),
      'htmlOptions'=>array('style'=>'width: 80px'),
    ),*/
  ),
  ));
  ?>
</div>

 </div>
</div>