<div class="view">

<div class="span4">
     <?php echo CHtml::encode($data->details); ?>
       <div class="col">
        <p><span>When</span><br />
          <?php echo CHtml::encode($data->start_datetime);?></p>
        </div>
        <div class="col">
        <p><span>Repeat</span><br />
        	<?php switch(CHtml::encode($data->repeat_mode))
		{case 1: echo 'Daily';
       		    break;
		 case 2: echo 'Weekly';
			    break;
		 case 3: echo 'Fortnightly';
			    break;
		 case 4: echo 'Monthly';} ?></p>
        </div>       
       <div class="b_row">
      <p>
      <button class="btn btn-large btn-primary" type="button">Update</button>
      <button class="btn btn-large" type="button">Delete</button>
      </p>
       </div>
</div>

<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('details')); ?>:</b>
	<?php echo CHtml::encode($data->details); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_datetime')); ?>:</b>
	<?php echo CHtml::encode($data->start_datetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('repeat_mode')); ?>:</b>
	<?php echo CHtml::encode($data->repeat_mode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('repeat_day')); ?>:</b>
	<?php echo CHtml::encode($data->repeat_day); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('repeat_hour')); ?>:</b>
	<?php echo CHtml::encode($data->repeat_hour); ?>
	<br />

	
	<b><?php echo CHtml::encode($data->getAttributeLabel('repeat_min')); ?>:</b>
	<?php echo CHtml::encode($data->repeat_min); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('repeat_weekday')); ?>:</b>
	<?php echo CHtml::encode($data->repeat_weekday); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('repeat_day_interval')); ?>:</b>
	<?php echo CHtml::encode($data->repeat_day_interval); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated_at')); ?>:</b>
	<?php echo CHtml::encode($data->updated_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated_by')); ?>:</b>
	<?php echo CHtml::encode($data->updated_by); ?>
	<br />

	*/ ?>
</div>