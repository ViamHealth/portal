<style>
.diary-plus {
	background: url(/assets/images/gr-plus-ic.png) no-repeat 3px 3px;
	
}
.exercise-plus {
	background: url(/assets/images/gr-plus-ic.png) no-repeat 3px 3px;
	
}
.dairy-food-item-delete {
	font: 12px Segoe UI, Arial, Helvetica, sans-serif;
color: #ff0000;

text-align: center;
}
.exercise-item-delete {
	font: 12px Segoe UI, Arial, Helvetica, sans-serif;
color: #ff0000;

text-align: center;
}

.table tbody tr.success>td.childhidden {
	background: url(/assets/images/ic-tgle_1.png) no-repeat 5px center #dff0d8;
}
.table tbody tr.success>td.childshown {
	background: url(/assets/images/ic-tgle_2.png) no-repeat 5px center #dff0d8;
}
.table tbody tr>td.childhidden {
	background: url(/assets/images/ic-tgle_1.png) no-repeat 5px center #f9f9f9;
}
.table tbody tr>td.childshown {
	background: url(/assets/images/ic-tgle_2.png) no-repeat 5px center #f9f9f9;
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
<div class="page-header">
  <h1>Food Diary 
  	<div class="pull-right viam_date_parent"  id="sandbox-container">
  		<div class="input-append date">
  		<input type="text" class="viam_date_selector" name="food_diary_date" id="food_diary_date">
  		<span class="add-on glyphicon glyphicon-calendar"></span>
  		</div>
	</div></h1>
</div>


<div class="row">
	<div class="col-md-12" id="total-items">Loading..</div>
	<div class="table-responsive" style="font-size:12px;">
		<table class="table table-condensed table-striped table-bordered" id="diet-table">
	        
		</table>
	</div>
</div>
</div>

<?php $this->load->view('fooddiary/_modal_addfood'); ?>
<?php $this->load->view('fooddiary/_modal_addexercise'); ?>


<script>
var food_items_collection = {};
var total_food_items = 0;
var results_page_size = 6;

var physical_activity = {};

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
	'EXERCISE' :{
		"total_calories" : 0,
		"total_fat" : 0,
		"total_carbs" : 0,
		"total_proteins" : 0,
	},
}

$(document).ready(function(){
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	
	$('#sandbox-container .input-append.date').datepicker({
	    format: "dd MM yyyy",
	    endDate: now,
	    todayBtn: "linked",
	    keyboardNavigation: false,
	    forceParse: false,
	    autoclose: true,
	    todayHighlight: true
	}).on('changeDate', function(ev){
		load_diary(format_date_for_api_from_datepicker(ev.date));
	});;

	$('#sandbox-container .input-append.date').datepicker("setValue", new Date());

	load_diary();
	load_physical_activity_array();


	$("#add_food_item_search").keyup(function(){
		populate_search_data($( this ).val());
	});

	$('#activity_date_parent .input-append.date').datepicker({
	    format: "dd MM yyyy",
	    todayBtn: "linked",
	    endDate: now,
	    keyboardNavigation: false,
	    forceParse: false,
	    autoclose: true,
	    todayHighlight: true
	});

	$('#activity_date_parent .input-append.date').datepicker("setValue", new Date());

	$("#add-exercise-modal").find('.btn-save').on('click',function(e){
		save_upa(e);
	});

});

function save_upa(event){
	event.preventDefault();
	var form = $('#add-exercise-form');
	form.validate();
	if(form.valid()){

		var upa = {};
		upa.physical_activity =  $(form).find('select[name=physical_activity] option:selected').val();
		upa.weight =  $(form).find('input:text[name=weight]').val();
		upa.activity_date =  format_date_for_api_from_datepicker($(form).find('input:text[name=activity_date]').val());
		upa.time_spent =  $(form).find('input:text[name=time_spent]').val();
		var id = $("#add-exercise-modal").find('.btn-save').attr("data_id");
		if(!id){
			_DB.UserPhysicalActivity.create(upa,function(json,success){

				post_upa_save(json,success);
			});
		} else {
			_DB.UserPhysicalActivity.update(id,upa,function(json,success){
				post_upa_save(json,success);
			});
		}
	}
}

