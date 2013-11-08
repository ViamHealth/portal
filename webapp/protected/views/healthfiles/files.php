<?php
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Food Diary',
);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.validate.min.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/bootbox.min.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/bootstrap-datepicker.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.ui.widget.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.iframe-transport.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.fileupload.js');

?>
<style>
#healthfiles-table thead th
{font: bold 12px Segoe UI, Arial, Helvetica, sans-serif;
line-height: 36px;
color: #0099cc;
border-bottom: 2px solid #ccc;
}
#healthfiles-table {
	font: 12px Segoe UI, Arial, Helvetica, sans-serif;

color: #000;
}
#healthfiles-table  .files-options {
width: 18px;
height: 15px;
background: url(/images/sprite-icn.png) no-repeat -9px -788px;
padding: 0 0 3px 0;
margin: 0 auto;
display: block;

cursor: pointer;
}
#healthfiles-table .sb_list1 {
	width: 160px;
position: absolute;
top: 16px;
right: 0;
z-index: 1;
display: none;
background-color: white;
border: 2px solid #ccc;
}
#healthfiles-table .sb_list1 ul {
	list-style: none;
}
#healthfiles-table .sb_list1 ul  li {
background: url(/images/sprite-icn.png) no-repeat scroll 0 0 transparent;
padding: 2px 0;
border: none;
}

#healthfiles-table .sb_list1 ul li.download{
background-position: -2px -858px;
}

.filetype_icon {
	padding: 0 0 22px 48px;
background-image: url(/images/sprite-icn.png);
background-repeat: no-repeat;
}
.img_ft {
	background-position: -2px -705px;
}
.pdf_ft {
	background-position: -2px -446px;
}
</style>

<div class="row-fluid" style="font-size:12px;">
	<table class="table table-condensed " id="healthfiles-table">
		<thead> 
			<tr class="">
				<th style="">Filename</td>
				<th style="border-left: none;">Description</td>
				<th style="border-left: none;">Date</td>
				<th style="border-left: none;">Action</td>
			</tr>
        </thead>
		<tbody> 
			
        </tbody>
	</table>
	<a href="#" onclick="$('#upload-file-modal').modal();"><button id="family-users-add" class="btn btn-success" type="button">Upload File</button></a>
</div>


<div id="upload-file-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Upload a file" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    Upload new file
  </div>
  <div class="modal-body" itemid="">
    <input id="fileupload" type="file" name="file" data-url="<?php echo Yii::app()->params['apiBaseUrl'] ?>healthfiles/" >
	<div id="fileupload-status" style="display:none;">Uploading..</div>
  </div>
</div>

<script>
$(document).ready(function(){
	$(function () {
	    $('#fileupload').fileupload({
	        dataType: 'json',
	        beforeSend: function(xhr) {
	                 xhr.setRequestHeader("Authorization", "Token <?php echo Yii::app()->user->token; ?>")
	                 console.log(xhr);

	              $('#fileupload-status').show();
	            },
	        done: function (e, data) {
	          $('#fileupload-status').hide();
	          var id = data.result.id;
	          $('#upload-file-modal').modal('hide');
	          fetch_healthfiles();
	          //window.location.replace("<?php echo $this->createUrl('/healthfiles/update/'); ?>"+'/'+id);
	        }
	    });
	});

	fetch_healthfiles();
	$("#healthfiles-table .files-options").mousemove(function(){
			$(this).css({"position":"relative"});
			$(this).child().css("display","block");
	});
	$("#healthfiles-table .files-options").mouseleave(function(){
			$(this).css({"position":""});
			$(this).child().css("display","none");
	});
});

var _t_hf_row = '<tr class=""><td style="height: 40px;"><span class="filetype_icon"></span><span class="filename"></span></td><td class="file_description"></td><td class="file_date"></td>'+
	'<td ><div class="files-options"></div></td></tr>';

var _t_fo = '<div class="sb_list1"><ul><li class="download"><a href="#">Download</a></li>'+
			'<li class="share"><a class="open_ppFS" href="javascript:void(0);">Share</a></li>'+
			'<li class="add"><a class="open_ppADL" href="javascript:void(0);">Add Label</a></li>'+
			'<li class="delete"><a href="#">Delete</a></li></ul></div>';

function fetch_healthfiles(page){
	if(!page) page = 1;
	var page_size = 100;
	var options = {};
	options.page_size = page_size;
	_DB.HealthFile.list(options,function(json,success){
		
		if(success){
			var data = json.results;
			$("#healthfiles-table > tbody").html('');
			$.each(data,function(i,val){
				var _t = $.parseHTML(_t_hf_row);

				var f_date = new Date(val.updated_at*1000);
				$(_t).find(".filetype_icon").addClass(get_filtype_icon_class(val.mime_type));
				var name = val.name;
				if(name.length>10){
					name = name.substring(0,6);
					name = name + '...';
				}
				$(_t).find(".filename").html(name);
				$(_t).find(".file_description").html(val.description);
				$(_t).find(".file_date").html(f_date.toDateString());
				$(_t).find(".files-options").html(_t_fo);
				$(_t).find(".files-options").mousemove(function(){
					$(this).css({"position":"relative"});
					$(this).find(".sb_list1").css("display","block");
				}).mouseleave(function(){
						$(this).css({"position":""});
						$(this).find(".sb_list1").css("display","none");
				});
				$("#healthfiles-table > tbody").append(_t);

			});
		}
	});
}

function get_filtype_icon_class(mime_type){
	if(!mime_type) return false;
	
	if(mime_type == 'application/pdf') return "pdf_ft";
	if(mime_type == "image/jpeg") return "img_ft";

}
</script>
