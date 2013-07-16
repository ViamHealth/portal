var oldSync = Backbone.sync;
Backbone.sync = function(method, model, options){
    options.beforeSend = function(xhr){
        xhr.setRequestHeader('Authorization', "Token "+_auth_token);
    };
    return oldSync(method, model, options);
};


// The Template Loader. Used to asynchronously load templates located in separate .html files
window.templateLoader = {

    load: function(views, callback) {

        var deferreds = [];

        $.each(views, function(index, view) {
            if (window[view]) {
                deferreds.push($.get('tpl/' + view + '.html', function(data) {
                    window[view].prototype.template = _.template(data);
                }, 'html'));
            } else {
                alert(view + " not found");
            }
        });

        $.when.apply(null, deferreds).done(callback);
    }

};

window.Router = Backbone.Router.extend({

    routes: {
        "": "home",
        "users/:id": "userDetails"
    },

    initialize: function () {
        this.headerView = new HeaderView();
        $('.header').html(this.headerView.render().el);

        // Close the search dropdown on click anywhere in the UI
        $('body').click(function () {
            $('.dropdown').removeClass("open");
        });
    },

    home: function () {
        // Since the home view never changes, we instantiate it and render it only once
        if (!this.homeView) {
            this.homeView = new HomeView();
            this.homeView.render();
        } else {
            this.homeView.delegateEvents(); // delegate events when the view is recycled
        }
        $("#content").html(this.homeView.el);
        this.headerView.select('home-menu');
    },

    userDetails: function (id) {
        var user = new User({id: id});
        user.fetch({
            success: function (data) {
                console.log('fecthed');
                console.log(data);
                // Note that we could also 'recycle' the same instance of EmployeeFullView
                // instead of creating new instances
                $('#content').html(new UserView({model: data}).render().el);
            },
            error: function(model, response, options){
                console.log('error');
                console.log(model);
                console.log(response);
                console.log(options);
            }
        });
    }

});

templateLoader.load(["HomeView",  "HeaderView", "UserView", "UserSummaryView", "UserListItemView"],
    function () {
        app = new Router();
        Backbone.history.start();
    });