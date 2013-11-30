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


api_ajax = function(url,options,callback){
	if(!options.data) options.data = {};
	//console.log(options);
	//console.log(url);
	//console.log(callback);
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
			if(errorThrown == 'UNAUTHORIZED'){
				window.location.href = "/logout/";
			} else {
				console.log('error called of url '+url);
				//console.log(textStatus);
				//console.log(jqXHR);
				callback(jqXHR,false);
			}
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
	var search = options['search'] || null;
	var reading_date = options['reading_date'] || null;
	var activity_date = options['activity_date'] || null;

	var url = VH.params.apiUrl+resource;
	if(pk)
		url = url+'/'+pk;
	if(sub_resource) {
		url = url+'/'+sub_resource;
		if(sub_resource_id) 
			url = url+'/'+sub_resource_id;
	}
	

	if(api_backslash) url = url+'/';

	if(search) url  = url+'?search='+search;
	if(find_family_user_id())
		url=url+get_url_amp_ques(url)+'user='+find_family_user_id();
	if(current_page && current_page !=1 )
		url = url+get_url_amp_ques(url)+'page='+current_page;
	if(page_size)
		url = url+get_url_amp_ques(url)+'page_size='+page_size;

	if(reading_date)
		url = url+get_url_amp_ques(url)+'reading_date='+reading_date;
	if(activity_date)
		url = url+get_url_amp_ques(url)+'activity_date='+activity_date;
	
	return url;
}

api_url = function(resource,pk,sub_resource, sub_resource_id,get_params){
	if(!resource)
		throw new Error('resource not defined');
	var url = VH.params.apiUrl+resource;
	var get_started = false;
	if(pk)
		url = url+'/'+pk;

	if(sub_resource) {
		url = url+'/'+sub_resource;
		if(sub_resource_id) 
			url = url+'/'+sub_resource_id;
	}

	if(api_backslash) url = url+'/';
	if(find_family_user_id()){
		url=url+'?user='+find_family_user_id()
		get_started = true;
	}
	if(get_params){
		if(get_started){
			url = url+'&';
		} else {
			url = url+'?';
		}
		$.each(get_params, function(i, val){
			url = url+i+"="+val+"&";
		});
		url = url.substring(0, url.length - 1);
	}
	return url;
}

_DB.Reminder = {
	resource : 'reminders',
	retrieve : function(id,callback){
		var url = api_url(this.resource,id);
		api_get(url,callback);
	},
	list : function(options,callback){
		var url = api_url_x(this.resource,options);
		api_get(url,callback);
	},
	update : function(id,reminder,callback){
		var url = api_url(this.resource,id);
		api_put(url,reminder,callback);
	},
	create : function(reminder,callback){
		var url = api_url(this.resource);
		api_post(url,reminder,callback);
	},
	destroy: function(id,callback){
		var url = api_url(this.resource,id);
		api_delete(url,callback);
	},
}

_DB.ReminderReading = {
	resource : 'reminderreadings',
	list : function(options,callback){
		if(!options['reading_date'])
			throw 'Need reading date';
		var url = api_url_x(this.resource,options);
		api_get(url,callback);
	},
	update : function(id,goal,callback){
		var url = api_url(this.resource,id);
		api_put(url,goal,callback);
	},
}

_DB.PhysicalActivity = {
	resource : 'physical-activity',
	list : function(options,callback){
		var url = api_url_x(this.resource,options);
		api_get(url,callback);
	},
}

_DB.UserPhysicalActivity = {
	resource : 'user-physical-activity',
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
}
_DB.CholesterolReading = {
	resource : 'cholesterol-readings',
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
}

