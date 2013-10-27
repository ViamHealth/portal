var _DB = {};
var api_backslash = true;

function isFunction(functionToCheck) {
 var getType = {};
 return functionToCheck && getType.toString.call(functionToCheck) === '[object Function]';
}

function find_family_user_id(){
	var str = document.URL;
	var re = /\/u\/\d+/i;
	var found = str.match(re);
	if(found){
		var re = /\d+/i;
		var f1 = found[0].match(re);
		if(f1){
			return f1[0];
		}
	} else{
		return false;
	}
	
}

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
		success: function(json, textStatus){
			// PlainObject data, String textStatus, jqXHR jqXHR
			console.log('success called of url '+url);
			//console.log(json);
			callback(json,true);
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log('error called of url '+url);
			//console.log(textStatus);
			//console.log(jqXHR);
			callback(jqXHR,false);
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

api_delete = function(url, callback){
	var options = {};
	options.type = 'DELETE';
	api_ajax(url,options,callback)	
}

get_url_amp_ques = function(current_url){
	if(current_url)
		if(current_url.slice(-1) == '/')
			return '?';
		else
			return '&';
	else
		throw new Error('Url empty');
}

api_url_x = function(resource,options){
	if(!resource)
		throw new Error('resource not defined');
	//pk,sub_resource, sub_resource_id

	var options = options || {};
	var pk = options['pk'] || null;
	var sub_resource = options['sub_resource'] || null;
	var sub_resource_id = options['sub_resource_id'] || null;
	var current_page = options['current_page'] || null;
	var page_size = options['page_size'] || null;

	var url = VH.params.apiUrl+resource;
	if(pk)
		url = url+'/'+pk;
	if(sub_resource) {
		url = url+'/'+sub_resource;
		if(sub_resource_id) 
			url = url+'/'+sub_resource_id;
	}

	if(api_backslash) url = url+'/';
	if(find_family_user_id())
		url=url+get_url_amp_ques(url)+'user='+find_family_user_id();
	if(current_page && current_page !=1 )
		url = url+get_url_amp_ques(url)+'page='+current_page;
	if(page_size)
		url = url+get_url_amp_ques(url)+'page_size='+page_size;
	
	return url;
}

api_url = function(resource,pk,sub_resource, sub_resource_id){
	if(!resource)
		throw new Error('resource not defined');
	var url = VH.params.apiUrl+resource;
	if(pk)
		url = url+'/'+pk;
	console.log(pk);
	console.log(url);
	if(sub_resource) {
		url = url+'/'+sub_resource;
		if(sub_resource_id) 
			url = url+'/'+sub_resource_id;
	}

	if(api_backslash) url = url+'/';
	if(find_family_user_id())
		url=url+'?user='+find_family_user_id()
	return url;
}

_DB.Medication = {
	resource : 'medications',
	retrieve : function(id,callback){
		var url = api_url(this.resource,id);
		api_get(url,callback);
	},
	list : function(options,callback){
		var url = api_url_x(this.resource,options);
		api_get(url,callback);
	},
	update : function(id,goal,callback){
		var url = api_url(this.resource,id);
		api_put(url,goal,callback);
	},
	create : function(goal,callback){
		var url = api_url(this.resource);
		api_post(url,goal,callback);
	},
	destroy: function(id,callback){
		var url = api_url(this.resource,id);
		api_delete(url,callback);
	},
}

_DB.Medicaltest = {
	resource : 'medicaltests',
	retrieve : function(id,callback){
		var url = api_url(this.resource,id);
		api_get(url,callback);
	},
	list : function(options,callback){
		var url = api_url_x(this.resource,options);
		api_get(url,callback);
	},
	update : function(id,goal,callback){
		var url = api_url(this.resource,id);
		api_put(url,goal,callback);
	},
	create : function(goal,callback){
		var url = api_url(this.resource);
		api_post(url,goal,callback);
	},
	destroy: function(id,callback){
		var url = api_url(this.resource,id);
		api_delete(url,callback);
	},
}

_DB.CholesterolGoal = {
	resource : 'cholesterol-goals',
	retrieve : function(id,callback){
		var url = api_url(this.resource,id);
		api_get(url,callback);
	},
	list : function(callback){
		var url = api_url(this.resource);
		api_get(url,callback);
	},
	update : function(id,goal,callback){
		var url = api_url(this.resource,id);
		api_put(url,goal,callback);
	},
	create : function(goal,callback){
		var url = api_url(this.resource);
		api_post(url,goal,callback);
	},
	destroy: function(id,callback){
		var url = api_url(this.resource,id);
		api_delete(url,callback);
	},
	set_reading : function(id,reading,callback){
		var url = api_url(this.resource,id,'set-reading');
		api_post(url,reading,callback);
	},
	destroy_reading: function(id, reading_date, callback){
		var url = api_url(this.resource,id,'destroy-reading');
		url = url + "?reading_date="+reading_date
		api_delete(url,callback);
	}
}

_DB.GlucoseGoal = {
	resource : 'glucose-goals',
	retrieve : function(id,callback){
		var url = api_url(this.resource,id);
		api_get(url,callback);
	},
	list : function(callback){
		var url = api_url(this.resource);
		api_get(url,callback);
	},
	update : function(id,goal,callback){
		var url = api_url(this.resource,id);
		api_put(url,goal,callback);
	},
	create : function(goal,callback){
		var url = api_url(this.resource);
		api_post(url,goal,callback);
	},
	destroy: function(id,callback){
		var url = api_url(this.resource,id);
		api_delete(url,callback);
	},
	set_reading : function(id,reading,callback){
		var url = api_url(this.resource,id,'set-reading');
		api_post(url,reading,callback);
	},
	destroy_reading: function(id, reading_date, callback){
		var url = api_url(this.resource,id,'destroy-reading');
		url = url + "?reading_date="+reading_date
		api_delete(url,callback);
	}
}

_DB.BloodPressureGoal = {
	resource : 'blood-pressure-goals',
	retrieve : function(id,callback){
		var url = api_url(this.resource,id);
		api_get(url,callback);
	},
	list : function(callback){
		var url = api_url(this.resource);
		api_get(url,callback);
	},
	update : function(id,goal,callback){
		var url = api_url(this.resource,id);
		api_put(url,goal,callback);
	},
	create : function(goal,callback){
		var url = api_url(this.resource);
		api_post(url,goal,callback);
	},
	destroy: function(id,callback){
		var url = api_url(this.resource,id);
		api_delete(url,callback);
	},
}
_DB.BloodPressureReading = {
	resource : 'blood-pressure-readings',
	retrieve : function(id,callback){
		var url = api_url(this.resource,id)
		api_get(url,callback);
	},
	list : function(callback){
		var url = api_url(this.resource)
		api_get(url,callback);
	},
	update : function(id,data,callback){
		var url = api_url(this.resource,id)
		api_put(url,data,callback);
	},
	create : function(data,callback){
		var url = api_url(this.resource)
		api_post(url,data,callback);
	},
	destroy: function(id,callback){
		var url = api_url(this.resource,id);
		api_delete(url,callback);
	},
}

_DB.WeightGoal = {
	resource : 'weight-goals',
	retrieve : function(id,callback){
		var url = api_url(this.resource,id)
		api_get(url,callback);
	},
	list : function(callback){
		var url = api_url(this.resource)
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
	destroy: function(id,callback){
		var url = api_url(this.resource,id);
		api_delete(url,callback);
	},
	set_reading : function(id,reading,callback){
		var url = api_url(this.resource,id,'set-reading');
		reading.weight_measure = 'METRIC';
		//profile.gender = profile.gender?profile.gender.toUpperCase():profile.gender;
		api_post(url,reading,callback);
	},
	destroy_reading: function(id, reading_date, callback){
		var url = api_url(this.resource,id,'destroy-reading');
		url = url + "?reading_date="+reading_date
		api_delete(url,callback);
	}
}
_DB.WeightReading = {
	resource : 'weight-readings',
	retrieve : function(id,callback){
		var url = api_url(this.resource,id)
		api_get(url,callback);
	},
	list : function(callback){
		var url = api_url(this.resource)
		api_get(url,callback);
	},
	update : function(id,data,callback){
		var url = api_url(this.resource,id)
		api_put(url,data,callback);
	},
	create : function(data,callback){
		var url = api_url(this.resource)
		api_post(url,data,callback);
	},
	destroy: function(id,callback){
		var url = api_url(this.resource,id);
		api_delete(url,callback);
	},
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
	},
	update_profile_picture : function(callback){
    	$('#fileupload').fileupload({
	        dataType: 'json',
	        type: 'put',
	        beforeSend: function(xhr) {
	                 xhr.setRequestHeader("Authorization", "Token "+VH.params.auth_token)
	            },
	        success: function (result, textStatus) {
	          callback(result, textStatus);
	        }
    	});
	},
	retrieve_bmi_profile : function(id,callback){
		var url = api_url(this.resource,id,'bmi-profile')
		api_get(url,callback);	
	},
	update_bmi_profile : function(id,profile,callback){
		var url = api_url(this.resource,id,'bmi-profile');
		api_put(url,profile,callback);
	},

}