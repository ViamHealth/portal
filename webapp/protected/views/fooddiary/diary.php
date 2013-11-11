<?php
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Food Diary',
);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.validate.min.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/bootbox.min.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/bootstrap-datepicker.js');

?>
<style>
.diary-plus {
	background: url(/images/gr-plus-ic.png) no-repeat 3px 3px;
	width:17px;
}
.dairy-food-item-delete {
	font: 12px Segoe UI, Arial, Helvetica, sans-serif;
color: #ff0000;
width:17px;
text-align: center;
}
.table tbody tr.success>td.diary-pahar {
	padding-left:20px;background: url(/images/ic-tgle_1.png) no-repeat 5px center #dff0d8;
}
.table tbody tr>td.diary-pahar {
	padding-left:20px;background: url(/images/ic-tgle_1.png) no-repeat 5px center;
}
#total-items {
	font-size: 10px;
}
</style>
<div>
<div class="row-fluid">
	<h5><div class="span9">Food Diary</div>
	<div class="span3 pull-right">
		<input style="width: 138px;
	  height: 18px;
	  font: 12px Segoe UI, Arial, Helvetica, sans-serif;
	  color: #333;
	  line-height: 18px;
	  padding: 0 11px;
	  border: 0 none;
	  background: url(/images/sprite-img.png) no-repeat 0 -581px;
	  margin: 0 0 5px 0;
	  cursor: pointer;
	  box-shadow: none;
	  "
	          name="food_diary_date" class="disp_dp fld_box hasDatepicker" 
	          type="text" value="" id="food_diary_date" data-date-format="yyyy-mm-dd">
	</div>
	</h5>
</div>
<div class="row-fluid">
	<div class="span12" id="total-items">Loading..</div>
	<div class="row-fluid" style="font-size:12px;">
		<table class="table table-condensed table-striped table-bordered" id="diet-table">
	        
		</table>
	</div>
</div>
</div>
<script>
var food_items_collection = {};
var total_food_items = 0;

var total_diet_numbers = {
	'BREAKFAST' : {
	"total_calories" : 0,
	"total_fat" : 0,
	"total_carbs" : 0,
	"total_proteins" : 0,
},
	'LUNCH' : {
	"total_calories" : 0,
	"total_fat" : 0,
	"total_carbs" : 0,
	"total_proteins" : 0,
},
	'SNACKS' : {
	"total_calories" : 0,
	"total_fat" : 0,
	"total_carbs" : 0,
	"total_proteins" : 0,
},
	'DINNER' :{
	"total_calories" : 0,
	"total_fat" : 0,
	"total_carbs" : 0,
	"total_proteins" : 0,
},
}
$(document).ready(function(){
	

	//breakfast-data
	/*Custom Spinner*/
	$(function() {
		/*Date Picker Script*/
		$(function() {
			var nowTemp = new Date();
			var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
			var set_date = $(".hasDatepicker").datepicker({
				showOtherMonths: true,
				selectOtherMonths: true,
				"dateFormat": "YYYY-MM-DD",
				/* onRender: function(date) {
					return date.valueOf() < now.valueOf() ? 'disabled' : '';
				}*/
			}).on('changeDate', function(ev){
				var date = new Date(ev.date);
				
				var yyyy = date.getFullYear();
				var mm = date.getMonth()+1;
				var dd = date.getDate();
				var formattedTime = yyyy + '-' + mm + '-' + dd;
				load_diary(formattedTime);
				//set_date.hide();
			});
			$(".fld_box").datepicker("setValue", new Date());
		});
	});

	
	load_diary();
	//events_diet();
	
});

function events_diet(){
	$(".breakfast-diary").click(function(){
		toggle_data_row(this);
	});
	$(".lunch-diary").click(function(){
		toggle_data_row(this);
	});
	$(".snacks-diary").click(function(){
		toggle_data_row(this);
	});
	$(".dinner-diary").click(function(){
		toggle_data_row(this);
	});
}
function toggle_data_row(e){
	var elem = $(e).find('.diary-pahar');
	if($(elem).hasClass("hide"))
		$(elem).removeClass("hide");
	else
		 $(elem).addClass("hide");
}
function load_diary(dairy_date){
	
	$("#diet-table").html('<?php  $this->renderPartial("_table",array()); ?>');
	if(!dairy_date) {
		var date = new Date();
		var yyyy = date.getFullYear();
		var mm = date.getMonth()+1;
		var dd = date.getDate();
		var dairy_date = yyyy + '-' + mm + '-' + dd;
	}
		
	set_total_food_items();
	load_diary_page('BREAKFAST',dairy_date);
	load_diary_page('LUNCH',dairy_date);
	load_diary_page('SNACKS',dairy_date);
	load_diary_page('DINNER',dairy_date);

}
function delete_diary_row(elem){
	bootbox.confirm("Delete Entry?", function(result) {
	  //Example.show("Confirm result: "+result);
	  if(result){
		var id = $(elem).attr("data-diet-id");
		if(id){
			_DB.FoodDiary.destroy(id,function(json,success){
				if(success){
					$(elem).parent().remove();
					set_total_food_items();
				}
			});
		}	
	  }
	}); 

	
}

