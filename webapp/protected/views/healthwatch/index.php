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

<div id="medicaltest-paginator"></div>
<script type="text/javascript">
VH.vars.profile_id = find_family_user_id()?find_family_user_id():'<?php echo $profile_id; ?>';

$(document).ready(function(){
	populate_medicaltests();
});

function populate_medicaltests(current_page){
	var current_page = current_page || 1;
	var options = {};
	options = {'current_page':current_page};
	options['page_size'] = 1;
	_DB.Medicaltest.list(options,function(response,success){
		var page_options = {
			totalPages : response.count,
			currentPage : current_page,
			onPageClicked: function(e,oE,type,page){
				console.log(page);
				populate_medicaltests(page);
			}
		};
		$("#medicaltest-paginator").bootstrapPaginator(page_options);

	});
}

</script>
