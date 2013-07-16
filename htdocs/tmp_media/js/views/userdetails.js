window.UserView = Backbone.View.extend({

    tagName:"div", // Not required since 'div' is the default if no el or tagName specified

    initialize:function () {
//        this.template = templates['User'];
    },

    render: function () {
        $(this.el).html(this.template(this.model.toJSON()));
        $('#details', this.el).html(new UserSummaryView({model:this.model}).render().el);
        /*this.model.reports.fetch({
            success:function (data) {
                if (data.length == 0)
                    $('.no-reports').show();
            }
        });
        $('#reports', this.el).append(new UserListView({model:this.model.reports}).render().el);*/
        return this;
    }
});

window.UserSummaryView = Backbone.View.extend({

    tagName:"div", // Not required since 'div' is the default if no el or tagName specified

    initialize:function () {
//        this.template = templates['UserSummary'];
        this.model.bind("change", this.render, this);
    },

    render:function () {
        //$(this.el).html(this.template(this.model.toJSON()));
        return this;
    }

});