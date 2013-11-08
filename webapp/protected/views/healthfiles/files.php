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
.fileinput-button {
  position: relative;
  overflow: hidden;
}
.fileinput-button input {
  position: absolute;
  top: 0;
  right: 0;
  margin: 0;
  opacity: 0;
  -ms-filter: 'alpha(opacity=0)';
  font-size: 200px;
  direction: ltr;
  cursor: pointer;
}

/* Fixes for IE < 8 */
@media screen\9 {
  .fileinput-button input {
    filter: alpha(opacity=0);
    font-size: 100%;
    height: 100%;
  }
}
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
border: 1px solid #ccc;
border-radius:5px;
}
#healthfiles-table .sb_list1 ul {
	list-style: none;
	margin:10px 0 10px 15px;
}
#healthfiles-table .sb_list1 ul  li {
background: url(/images/sprite-icn.png) no-repeat scroll 0 0 transparent;
padding: 2px 0;
border: none;
padding-left:20px;
}

#healthfiles-table .sb_list1 ul li.download{
background-position: -10px -858px;
}

#healthfiles-table .sb_list1 ul li.share{
background-position: -10px -937px;
}
#healthfiles-table .sb_list1 ul li.edit{
background-position: -10px -967px;
}

#healthfiles-table .sb_list1 ul li.delete{
background-position: -10px -1058px;
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
  <span class="btn btn-success fileinput-button">
        <!--<i class="glyphicon glyphicon-plus"></i>-->
        <span>Upload file</span>

    <input id="fileupload" type="file" name="file" data-url="<?php echo Yii::app()->params['apiBaseUrl'] ?>healthfiles/" >
   </span>
	<div id="fileupload-status" style="display:none;">Uploading..</div>
	<div id="fileupload-error" style="display:none;">There was some error. Please try again after some time.</div>
  </div>
</div>

<div id="edit-file-modal" data-fileid="" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Upload a file" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    Edit file description
  </div>
  <div class="modal-body" >
  	<img id="file_edit_img" width="100px" src="" style="display:none;"></img>
  	<div><span class="filetype_icon img_ft"></span></div>
  	<div>
  	<label>Description</label>
    <input type="text" id="file_edit_description" name="description" value="" />
    </div>
    <br/>
    <button id="family-users-add" class="btn btn-success" type="button">Save</button>
  </div>
</div>

<script>
function delete_healthfile(id)
{
	_DB.HealthFile.destroy(id,function(json,success){
		fetch_healthfiles();
	});	
}
function save_edit_healthfile(elem)
{

}
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
	          if(data.textStatus == 'success'){
	          	$('#upload-file-modal').modal('hide');
	          	var fid = data.result.id;
	          	
	          	populate_file_edit_details(fid);
	          	
	          } else {
	          	$('#fileupload-error').show();
	          	fetch_healthfiles();
	          }
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

function populate_file_edit_details(fid){
	$('#edit-file-modal').modal();
	$('#edit-file-modal').attr("data-fileid",fid);
	_DB.HealthFile.retrieve(fid,function(json,success){
		if(success){
			if (json.mime_type == "image/jpeg"){
				$("#file_edit_img").attr("src","/healthfiles/getdownloadurl/"+json.id).show();	
				$('#edit-file-modal').find(".filetype_icon").hide();
			}
			$("#file_edit_description").val(json.description);

			$('#edit-file-modal').find("button").click(function(){
				var params = {};
				params.description = $('#file_edit_description').val();
				_DB.HealthFile.update(fid,params,function(){
					fetch_healthfiles();
					$('#edit-file-modal').modal('hide');
				});
			});
			//save_edit_healthfile(this)
		}
	});
}

var _t_hf_row = '<tr class=""><td style="height: 40px;"><span class="filetype_icon"></span><span class="filename"></span></td><td class="file_description"></td><td class="file_date"></td>'+
	'<td ><div class="files-options"></div></td></tr>';

var _t_fo = '<div class="sb_list1"><ul><li class="download"><a href="#">Download</a></li>'+
			/*'<li class="share"><a class="open_ppFS" href="javascript:void(0);">Share</a></li>'+*/
			'<li class="edit"><a class="open_ppADL" href="javascript:void(0);">Edit</a></li>'+
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
				if(name && name.length>10){
					name = name.substring(0,6);
					name = name + '...';
				}
				var fid = val.id;
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
				$(_t).find(".files-options .delete a").click(function(){
					delete_healthfile(fid);
				});
				$(_t).find(".files-options .edit a").click(function(){
					populate_file_edit_details(fid);
				});
				$(_t).find(".files-options .download a").attr({
					target: '_blank', 
                    href  : "<?php echo $this->createUrl('/healthfiles/getdownloadurl/'); ?>"+'/'+fid,
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