function load_diary_page(meal_type,dairy_date){
	
	if(!meal_type) return false;
	var options = {};
	options.meal_type = meal_type;
	options.diet_date = dairy_date;
	options.page_size = 100;
	_DB.FoodDiary.list(options,function(json,success){
		set_total_food_items();
		if(success && json.count){
			var data = json.results;

			if(meal_type == 'BREAKFAST'){
				var predessor = $("#breakfast-tr");
				var data_rows = $(".breakfast-data");
				var _t = '<?php $this->renderPartial("_breakfast_row",array()); ?>';
			}
			else if (meal_type == 'LUNCH'){
				var predessor = $("#lunch-tr");
				var data_rows = $(".lunch-data");
				var _t = '<?php $this->renderPartial("_lunch_row",array()); ?>';
			}
			else if (meal_type == 'SNACKS'){
				var predessor = $("#snacks-tr");
				var data_rows = $(".snacks-data");
				var _t = '<?php $this->renderPartial("_snacks_row",array()); ?>';
			}
			else if (meal_type == 'DINNER'){
				var predessor = $("#dinner-tr");
				var data_rows = $(".dinner-data");
				var _t = '<?php $this->renderPartial("_dinner_row",array()); ?>';
			}
			
			
			$.each(data,function(i,val){
				var _t_elem = $.parseHTML(_t);
				$(predessor).after(_t_elem);
				
				if(meal_type == 'BREAKFAST'){
                        var data_rows = $(".breakfast-data");
                }
                else if (meal_type == 'LUNCH'){
                        var data_rows = $(".lunch-data");
                }
                else if (meal_type == 'SNACKS'){
                        var data_rows = $(".snacks-data");
                }
                else if (meal_type == 'DINNER'){
                        var data_rows = $(".dinner-data");
                }

                $($(predessor).find("td")[0]).addClass("diary-pahar").on('click',function(){
                        $(data_rows).toggle();
                });

				var food_item_id = val.food_item;
				if(food_items_collection.food_item_id){
					var f = food_items_collection.food_item_id;
					load_diary_rows(val,f,predessor,_t_elem);
				} else {
					_DB.FoodItems.retrieve(food_item_id,function(f,success){
						food_items_collection[food_item_id] = f;
						if(success){
							load_diary_rows(val,f,predessor,_t_elem);
						}
					});	
				}
			});

		}
	});

}
function load_diary_rows(diet,food,predessor,_t_elem){
	
	var ratio = parseFloat(diet.food_quantity_multiplier)/parseFloat(food.quantity);
	$(_t_elem).find(".dairy-food-item-delete").attr("data-diet-id",diet.id);
	$(_t_elem).find(".dairy-food-item-delete").click(function(){
		delete_diary_row($(this));
	});
	$(_t_elem).find('.food-item-name').html(food.name);
	$(_t_elem).find('.food-item-calories').html(parseFloat(food.calories)*ratio);
	$(_t_elem).find('.food-item-carbs').html(parseFloat(food.total_carbohydrates)*ratio);
	$(_t_elem).find('.food-item-fat').html(parseFloat(food.total_fat)*ratio);
	$(_t_elem).find('.food-item-protein').html(parseFloat(food.protein)*ratio);
	
	var a = total_diet_numbers[diet.meal_type];

	a.total_calories = a.total_calories + parseFloat(food.calories)*ratio;
	$(predessor).find(".total-calories").html(a.total_calories);

	a.total_fat = a.total_fat + parseFloat(food.total_fat)*ratio;
	$(predessor).find(".total-fat").html(a.total_fat);

	a.total_carbs = a.total_carbs + parseFloat(food.total_carbohydrates)*ratio;
	$(predessor).find(".total-carbs").html(a.total_carbs);

	a.total_proteins = a.total_proteins+ parseFloat(food.protein)*ratio;
	$(predessor).find(".total-proteins").html(a.total_proteins);

	total_food_items++;
	set_total_food_items();	
	events_diet();
	
}
function set_total_food_items(){
	if(total_food_items > 1)
		$("#total-items").html("Total ( "+total_food_items+" Foods )");
	else if (total_food_items <= 1)
		$("#total-items").html("Total ( "+total_food_items+" Food )");
}
</script>
