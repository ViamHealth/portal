<script src="<?php echo base_url('assets/js/jquery.tablesorter.min.js') ?>"></script>


<div class="page-header">
  <h1>
  	Files
  	<div class="pull-right" >
		<span class="btn btn-success fileinput-button">
    	    <span class="glyphicon glyphicon-cloud-upload"> Upload</span>
    		<input id="fileupload" type="file" name="file" 
    			data-url="<?php echo  $api_url.'healthfiles/?user='.$current_user_id;?>" >
	   	</span>
		<div id="fileupload-status" style="display:none;">Uploading..</div>
	</div>
  </h1>
</div>

<div class="row" >
	<div class="loading_healthfiles" style="display:none;">Loading Data. Please wait....</div>
	
	<div class="table-responsive">
	<table class="table table-condensed table-striped tablesorter" id="healthfiles-table" >
		<thead> 
			<tr class="success">
				<th style="">Filename</th>
				<th style="border-left: none;">Description</th>
				<th style="border-left: none;">Date</th>
				<th style="border-left: none;">Action</th>
			</tr>
        </thead>
		<tbody> 
			
        </tbody>
	</table>
	</div>
</div>
<?php $this->load->view('healthfiles/_upload_file_modal'); ?>

<?php $this->load->view('healthfiles/_edit_file_modal'); ?>
<?php $this->load->view('healthfiles/_share_file_modal'); ?>



<script>
function delete_healthfile(id)
{
	bootbox.confirm("Delete file?", function(result) {
	  if(result){
		_DB.HealthFile.destroy(id,function(json,success){
			fetch_healthfiles();
		});  	
	  }
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
	                 $('#upload-file-modal').modal({
	                 	backdrop:"static",
	                 	keyboard: false,
	                 })
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
	          	show_body_alerts('Could not upload file at this moment. Please try again later.');
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
	$('#edit-file-modal').modal({
		backdrop:"static",
	    keyboard: false,
	});
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
			$('#edit-file-modal').find("button.btn-save").attr('onclick','').unbind('click');
			$('#edit-file-modal').find("button.btn-save").click(function(){
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
			'<li class="share">Share</li>'+
			'<li class="edit">Edit</li>'+
			'<li class="delete">Delete</li></ul></div>';


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
			$("#healthfiles-table > tbody").find("tr").remove();
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
				$(_t).find(".files-options .delete").attr('onclick','').unbind('click');
				$(_t).find(".files-options .delete").click(function(){
					delete_healthfile(fid);
				});
                $(_t).find(".files-options .edit").attr('onclick','').unbind('click');
				$(_t).find(".files-options .edit").click(function(){
					populate_file_edit_details(fid);
				});
				$(_t).find(".files-options .share").attr('onclick','').unbind('click');
				$(_t).find(".files-options .share").click(function(){
					$("#share-file-modal .btn-save").attr("data-id",fid);
					$("#share-file-modal").modal();
				});
				$(_t).find(".files-options .download a").attr({
					//target: '_blank', 
                    href  : "<?php echo site_url('download_healthfile'); ?>"+'/'+fid,
                });
				
				$("#healthfiles-table > tbody").append(_t);

			});
			if(0){//json.count){
			    $("#healthfiles-table").tablesorter({
				sortList: [[2,1],],
				headers: { 
		            		3: { 
		                		sorter: false 
		            		}, 
		        	},
			    }); 
			}
			$(".loading_healthfiles").hide();
		}
	});
}

function get_filtype_icon_class(mime_type){
	if(!mime_type) return false;
	console.log(mime_type);
	if(mime_type == 'application/pdf') return "pdf_ft";
	if(mime_type == "image/jpeg") return "img_ft";
	if(mime_type == "image/png") return "img_ft";

}
</script>
