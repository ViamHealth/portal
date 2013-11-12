<?php
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Food Diary',
);
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.ui.widget.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.validate.min.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/bootbox.min.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/bootstrap-datepicker.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.ddslick.min.js');

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
.table tbody tr.success>td.childhidden {
	background: url(/images/ic-tgle_1.png) no-repeat 5px center #dff0d8;
}
.table tbody tr.success>td.childshown {
	background: url(/images/ic-tgle_2.png) no-repeat 5px center #dff0d8;
}
.table tbody tr>td.childhidden {
	background: url(/images/ic-tgle_1.png) no-repeat 5px center #f9f9f9;
}
.table tbody tr>td.childshown {
	background: url(/images/ic-tgle_2.png) no-repeat 5px center #f9f9f9;
}
.table tbody tr.success>td.diary-pahar {
	padding-left:20px;
}
.table tbody tr>td.diary-pahar {
	padding-left:20px;
}
#total-items {
	font-size: 10px;
}
#add_food_quantity_selector label
{font-size: 12px;
line-height: 10px;
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
<?php  $this->renderPartial("_modal_addfood",array()); ?>

<script>
var food_items_collection = {};
var total_food_items = 0;
var results_page_size = 6;

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

	$("#add_food_item_search").keyup(function(){
		populate_search_data($( this ).val());
	});
	
});

function load_diary(dairy_date){
	
	$("#diet-table").html('<?php  $this->renderPartial("_table",array()); ?>');
	if(!dairy_date) {
		var date = new Date();
		var yyyy = date.getFullYear();
		var mm = date.getMonth()+1;
		var dd = date.getDate();
		var dairy_date = yyyy + '-' + mm + '-' + dd;
	}
	$("#diet-table").attr("diary-date",dairy_date);
	total_food_items = 0;
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
					total_food_items --;
					var type = $(elem).parent().attr("diary-type");
					var dairy_date = $(elem).parents("table").attr("diary-date");
					

					$(elem).parent().remove();
					load_diary_page(type,dairy_date);

					//set_total_food_items();
				}
			});
		}	
	  }
	}); 

	
}

