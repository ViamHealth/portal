window.UserListView = Backbone.View.extend({

    tagName:'ul',

    className:'nav nav-list',

    initialize:function () {
        var self = this;
        this.model.bind("reset", this.render, this);
        this.model.bind("add", function (user) {
            $(self.el).append(new UserListItemView({model:user}).render().el);
        });
    },

    render:function () {
        $(this.el).empty();
        _.each(this.model.models, function (user) {
            $(this.el).append(new UserListItemView({model:user}).render().el);
        }, this);
        return this;
    }
});

window.UserListItemView = Backbone.View.extend({

    tagName:"li",

    initialize:function () {
        this.model.bind("change", this.render, this);
        this.model.bind("destroy", this.close, this);
    },

    render:function () {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    }

});



window.HealthFilesView = Backbone.View.extend({

    tagName:'ul',

    className:'nav nav-list',

    initialize:function () {
        var self = this;
        this.model.bind("reset", this.render, this);
        this.model.bind("add", function (user) {
            $(self.el).append(new HealthFileView({model:user}).render().el);
        });
    },

    render:function () {
        $(this.el).empty();
        _.each(this.model.models, function (user) {
            $(this.el).append(new HealthFileView({model:user}).render().el);
        }, this);
        return this;
    }
});

window.HealthFileView = Backbone.View.extend({

    tagName:"li",

    initialize:function () {
        this.model.bind("change", this.render, this);
        this.model.bind("destroy", this.close, this);
    },

    render:function () {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    }

});