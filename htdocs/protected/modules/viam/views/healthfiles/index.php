<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>




<div class="">
    <div class="">
    	<h1>Health Files</h1>
        <a id="upload_pp" class="bnt_upld" href="javascript:void(0);">Upload</a>
    </div>
</div>


<div id="">
	<div id="l">
		<?php /*$this->widget('EBootstrapSidebar', array(
					'items'=>array(
							array(
								'label' => 
								),
						),
				):
*/
		?>
	</div>
	<div id="	">

		<div class="">
    <div class="">
    <?php $this->widget('bootstrap.widgets.TbGridView', array(
	    'type'=>'striped condensed ',
	    'dataProvider'=>$HealthfileModel->search(array('user_id'=>$profile_id)),
	    'template'=>"{items} {pager}",
	    'enablePagination' => true,
	    //'filter'=>$gridDataProvider,
	    'columns'=>array(
	    	array(
          'name'=>'name',
          'header'=>'File Name',
          'type'=>'raw',
          //TODO: Need template for Tags
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
          'class'=>'bootstrap.widgets.TbButtonColumn',
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
)); ?>
    </div>
  	</div>
	</div>
</div>

