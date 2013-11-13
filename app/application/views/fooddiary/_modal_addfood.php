<style>
.modal-body{
	background-color: #efefef;
}
@media (min-width: 979px) {
.dd-options {
	position: fixed;
}
}
.dd-selected {
	padding: 5px;
}
.dd-option {
	padding: 5px;
}
.dd-select label {
	margin-bottom: 0px;
}
.fid_row {
	font: 12px Segoe UI, Arial, Helvetica, sans-serif;
	
	color: #666;
	
}
.fid_row  .fid_val {
	text-align: right;
	min-height: 10px;
}
.fid_row .fid_text {
	min-height: 10px;
}
.fid_row .name {
	font: 14px Segoe UI, Arial, Helvetica, sans-serif;
	font-weight: bold;
	text-transform:capitalize;
	padding-top: 5px;
	padding-bottom: 5px;
}

.fid_row > .col-md-6 {
	min-height:10px;
}
#add_food_results {
	font-size: 12px;
}
#add_food_details {
	
}
.fi_result {
	background-color: #fff;
	color:#000;
	border-bottom: 1px dotted #ccc;
	padding:5px 5px;
}
.fi_result.active {
	background-color: #0099cc;	
	color:#fff;
}
</style>

<?php 
$modal_id = "add-food-item-modal";
$aria_labelledby = "Food Diary / Add new item";

$footer_html = '<div class="col-md-3 col-md-offset-4">'.
'<div id="add_food_quantity_selector"></div>'.
'</div>';

$modal_body_html = '<div class="row"  style="border-bottom: 1px solid #cccccc;">
	<div class="col-md-3">
		Search
	</div>
	<div class="col-md-6">
		<input type="text" id="add_food_item_search"/>
	</div>
</div>
<div class="row">

<div class="col-md-5" id="add_food_results">

</div>
<div class="col-md-7" id="add_food_details">
	<div class="row"><div class="col-md-11 col-md-offset-1">
		<div class="fid_row row">
			<div class="col-md-12 name">Loading...</div>
		</div>
		<div class="fid_row row">
			<div class="col-md-5 fid_text">Calories</div>
			<div class="col-md-3 fid_val calories"></div>
		</div>

		<div class="fid_row row">
			<div class="col-md-5 fid_text">Fat</div>
			<div class="col-md-3 fid_val total_fat"></div>
		</div>
		<div class="fid_row row">
			<div class="col-md-5 fid_text">Carbs</div>
			<div class="col-md-3 fid_val total_carbohydrates"></div>
		</div>
		<div class="fid_row row">
			<div class="col-md-5 fid_text">Protein</div>
			<div class="col-md-3 fid_val protein"></div>
		</div>
		<div class="fid_row row">
			<div class="col-md-5 fid_text">Sugars</div>
			<div class="col-md-3 fid_val sugars"></div>
		</div>
		<div class="fid_row row">
			<div class="col-md-5 fid_text">Cholesterol</div>
			<div class="col-md-3 fid_val cholesterol"></div>
		</div>
		<div class="fid_row row">
			<div class="col-md-5 fid_text">Sodium</div>
			<div class="col-md-3 fid_val sodium"></div>
		</div>
		<div class="fid_row row">
			<div class="col-md-5 fid_text">Potassium</div>
			<div class="col-md-3 fid_val potassium"></div>
		</div>
		<div class="fid_row row">
			<div class="col-md-5 fid_text">Saturated</div>
			<div class="col-md-3 fid_val saturated_fat"></div>
		</div>
		<div class="fid_row row">
			<div class="col-md-5 fid_text">Polyunsaturated</div>
			<div class="col-md-3 fid_val polyunsaturated_fat"></div>
		</div>
		<div class="fid_row row">
			<div class="col-md-5 fid_text">Dietary Fiber</div>
			<div class="col-md-3 fid_val dietary_fiber"></div>
		</div>
		<div class="fid_row row">
			<div class="col-md-5 fid_text">Trans</div>
			<div class="col-md-3 fid_val trans_fat"></div>
		</div>
		<div class="fid_row row">
			<div class="col-md-5 fid_text">Vitamin A</div>
			<div class="col-md-3 fid_val vitamin_a"></div>
		</div>
		<div class="fid_row row">
			<div class="col-md-5 fid_text">Calcium</div>
			<div class="col-md-3 fid_val calcium"></div>
		</div>
		<div class="fid_row row">
			<div class="col-md-5 fid_text">Vitamin C</div>
			<div class="col-md-3 fid_val vitamin_c"></div>
		</div>
		<div class="fid_row row">
			<div class="col-md-5 fid_text">Iron</div>
			<div class="col-md-3 fid_val iron"></div>
		</div>

	</div></div>

</div>

';;
$modal_data = array();
$modal_data['modal_id'] = $modal_id;
$modal_data['hide_close_button'] = false;
$modal_data['aria_labelledby'] = $aria_labelledby;
$modal_data['header_title'] = $aria_labelledby;
$modal_data['btn_primary_text'] = 'Save';
$modal_data['footer_html'] = $footer_html;
$modal_data['modal_body_html'] = $modal_body_html;


$this->load->view('fooddiary/_modal_viam',$modal_data);
?>
