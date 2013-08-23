<?php
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Food Diary',
);
?>
<div>
<div class="row-fluid">
  <div class="span4 offset4">
  	<table class="table table-striped table-hover " id="breakfast-food-diary" type="BREAKFAST">
  		<thead>
  			<tr class="info">
  				<th class="item-label">Breakfast</th>
  				<th class="total-calories">340</th>
  				<th class="toggle-diary-table text-right">+</th>
  			</tr>
  		</thead>
  		<tbody>
  			<tr class="info">
  				<td >Items</td>
  				<td>Calories</td>
  				<td class="add-item">Add</td>
  			</tr>
  		</tbody>
  	</table>
  	<table class="table table-striped table-hover" id="lunch-food-diary" type="LUNCH">
  		<thead>
  			<tr class="info">
  				<th class="item-label">Lunch</th>
  				<th class="total-calories">340</th>
  				<th class="toggle-diary-table text-right" >+</th>
  			</tr>
  		</thead>
  		<tbody style="display:none">
  			<tr class="info">
  				<td>Items</td>
  				<td>Calories</td>
  				<td class="add-item">Add</td>
  			</tr>
  		</tbody>
  	</table>
  </div>
  <div class="span4">
  	<div id="item-adder" style="display:none;">
  		<div>
  			Add <span class="add-type"></span>  
  			<input type="text" class="input-medium search-query search-item">
  			<button type="submit" class="btn search-query-button">Search</button>
  		</div>
  		<table class="table table-striped table-hover" >
  			<thead>
  				<tr class="info">
  					<th>All Foods</th>
  					<th>

  					</th>
  				</tr>
  			</thead>
  			<tbody>
  				
  			</tbody>
  		</table>
  	</div>
  </div>
</div>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Modal header</h3>
  </div>
  <div class="modal-body" itemid="">
    <p class="">Calories</p><p class=" itemstat" stattype="calories"></p>
    <p class="">Fat</p><p class=" itemstat" stattype="total_fat"></p>
    <p class="">Carbs</p><p class=" itemstat" stattype="total_carbohydrates"></p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button class="btn btn-primary" onclick="saveItem()">Save changes</button>
  </div>
</div>


<script type="text/javascript">
function saveItem()
{
	var itemid = $("#myModal").find(".modal-body").attr("itemid");
	var type = $(".add-type").html().toUpperCase();
	$.post('<?php echo $this->createUrl("/fooddiary/savefooditem"); ?>', 
		{ 
			food_item_id : itemid,
			meal_type: type,
			food_quantity_multiplier : '1'
		},
		function(){
			$('#myModal').modal('hide');
			$("#item-adder").hide();
			load_diary($("#breakfast-food-diary"));	
			load_diary($("#lunch-food-diary"));
		}
	);
}
function get_item_stat(e)
{
	var item_id = $(e).attr('itemid');
	$.get('<?php echo $this->createUrl("/fooddiary/getfooditem/'+item_id+'"); ?>', function(item){
		item = $.parseJSON(item);
		$("#myModal").find(".modal-body").attr("itemid",item_id);
		$("#myModal").find(".itemstat").each(function(i,e){
			var a = $(e).attr("stattype");
			$(e).html(item[a]);
		});
	});
	$('#myModal').modal();
}
function get_items()
{
	var search_string = $(".search-query").val();
	$.get('<?php echo $this->createUrl("/fooddiary/searchfooditem?str='+search_string+'"); ?>', function(items){
		
		items = $.parseJSON(items);
		items = items.results;
		$("#item-adder").find('tbody').html('');

		for (var i = items.length - 1; i >= 0; i--) {
			var _tab_obj = $.parseHTML("<tr><td class='item-name'></td><td class='fetch-item' itemid=''>+</td></tr>");
			$(_tab_obj).find(".item-name").html(items[i].name);
			$(_tab_obj).find(".fetch-item").attr("itemid",items[i].id);
			
			$(_tab_obj).find(".fetch-item").click(function(){
				console.log($(this).html());
				get_item_stat(this)
			});
			$("#item-adder").find('tbody').append(_tab_obj);
		};
	});
}
function add_item_show(elem){
	var type = $(elem).parents('table').attr("type");
	$(".add-type").html(type.charAt(0).toUpperCase() + type.slice(1).toLowerCase());
	$('#item-adder').show();
}

function load_diary(fdobj){
	var type = $(fdobj).attr('type');
	if(type ==null) {
		throw new Error("type not initialized");
		return false;
	}
	$.get('<?php echo $this->createUrl("/fooddiary/getdiary?type='+type+'"); ?>', function(data){
		data = $.parseJSON(data);
		var tab = data.results;
		var total_items = data.count;
		$(fdobj).find('tbody').html('<tr class="info"><td >Items</td><td>Calories</td><td class="add-item">Add</td></tr>');
		$(".add-item").click(function(){
	add_item_show(this);

});
		for (var i = tab.length - 1; i >= 0; i--) {
			var tab_item = tab[i];
			
			var total_cals = 0;

			$.get('<?php echo $this->createUrl("/fooddiary/getfooditem/'+tab[i].food_item+'"); ?>', function(item){
				item = $.parseJSON(item);
				var cals = item.calories * ( tab_item.food_quantity_multiplier / item.quantity );
				total_cals += cals;

				var _tab_obj = $.parseHTML('<tr><td class="food_item_name"></td><td class="food_item_cals"></td><td class="del_food_item">X</td></tr>');	
				$(_tab_obj).find(".food_item_name").html(item.name);
				$(_tab_obj).find(".food_item_cals").html(cals);
				//$(_tab_obj).find(".del_food_item").html(cals);
				$(fdobj).find('tbody').append(_tab_obj);
				$(fdobj).find('.total-calories').html(total_cals);
			});
			
		};
  		//$(_tab_obj).('food_item_name').html()
	});
}
load_diary($("#breakfast-food-diary"));
load_diary($("#lunch-food-diary"));
$(".toggle-diary-table").click(function(){
	$(this).parents('table').find('tbody').toggle();
});
$(".add-item").click(function(){
	add_item_show(this);

});
$(".search-query-button").click(function(){
		get_items();
	});

</script>