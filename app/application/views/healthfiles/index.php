
<div class="row" style="font-size:12px;">
	<span class="loading_healthfiles" style="display:none;">Loading Data. Please wait....</span>
	<br/>
	<table class="table table-condensed " id="healthfiles-table" >
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
	 <span class="btn btn-success fileinput-button">
        <!--<i class="glyphicon glyphicon-plus"></i>-->
        <span>Upload file</span>

    	<input id="fileupload" type="file" name="file" data-url="<?php echo  $api_url.'healthfiles/?user='.$appuser->id;?>" >
   	</span>
   	
	<div id="fileupload-status" style="display:none;">Uploading..</div>
	<div id="fileupload-error" style="display:none;">There was some error. Please try again after some time.</div>

	<!--<a href="#" onclick="$('#upload-file-modal').modal();"><button id="family-users-add" class="btn btn-success" type="button">Upload File</button></a>-->
</div>
<?php $this->load->view('healthfiles/_upload_file_modal'); ?>

<?php $this->load->view('healthfiles/_edit_file_modal'); ?>



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
	                 xhr.setRequestHeader("Authorization", "Token <?php echo $token; ?>");
	                 $('#upload-file-modal').modal()
	              //$('#fileupload-status').show();
	            },
	        progressall: function (e, data) {
		        var progress = parseInt(data.loaded / data.total * 100, 10);
		        if (progress > 90) progress = 90;
		        $('#progress .bar').css(
		            'width',
		            progress + '%'
		        );
		    },
	        done: function (e, data) {
	          //$('#fileupload-status').hide();
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
			/*if (json.mime_type == "image/jpeg"){
				$("#file_edit_img").attr("src","/healthfiles/getdownloadurl/"+json.id).show();	
				$('#edit-file-modal').find(".filetype_icon").hide();
			}*/
			$('#edit-file-modal').find(".filetype_icon").addClass(get_filtype_icon_class(json.mime_type));

			$("#file_edit_description").val(json.description);
			$("#file_edit_name").html(json.name);

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
	$(".loading_healthfiles").show();
	//$("#healthfiles-table").hide();
	if(!page) page = 1;
	var page_size = 100;
	var options = {};
	options.page_size = page_size;
	_DB.HealthFile.list(options,function(json,success){
		
		if(success){
			$("#healthfiles-table").show();
			var data = json.results;
			$("#healthfiles-table > tbody").html('');
			$.each(data,function(i,val){
				var _t = $.parseHTML(_t_hf_row);

				var f_date = new Date(val.updated_at*1000);
				$(_t).find(".filetype_icon").addClass(get_filtype_icon_class(val.mime_type));
				var name = val.name;
				if(name && name.length>15){
					name = name.substring(0,15);
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
                    href  : "<?php echo site_url('getdownloadurl'); ?> ?>"+'/'+fid,
                });
				
				$("#healthfiles-table > tbody").append(_t);

			});
			$(".loading_healthfiles").hide();
		}
	});
}

function get_filtype_icon_class(mime_type){
	if(!mime_type) return false;
	
	if(mime_type == 'application/pdf') return "pdf_ft";
	if(mime_type == "image/jpeg") return "img_ft";

}
</script>