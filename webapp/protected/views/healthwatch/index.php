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

<script type="text/javascript">
VH.vars.profile_id = find_family_user_id()?find_family_user_id():'<?php echo $profile_id; ?>';


$(document).ready(function(){
	populate_medicaltests();

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