function post_upa_save(json,success){
	if(success){
		$('#add-exercise-modal').modal('hide');
		load_exercise_page($("#food_diary_date").val());
	} else {
		$('#add-exercise-modal').modal('hide');
	}
}
function load_diary(diary_date){
	
	$("#diet-table").html('<?php  $this->load->view("fooddiary/_table"); ?>');
	if(!diary_date) {
		var date = new Date();
		var yyyy = date.getFullYear();
		var mm = date.getMonth()+1;
		var dd = date.getDate();
		var diary_date = yyyy + '-' + mm + '-' + dd;
	}
	$("#diet-table").attr("diary-date",diary_date);
	total_food_items = 0;
	set_total_food_items();
	load_diary_page('BREAKFAST',diary_date);
	load_diary_page('LUNCH',diary_date);
	load_diary_page('SNACKS',diary_date);
	load_diary_page('DINNER',diary_date);
	load_exercise_page(diary_date);	

	
	

}

function load_physical_activity_array(){
	_DB.PhysicalActivity.list({},function(json,success){
		if(success){
			if(json.results){
				$.each(json.results,function(i,val){
					physical_activity[val.id] = val;
					var o = new Option(val.label, val.id);
					$(o).html(val.label);
					$("#add-exercise-form select[name=physical_activity]").append(o);
				});

			}
		}
	});
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
					var diary_date = $(elem).parents("table").attr("diary-date");
					

					$(elem).parent().remove();
					load_diary_page(type,diary_date);
				} else {
					show_body_alerts('Could not delete the entry right now. Please try again later.','danger');
				}
			});
		}	
	  }
	}); 

	
}
function delete_exercise_row(elem){
	bootbox.confirm("Delete Entry?", function(result) {
	  if(result){
		var id = $(elem).attr("data-exercise-id");
		if(id){
			_DB.UserPhysicalActivity.destroy(id,function(json,success){
				if(success){
					//total_food_items --;
					var type = $(elem).parent().attr("diary-type");
					var activity_date = format_date_for_api_from_datepicker($(elem).parents("table").attr("diary_date"));
					$(elem).parent().remove();
					load_exercise_page(activity_date);
				}
			});
		}	
	  }
	}); 

	
}
function load_exercise_page(activity_date){
	
	var options = {};
	options.activity_date = activity_date;
	options.page_size = 100;
	_DB.UserPhysicalActivity.list(options,function(json,success){
		
		if(success){
			var predessor = $("#exercise-tr");
			var _t = '<?php $this->load->view("fooddiary/_exercise_row",array()); ?>';
			var data_rows = $(".exercise-data");
			$(data_rows).remove();
			reset_diary_group(predessor);

			if(json.count){
				var _a = $($(predessor).find("td")[0]);
				$(_a).addClass("diary-pahar").addClass("childhidden");
			}
			var data = json.results;

			
			$($(predessor).find(".exercise-plus")[0]).attr('onclick','').unbind('click');
			
            $($(predessor).find(".exercise-plus")[0]).on('click',function(){
            	var data = {};
            	data['meal_type']='EXERCISE';
            	data['activity_date'] = activity_date;
				//$("#add_food_item_search").val('');
            	$("#add-exercise-modal").modal();
            	$("#add-exercise-modal").find('.btn-save').attr("data_id","");
				//$($("#add-exercise-modal").find('.btn-save')[0]).attr('onclick','').unbind('click');
            	

            });

			$.each(data,function(i,upa){
				var _t_elem = $.parseHTML(_t);
				$(predessor).after(_t_elem);
				
				
				$(_t_elem).find(".exercise-item-delete").attr("data-exercise-id",upa.id);
				$(_t_elem).find(".exercise-item-delete").click(function(){
					delete_exercise_row($(this));
				});
				$(_t_elem).find('.exercise-item-name').html(upa.physical_activity.label);
				$(_t_elem).find('.exercise-item-calories').html(parseInt(upa.calories_spent));
				
				var a = total_diet_numbers['EXERCISE'];

				a.total_calories = a.total_calories + parseInt(upa.calories_spent);
				$(predessor).find(".total-calories").html(a.total_calories);
				load_final_analysis();
				$($(predessor).find("td")[0]).attr('onclick','').unbind('click');

				$($(predessor).find("td")[0]).on('click',function(){
	            	if($(this).hasClass("childhidden")){
	            		$(this).removeClass("childhidden");
	            		$(this).addClass("childshown");
	            	} else if($(this).hasClass("childshown")){
	            		$(this).removeClass("childshown");
	            		$(this).addClass("childhidden");
	            	}
	            	
	                var data_rows = $(".exercise-data");
					$(data_rows).attr("diary-type",'EXERCISE');

	            	$.each(data_rows,function(i,val){
	            		
	            		if(!$(val).hasClass("hidden")){
	            			$(val).addClass("hidden");
	            		} else {
	            			$(val).removeClass("hidden");
	            		}
	            	});
	            });

				

			});
		}
		//$("#add-exercise-modal").modal();
		//load_final_analysis();
	});
}
function load_diary_page(meal_type,diary_date){
	
	if(!meal_type) throw "no meal_type";
	var options = {};
	options.meal_type = meal_type;
	options.diet_date = diary_date;
	options.page_size = 100;
	_DB.FoodDiary.list(options,function(json,success){
		set_total_food_items();
		if(success){

			var data = json.results;

			if(meal_type == 'BREAKFAST'){
				var predessor = $("#breakfast-tr");
				var _t = '<?php $this->load->view("fooddiary/_breakfast_row",array()); ?>';
				var data_rows = $(".breakfast-data");
			}
			else if (meal_type == 'LUNCH'){
				var predessor = $("#lunch-tr");
				var _t = '<?php $this->load->view("fooddiary/_lunch_row",array()); ?>';
				var data_rows = $(".lunch-data");
			}
			else if (meal_type == 'SNACKS'){
				var predessor = $("#snacks-tr");
				var _t = '<?php $this->load->view("fooddiary/_snacks_row",array()); ?>';
				var data_rows = $(".snacks-data");
			}
			else if (meal_type == 'DINNER'){
				var predessor = $("#dinner-tr");
				var _t = '<?php $this->load->view("fooddiary/_dinner_row",array()); ?>';
				var data_rows = $(".dinner-data");
			}
			else if (meal_type == 'EXERCISE'){
				var predessor = $("#exercise-tr");
				var _t = '<?php $this->load->view("fooddiary/_exercise_row",array()); ?>';
				var data_rows = $(".exercise-data");
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
            		
            		if(!$(val).hasClass("hidden")){
            			$(val).addClass("hidden");
            		} else {
            			$(val).removeClass("hidden");
            		}
            		//$(val).toggle();
            	});
            });

			$($(predessor).find(".diary-plus")[0]).attr('onclick','').unbind('click');

            $($(predessor).find(".diary-plus")[0]).on('click',function(){
            	var data = {};
            	data['meal_type']=meal_type;
            	data['diary_date'] = diary_date;
				$("#add_food_item_search").val('');
            	$("#add-food-item-modal").modal();
				$($("#add-food-item-modal").find('.btn-save')[0]).attr('onclick','').unbind('click');
            	$("#add-food-item-modal").on("shown",handle_modal_shown(data));

            });
		}
		//load_final_analysis();
	});
}