function load_diary_page(meal_type,dairy_date){
	
	if(!meal_type) throw "no meal_type";
	var options = {};
	options.meal_type = meal_type;
	options.diet_date = dairy_date;
	options.page_size = 100;
	_DB.FoodDiary.list(options,function(json,success){
		set_total_food_items();
		if(success){

			var data = json.results;

			if(meal_type == 'BREAKFAST'){
				var predessor = $("#breakfast-tr");
				var _t = '<?php $this->renderPartial("_breakfast_row",array()); ?>';
				var data_rows = $(".breakfast-data");
			}
			else if (meal_type == 'LUNCH'){
				var predessor = $("#lunch-tr");
				var _t = '<?php $this->renderPartial("_lunch_row",array()); ?>';
				var data_rows = $(".lunch-data");
			}
			else if (meal_type == 'SNACKS'){
				var predessor = $("#snacks-tr");
				var _t = '<?php $this->renderPartial("_snacks_row",array()); ?>';
				var data_rows = $(".snacks-data");
			}
			else if (meal_type == 'DINNER'){
				var predessor = $("#dinner-tr");
				var _t = '<?php $this->renderPartial("_dinner_row",array()); ?>';
				var data_rows = $(".dinner-data");
			}
			//$(predessor).html('');
			$(data_rows).remove();
			reset_diary_group(predessor);
			
			if(json.count){
				var _a = $($(predessor).find("td")[0]);
				$(_a).addClass("diary-pahar").addClass("childhidden");
			}
			$.each(data,function(i,val){
				var _t_elem = $.parseHTML(_t);
				$(predessor).after(_t_elem);
				

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
			
			$($(predessor).find("td")[0]).attr('onclick','').unbind('click');

			$($(predessor).find("td")[0]).on('click',function(){
            	if($(this).hasClass("childhidden")){
            		$(this).removeClass("childhidden");
            		$(this).addClass("childshown");
            	} else if($(this).hasClass("childshown")){
            		$(this).removeClass("childshown");
            		$(this).addClass("childhidden");
            	}
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
				$(data_rows).attr("diary-type",meal_type);

            	$.each(data_rows,function(i,val){
            		$(val).toggle();
            	});
            });

			$($(predessor).find(".diary-plus")[0]).attr('onclick','').unbind('click');

            $($(predessor).find(".diary-plus")[0]).on('click',function(){
            	var data = {};
            	data['meal_type']=meal_type;
            	data['dairy_date'] = dairy_date;
		$("#add_food_item_search").val('');
            	$("#add-food-item-modal").modal();
		$($("#add-food-item-modal").find('.save')[0]).attr('onclick','').unbind('click');
            	$("#add-food-item-modal").on("shown",handle_modal_shown(data));

            });
		}
	});
}

function handle_modal_shown(data){
	populate_search_data();
	$("#add-food-item-modal").find('.save').attr("meal-type",data['meal_type']);
	$("#add-food-item-modal").find('.save').attr("diet_date",data['dairy_date']);
	$("#add-food-item-modal").find('.save').on('click',function(){
		save_new_item(this);
	});
	$('#add-food-item-modal').off('shown', handle_modal_shown);
}
function save_new_item(elem){

	console.log('save called');
	var meal_type = $(elem).attr("meal-type");
	var food_item_id = $(elem).attr("itemid");
	var ddData = $('#add_food_quantity_selector').data('ddslick');
	var food_quantity_multiplier = ddData.selectedData.value;
	var diet_date = $(elem).attr("diet_date");

	var data = {};
	data['meal_type'] = meal_type;
	data['food_item'] = food_item_id;
	data['food_quantity_multiplier'] = food_quantity_multiplier;
	data['diet_date'] = diet_date;
	$(elem).attr('onclick','').unbind('click');
	$("#add-food-item-modal").modal('hide');
	_DB.FoodDiary.create(data, function(json,success){
		if(success){

			load_diary_page(meal_type,diet_date);
			
		}
	});

}
function populate_search_data(keyword){
	var tim = new Date().getTime();
	$('#add_food_results').attr('loading_time',tim);
	if(!keyword) keyword = '';
	var options = {};
	options['page_size'] = results_page_size;
	options['search'] = keyword;
	$('#add_food_results').html('');
	console.log($('#add_food_results').html());
	_DB.FoodItems.search(options,function(json,success){
		if($('#add_food_results').attr('loading_time')!=tim)
			return ;

		if(success){

			var _ft = '<div class="fi_result" ><div class="fi_name"></div></div>';
			var loaded = false;

			$.each(json.results,function(i,val){

			if($('#add_food_results').attr('loading_time')!=tim)
				return ;

				food_items_collection[val.id] = val;

				var _ft_elem = $.parseHTML(_ft);
				$(_ft_elem).find(".fi_name").html(val.name);
				

				$(_ft_elem).on('click', function(){
					load_search_detail(val);
					$("#add_food_results").find(".fi_result").removeClass("active");
					$(this).addClass("active");
				});

				$('#add_food_results').append(_ft_elem);
				if(!loaded){
					loaded = true;
					load_search_detail(val);
				}
				
			});
			$($("#add_food_results").find(".fi_result")[0]).addClass("active");
		}						
	});
}
function load_search_detail(fi){
	$('#add_food_quantity_selector').ddslick('destroy');
	var k = $("#add_food_details");
	$(k).find(".name").html(fi.name);
	$(k).find(".calories").html(fi.calories+' '+fi.calories_unit);
	$(k).find(".total_fat").html(fi.total_fat+' '+fi.total_fat_unit);

	$(k).find(".total_carbohydrates").html(fi.total_carbohydrates+' '+fi.total_carbohydrates_unit);
	$(k).find(".protein").html(fi.protein+' '+fi.protein_unit);
	$(k).find(".sugars").html(fi.sugars+' '+fi.sugars_unit);
	$(k).find(".cholesterol").html(fi.cholesterol+' '+fi.cholesterol_unit);
	$(k).find(".sodium").html(fi.sodium+' '+fi.sodium_unit);
	$(k).find(".potassium").html(fi.potassium+' '+fi.potassium_unit);
	$(k).find(".saturated_fat").html(fi.saturated_fat+' '+fi.saturated_fat_unit);
	$(k).find(".polyunsaturated_fat").html(fi.polyunsaturated_fat+' '+fi.polyunsaturated_fat_unit);
	$(k).find(".dietary_fiber").html(fi.dietary_fiber+' '+fi.dietary_fiber_unit);
	$(k).find(".trans_fat").html(fi.trans_fat+' '+fi.trans_fat_unit);
	$(k).find(".vitamin_a").html(fi.vitamin_a+' '+fi.vitamin_a_unit);
	$(k).find(".calcium").html(fi.calcium+' '+fi.calcium_unit);
	$(k).find(".vitamin_c").html(fi.vitamin_c+' '+fi.vitamin_c_unit);
	$(k).find(".iron").html(fi.iron+' '+fi.iron_unit);
	

	var q = fi.quantity;
	var q_u = fi.quantity_unit;

	var ddData = [];
	for (var i = 1; i <= 5; i++) {
		var a = {
			text:  i*parseFloat(q) + " "+q_u,
			value:  i,
		}
		ddData.push(a);
	};
	
	$('#add_food_quantity_selector').ddslick({
	    data:ddData,
	    width:100,
	    selectText: "Servings",
	    defaultSelectedIndex:0,
	    //imagePosition:"left",
	    onSelected: function(selectedData){
	        //callback function: do something with selectedData;
	    }   
	});	

	$("#add-food-item-modal").find('.save').attr("itemid",fi.id);
}

function reset_diary_group(predessor){
	total_diet_numbers = {
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
	var _a = $($(predessor).find("td")[0]);
	$(_a).removeClass("diary-pahar").removeClass("childhidden").removeClass("childshown");
	$(predessor).find(".total-calories").html('');
	$(predessor).find(".total-fat").html('');
	$(predessor).find(".total-carbs").html('');
	$(predessor).find(".total-proteins").html('');
	$($(predessor).find("td")[0]).removeClass("childshown").removeClass("childhidden");

    $($(predessor).find(".diary-plus")[0]).attr('onclick','').unbind('click');
}
function load_diary_rows(diet,food,predessor,_t_elem){
	//console.log('here');
	//var ratio = parseFloat(diet.food_quantity_multiplier)/parseFloat(food.quantity);
	var ratio = parseFloat(diet.food_quantity_multiplier);
	$(_t_elem).find(".dairy-food-item-delete").attr("data-diet-id",diet.id);
	$(_t_elem).find(".dairy-food-item-delete").click(function(){
		delete_diary_row($(this));
	});
	$(_t_elem).find('.food-item-name').html(food.name);
	$(_t_elem).find('.food-item-calories').html(parseFloat(food.calories)/ratio);
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
	//events_diet();
	
}
function set_total_food_items(){
	if(total_food_items > 1)
		$("#total-items").html("Total ( "+total_food_items+" Foods )");
	else if (total_food_items <= 1)
		$("#total-items").html("Total ( "+total_food_items+" Food )");
}
</script>
