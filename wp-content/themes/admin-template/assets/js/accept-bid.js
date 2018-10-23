(function($, Views, Models, Collections) {
    $(document).ready(function() {
        Views.Modal_Transfer_Money = Views.Modal_Box.extend({
            el: '#modal_transfer_money',
            template : _.template($('#transfer_money_info_template').html()),
            events: {
                'submit form#transfer-money-form' : 'transfer_money'
            },
            initialize: function(){
                this.blockUi = new Views.BlockUi();
            },
            setProject: function(id) {
                this.project_id = id;
                this.getInformation();
            },
            getInformation: function(){
                var view = this;
                $.ajax({
                    url: ae_globals.ajaxURL,
                    type: 'post',
                    data: {
                        project_id: view.project_id,
                        action: 'transfer_money_info'
                    },
                    beforeSend: function() {
                        view.blockUi.block(view.$el);
                    },
                    success: function(res) {
                        if(res.data.success){
                            view.$el.find('.fre-transfer-money-info').html(view.template(res.data));
                        }
                        view.blockUi.unblock();
                    }
                });
            },
            transfer_money: function(event){
                event.preventDefault();
                var view = this,
                    $target = $(event.currentTarget);
                $.ajax({
                    url: ae_globals.ajaxURL,
                    type: 'post',
                    data: {
                        project_id: view.project_id,
                        action: 'transfer_money'
                    },
                    beforeSend: function() {
                        view.blockUi.block($target);
                    },
                    success: function(res) {
                        if (res.success) {
                            AE.pubsub.trigger('ae:notification', {
                                msg: res.msg,
                                notice_type: 'success'
                            });
                            location.href = location.href.replace(location.search, "");
                        } else {
                            view.blockUi.unblock();
                            AE.pubsub.trigger('ae:notification', {
                                msg: res.msg,
                                notice_type: 'error'
                            });
                        }
                    }
                });
            }
        });
        Views.Modal_AcceptBid = Views.Modal_Box.extend({
            el: '#acceptance_project',
            template: _.template($('#bid-info-template').html()),
            events: {
                // user register
                'submit form#escrow_bid': 'submit'
            },
            /**
             * init view setup Block Ui and Model User
             */
            initialize: function() {
                // init block ui
                this.blockUi = new Views.BlockUi();
            },
            // setup a bid id to modal accept bid
            setBidId: function(id) {
                this.bid_id = id;
                this.getPaymentInfo();
            },
            // load payment info and display
            getPaymentInfo: function() {
                var view = this;
                
                $.ajax({
                    url: ae_globals.ajaxURL,
                    type: 'get',
                    data: {
                        bid_id: view.bid_id,
                        action: 'ae-accept-bid-info',
                    },
                    beforeSend: function() {
                        view.blockUi.block(view.$el);
                    },
                    success: function(res) {
                        view.blockUi.unblock();
                        if (res.success) {
                            res.data['accept_bid'] = 1;
                            if(typeof fre_credit_globals != 'undefined' && fre_credit_globals.is_credit_escrow == 1){
                                // add more data
                                res.data['available_balance'] = fre_credit_globals.balance_format;
                                if(parseFloat(res.data.data_not_format.total) <= parseFloat(fre_credit_globals.balance_number_format)){
                                    res.data['accept_bid'] = 1;
                                }else{
                                    res.data['accept_bid'] = 0;
                                }
                            }
                            view.$el.find('.escrow-info').html(view.template(res.data));

                            if(typeof fre_credit_globals != 'undefined' && fre_credit_globals.is_credit_escrow == 1){
                                // check balance
                                if(parseFloat(res.data.data_not_format.total) <= parseFloat(fre_credit_globals.balance_number_format)){
                                    view.$el.find('.notice_credit').html(fre_credit_globals.text_acceptance_bid.success);
                                }else{
                                    var project_data = JSON.parse($('body').find('#project_data').html());
                                    var url_deposit = fre_credit_globals.url_deposit + '?project_id=' + project_data.ID;
                                    view.$el.find('.escrow-info').find('.btn-buy-credit').attr('href', url_deposit);
                                    view.$el.find('.notice_credit').html('<span class="error">'+fre_credit_globals.text_acceptance_bid.fail+'</span>');
                                }
                            }
                        }else{
                            AE.pubsub.trigger('ae:notification', {
                                msg: res.msg,
                                notice_type: 'error'
                            });
                        }
                    }
                });
            },
            // submit accept bid an pay
            submit: function(event) {
                event.preventDefault();
                var $target = $(event.currentTarget),
                    view = this;
                var data = $target.serialize();
                $.ajax({
                    url: ae_globals.ajaxURL,
                    type: 'post',
                    data: {
                        bid_id: view.bid_id,
                        action: 'ae-escrow-bid',
                        data: data
                    },
                    beforeSend: function() {
                        view.blockUi.block($target);
                    },
                    success: function(res) {
                        if (res.redirect_url) {
                            window.location.href = res.redirect_url;
                        }else{
                            AE.pubsub.trigger('ae:notification', {
                                msg : res.msg,
                                notice_type : 'error'
                            })
                            view.blockUi.unblock();
                        }
                    }
                });
            }
        });
    });
})(jQuery, AE.Views, AE.Models, AE.Collections);