function handle_modal_shown(data){
	populate_search_data();
	$("#add-food-item-modal").find('.btn-save').attr("meal-type",data['meal_type']);
	$("#add-food-item-modal").find('.btn-save').attr("diet_date",data['diary_date']);
	$("#add-food-item-modal").find('.btn-save').on('click',function(){
		save_new_item(this);
	});
	$('#add-food-item-modal').off('shown', handle_modal_shown);
}
function save_new_item(elem){

	
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
	$('#add_food_quantity_selector').ddslick('destroy');
	$("#add_food_details").hide();

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
	$("#add_food_details").show();
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

	$("#add-food-item-modal").find('.btn-save').attr("itemid",fi.id);
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
	'EXERCISE' :{
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
    load_final_analysis();
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
	$(_t_elem).find('.food-item-calories').html(parseInt(food.calories)*ratio);
	$(_t_elem).find('.food-item-carbs').html(parseInt(food.cholesterol)*ratio);
	$(_t_elem).find('.food-item-fat').html(parseInt(food.total_fat)*ratio);
	$(_t_elem).find('.food-item-protein').html(parseInt(food.sugars)*ratio);
	
	var a = total_diet_numbers[diet.meal_type];

	a.total_calories = a.total_calories + parseFloat(food.calories)*ratio;
	$(predessor).find(".total-calories").html(a.total_calories);

	a.total_fat = a.total_fat + parseFloat(food.total_fat)*ratio;
	$(predessor).find(".total-fat").html(a.total_fat);

	a.total_carbs = a.total_carbs + parseFloat(food.cholesterol)*ratio;
	$(predessor).find(".total-carbs").html(a.total_carbs);

	a.total_proteins = a.total_proteins+ parseFloat(food.sugars)*ratio;
	$(predessor).find(".total-proteins").html(a.total_proteins);

	total_food_items++;
	set_total_food_items();	
	load_final_analysis();
	//events_diet();
	
}
function set_total_food_items(){
	if(total_food_items > 1)
		$("#total-items").html("Total ( "+total_food_items+" Foods )");
	else if (total_food_items <= 1)
		$("#total-items").html("Total ( "+total_food_items+" Food )");
}

function load_final_analysis(){	
	
	$("#gain-tr .total-calories").html(parseInt(total_diet_numbers.BREAKFAST.total_calories) + parseInt(total_diet_numbers.LUNCH.total_calories) +  parseInt(total_diet_numbers.SNACKS.total_calories) + parseInt(total_diet_numbers.DINNER.total_calories)  );
	$("#gain-tr .total-fat").html(parseInt(total_diet_numbers.BREAKFAST.total_fat) + parseInt(total_diet_numbers.LUNCH.total_fat) +  parseInt(total_diet_numbers.SNACKS.total_fat) + parseInt(total_diet_numbers.DINNER.total_fat)  );
	$("#gain-tr .total-carbs").html(parseInt(total_diet_numbers.BREAKFAST.total_carbs) + parseInt(total_diet_numbers.LUNCH.total_carbs) +  parseInt(total_diet_numbers.SNACKS.total_carbs) + parseInt(total_diet_numbers.DINNER.total_carbs)  );
	$("#gain-tr .total-proteins").html(parseInt(total_diet_numbers.BREAKFAST.total_proteins) + parseInt(total_diet_numbers.LUNCH.total_proteins) +  parseInt(total_diet_numbers.SNACKS.total_proteins) + parseInt(total_diet_numbers.DINNER.total_proteins) );

	$("#net-tr .total-calories").html(parseInt(total_diet_numbers.BREAKFAST.total_calories) + parseInt(total_diet_numbers.LUNCH.total_calories) +  parseInt(total_diet_numbers.SNACKS.total_calories) + parseInt(total_diet_numbers.DINNER.total_calories) -  parseInt(total_diet_numbers.EXERCISE.total_calories) );
	$("#net-tr .total-fat").html(parseInt(total_diet_numbers.BREAKFAST.total_fat) + parseInt(total_diet_numbers.LUNCH.total_fat) +  parseInt(total_diet_numbers.SNACKS.total_fat) + parseInt(total_diet_numbers.DINNER.total_fat) -  parseInt(total_diet_numbers.EXERCISE.total_fat) );
	$("#net-tr .total-carbs").html(parseInt(total_diet_numbers.BREAKFAST.total_carbs) + parseInt(total_diet_numbers.LUNCH.total_carbs) +  parseInt(total_diet_numbers.SNACKS.total_carbs) + parseInt(total_diet_numbers.DINNER.total_carbs) -  parseInt(total_diet_numbers.EXERCISE.total_carbs) );
	$("#net-tr .total-proteins").html(parseInt(total_diet_numbers.BREAKFAST.total_proteins) + parseInt(total_diet_numbers.LUNCH.total_proteins) +  parseInt(total_diet_numbers.SNACKS.total_proteins) + parseInt(total_diet_numbers.DINNER.total_proteins) -  parseInt(total_diet_numbers.EXERCISE.total_proteins) );
	
}
</script>
