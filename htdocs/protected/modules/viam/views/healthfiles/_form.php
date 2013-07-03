

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'healthfile-form',
    'type'=>'horizontal',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

  <?php echo $form->textFieldRow($model,'name', array('disabled'=>true)); ?>
  <?php echo $form->textAreaRow($model,'description',array('rows'=>6, 'class'=>'span8')); ?>
  <?php //echo $form->textFieldRow($model,'tagsCsv',array('rows'=>6, 'class'=>'span8','hint'=>'Store tags in CSV Format')); ?>
  
  <div id="children">
        <?php
        //TODO: Bootstrapify child widget
        $index = 0;
        foreach ($model->healthfileTags as $id => $child):
            $this->renderPartial('_form_healthfileTag', array(
                'model' => $child,
                'index' => $id,
                'display' => 'block'
            ));
            $index++;
        endforeach;
        ?>
  </div>
  <?php
    echo CHtml::link('Add Tag', '#', array('id' => 'loadChildByAjax'));
  ?>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton',array(
      'buttonType'=>'submit',
      'type'=>'primary',
      'label'=>'Submit',
    )); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php
//TODO: Secure javscript vars
Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScript('loadchild', '
var _index = ' . $index . ';
var _action_id = "' .$this->action->id. '";
$("#loadChildByAjax").click(function(e){
    e.preventDefault();
    var _url = "' . Yii::app()->controller->createUrl("loadChildByAjax") . '?index="+_index+"&load_for="+_action_id;
    $.ajax({
        url: _url,
        success:function(response){
            $("#children").append(response);
            $("#children .crow").last().animate({
                opacity : 1, 
                left: "+50", 
                height: "toggle"
            });
        }
    });
    _index++;
});
', CClientScript::POS_END);
?>