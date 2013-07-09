<div class="view" style="margin: auto">
 <div class="span4" style="border-radius: 5px #ccc; border: 1px solid #ccc; postion: relative; margin-left: 20px; margin-top: 10px; height: 230px">
    <div class="box-container" style="height: 200px; background: #eee">
      <div class="t_row" style="padding: 20px; background: #ffffff; height: 150px">
      <?php echo CHtml::encode($data->details); ?>
        <div class="col" style="margin-top: 10px">
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
      </div>

       <div class="b_row" style="overflow: hidden; position: relative; height: 50px"> 
       <div class="btn-toolbar" style="margin-left: 10px; margin-top: 5px">
       <div class="btn btn-warning">Modify</div>
       <div class="btn btn-danger">Delete</div>  
       </div>
       </div>

     </div>
 </div>
</div>