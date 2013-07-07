<?php
/* @var $this SiteController */

$this->pageTitle='Health Files';
?>




<div class="">
    <div class="">
    	<h1>Health Files</h1>
        <a id="upload_pp" class="bnt_upld" href="javascript:void(0);">Upload</a>
    </div>
</div>


<div>
  <?php
  $this->widget('bootstrap.widgets.TbJsonGridView', array(
  'dataProvider' => $HealthfileModel->search(),
  //'filter' => $HealthfileModel,
  'template' => "{pager}\n{items}\n{pager}",
  'type' => 'striped  condensed',
  'summaryText' => false,
  'cacheTTL' => 10, // cache will be stored 10 seconds (see cacheTTLType)
  'cacheTTLType' => 's', // type can be of seconds, minutes or hours
  'columns' => array(
    array(
      'name'=>'name',
      'header'=>'File Name',
      'type'=>'raw',
      //TODO: Need template for Tags
      //TODO: Find better way to show children tags
      'value'=>'"<a href=\"".$data->stored_url."\" >".$data->name."</a></br>".implode(", ",$data->getTagsArray())',
    ),
    array(
      'name'=>'description',
      'header'=>'Label',
      'type'=>'text',
      'value'=>'$data->description',
      //'htmlOptions'=>array('width'=>'1000'),
    ),
    array(
      'name'=>'created_at',
      'header'=>'Date',
      'type'=>'text',
      //TODO: Need a helper for formatting date
      'value'=>'$data->updated_at="0000-00-00 00:00:00"?date_format(date_create($data->created_at),"F j, Y"):date_format(date_create($data->updated_at),"F j, Y")',
    ),
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
    ),
  ),
  ));
  ?>
</div>


