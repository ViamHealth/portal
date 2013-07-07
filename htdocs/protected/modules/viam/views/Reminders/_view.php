<div class="view color-yellow">
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
       <button class="btn btn-medium btn-primary" type="button">Update</button>
       <button class="btn btn-medium" type="button">Delete</button>
       </p>
       </div>
</div>
</div>