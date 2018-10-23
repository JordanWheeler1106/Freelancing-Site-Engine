(function ($, Views, Models, Collections) {

    $(document).ready(function () {

        var resized = "0";
        var didResize;
        AE.Views.SingleProject = Backbone.View.extend({
            // action: 'ae-project-sync',
            el: 'body.single',
            events: {
                'click a.btn-apply-project': 'modalBidProject',
                'click a.purchase-bid-btn': 'modalPurchaseBid',
                'click a.btn-accept-bid': 'confirmShow',
                'click a.btn-accept-bid-no-escrow': 'confirmShowNoEscrow',
                'click a.btn-complete-project': 'showReviewModal',
                'click .confirm .btn-agree': 'acceptBid',
                'click .confirm .btn-skip': 'skipAccept',
                // open close project modal
                'click a.btn-close-project': 'openCloseProjectModal',
                // freelancer quit project
                'click a.btn-quit-project': 'openQuitProjectModal',
                /*
                 * delete a bidding
                 */
                'click .btn-del-project': 'deleteBidding',
                /*
                 *for mobile js
                 */
                'click .btn-bid-mobile': 'toggleBidForm',
                'submit form.bid-form-mobile': 'submitBidProject',
                // 'click .btn-complete-mobile': 'toggleReviewForm',
                'submit form.review-form-mobile': 'submitReview',

                'mouseleave .single-project-wrapper .info-bidding': 'hideAccept',
                'mouseover .single-project-wrapper .info-bidding': 'showAccept',
                // 'click a.btn-refund-project' : 'refundProjectPayment',
                // 'click a.manual-transfer' : 'transferMoney',
                'click a.manual-transfer': 'transferMoneyModal',
                // 'submit form.transfer-escrow': 'executeProjectPayment',
                // user click on action button such as edit, archive, reject
                'click a.action': 'acting',
                'click .bid_unaccepted .show-info': 'showInfoBid',
                'click .btn-arbitrate-project': 'showArbitrateModal',
                'click .bid-action': 'bidActionModal',
                'click .project-action': 'projectActionModal',
                'click .delete-attach-file': 'deletedAttachFile',
                'click .lock-file-upload-btn': 'lockFile'
            },
            deletedAttachFile: function (event) {
                event.preventDefault();
                var $target = event.target;
                if (typeof view.modal_delete_file == 'undefined') {
                    view.modal_delete_file = new Views.Modal_Delete_File();
                }
                view.modal_delete_file.$('#post-id').val($target.getAttribute('data-post-id'));
                view.modal_delete_file.$('#file-name').val($target.getAttribute('data-file-name'));
                view.modal_delete_file.$('#project-id').val($target.getAttribute('data-project-id'));
                view.modal_delete_file.openModal();
            },
            modalPurchaseBid: function (event) {
                var view = this;
                event.preventDefault();
                var $target = event.target;
                if (typeof view.modal_purchase_bid == 'undefined') {
                    view.modal_purchase_bid = new Views.Modal_Purchase_Bid_Project();
                }
                view.modal_purchase_bid.$('#project-id').val($target.getAttribute('data-project-id'));
                view.modal_purchase_bid.openModal();
            },
            bidActionModal: function (event) {
                var view = this;
                event.preventDefault();
                var $target = event.target;
                if ($target.getAttribute('data-action') == 'remove') {
                    if (typeof view.modal_remove_bid == 'undefined') {
                        view.modal_remove_bid = new Views.Modal_Remove_Bid();
                    }
                    view.modal_remove_bid.$('#bid-id').val($target.getAttribute('data-bid-id'));
                    view.modal_remove_bid.openModal();
                } else if ($target.getAttribute('data-action') == 'cancel') {
                    if (typeof view.modal_cancel_bid == 'undefined') {
                        view.modal_cancel_bid = new Views.Modal_Cancel_Bid();
                    }
                    view.modal_cancel_bid.$('#bid-id').val($target.getAttribute('data-bid-id'));
                    view.modal_cancel_bid.openModal();
                }
            },
            projectActionModal: function (event) {
                event.preventDefault();
                var $target = event.target;
                var view = this;
                if ($target.getAttribute('data-action') == 'delete') {
                    if (typeof view.modal_delete_project == 'undefined') {
                        view.modal_delete_project = new Views.Modal_Delete_Project();
                    }
                    view.modal_delete_project.$('#project-id').val($target.getAttribute('data-project-id'));
                    view.modal_delete_project.openModal();
                } else if ($target.getAttribute('data-action') == 'archive') {
                    if (typeof view.modal_archive_project == 'undefined') {
                        view.modal_archive_project = new Views.Modal_Archive_Project();
                    }
                    view.modal_archive_project.$('#project-id').val($target.getAttribute('data-project-id'));
                    view.modal_archive_project.openModal();
                } else if ($target.getAttribute('data-action') == 'approve') {
                    if (typeof view.modal_approve_project == 'undefined') {
                        view.modal_approve_project = new Views.Modal_Approve_Project();
                    }
                    view.modal_approve_project.$('#project-id').val($target.getAttribute('data-project-id'));
                    view.modal_approve_project.openModal();
                } else if ($target.getAttribute('data-action') == 'reject') {
                    if (typeof view.modal_reject_project == 'undefined') {
                        view.modal_reject_project = new Views.Modal_Reject_Project();
                    }
                    view.modal_reject_project.$('#project-id').val($target.getAttribute('data-project-id'));
                    view.modal_reject_project.openModal();
                }
            },
            showInfoBid: function (e) {
                var $target = $(e.currentTarget),
                    container = $target.parents('.bid_unaccepted');

                if (ae_globals.ae_is_mobile == 1) {
                    $('.info-bidding-wrapper .list-bidding .info-bidding .bid-item').each(function () {
                        if ($(this).hasClass('bid-item-close') ||
                            $(this).hasClass('bid-item-complete') ||
                            $(this).hasClass('bid-item-disputing') ||
                            $(this).hasClass('bid-item-disputed')) return false;
                        $(this).addClass('bid_hide');
                        $(this).removeClass('icon-show-info');
                    });
                } else {
                    $('.info-bidding-wrapper .list-bidding .info-bidding').each(function () {
                        if ($(this).hasClass('bid-item-close') ||
                            $(this).hasClass('bid-item-complete') ||
                            $(this).hasClass('bid-item-disputing') ||
                            $(this).hasClass('bid-item-disputed')) return false;
                        $(this).addClass('bid_hide');
                        $(this).removeClass('icon-show-info');
                    });
                }

                if ($(container).hasClass('icon-show-info')) {
                    $(container).addClass('bid_hide');
                    $(container).removeClass('icon-show-info');
                } else {
                    $(container).removeClass('bid_hide');
                    $(container).addClass('icon-show-info');
                }
            },
            lockFile: function (event) {
                event.preventDefault();
                var $target = event.target;
                if ($target.getAttribute('data-action') == 'lock') {
                    if (typeof view.modal_lock_file == 'undefined') {
                        view.modal_lock_file = new Views.Modal_Lock_File();
                    }
                    view.modal_lock_file.$('#project-id').val($target.getAttribute('data-project-id'));
                    view.modal_lock_file.openModal();
                } else if ($target.getAttribute('data-action') == 'unlock') {
                    if (typeof view.modal_unlock_file == 'undefined') {
                        view.modal_unlock_file = new Views.Modal_Unlock_File();
                    }
                    view.modal_unlock_file.$('#project-id').val($target.getAttribute('data-project-id'));
                    view.modal_unlock_file.openModal();
                }
            },
            acting: function (e) {
                // e.preventDefault();
                var target = $(e.currentTarget),
                    action = target.attr('data-action'),
                    model = this.model;
                view = this;
                // fetch model data
                switch (action) {
                    case 'edit':
                        //trigger an event will be catch by AE.App to open modal edit
                        AE.pubsub.trigger('ae:model:onEdit', model);
                        break;
                    case 'reject':
                        //trigger an event will be catch by AE.App to open modal reject
                        AE.pubsub.trigger('ae:model:onReject', model);
                        break;
                    case 'archive':
                        // archive a model
                        //model.set('do', 'archivePlace');
                        if (confirm(ae_globals.confirm_message)) {
                            model.set('archive', 1);
                            model.save('archive', '1', {
                                beforeSend: function () {
                                    view.blockUi.block(target);
                                },
                                success: function (result, status) {
                                    view.blockUi.unblock();
                                    if (status.success) {
                                        AE.pubsub.trigger('ae:notification', {
                                            msg: status.msg,
                                            notice_type: 'success',
                                        });
                                        window.location.reload();
                                    } else {
                                        AE.pubsub.trigger('ae:notification', {
                                            msg: status.msg,
                                            notice_type: 'error',
                                        });
                                    }
                                }
                            });
                        }
                        break;
                    case 'delete':
                        if (confirm(ae_globals.confirm_message)) {
                            // archive a model
                            this.model.save('delete', '1', {
                                beforeSend: function () {
                                    view.blockUi.block(target);
                                },
                                success: function (result, status) {
                                    view.blockUi.unblock();
                                    if (status.success) {
                                        AE.pubsub.trigger('ae:notification', {
                                            msg: status.msg,
                                            notice_type: 'success',
                                        });
                                        window.location.reload();
                                    } else {
                                        AE.pubsub.trigger('ae:notification', {
                                            msg: status.msg,
                                            notice_type: 'error',
                                        });
                                    }
                                }
                            });
                        }
                        break;
                    case 'toggleFeature':
                        // toggle featured
                        //model.set('do', 'toggleFeature');
                        if (parseInt(model.get('et_featured')) == 1) {
                            model.set('et_featured', 0);
                        } else {
                            model.set('et_featured', 1);
                        }
                        model.save('', '', {
                            beforeSend: function () {
                                view.blockUi.block(target);
                            },
                            success: function (result, status) {
                                view.blockUi.unblock();
                                if (status.success) {
                                    AE.pubsub.trigger('ae:notification', {
                                        msg: status.msg,
                                        notice_type: 'success',
                                    });
                                    window.location.reload();
                                } else {
                                    AE.pubsub.trigger('ae:notification', {
                                        msg: status.msg,
                                        notice_type: 'error',
                                    });
                                }
                            }
                        });
                        break;
                    case 'approve':
                        // publish a model
                        model.save('publish', '1', {
                            beforeSend: function () {
                                view.blockUi.block(target);
                            },
                            success: function (result, status) {
                                view.blockUi.unblock();
                                if (status.success) {
                                    window.location.href = model.get('permalink');
                                }
                            }
                        });
                        break;
                    case 'resolve-dispute' :
                        // publish a model
                        model.save('disputed', '1', {
                            beforeSend: function () {
                                view.blockUi.block(target);
                            },
                            success: function (result, status) {
                                view.blockUi.unblock();
                                if (status.success) {
                                    window.location.href = model.get('permalink');
                                }
                            }
                        });
                        break;
                    default:
                        break;
                }
            },
            /**
             * init view single project
             */
            initialize: function () {
                _.bindAll(this, 'modalBidProject');
                var view = this;
                if ($('body').find('.biddata').length > 0) {
                    // parset biddata to create collection
                    var biddata = JSON.parse($('body').find('.biddata').html());
                    // create a bid collections
                    this.collection_bids = new Collections.Bids(biddata);
                } else {
                    this.collection_bids = new Collections.Bids();
                }

                this.model = new AE.Models.Project(JSON.parse($('body').find('#project_data').html()));
                // get project id
                view.project_id = this.$el.find('input#project_id').val();
                // init modal bid for freelancer can user to submit a bid
                view.modal_bid = new AE.Views.Modal_Bid();

                // init block ui
                view.blockUi = new Views.BlockUi();
                if (ae_globals.ae_is_mobile == 1) {
                    var listbid = $('.info-bidding-wrapper .list-history-bidders'),
                        el = this.$(".info-bidding-wrapper");
                } else {
                    var listbid = $('.list-bid-project .list-bidden'),
                        el = this.$(".list-bid-project");
                }
                new SingleListBids({
                    //itemView: BidItem,
                    collection: this.collection_bids,
                    el: $(listbid)
                });
                if (typeof Views.BlockControl !== "undefined") {
                    //list user bid control
                    new Views.BlockControl({
                        collection: this.collection_bids,
                        el: $(el),
                        query: {
                            paginate: 'page'
                        },
                        onAfterFetch: function (result, res) {
                            $.fn.trimContent();
                        }
                    });
                }
                $(".btn-login-trigger").click(function () {
                    $("a.login-btn").trigger('click');
                });
                $('.rating-it').raty({
                    half: true,
                    hints: raty.hint
                });
                if ($('.info-bidding').hasClass('bid-of-user')) {
                    $('.bid-of-user').removeClass('bid_hide');
                    $('.bid-of-user').removeClass('bid_unaccepted');
                }
                // view.resize();
                didResize = false;
                $(window).resize(function () {
                    return didResize = true;
                });
                setInterval((function () {
                    if (didResize) {
                        didResize = false;
                        // view.resize();
                    }
                }), 250);
                this.trimContent();
            },
            resize: function (event) {
                var _singleProjectsH = $('.single-projects .project-item').height();
                var _btnH = $('.single-projects .btn-apply-project-item').outerHeight();
                if ((resized === "0")) {
                    if ($('.single-projects .content-title-project-item').height() < 20) {
                        $('.single-projects .content-title-project-item').css({
                            'line-height': _singleProjectsH + 'px'
                        });
                    }
                    $('.author-link-project-item, .time-post-project-item, .budget-project-item').css({
                        'line-height': _singleProjectsH + 'px'
                    });
                    $('.btn-apply-project-item').css({
                        'margin-top': (_singleProjectsH - _btnH) / 2 + 'px'
                    });
                    resized = 1;
                }
                else {
                    if ($('.single-projects .content-title-project-item').height() < 20) {
                        $('.single-projects .content-title-project-item').css({
                            'line-height': _singleProjectsH + 'px'
                        });
                    }
                    $('.author-link-project-item, .time-post-project-item, .budget-project-item').css({
                        'line-height': _singleProjectsH + 'px'
                    });
                    $('.btn-apply-project-item').css({
                        'margin-top': (_singleProjectsH - _btnH) / 2 + 'px'
                    });
                }
            },
            // show modal bid project
            modalBidProject: function () {
                var view = this;
                view.modal_bid.openModal();
            },
            showArbitrateModal: function (event) {
                var view = this;
                var $target = event.target;
                if (typeof view.Modal_Arbitrate == 'undefined') {
                    view.Modal_Arbitrate = new AE.Views.Modal_Arbitrate();
                }
                view.Modal_Arbitrate.setProject($target.getAttribute('id'));
                view.Modal_Arbitrate.openModal();
            },
            // open modal review project
            showReviewModal: function (event) {
                var view = this;
                event.preventDefault();
                var $target = event.target;
                if (typeof view.modal_review == 'undefined') {
                    view.modal_review = new AE.Views.Modal_Review();
                }
                view.modal_review.$('#project-id').val($target.getAttribute('id'));
                view.modal_review.openModal();
            },
            confirmShowNoEscrow: function (event) {
                event.preventDefault();
                var $target = $(event.currentTarget),
                    view = this;
                view.bid_id = $target.attr('id');
                if (typeof view.acceptbid_no_escrow_modal == 'undefined') {
                    view.acceptbid_no_escrow_modal = new Views.Modal_AcceptBid_NoEscrow();
                }
                view.acceptbid_no_escrow_modal.setBidId(view.bid_id);
                view.acceptbid_no_escrow_modal.openModal();
            },
            /**
             *Emplooyer accept this bid
             */
            confirmShow: function (event) {
                event.preventDefault();
                var $target = $(event.currentTarget),
                    view = this;
                view.bid_id = $target.attr('id');
                if (typeof Views.Modal_AcceptBid !== 'undefined') {
                    if (typeof view.acceptbid_modal == 'undefined') {
                        view.acceptbid_modal = new Views.Modal_AcceptBid();
                    }
                    view.acceptbid_modal.setBidId(view.bid_id);
                    view.acceptbid_modal.openModal();
                }
            },
            // open modal close project
            openCloseProjectModal: function (event) {
                var view = this;
                event.preventDefault();
                var $target = event.target;
                if (typeof view.modal_close === 'undefined') {
                    view.modal_close = new AE.Views.Modal_Close();
                }
                view.modal_close.$('#project-id').val($target.getAttribute('id'));
                view.modal_close.openModal();
            },
            // open modal quit project
            openQuitProjectModal: function (event) {
                var view = this;
                var $target = event.target;
                if (typeof view.modal_quit === 'undefined') {
                    view.modal_quit = new AE.Views.Modal_Quit();
                }
                view.modal_quit.setProject($target.getAttribute('id'));
                view.modal_quit.openModal();
            },
            /*
             * For freelancer delete a bidding.
             */
            deleteBidding: function (event) {
                event.preventDefault();
                var view = this,
                    $target = $(event.currentTarget),
                    bid_id = $target.attr('ID');
                $.ajax({
                    url: ae_globals.ajaxURL,
                    type: 'post',
                    data: {
                        ID: bid_id,
                        action: 'ae-sync-bid',
                        method: 'remove'
                    },
                    beforeSend: function () {
                        view.blockUi.block($target);
                    },
                    success: function (res) {
                        if (res.success) {
                            $target.closest('.info-bidding').remove();
                            AE.pubsub.trigger('ae:notification', {
                                msg: res.msg,
                                notice_type: 'success'
                            });
                        } else {
                            AE.pubsub.trigger('ae:notification', {
                                msg: res.msg,
                                notice_type: 'error'
                            });
                        }
                        location.reload();
                    }
                });
            },
            /*
             * for mobile version. toggle bid form
             */
            toggleBidForm: function (event) {
                event.preventDefault();
                var display = $('#bid_form').css('display');
                if (display == 'block') $('#bid_form').slideUp();
                else $('#bid_form').slideDown();
                return false;
            },
            initValidator: function () {
                this.bidFormMobile_validator = $("form.bid-form-mobile").validate({
                    ignore: "",
                    rules: {
                        bid_budget: "required",
                        bid_time: "required",
                        bid_content: "required",
                    }
                    // errorPlacement: function(label, element) {
                    //     // position error label after generated textarea
                    //     if (element.is("textarea")) {
                    //         label.insertAfter(element.next());
                    //     } else {
                    //         $(element).closest('div').append(label);
                    //     }
                    // }
                });
            },
            /*
             *submid bid on mobile version
             */
            submitBidProject: function (event) {
                event.preventDefault();
                var view = this,
                    $target = $(event.currentTarget),
                    button = $target.find('button.btn-submit');
                data = $target.serializeObject() || [];
                this.initValidator();
                if (this.bidFormMobile_validator.form()) {
                    $.ajax({
                        url: ae_globals.ajaxURL,
                        type: 'post',
                        data: data,
                        beforeSend: function () {
                            view.blockUi.block(button);
                        },
                        success: function (res) {
                            view.blockUi.unblock();
                            AE.pubsub.trigger('ae:notification', {
                                msg: res.msg,
                                notice_type: res.success
                            });
                            if (res.success) {
                                location.reload();
                            } else {
                                AE.pubsub.trigger('ae:notification', {
                                    msg: res.msg,
                                    notice_type: 'error'
                                });
                            }
                        }
                    });
                    return false;
                }
            },
            /*
             * toggle review form on mobile version.
             */
            // toggleReviewForm: function(event) {
            //     event.preventDefault();
            //     var display = $('#review_form').css('display');
            //     if (display == 'block') $('#review_form').slideUp();
            //     else $('#review_form').slideDown();
            //     return false;
            // },
            /*
             * review on mobile version
             */
            submitReview: function (event) {
                event.preventDefault();
                var view = this,
                    $target = $(event.currentTarget),
                    button = $target.find('button.btn-submit');
                data = $target.serializeObject() || [];
                view.blockUi = new Views.BlockUi();
                $.ajax({
                    url: ae_globals.ajaxURL,
                    type: 'post',
                    data: data,
                    beforeSend: function () {
                        view.blockUi.block(button);
                    },
                    success: function (res) {
                        view.blockUi.unblock();
                        if (res.success) {
                            AE.pubsub.trigger('ae:notification', {
                                msg: res.msg,
                                notice_type: 'success'
                            });
                            $(".btn-project-status").removeClass('btn-complete-project');
                            $(".btn-project-status").html(single_text.completed);
                        } else {
                            AE.pubsub.trigger('ae:notification', {
                                msg: res.msg,
                                notice_type: 'error'
                            });
                        }
                    }
                });
                return false;
            },
            // show confirm accept bid
            showAccept: function (event) {
                //btn-accept-bid
                var $target = $(event.currentTarget);
                /*$('.info-bidding').find('.btn-accept-bid').hide();*/
                if (!$target.hasClass('hide-accept') && $target.find('.btn-accept-bid').length) {
                    /* $target.find('.btn-accept-bid').show();
                     $target.find('span.number-price').hide();
                     $target.find('span.number-day').hide();*/
                    $target.find('.btn-accept-bid').tooltip();
                }
                // $('.btn-accept-bid').tooltip();
            },
            // hid confirm accept bid
            hideAccept: function (event) {
                //btn-accept-bid
                var $target = $(event.currentTarget);
                /*$target.find('.btn-accept-bid').hide();
                $target.find('span.number-price').show();
                $target.find('span.number-day').show();*/
                // $('[data-toggle="tooltip"]').tooltip();
            },
            /**
             * refund payment to employer
             */
            // refundProjectPayment : function(event){
            //     event.preventDefault();
            //     var view = this,
            //         $target = $(event.currentTarget);
            //     if(confirm('You are going to send money back to employer.')) {
            //         $.ajax({
            //             url: ae_globals.ajaxURL,
            //             type: 'post',
            //             data : {project_id : view.project_id, action:'refund_payment'},
            //             beforeSend: function(){
            //                 view.blockUi.block($target);
            //             },
            //             success:function(res){
            //                 view.blockUi.unblock();
            //                 if (res.success) {
            //                     AE.pubsub.trigger('ae:notification', {
            //                         msg: res.msg,
            //                         notice_type: 'success'
            //                     });
            //                 } else {
            //                     AE.pubsub.trigger('ae:notification', {
            //                         msg: res.msg,
            //                         notice_type: 'error'
            //                     });
            //                 }
            //             }
            //         });
            //     }
            // },
            //
            transferMoneyModal: function (event) {
                var view = this;
                if (typeof view.Modal_Transfer_Money === 'undefined' && $('#transfer_money_info_template').length > 0) {
                    view.Modal_Transfer_Money = new Views.Modal_Transfer_Money();
                }
                var $target = event.target;
                view.Modal_Transfer_Money.setProject($target.getAttribute('data-project-id'));
                view.Modal_Transfer_Money.openModal();
            },
            // send payment to freelancer
            executeProjectPayment: function (event) {
                event.preventDefault();
                var view = this,
                    $target = $(event.currentTarget);
                if ($target.find('.transfer-select').val() == 'freelancer') {
                    var text = ae_globals.text_message.execute,
                        action = 'execute_payment';
                } else {
                    var text = ae_globals.text_message.refund,
                        action = 'refund_payment';
                }
                if (confirm(text)) {
                    $.ajax({
                        url: ae_globals.ajaxURL,
                        type: 'post',
                        data: {
                            project_id: view.project_id,
                            action: action
                        },
                        beforeSend: function () {
                            view.blockUi.block($target);
                        },
                        success: function (res) {
                            view.blockUi.unblock();
                            if (res.success) {
                                AE.pubsub.trigger('ae:notification', {
                                    msg: res.msg,
                                    notice_type: 'success'
                                });
                                location.reload();
                            } else {
                                AE.pubsub.trigger('ae:notification', {
                                    msg: res.msg,
                                    notice_type: 'error'
                                });
                            }
                        }
                    });
                }
            },
            trimContent: function () {
                var showChar = 90;  // How many characters are shown by default
                var ellipsestext = "...";
                $('.comment-author-history p').each(function () {
                    var content = $(this).html();
                    if (content.length > showChar && !$(this).parent().find('.show-more').length) {
                        var c = content.substr(0, showChar);
                        var h = content.substr(showChar, content.length - showChar);
                        var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent" style="display:none;"><span>' + h + '</span>&nbsp;&nbsp;</span>';
                        $(this).html(html);
                        var html_view = '<a class="show-more">' + ae_globals.text_view.more + '</a>';
                        $(this).parent().append(html_view);
                    }
                });

                $(".show-more").click(function (e) {
                    e.preventDefault();
                    var content = $(this).parent();
                    if ($(this).hasClass("less")) {
                        $(content).find('p .morecontent').hide();
                        $(content).find('p .moreellipses').show();
                        $(this).removeClass("less");
                        $(this).html(ae_globals.text_view.more);
                    } else {
                        $(content).find('p .morecontent').show();
                        $(content).find('p .moreellipses').hide();
                        $(this).addClass("less");
                        $(this).html(ae_globals.text_view.less);
                    }
                });
            }
        });
        AE.Views.Modal_Arbitrate = AE.Views.Modal_Box.extend({
            el: '#modal_arbitrate',
            events: {
                'submit form#arbitrate_form': 'submitArbitrate'
            },
            initialize: function () {
                AE.Views.Modal_Box.prototype.initialize.apply(this, arguments);
                this.blockUi = new Views.BlockUi();
                this.initValidator();
            },
            initValidator: function () {
                this.arbitrateForm_validator = $("form#arbitrate_form").validate({
                    ignore: "",
                    rules: {
                        comment_resolved: "required",
                        transfer_select: "required",
                    }
                });
            },
            setProject: function (project_id) {
                this.project_id = project_id;
            },
            submitArbitrate: function (event) {
                event.preventDefault();
                var view = this,
                    $target = $(event.currentTarget),
                    button = $target.find('button.btn-submit');
                if (this.arbitrateForm_validator.form() && !$target.hasClass("processing")) {
                    var transfer_select = $target.find('input[name=transfer_select]:checked').val();
                    if (transfer_select == 'freelancer') {
                        var action = 'execute_payment';
                    } else {
                        var action = 'refund_payment';
                    }
                    var comment_resolved = $target.find('textarea').val();
                    $.ajax({
                        url: ae_globals.ajaxURL,
                        type: 'post',
                        data: {
                            project_id: view.project_id,
                            action: action,
                            comment: comment_resolved,
                            winner: transfer_select
                        },
                        beforeSend: function () {
                            view.blockUi.block($target);
                        },
                        success: function (res) {
                            if (res.success) {
                                AE.pubsub.trigger('ae:notification', {
                                    msg: res.msg,
                                    notice_type: 'success'
                                });
                                location.reload();
                            } else {
                                AE.pubsub.trigger('ae:notification', {
                                    msg: res.msg,
                                    notice_type: 'error'
                                });
                                view.blockUi.unblock();
                            }
                        }
                    });
                }
            }
        });
        AE.Views.Modal_Bid = AE.Views.Modal_Box.extend({
            el: '#modal_bid',
            events: {
                'submit form.bid-form': 'submitBidProject',
            },
            initialize: function () {
                AE.Views.Modal_Box.prototype.initialize.apply(this, arguments);
                this.blockUi = new Views.BlockUi();

            },
            initValidator: function () {
                this.bidForm_validator = $("form#bid_form").validate({
                    ignore: "",
                    rules: {
                        bid_budget: "required",
                        bid_time: "required",
                        bid_content: "required",
                    },
                    errorPlacement: function (label, element) {
                        // position error label after generated textarea
                        if (element.is("#bid_budget")) {
                            $(element).closest('.fre-project-budget').append(label);
                        } else {
                            $(element).closest('.fre-input-field').append(label);
                        }
                    },
                    highlight: function (element, errorClass) {
                        $(element).closest('.fre-input-field').addClass('error');
                    },
                    unhighlight: function (element, errorClass) {
                        $(element).closest('.fre-input-field').removeClass('error');
                    },
                });
            },
            submitBidProject: function (event) {
                event.preventDefault();
                this.initValidator();

                var view = this,
                    $target = $(event.currentTarget),
                    button = $target.find('button.btn-submit');
                data = $target.serializeObject() || [];
                if (this.bidForm_validator.form() && !$target.hasClass("processing")) {
                    $.ajax({
                        url: ae_globals.ajaxURL,
                        type: 'post',
                        data: data,
                        beforeSend: function () {
                            view.blockUi.block(button);
                        },
                        success: function (res) {
                            view.blockUi.unblock();

                            if (res.success) {
                                // AE.pubsub.trigger('ae:after:bid', res);
                                AE.pubsub.trigger('ae:notification', {
                                    msg: res.msg,
                                    notice_type: 'success'
                                });
                                setTimeout(function () {
                                    location.reload();
                                }, 3000);
                                view.closeModal();
                            } else {
                                AE.pubsub.trigger('ae:notification', {
                                    msg: res.msg,
                                    notice_type: 'error'
                                });
                            }
                        }
                    });
                }
                return false;
            },
        });
        AE.Views.Modal_Review = AE.Views.Modal_Box.extend({
            el: '#modal_review',
            events: {
                'submit form.review-form': 'submitReview',
            },
            initialize: function () {
                AE.Views.Modal_Box.prototype.initialize.apply(this, arguments);
                this.blockUi = new Views.BlockUi();
                $("form.review-form").validate({
                    ignore: "",
                    rules: {
                        comment_content: "required",
                    }
                });
            },
            setProject: function (project_id) {
                this.project_id = project_id;
            },
            submitReview: function (event) {
                event.preventDefault();
                var view = this,
                    $target = $(event.currentTarget),
                    data = $target.serializeObject() || [];
                data.project_id = this.$('#project-id').val();
                $.ajax({
                    url: ae_globals.ajaxURL,
                    type: 'post',
                    data: data,
                    beforeSend: function () {
                        view.blockUi.block($target);
                    },
                    success: function (res) {
                        view.blockUi.unblock();
                        if (res.success) {
                            AE.pubsub.trigger('ae:notification', {
                                msg: res.msg,
                                notice_type: 'success'
                            });
                            // view.closeModal();
                            // $(".btn-project-status").removeClass('btn-complete-project');
                            // $(".btn-project-status").html(single_text.completed);
                            window.location.reload();
                        } else {
                            AE.pubsub.trigger('ae:notification', {
                                msg: res.msg,
                                notice_type: 'error'
                            });
                        }
                    }
                });
                return false;
            },
        });
        AE.Views.Modal_Finish = AE.Views.Modal_Box.extend({
            el: '#finish_project',
            events: {},
            initialize: function (options) {
                AE.Views.Modal_Box.prototype.initialize.apply(this, arguments);
                this.blockUi = new Views.BlockUi();
                // $("form.review-form").validate({
                //     ignore: "",
                //     rules: {
                //         post_content: "required",
                //     }
                // });
            },
            submitFinish: function () {
            }
        });
        AE.Views.Modal_Close = AE.Views.Modal_Box.extend({
            el: '#quit_project',
            events: {
                'submit form.quit_project_form': 'submitClose'
            },
            initialize: function (options) {
                AE.Views.Modal_Box.prototype.initialize.apply(this, arguments);
                this.blockUi = new Views.BlockUi();
                this.$("form.quit_project_form").validate({
                    ignore: "",
                    rules: {
                        comment_content: "required",
                    }
                });
            },
            setProject: function (project_id) {
                this.project_id = project_id;
            },
            submitClose: function (event) {
                event.preventDefault();
                event.preventDefault();
                var view = this,
                    $target = $(event.currentTarget),
                    // button = $target.find('button.btn-submit');
                    data = $target.serializeObject() || [];
                data.comment_post_ID = this.$('#project-id').val();
                data.action = 'fre-close-project';
                $.ajax({
                    url: ae_globals.ajaxURL,
                    type: 'post',
                    data: data,
                    beforeSend: function () {
                        view.blockUi.block($target);
                    },
                    success: function (res) {
                        view.blockUi.unblock();
                        if (res.success) {
                            window.location.href = res.url;
                        } else {
                            AE.pubsub.trigger('ae:notification', {
                                msg: res.msg,
                                notice_type: 'error'
                            });
                        }
                    }
                });
            }
        });
        AE.Views.Modal_Quit = AE.Views.Modal_Box.extend({
            el: '#quit_project',
            events: {
                'submit form.quit_project_form': 'submitQuit'
            },
            initialize: function (options) {
                AE.Views.Modal_Box.prototype.initialize.apply(this, arguments);
                this.blockUi = new Views.BlockUi();
                this.$("form.quit_project_form").validate({
                    ignore: "",
                    rules: {
                        comment_content: "required",
                    }
                });
            },
            setProject: function (project_id) {
                this.project_id = project_id;
            },
            submitQuit: function (event) {
                event.preventDefault();
                var view = this,
                    $target = $(event.currentTarget),
                    // button = $target.find('button.btn-submit');
                    data = $target.serializeObject() || [];
                data.comment_post_ID = view.project_id;
                data.action = 'fre-quit-project';
                $.ajax({
                    url: ae_globals.ajaxURL,
                    type: 'post',
                    data: data,
                    beforeSend: function () {
                        view.blockUi.block($target);
                    },
                    success: function (res) {
                        view.blockUi.unblock();
                        if (res.success) {
                            window.location.href = res.url;
                        } else {
                            AE.pubsub.trigger('ae:notification', {
                                msg: res.msg,
                                notice_type: 'error'
                            });
                        }
                    }
                });
            }
        });
        new AE.Views.SingleProject();
        AE.Views.Modal_AcceptBid_NoEscrow = Views.Modal_Box.extend({
            el: '#accept-bid-no-escrow',
            events: {
                // user register
                'click form#accept_bid_no_escrow button#submit_accept_bid': 'submit'
            },
            /**
             * init view setup Block Ui and Model User
             */
            initialize: function () {
                // init block ui
                this.blockUi = new Views.BlockUi();
            },
            // setup a bid id to modal accept bid
            setBidId: function (id) {
                this.bid_id = id;
            },
            // submit accept bid an pay
            submit: function (event) {
                event.preventDefault();
                var $target = $(event.currentTarget),
                    view = this;
                $.ajax({
                    url: ae_globals.ajaxURL,
                    type: 'post',
                    data: {
                        bid_id: view.bid_id,
                        action: 'ae-accept-bid',
                    },
                    beforeSend: function () {
                        view.blockUi.block($target);
                    },
                    success: function (res) {
                        if (res.success) {
                            window.location.reload();
                            AE.pubsub.trigger('ae:notification', {
                                msg: res.msg,
                                notice_type: 'success'
                            })
                            // view.closeModal();
                        } else {
                            view.blockUi.unblock();
                            AE.pubsub.trigger('ae:notification', {
                                msg: res.msg,
                                notice_type: 'error'
                            })
                        }
                    }
                });
            }
        });
    });

    //Modal Purchase Bid Project
    Views.Modal_Purchase_Bid_Project = AE.Views.Modal_Box.extend({
        el: '#modal_not_bid',
        events: {
            'submit form#not_bid_form': 'purchaseBidPackage'
        },
        initialize: function () {
            AE.Views.Modal_Box.prototype.initialize.apply(this, arguments);
            this.blockUi = new Views.BlockUi();
        },
        purchaseBidPackage: function (event) {
            event.preventDefault();
            var project_id = this.$('#project-id').val();
            window.location.href = ae_globals.purchase_bid + '?project_id=' + project_id;
        }
    });

    //Modal Approve Project
    Views.Modal_Approve_Project = AE.Views.Modal_Box.extend({
        el: '#modal_approve_project',
        events: {
            'submit form.form-approve-project': 'approveProject'
        },
        initialize: function () {
            AE.Views.Modal_Box.prototype.initialize.apply(this, arguments);
            this.blockUi = new Views.BlockUi();
        },
        approveProject: function (event) {
            event.preventDefault();
            var view = this,
                $target = $(event.currentTarget),
                project_id = this.$('#project-id').val();
            $.ajax({
                url: ae_globals.ajaxURL,
                type: 'post',
                data: {
                    ID: project_id,
                    action: 'ae-project-action',
                    method: 'approve'
                },
                beforeSend: function () {
                    view.blockUi.block($target);
                },
                success: function (res) {
                    view.blockUi.unblock();
                    if (res.success) {
                        window.location.href = res.permalink
                    }
                }
            });
        }
    });

    //Modal Reject Project
    Views.Modal_Reject_Project = AE.Views.Modal_Box.extend({
        el: '#modal_reject_project',
        events: {
            'submit form.reject-project-form': 'rejectProject'
        },
        initialize: function () {
            AE.Views.Modal_Box.prototype.initialize.apply(this, arguments);
            this.blockUi = new Views.BlockUi();
            this.initValidator();
        },
        initValidator: function () {
            this.login_validator = this.$("form.reject-project-form").validate({
                rules: {
                    reject_message: "required"
                }
            });
        },
        rejectProject: function (event) {
            event.preventDefault();
            var view = this,
                $target = $(event.currentTarget),
                project_id = this.$('#project-id').val(),
                reject_message = this.$('#reject-message').val();
            $.ajax({
                url: ae_globals.ajaxURL,
                type: 'post',
                data: {
                    ID: project_id,
                    reject_message: reject_message,
                    action: 'ae-project-action',
                    method: 'reject'
                },
                beforeSend: function () {
                    view.blockUi.block($target);
                },
                success: function (res) {
                    view.blockUi.unblock();
                    if (res.success) {
                        window.location.href = res.permalink
                    }
                }
            });
        }
    });

    //Modal Delete File in Workspace
    Views.Modal_Delete_File = AE.Views.Modal_Box.extend({
        el: '#modal_delete_file',
        events: {
            'submit form.form-delete-file': 'deleteFile'
        },
        initialize: function () {
            AE.Views.Modal_Box.prototype.initialize.apply(this, arguments);
            this.blockUi = new Views.BlockUi();
        },
        deleteFile: function (event) {
            event.preventDefault();
            var view = this,
                $target = $(event.currentTarget),
                post_id = this.$('#post-id').val(),
                file_name = this.$('#file-name').val(),
                project_id = this.$('#project-id').val();
            $.ajax({
                url: ae_globals.ajaxURL,
                type: 'post',
                data: {
                    action: 'free_remove_attack_file',
                    post_id: post_id,
                    file_name: file_name,
                    project_id: project_id
                },
                beforeSend: function () {
                    view.blockUi.block($target);
                },
                success: function (data) {
                    if (data !== "0" && data !== 'locked') {
                        var item = '.attachment-' + post_id;
                        $(item).remove();
                        view.blockUi.unblock();
                        view.closeModal();
                        AE.pubsub.trigger('ae:notification', {
                            msg: fre_fronts.deleted_file_successfully,
                            notice_type: 'success'
                        });
                    } else if (data === 'locked') {
                        view.blockUi.unblock();
                        view.closeModal();
                        AE.pubsub.trigger('ae:notification', {
                            msg: fre_fronts.cannot_deleted_file,
                            notice_type: 'error',
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 5000);
                    } else {
                        view.blockUi.unblock();
                        view.closeModal();
                        AE.pubsub.trigger('ae:notification', {
                            msg: fre_fronts.failed_deleted_file,
                            notice_type: 'error',
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 3000);
                    }
                }
            });
        }
    });

    Views.Modal_Lock_File = AE.Views.Modal_Box.extend({
        el: '#modal_lock_file',
        events: {
            'submit form.form-lock-file': 'lockFile'
        },
        initialize: function () {
            AE.Views.Modal_Box.prototype.initialize.apply(this, arguments);
            this.blockUi = new Views.BlockUi();
        },
        lockFile: function (event) {
            event.preventDefault();
            var view = this,
                $target = $(event.currentTarget),
                project_id = this.$('#project-id').val();
            $.ajax({
                url: ae_globals.ajaxURL,
                type: 'post',
                data: {
                    action: 'lock_upload_file',
                    project_id: project_id,
                    type: 'lock'
                },
                beforeSend: function () {
                    view.blockUi.block($target);
                },
                success: function (res) {
                    view.blockUi.unblock();
                    if (res.success) {
                        view.closeModal();
                        $('.lock-btn-wrapper').empty().html('<a href="#" class="lock-file-upload-btn" data-action="unlock" data-project-id="' + project_id + '"><i class="fa fa-unlock"></i> ' + ae_globals.text_view.unlock + '</a>');
                    }
                }
            });
        }
    });

    Views.Modal_Unlock_File = AE.Views.Modal_Box.extend({
        el: '#modal_unlock_file',
        events: {
            'submit form.form-unlock-file': 'unlockFile'
        },
        initialize: function () {
            AE.Views.Modal_Box.prototype.initialize.apply(this, arguments);
            this.blockUi = new Views.BlockUi();
        },
        unlockFile: function (event) {
            event.preventDefault();
            var view = this,
                $target = $(event.currentTarget),
                project_id = this.$('#project-id').val();
            $.ajax({
                url: ae_globals.ajaxURL,
                type: 'post',
                data: {
                    action: 'lock_upload_file',
                    project_id: project_id,
                    type: 'unlock'
                },
                beforeSend: function () {
                    view.blockUi.block($target);
                },
                success: function (res) {
                    view.blockUi.unblock();
                    if (res.success) {
                        view.closeModal();
                        $('.lock-btn-wrapper').empty().html('<a href="#" class="lock-file-upload-btn" data-action="lock" data-project-id="' + project_id + '"><i class="fa fa-lock"></i> ' + ae_globals.text_view.lock + '</a>');
                    }
                }
            });
        }
    });

})(jQuery, AE.Views, AE.Models, AE.Collections);