_DB.GlucoseReading = {
	resource : 'glucose-readings',
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

_DB.FoodDiary = {
	resource : 'diet-tracker',
	retrieve : function(id,callback){
		var url = api_url(this.resource,id)
		api_get(url,callback);
	},
	list : function(params,callback){
		var url = api_url(this.resource,null,null,null,params);
		//if(meal_type) url = url + '&meal_type='+meal_type;
		api_get(url,callback);
	},
	update : function(id,data,callback){
		var url = api_url(this.resource,id);
		api_put(url,data,callback);
	},
	create : function(data,callback){
		var url = api_url(this.resource);
		api_post(url,data,callback);
	},
	destroy: function(id,callback){
		var url = api_url(this.resource,id);
		api_delete(url,callback);
	},
}

_DB.HealthFile = {
	resource : 'healthfiles',
	retrieve : function(id,callback){
		var url = api_url(this.resource,id)
		api_get(url,callback);
	},
	list : function(params,callback){
		var url = api_url(this.resource,null,null,null,params);
		api_get(url,callback);
	},
	update : function(id,params,callback){
		var url = api_url(this.resource,id)
		api_put(url,params,callback);
	},
	create : function(user,callback){
		var url = api_url(this.resource)
		api_post(url,user,callback);
	},
	destroy: function(id,callback){
		var url = api_url(this.resource,id);
		api_delete(url,callback);
	},
}

_DB.FoodItems = {
	resource : 'food-items',
	retrieve : function(id,callback){
		var url = api_url(this.resource,id)
		api_get(url,callback);
	},
	search: function(params,callback){
		var url = api_url_x(this.resource,params);
		api_get(url,callback);
	}
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
		var options = {};
		options['pk'] = id;
		options['sub_resource'] = 'profile'
		var url = api_url_x(this.resource,options);
		//var url = VH.params.apiUrl+this.resource+'/'+id+'/';
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
		var options = {};
		options['pk'] = id;
		options['sub_resource'] = 'bmi-profile'
		var url = api_url_x(this.resource,options);
		api_get(url,callback);	
	},
	update_bmi_profile : function(id,profile,callback){
		var options = {};
		options['pk'] = id;
		options['sub_resource'] = 'bmi-profile'
		var url = api_url_x(this.resource,options);
		api_put(url,profile,callback);
	},
    attach_facebook : function(access_token,callback){
		var url = VH.params.apiUrl+'users/attach-facebook/';
		var data = {};
		data.access_token = access_token;
		api_post(url,data,callback);
	},
	invite: function(data,callback){
		var url = api_url_x('invite');
		api_post(url,data,callback);
	},
	change_password: function(data,callback){
		var url = VH.params.apiUrl+'users/change-password/';
		api_post(url,data,callback);
	},
	share: function(data,callback){
		var url = VH.params.apiUrl+'share/';
		api_post(url,data,callback);
	}

}


api_ajax_no_auth = function(url,options,callback){
	if(!options.data) options.data = {};
	$.ajax({
		url: url,
		dataType: 'json',
		type: options.type,
		data : options.data,
		success: function(json, textStatus){
			// PlainObject data, String textStatus, jqXHR jqXHR
			console.log('success called of url '+url);
			//console.log(json);
			callback(json,true);
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log('error called of url '+url);
			callback(jqXHR,false);
		}
	});
} 

_DB.Login = {
	resource : 'api-token-auth',
	by_email: function(email,password,callback){
		var url = api_url(this.resource);
		var data = {
			'email': email,
			'password' : password,
		}
		var options = {};
		options.type = 'POST';
		options.data = data;
		api_ajax_no_auth(url,options,callback)
	},
	by_username: function(username,password,callback){
		var url = api_url(this.resource);
		var data = {
			'username': username,
			'password' : password,
		}
		var options = {};
		options.type = 'POST';
		options.data = data;
		api_ajax_no_auth(url,options,callback)
	},
	by_facebook: function(access_token,callback){
		var url = api_url(this.resource);
		var data = {
			'access_token': access_token
		}
		var options = {};
                options.type = 'POST';
                options.data = data;
                api_ajax_no_auth(url,options,callback)
	},
	forgot_password_email: function(email,callback){
		var url = VH.params.apiUrl+'forgot-password-email/';
		var options = {}
		options.type = 'POST';
		options.data = {};
		options.data.email = email;
		api_ajax_no_auth(url,options,callback);
	}

}
