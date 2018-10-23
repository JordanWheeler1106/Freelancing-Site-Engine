(function ($, Models, Collections, Views) {
    $(document).ready(function () {
        $('.trim-text .show-more').click(function (event) {
            $('.full-text').removeClass('hide');
            $('.trim-text').addClass('hide');
        });
        $('.full-text .show-less').click(function (event) {
            $('.full-text').addClass('hide');
            $('.trim-text').removeClass('hide');
        });
        $('.btn-fre-credit-payment, .not-have-bid, .btn-submit-price-plan').popover({
            trigger: 'hover',
            placement: 'auto',
        });
        $('#datetimepicker5').datetimepicker({
            defaultDate: new Date(),
            format: 'DD/MM/YYYY',
            icons: {
                previous: 'fa fa-angle-left',
                next: 'fa fa-angle-right',
            }

        });
        $('#datetimepicker6').datetimepicker({
            defaultDate: new Date(),
            format: 'DD/MM/YYYY',
            icons: {
                previous: 'fa fa-angle-left',
                next: 'fa fa-angle-right',
            }
        });

        $.fn.trimContent = function () {
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
        };
        var load_more_home = $('.section-project-home .paginations-wrapper a.load-more-post');
        if (load_more_home.length > 0) {
            //load_more_home.text('');
        }
        // blog list control
        if ($('#posts_control').length > 0) {
            if ($('#posts_control').find('.postdata').length > 0) {
                var postsdata = JSON.parse($('#posts_control').find('.postdata').html()),
                    posts = new Collections.Blogs(postsdata);
            } else {
                posts = new Collections.Blogs();
            }
            /**
             * init list blog view
             */
            new ListBlogs({
                itemView: BlogItem,
                collection: posts,
                el: $('#posts_control').find('.post-list')
            });
            /**
             * init block control list blog
             */
            new Views.BlockControl({
                collection: posts,
                el: $('#posts_control')
            });
        }
        /**
         * // end blog list control
         */
        // projects list control
        $('.section-archive-project, .tab-project-home').each(function () {
            if ($(this).find('.postdata').length) {
                var postdata = JSON.parse($(this).find('.postdata').html()),
                    collection = new Collections.Projects(postdata);
            } else {
                var collection = new Collections.Projects();
            }
            var skills = new Collections.Skills();
            /**
             * init list blog view
             */
            new ListProjects({
                itemView: ProjectItem,
                collection: collection,
                el: $(this).find('.project-list-container')
            });
            /**
             * init block control list blog
             */
            new Views.BlockControl({
                collection: collection,
                skills: skills,
                el: $(this),
                onAfterInit: function () {
                    var view = this;
                    // Auto filter keyword

                    if (view.$el.find('#search_data').length > 0) {
                        var search_data = JSON.parse(view.$el.find('#search_data').html());
                        if (search_data.keyword) {
                            $('.fre-project-list-filter input[name="s"]').val(search_data.keyword).keyup();
                        }
                    }

                    // filter skill
                    if (view.getUrlParameter('skill_project')) {
                        setTimeout(function () {
                            $('.fre-skill-dropdown li a[name="' + view.getUrlParameter('skill_project') + '"]').click();
                            $('body').click();
                        }, 1000);
                    }
                    // fitler category
                    if (view.getUrlParameter('category_project')) {
                        setTimeout(function () {
                            //$('#project_category.fre-chosen-single').val('website-project-management');
                            $('#project_category.fre-chosen-single').trigger("chosen:updated").change();

                        }, 1000);
                    }
                    // filter skill
                    $('.fre-skill-dropdown').on('click', function (event) {
                        var $target = $(event.currentTarget),
                            name = $target.attr('data-name'),
                            selected = $target.find('li a.active');
                        if (name !== 'undefined') {
                            skill = _.map(selected, function (element) {
                                return $(element).html();
                            });
                            view.query[name] = skill;
                            view.page = 1;
                            view.fetch($target);
                        }
                    });

                    // filter budget
                    view.filterRanger();
                    $('.clear-filter').on('click', function (event) {
                        view.clearFilter(event);
                    });
                },
                clearFilter: function (event) {
                    event.preventDefault();
                    var view = this,
                        $target = $(event.currentTarget);
                    // reset input, select
                    view.$el.find('form')[0].reset();
                    view.$el.find('form select').trigger('chosen:updated');
                    view.$el.find('form ul.fre-skill-dropdown li .fre-skill-item').removeClass('active');
                    // reset query
                    view.query['project_category'] = '';
                    view.query['et_budget'] = '';
                    view.query['number_bids'] = '';
                    view.query['country'] = '';
                    view.query['skill'] = '';
                    view.query['s'] = '';
                    // request
                    view.fetch($target);
                },
                filterRanger: function () {
                    var view = this,
                        $et_budget = $('.fre-budget-field input[name="et_budget"]'),
                        name = $et_budget.attr('name'),
                        $min = $('.fre-budget-field input[name="min_budget"]'),
                        $max = $('.fre-budget-field input[name="max_budget"]');
                    $min.on('change', function (event) {
                        var $target_min = $(event.currentTarget),
                            value_min = $(this).val(),
                            value_max = $max.val();
                        value_min = parseInt(value_min);
                        value_max = parseInt(value_max);
                        if (value_min > value_max) {
                            $max.addClass('validate_ranger').focus();
                        } else {
                            $min.removeClass('validate_ranger');
                            $max.removeClass('validate_ranger');
                            $et_budget.val(value_min + ',' + value_max);
                            view.query[name] = $et_budget.val();
                            view.page = 1;
                            view.fetch($target_min);
                        }
                    });

                    $max.on('change', function (event) {
                        var $target_max = $(event.currentTarget),
                            value_max = $(this).val(),
                            value_min = $min.val();
                        value_min = parseInt(value_min);
                        value_max = parseInt(value_max);
                        if (value_min > value_max) {
                            $min.addClass('validate_ranger').focus();
                        } else {
                            $min.removeClass('validate_ranger');
                            $max.removeClass('validate_ranger');
                            $et_budget.val(value_min + ',' + value_max);
                            view.query[name] = $et_budget.val();
                            view.page = 1;
                            view.fetch($target_max);
                        }
                    });
                },
                getUrlParameter: function (name) {
                    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
                    if (results == null) {
                        return null;
                    }
                    else {
                        return results[1] || 0;
                    }
                },
                onAfterLoadMore: function (result, resp) {
                    var view = this;
                    view.addViewAll();
                },
                onBeforeFetch: function () {
                    var view = this;
                    view.blockUi.unblock();
                    view.blockUi.block(view.$('.fre-project-list-box'));
                },
                onAfterFetch: function (result, resp) {
                    var view = this;
                    view.addViewAll();
                    if (result.length > 0) {
                        $('.profile-no-result').hide();
                        $('.plural,.singular').removeClass('hide');
                        if (result.length == 1) {
                            $('.plural').hide();
                            $('.singular').show();
                        } else {
                            $('.plural').show();
                            $('.singular').hide();
                        }
                    } else {
                        $('.plural').show().removeClass('hide');
                        $('.singular').hide();
                        $('.profile-no-result').show();
                    }
                },
                addViewAll: function () {
                    $('.vs-tab-project-home').each(function () {
                        if ($(this).find('.paginations-wrapper a.view-all').length > 0) {
                            $(this).find('.paginations-wrapper a.view-all').remove();
                            $(this).find('.paginations-wrapper').append(ae_globals.view_all_text);
                        }
                        else {
                            $(this).find('.paginations-wrapper').append(ae_globals.view_all_text);
                        }
                    });
                },
                onAfterOrder: function ($target, view) {
                    var buttons = view.$el.find('.orderby');
                    $.each(buttons, function (key, value) {
                        if (!$(value).hasClass('active')) {
                            $(value).find('i.fa').attr('class', 'fa fa-sort');
                        }
                    });

                    var order = $target.attr('data-order');
                    if (order == 'ASC') {
                        $target.attr('data-order', 'DESC');
                        $target.find('i.fa').removeClass('fa-sort');
                        $target.find('i.fa').removeClass('fa-sort-desc').addClass('fa-sort-asc');
                    }
                    if (order == 'DESC') {
                        $target.attr('data-order', 'ASC');
                        $target.find('i.fa').removeClass('fa-sort');
                        $target.find('i.fa').removeClass('fa-sort-asc').addClass('fa-sort-desc');
                    }
                }
            });

        });
        if ($('.info-project-items').length > 0) {
            if ($('.info-project-items').find('.postdata').length > 0) {
                var postdata = JSON.parse($('.info-project-items').find('.postdata').html()),
                    collection = new Collections.Bids(postdata);
            } else {
                collection = new Collections.Bids();
            }

            // init list blog view

            new User_ListBids({
                itemView: User_BidItem,
                collection: collection,
                el: $('.info-project-items').find('.bid-list-container')
            });
            /**
             * init block control list blog
             */
            new Views.BlockControl({
                collection: collection,
                el: $('.info-project-items'),
                onBeforeFetch: function () {
                    var view = this;
                    view.blockUi.unblock();
                    view.blockUi.block(view.$el);
                    if ($('.info-project-items').find('.no-results').length > 0) {
                        $('.info-project-items').find('.no-results').remove();
                    }
                },
                onAfterFetch: function (result, res) {
                    if (!res.success || result.length == 0) {
                        $('.info-project-items').find('.bid-list-container').html(ae_globals.text_message.no_project);
                    }
                }
            });
        }
        /**
         * // end project list control
         */
        //profile list control
        $('.section-archive-profile, .tab-profile-home').each(function () {
            if ($(this).find('.postdata').length > 0) {
                var postdata = JSON.parse($(this).find('.postdata').html()),
                    collection = new Collections.Profiles(postdata);
            } else {
                var collection = new Collections.Profiles();
            }
            var skills = new Collections.Skills();
            /**
             * init list blog view
             */
            new ListProfiles({
                itemView: ProfileItem,
                collection: collection,
                el: $(this).find('.profile-list-container')
            });
            /**
             * init block control list blog
             */
            new Views.BlockControl({
                collection: collection,
                skills: skills,
                el: $(this),
                onAfterInit: function () {
                    var view = this;

                    // Auto filter keyword
                    if (view.$el.find('#search_data').length > 0) {
                        var search_data = JSON.parse(view.$el.find('#search_data').html());
                        if (search_data.keyword) {
                            $('.fre-profile-list-filter input[name="s"]').val(search_data.keyword).keyup();
                        }
                    }
                    // filter skill
                    if (view.getUrlParameter('skill_profile')) {
                        setTimeout(function () {
                            $('.fre-skill-dropdown li a[name="' + view.getUrlParameter('skill_profile') + '"]').click();
                            $('body').click();
                        }, 1000);
                    }
                    $('.fre-skill-dropdown').on('click', function (event) {
                        var $target = $(event.currentTarget),
                            name = $target.attr('data-name'),
                            selected = $target.find('li a.active');
                        if (name !== 'undefined') {
                            skill = _.map(selected, function (element) {
                                return $(element).html();
                            });
                            view.query[name] = skill;
                            view.page = 1;
                            view.fetch($target);
                        }
                    });

                    // filter budget
                    view.filterRanger();
                    $('.clear-filter').on('click', function (event) {
                        view.clearFilter(event);
                    });
                },
                clearFilter: function (event) {
                    event.preventDefault();
                    var view = this,
                        $target = $(event.currentTarget);
                    // reset input, select
                    view.$el.find('form')[0].reset();
                    view.$el.find('form select').trigger('chosen:updated');
                    view.$el.find('form ul.fre-skill-dropdown li .fre-skill-item').removeClass('active');
                    // reset query
                    view.query['project_category'] = '';
                    view.query['hour_rate'] = '';
                    view.query['total_projects_worked'] = '';
                    view.query['country'] = '';
                    view.query['skill'] = '';
                    view.query['s'] = '';
                    // request
                    view.fetch($target);
                },
                filterRanger: function () {
                    var view = this,
                        $hour_rate = $('.fre-budget-field input[name="hour_rate"]'),
                        name = $hour_rate.attr('name'),
                        $min = $('.fre-budget-field input[name="min_budget"]'),
                        $max = $('.fre-budget-field input[name="max_budget"]');
                    $min.on('change', function (event) {
                        var $target_min = $(event.currentTarget),
                            value_min = $(this).val(),
                            value_max = $max.val();
                        value_min = parseInt(value_min);
                        value_max = parseInt(value_max);
                        if (value_min > value_max) {
                            $max.addClass('validate_ranger').focus();
                        } else {
                            $min.removeClass('validate_ranger');
                            $max.removeClass('validate_ranger');
                            $hour_rate.val(value_min + ',' + value_max);
                            view.query[name] = $hour_rate.val();
                            view.page = 1;
                            view.fetch($target_min);
                        }
                    });

                    $max.on('change', function (event) {
                        var $target_max = $(event.currentTarget),
                            value_max = $(this).val(),
                            value_min = $min.val();
                        value_min = parseInt(value_min);
                        value_max = parseInt(value_max);
                        if (value_min > value_max) {
                            $min.addClass('validate_ranger').focus();
                        } else {
                            $min.removeClass('validate_ranger');
                            $max.removeClass('validate_ranger');
                            $hour_rate.val(value_min + ',' + value_max);
                            view.query[name] = $hour_rate.val();
                            view.page = 1;
                            view.fetch($target_max);
                        }
                    });
                },
                getUrlParameter: function (name) {
                    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
                    if (results == null) {
                        return null;
                    }
                    else {
                        return results[1] || 0;
                    }
                },
                onAfterLoadMore: function (result, resp) {
                    var view = this;
                    view.addViewAllProfile();
                },
                onBeforeFetch: function () {
                    var view = this;
                    view.blockUi.unblock();
                    view.blockUi.block(view.$('.fre-profile-list-box'));
                },
                onAfterFetch: function (result, resp) {
                    var view = this;
                    view.addViewAllProfile();
                    if (result.length > 0) {
                        $('.profile-no-result').hide();
                        $('.plural,.singular').removeClass('hide');
                        if (result.length == 1) {
                            $('.plural').hide();
                            $('.singular').show();
                        } else {
                            $('.plural').show();
                            $('.singular').hide();
                        }
                    } else {
                        $('.plural').show().removeClass('hide');
                        $('.singular').hide();
                        $('.profile-no-result').show();
                    }
                },
                addViewAllProfile: function () {
                    $('.vs-tab-profile-home').each(function () {
                        if ($(this).find('.paginations-wrapper a.view-all').length > 0) {
                            $(this).find('.paginations-wrapper a.view-all').remove();
                            $(this).find('.paginations-wrapper').append(ae_globals.view_all_text_profile);
                        }
                        else {
                            $(this).find('.paginations-wrapper').append(ae_globals.view_all_text_profile);
                        }
                    });
                }
            });
        });

        if ($('.freelancer-project-history').length > 0) {
            var $container = $('.freelancer-project-history');
            if ($container.find('.postdata').length > 0) {
                var postdata = JSON.parse($container.find('.postdata').html()),
                    collection = new Collections.Bids(postdata);
            } else {
                var collection = new Collections.Bids();
            }
            /**
             * init list bid view
             */
            new AuthorFreelancerHistory({
                itemView: AuthorFreelancerHistoryItem,
                collection: collection,
                el: $container.find('.list-work-history-profile')
            });
            /**
             * init block control list blog
             */
            new Views.BlockControl({
                collection: collection,
                el: $container,
                onBeforeFetch: function () {
                    if ($container.find('.profile-no-results').length > 0) {
                        $container.find('.profile-no-results').remove();
                    }
                },
                onAfterFetch: function (result, res) {
                    $.fn.showHideReview();
                    if (!res.success || result.length == 0) {
                        $container.find('.list-history-profile').html(ae_globals.text_message.no_project);
                    }
                }
            });
        }
        if ($('.employer-project-history').length > 0) {
            var $container = $('.employer-project-history');
            if ($container.find('.postdata').length > 0) {
                var postdata = JSON.parse($container.find('.postdata').html()),
                    collection = new Collections.Projects(postdata);
            } else {
                var collection = new Collections.Projects();
            }
            /**
             * init list bid view
             */
            new AuthorEmployerHistory({
                itemView: AuthorEmployerHistoryItem,
                collection: collection,
                el: $container.find('.list-work-history-profile')
            });
            /**
             * init block control list blog
             */
            new Views.BlockControl({
                collection: collection,
                el: $container,
                onBeforeFetch: function () {
                    if ($container.find('.project-no-results').length > 0) {
                        $container.find('.project-no-results').remove();
                    }
                },
                onAfterFetch: function (result, res) {
                    $.fn.showHideReview();
                    $.fn.trimContent();
                    if (!res.success || result.length == 0) {
                        $container.find('.list-work-history-profile').html(ae_globals.text_message.no_project);
                    }
                }
            });
            $.fn.trimContent();
        }

        /**
         * // end profile list control
         */
        if ($('.portfolio-container').length > 0) {
            var $container = $('.portfolio-container');
            //portfolio list control
            if ($container.find('.postdata').length > 0) {
                var postdata = JSON.parse($container.find('.postdata').html()),
                    collection = new Collections.Portfolios(postdata);
            } else {
                var collection = new Collections.Portfolios();
            }
            /**
             * init list blog view
             */
            new ListPortfolios({
                itemView: PortfolioItem,
                collection: collection,
                el: $container.find('.freelance-portfolio-list')
            });
            /**
             * init block control list blog
             */
            new Views.BlockControl({
                collection: collection,
                el: $container
            });
        }
        /**
         * // end porfolio list control
         */
        if ($('.bid-history').length > 0) {
            var $container = $('.bid-history');
            // $('.profile-history').each(function(){
            if ($container.find('.postdata').length > 0) {
                var postdata = JSON.parse($container.find('.postdata').html()),
                    collection = new Collections.Bids(postdata);
            } else {
                var collection = new Collections.Bids();
            }

            /**
             * init list bid view
             */
            new ListBids({
                itemView: BidHistoryItem,
                collection: collection,
                el: $container.find('.list-history-profile')
            });
            /**
             * init block control list blog
             */
            new Views.BlockControl({
                collection: collection,
                el: $container,
                onBeforeFetch: function () {
                    var view = this;
                    view.blockUi.unblock();
                    view.blockUi.block(view.$el);
                    if ($container.find('.no-results').length > 0) {
                        $container.find('.no-results').remove();
                    }
                },
                onAfterFetch: function (result, res) {
                    $.fn.trimContent();
                    if (!res.success || result.length == 0) {
                        $container.find('.list-history-profile').html(ae_globals.text_message.no_work_history);
                    }
                }
            });
            // });
        }
        if ($('.project-history').length > 0) {
            var $container = $('.project-history');
            // $('.profile-history').each(function(){
            if ($container.find('.postdata').length > 0) {
                var postdata = JSON.parse($container.find('.postdata').html()),
                    collection = new Collections.Projects(postdata);
            } else {
                var collection = new Collections.Projects();
            }
            /**
             * init list bid view
             */
            new ListWorkHistory({
                itemView: WorkHistoryItem,
                collection: collection,
                el: $container.find('.list-history-profile')
            });
            /**
             * init block control list blog
             */
            new Views.BlockControl({
                collection: collection,
                el: $container,
                onBeforeFetch: function () {
                    var view = this;
                    view.blockUi.unblock();
                    view.blockUi.block(view.$el);
                    if ($container.find('.no-results').length > 0) {
                        $container.find('.no-results').remove();
                    }
                },
                onAfterFetch: function (result, res) {
                    $.fn.showHideReview();
                    if (!res.success || result.length == 0) {
                        $container.find('.list-history-profile').html(ae_globals.text_message.no_project);
                    }
                }
            });
            // });
        }
        if ($('.section-archive-testimonial').length > 0) {
            if ($('.section-archive-testimonial').find('.testimonial_data').length > 0) {
                var postsdata = JSON.parse($('.section-archive-testimonial').find('.testimonial_data').html()),
                    posts = new Collections.Posts(postsdata);
            } else {
                posts = new Collections.Posts();
            }
            /**
             * init list blog view
             */
            new ListTestimonials({
                itemView: TestimonialItem,
                collection: posts,
                el: $('.section-archive-testimonial').find('.testimonial-list-container')
            });
            /**
             * init block control list blog
             */
            new Views.BlockControl({
                collection: posts,
                el: $('.section-archive-testimonial'),
            });
        }

        $.fn.showHideReview = function () {
            var $container = $('.project-history, .employer-project-history');

            $('li.bid-item .review', $container).click(function (e) {
                e.preventDefault();
                var $target = $(e.currentTarget),
                    $bidItem = $target.parents('.bid-item ');
                if ($bidItem.find('.review-rate').css('display') == 'none') {
                    $('.review i', $bidItem).each(function () {
                        $(this).removeClass('fa-eye').addClass('fa-eye-slash');
                        $bidItem.find('.review-rate').show();
                    });
                } else {
                    $('.review i', $bidItem).each(function () {
                        $(this).removeClass('fa-eye-slash').addClass('fa-eye');
                        $bidItem.find('.review-rate').hide();
                    });
                }
            });

            $('.comment-author-history .comment-viewmore, .comment-author-history .comment-viewless').on('click', function () {
                $(this).parent().toggleClass('active');
            });
        };
        $.fn.showHideReview();


    });
})(jQuery, window.AE.Models, window.AE.Collections, window.AE.Views);
