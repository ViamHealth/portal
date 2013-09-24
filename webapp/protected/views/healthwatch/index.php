<?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.validate.min.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/bootstrap-paginator.min.js');
?>

<?php
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Health watch',
);
?>
<div class="row-fuild">
	<div class="span12">
		<ul class="nav nav-tabs">
  			<li class="active">
  				<a href="#medicaltests" data-toggle="tab" tab-type="medicaltests">Medical Tests</a>
  				
  			</li>
		  	<li><a href="#medications" data-toggle="tab" tab-type="medications">Medications</a>
		  		
		  	</li>
		</ul>
		<div id="my-tab-content" class="tab-content">
			<div class="tab-pane active" id="medicaltests">
					<button class="btn btn-primary pull-right" id="add-medicaltest" >Add Medical Test</button>
  					<table class="table table-striped table-hover " >
			  		<thead>
			  			<tr class="info">
			  				<th class="name">Test Name</th>
			  				<th class="details">Details</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  		</tbody>
			  	</table>
			  	<div class="pager"></div>
			</div>
			<div class="tab-pane" id="medications" >
  					<table class="table table-striped table-hover " >
				  		<thead>
				  			<tr class="info">
				  				<th class="name">Name</th>
				  				<th class="details">Details</th>
				  				<th class="morning_count">morning count</th>
				  			</tr>
				  		</thead>
				  		<tbody>
				  		</tbody>
				  	</table>
				  	<div class="pager"></div>
	  		</div>
  		</div>
	</div>
</div>
<?php $this->renderPartial('_medication_form',array()); ?>

<script type="text/javascript">
VH.vars.profile_id = find_family_user_id()?find_family_user_id():'<?php echo $profile_id; ?>';


$(document).ready(function(){
	populate_medicaltests();

	$("#add-medicaltest").click(function(event){
		$("#medicaltest-form-modal").modal();
	});
/*
repeat_mode = DAILY
h = 12 m = 14
repeat_mode= WEEKLY on Sunday
h = 12 m = 14 d = 1 ( SUnday)
repeat_mode = MONTHLY
h = 12 m = 14 d = 3rd/3
repeat_mode= N_WEEKDAY_MONTHLY ( eg.  Every  Sunday - once in 2 weeks )
h = 12 m = 14 d = 1 ( Sun ) , w = 2
Every 5 Days
h = 12 m = 14 di = 5
*/
	$("#medicaltest\\[repeat_mode\\]").change(function(event){
		var rm = $("#medicaltest\\[repeat_mode\\]");
		var d = $("#medicaltest\\[repeat_day\\]");
		var h = $("#medicaltest\\[repeat_hour\\]");
		var m = $("#medicaltest\\[repeat_minute\\]");
		var w = $("#medicaltest\\[repeat_weekday\\]");
		var di = $("#medicaltest\\[repeat_day_interval\\]");
		var repeat = $(rm).val();
		if(repeat == 'NONE'){
			$(h).attr("disabled",true).parents(".control-group").hide();
		} else if (repeat == 'DAILY'){
			$(d).attr("disabled",true).parents(".control-group").hide();
			$(h).removeAttr("disabled").parents(".control-group").show();
			$(m).removeAttr("disabled").parents(".control-group").show();
			$(w).attr("disabled",true).parents(".control-group").hide();
			$(di).attr("disabled",true).parents(".control-group").hide();
		} else if (repeat == 'WEEKLY'){
			$(d).removeAttr("disabled").parents(".control-group").show();
			$(h).removeAttr("disabled").parents(".control-group").show();
			$(m).removeAttr("disabled").parents(".control-group").show();
			$(w).attr("disabled",true).parents(".control-group").hide();
			$(di).attr("disabled",true).parents(".control-group").hide();
		} else if (repeat == 'MONTHLY'){
			$(d).removeAttr("disabled").parents(".control-group").show();
			$(h).removeAttr("disabled").parents(".control-group").show();
			$(m).removeAttr("disabled").parents(".control-group").show();
			$(w).attr("disabled",true).parents(".control-group").hide();
			$(di).attr("disabled",true).parents(".control-group").hide();
		} else if (repeat == 'N_WEEKDAY_MONTHLY'){
			$(d).removeAttr("disabled").parents(".control-group").show();
			$(h).removeAttr("disabled").parents(".control-group").show();
			$(m).removeAttr("disabled").parents(".control-group").show();
			$(w).attr("disabled",true).parents(".control-group").hide();
			$(di).attr("disabled",true).parents(".control-group").hide();
		} else if (repeat == 'N_DAYS_INTERVAL'){
			$(h).removeAttr("disabled").parents(".control-group").show();
		}
		
		
		
	});

	

	$('a[data-toggle="tab"]').on('shown', function (e) {
	  if($(e.target).attr("tab-type") == 'medicaltests'){
	  	populate_medicaltests();
	  }
	  else if($(e.target).attr("tab-type") == 'medications'){
	  	populate_medications();
	  }
	  //var ee = $(e.relatedTarget).parents("div").parents("div")[0];
	  //$(ee).hide();
	  //$(e.target).next().show();
	})
});

function populate_medicaltests(current_page){
	var current_page = current_page || 1;
	var options = {};
	options = {'current_page':current_page};
	

	_DB.Medicaltest.list(options,function(response,success){
		var data = response.results;
		var page_options = {
			totalPages : Math.ceil(response.count/data.length),
			currentPage : current_page,
			onPageClicked: function(e,oE,type,page){
				populate_medicaltests(page);
			}
		};
		var elem = $("#medicaltests");
		
		var elem_tbody = elem.find("table > tbody")[0];
		
		var elem_pager =  elem.find(".pager")[0];
		
		$(elem_pager).bootstrapPaginator(page_options);
		
		$(elem_tbody).html('');
		for(var i = 0; i < data.length; i++){
			var _tpl = "<tr><td>"+data[i].name+"</td><td>"+data[i].details+"</td></tr>";
			$(elem_tbody).append(_tpl);
		}
	});
}

function populate_medications(current_page){
	var current_page = current_page || 1;
	var options = {};
	options = {'current_page':current_page};
	
	_DB.Medication.list(options,function(response,success){
		var data = response.results;
		var page_options = {
			totalPages : Math.ceil(response.count/data.length),
			currentPage : current_page,
			onPageClicked: function(e,oE,type,page){
				
				populate_medications(page);
			}
		};
		var elem = $("#medications");
		
		var elem_tbody = elem.find("table > tbody")[0];
		
		var elem_pager =  elem.find(".pager")[0];
		
		$(elem_pager).bootstrapPaginator(page_options);
		
		$(elem_tbody).html('');
		for(var i = 0; i < data.length; i++){
			var _tpl = "<tr><td>"+data[i].name+"</td><td>"+data[i].details+"</td><td>"+data[i].morning_count+"</td></tr>";
			$(elem_tbody).append(_tpl);
		}
		
	});
}

</script>
