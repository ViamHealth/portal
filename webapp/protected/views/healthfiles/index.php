<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
?>

<?php
$this->breadcrumbs=array(
 'Healthfile',
); ?>


<h1>Health Files</h1>


<?php /*$this->widget('bootstrap.widgets.TbListView',array(
 'dataProvider'=>$model->search(array('user_id'=>$profile_id, 'status'=>'ACTIVE')),
 'itemView'=>'_view',
));*/ ?>

<?php
$gridColumns = array ( 'name','description','updated_at');
$i = 0 ;
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
      'type'=>'striped',
      'dataProvider'=>$model->search(array('user_id'=>$profile_id, 'status'=>'ACTIVE')),
      'template'=>"{items}",
      'columns' => array(
           // 'id',
    array(
      'name'=>'name',
      'header'=>'File Name',
      'type'=>'raw',
      'value'=>'"<a href=\"".$data->get_download_url()."\" >".$data->name."</a></br>"',
    ),
    array(
      'name'=>'description',
      'header'=>'Label',
      'type'=>'text',
      'value'=>'$data->description.$data->getPk()',
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
      'header' => Yii::t('ses', 'Edit'),
      'class'=>'bootstrap.widgets.TbButtonColumn',
      'template' => '{download} {share} {update} {delete}',
      'buttons' => array(
        'download' => array(
            'label' => 'Download',
            'icon' => 'icon-download',
            'url' => '$data->get_download_url()'
        ),
        'share' => array(
            'label' => 'Share',
            'icon' => 'icon-share',
            'url' => '$data->get_download_url()'
        ),
      ),
      'htmlOptions'=>array('style'=>'width: 80px'),
    ),
  ),
));
?>