<?php
//$this->widget('bootstrap.widgets.TbBox', array(
    //'title' => 'Basic Box',
    //'headerIcon' => 'icon-home',
//    'content' => 'My Basic Content (you can use renderPartial here too :))' // $this->renderPartial('_view')
//));
?>
<div class="well well-large span3">
    <div>
    <?php echo CHtml::encode($data->name); ?>
    <?php echo CHtml::encode($data->description); ?>
    </div>
    <div class="btn-toolbar" >
    <?php
    $this->widget('bootstrap.widgets.TbButton',array(
      'label' => 'Modify',
      'type' => 'primary',
      'url' => $this->createUrl('healthfiles/update/'.$data->id)
    ));
    $this->widget('bootstrap.widgets.TbButton',array(
      'label' => 'Delete',
      'type' => 'danger',
      'url' => $this->createUrl('healthfiles/delete/'.$data->id)
    ));
    ?>
    </div>
</div>
