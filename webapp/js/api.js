var _DB = {};
var api_backslash = true;


//VH.params.apiUrl = "<?php echo Yii::app()->params['apiBaseUrl'] ?>";
//VH.vars.profile_id = '<?php echo $profile_id; ?>';

api_ajax = function(url,options,callback){
	if(!options.data) options.data = {};
	$.ajax({
		url: url,
		dataType: 'json',
		type: options.type,
		data : options.data,
		beforeSend: function(xhr) {
		   xhr.setRequestHeader("Authorization", "Token "+VH.params.auth_token);
		},
		success: function(json){
			console.log('success called of url '+url);
			console.log(json)
			callback(json);
		},
		error: function(jqXHR,textStatus,errorThrown){
			console.log('error called of url '+url);
			console.log(textStatus);
			console.log(errorThrown);
			console.log(jqXHR);
			callback(jqXHR.responseText,textStatus,errorThrown);
		}
	});
} 
api_get = function(url,callback){
	var options = {};
	options.type = 'GET';
	api_ajax(url,options,callback)
}

api_put = function(url,data,callback){
	var options = {};
	options.type = 'PUT';
	options.data = data;
	api_ajax(url,options,callback)
}

api_post = function(url,data,callback){
	var options = {};
	options.type = 'POST';
	options.data = data;
	api_ajax(url,options,callback)
}

api_url = function(resource,pk,sub_resource){
	if(!resource)
		throw new Error('resource not defined');
	var url = VH.params.apiUrl+resource;
	if(pk)
		url = url+'/'+pk;
	if(sub_resource)
		url = url+'/'+sub_resource;

	if(api_backslash) url = url+'/';
	return url;
}

_DB.User = {
	resource : 'users',
	retrieve : function(id,callback){
		var url = api_url(this.resource,id)
		api_get(url,callback);
	},
	update : function(id,user,callback){
		var url = api_url(this.resource,id)
		api_put(url,user,callback);
	},
	create : function(user,callback){
		var url = api_url(this.resource)
		api_post(url,user,callback);
	},
	update_profile : function(id,profile,callback){
		var url = api_url(this.resource,id,'profile');
		profile.gender = profile.gender?profile.gender.toUpperCase():profile.gender;
		api_put(url,profile,callback);
	}

}