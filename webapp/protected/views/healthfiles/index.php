<?php
      
      Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.ui.widget.js');
      Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.iframe-transport.js');
      Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.fileupload.js');
?>
<script>
$(function () {
    $('#fileupload').fileupload({
        dataType: 'json',
        beforeSend: function(xhr) {
                 xhr.setRequestHeader("Authorization", "Token <?php echo Yii::app()->user->token; ?>")
              $('#fileupload-status').show();
            },
        done: function (e, data) {
          $('#fileupload-status').hide();
          var id = data.result.id;
          window.location.replace("<?php echo $this->createUrl('/healthfiles/update/'); ?>"+'/'+id);
        }
    });
});
</script>

<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
?>

<?php
$this->breadcrumbs=array(
 'Healthfile',
); ?>

<input id="fileupload" type="file" name="file" data-url="<?php echo Yii::app()->params['apiBaseUrl'] ?>healthfiles/" >
<div id="fileupload-status" style="display:none;">Uploading..</div>


<?php
$gridColumns = array ( 'name','description','updated_at');
$i = 0 ;
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
      'type'=>'striped',
      'dataProvider'=>$model->search(array('user_id'=>$profile_id, 'status'=>'ACTIVE')),
      'template'=>"{items}",
      'columns' => array(
           //'id',
    array(
      'name'=>'name',
      'header'=>'File Name',
      'type'=>'raw',
      'value'=>'"<a href=\"".$data->get_download_url()."\" >".$data->name."</a></br>"',
    ),
    array(
      'header'=>'Tags',
      'type'=>'raw',
      'value'=>'"<span class=\"get_tags\" data-id=\"".$data->id."\"></span>"'
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
      //'htmlOptions'=>array('style'=>'width: 80px'),
    ),
  ),
));
?>

<script>
$(document).ready(function(){
  $(".get_tags").each(function(index,element){
    var id = $(element).attr('data-id');
    var url = "<?php echo Yii::app()->params['apiBaseUrl'] ?>healthfiles/"+id+"/";
    $.ajax({
      url: url,
      dataType: 'json',
      beforeSend: function(xhr) {
           xhr.setRequestHeader("Authorization", "Token <?php echo Yii::app()->user->token; ?>");
           $(element).html('Loading...');
      },
      success: function(json){
        var tags = json.tags;
        $(element).html(tags.join(","));
      },
    });

  });
});

</script>