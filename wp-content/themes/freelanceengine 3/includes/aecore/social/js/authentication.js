(function ($) {
    AE.Views.SocialAuth = Backbone.View.extend({
        el: 'body',
        events: {
            'submit #form_username': 'confirm_username',
            'click .gplus_login_btn': 'gplusDoLogin',
            'click .lkin': 'lkinDoLogin',
            'change #user_role': 'onChangeRole'
        },

        /**
         * init view setup Block Ui and Model User
         */
        initialize: function () {
            this.blockUi = new AE.Views.BlockUi();
            this.initValidator();
        },

        onChangeRole: function (event) {
            var target = $(event.currentTarget);
            target.parent('div').removeClass('error');
        },
        /**
         * init form validator rules
         * can override this function by using prototype
         */
        initValidator: function () {
            // login rule
            this.$("form#form_username").validate({
                ignore: "",
                rules: {
                    user_login: "required",
                    user_role: "required"
                },
                errorClass: 'message',
                errorElement: "div",
                errorPlacement: function (error, element) {
                    if (element.attr("name") == "user_role") {
                        error.insertAfter("#user_role_chosen");
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function (element) {
                    $(element).parent('div').addClass('error');
                },
                unhighlight: function (element) {
                    $(element).parent('div').removeClass('error');
                }
            });
        },
        gplusDoLogin: function () {
            var view = this;
            $.ajax({
                url: ae_globals.ajaxURL,
                type: "get",
                data: {
                    action: "ae_gplus_auth",
                },
                beforeSend: function () {
                    view.blockUi.block('.gplus');
                },
                success: function (resp) {
                    if (resp.success) {
                        window.location.href = resp.redirect;
                    }
                    else {
                        AE.pubsub.trigger('ae:notification', {
                            msg: resp.msg,
                            notice_type: 'error',
                        });
                    }
                }
            });
        },
        lkinDoLogin: function (e) {
            var view = this;
            $.ajax({
                url: ae_globals.ajaxURL,
                type: "get",
                data: {
                    action: "ae_linked_auth",
                    state: "click"
                },
                beforeSend: function () {
                    view.blockUi.block('.lkin');
                },
                success: function (resp) {
                    if (resp.success) {
                        window.location.href = resp.redirect;
                    }
                    else {
                        AE.pubsub.trigger('ae:notification', {
                            msg: resp.msg,
                            notice_type: 'error',
                        });
                    }
                }
            });
        },

        confirm_username: function (event) {
            event.preventDefault();
            event.stopPropagation();
            this.removeError();

            /**
             * call validator init
             */
            this.initValidator();

            var form = $(event.currentTarget);
            var view = this;

            var params = {
                url: ae_globals.ajaxURL,
                type: 'post',
                data: {
                    action: ae_auth.action_confirm,
                    content: form.serializeObject()
                },
                beforeSend: function () {
                    var button = form.find('input[type=submit]');
                    view.blockUi.block(button);
                },
                success: function (resp) {

                    //console.log(resp);
                    if (resp.success == true) {
                        window.location = resp.data.redirect_url;
                    } else {
                        view.showError(form, resp.msg);
                        view.blockUi.unblock();
                    }
                },
                complete: function () {
                    view.showError(form, resp.msg);
                    view.blockUi.unblock();
                }
            }
            $.ajax(params);
        },
        /**
         * Remove HTML error
         */
        removeError: function () {
            $('.fre-validate-error').remove();
        },
        /**
         * Append HTML error
         */
        showError: function (form, msg) {
            var template = '<ul class="fre-validate-error">' +
                '<li>' + msg + '</li>' +
                '</ul>';
            form.prepend(template);
        }
    });

    $(document).ready(function () {
        var view = new AE.Views.SocialAuth();
    });
})(jQuery);