<?php
//$this->widget('bootstrap.widgets.TbBox', array(
    //'title' => 'Basic Box',
    //'headerIcon' => 'icon-home',
//    'content' => 'My Basic Content (you can use renderPartial here too :))' // $this->renderPartial('_view')
//));
?>
<div class="well well-large span3">
    <div>
    <?php echo CHtml::encode($data->details); ?>
      <p><span>When</span><br />
      <?php echo CHtml::encode(date('Y-m-d',$data->start_datetime));?></p>
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
      <div class="btn-toolbar" >
      <?php
      $this->widget('bootstrap.widgets.TbButton',array(
        'label' => 'Modify',
        'type' => 'primary',
        'url' => $this->createUrl('reminders/update/'.$data->id)
      ));
      $this->widget('bootstrap.widgets.TbButton',array(
        'label' => 'Delete',
        'type' => 'danger',
        'url' => $this->createUrl('reminders/delete/'.$data->id)
      ));
      ?>
      </div>
</div>
