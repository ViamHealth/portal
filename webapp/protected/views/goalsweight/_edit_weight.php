<div class="row guttered">
      <div class="span4">
      	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		      'action'=>Yii::app()->createUrl('goals/addweight'),
		      'id'=>'inlineForm',
		      'type'=>'inline',
		      'htmlOptions'=>array('class'=>'span4'),
		)); ?>
		<div>
		      <?php echo $form->textFieldRow($model, 'weight', array('class'=>'span1')); ?>
		      <?php echo $form->datepickerRow($model, 'target_date',
		      array(
		      'prepend'=>'<i class="icon-calendar"></i>',
		      'options'=>array('format'=>'yyyy-mm-dd'),
		      'class'=>'span2'
		      )); 
		      ?>
		      <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Add')); ?>
		</div>
		<?php $this->endWidget(); ?>
		
      </div>
</div>
