window.User = Backbone.Model.extend({

    urlRoot:"http://127.0.0.1:8080/users/",

    initialize:function () {
        //this.reports = new UserCollection();
        //this.reports.url = "http://127.0.0.1/users/" + this.id + '/reports';
    }

});

window.UserCollection = Backbone.Collection.extend({

    model: User,

    url:"http://127.0.0.1:8080/users/",

    /*findByName:function (key) {
        var url = (key == '') ? '../api/employees' : "../api/employees/search/" + key;
        console.log('findByName: ' + key);
        var self = this;
        $.ajax({
            url:url,
            dataType:"json",
            success:function (data) {
                console.log("search success: " + data.length);
                self.reset(data);
            }
        });
    }*/

});