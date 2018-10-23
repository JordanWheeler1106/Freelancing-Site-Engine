(function($, Models, Collections, Views) {
    $(document).ready(function() {
		$(".list").mCustomScrollbar({
			theme:"minimal"
		});
		$('.btn-fre-credit-payment, .btn-withdraw, .not-have-bid, .btn-submit-price-plan').popover({
			trigger: 'hover click',
			placement: 'auto',
		});

		var previousScroll = 0;
		$(window).on('scroll',function(){
			var btn_back = $(".back");
			if(btn_back.length){
				var btn_back_top = btn_back.offset().top;
				var currentScroll = $(this).scrollTop();
		        if ((currentScroll > previousScroll) && (previousScroll > 60)){
		        	$('.back').addClass("scroll-top");
		        } else {
		        	$('.back').removeClass("scroll-top");
		        }
		        previousScroll = currentScroll;
			}
		});
		$.validator.addClassRules({
			numberVal: {
				required: true,
				number: true,
				min: 1,
				//max: 5,
				maxlength: 10
			}
		});
		$('.validateNumVal').validate();
		$('[data-toggle="tooltip"]').tooltip();
		/**
		 * define blog item view
		 */
		BlogItem = Views.PostItem.extend({
			tagName: 'div',
			className: 'blog-wrapper post-item',
			template: _.template($('#ae-post-loop').html()),
			onItemBeforeRender: function() {
				// before render view
			},
			onItemRendered: function() {
				// after render view
			}
		});
		/**
		 * list view control blog list
		 */
		ListBlogs = Views.ListPost.extend({
			tagName: 'div',
			itemView: BlogItem,
			itemClass: 'post-item'
		});
		/**
		 * define blog item view
		 */
		if( $('#ae-testimonial-loop').length > 0 ){
			$('.grid').masonry({
				itemSelector: '.grid-item',
			});
			var elem = document.querySelector('.grid');
			TestimonialItem = Views.PostItem.extend({
				tagName: 'div',
				className: 'col-md-4 grid-item',
				template: _.template($('#ae-testimonial-loop').html()),
				onItemBeforeRender: function() {
					// before render view
				},
				onItemRendered: function() {
					// after render view
					// add Masonry
					new Masonry(elem, {itemSelector: '.grid-item'});
				}
			});
			/**
			 * list view control blog list
			 */
			ListTestimonials = Views.ListPost.extend({
				tagName: 'div',
				itemView: TestimonialItem,
				itemClass: 'col-md-4 grid-item'
			});
		}

		/**
		 * model Notify
		 */
		Models.Notify = Backbone.Model.extend({
			action: 'ae-notify-sync',
			initialize: function() {}
		});
		/**
		 * Notify collections
		 */
		Collections.Notify = Backbone.Collection.extend({
			model: Models.Notify,
			action: 'ae-fetch-notify',
			initialize: function() {
				this.paged = 1;
			}
		});
		/**
		 * define notify item view
		 * @since 1.2
		 * @author Dakachi
		 */
		NotifyItem = Views.PostItem.extend({
			tagName: 'li',
			className: 'notify-item',
			template: _.template($('#ae-notify-loop').html()),
			onItemBeforeRender: function() {
				// before render view
			},
			onItemRendered: function() {
				// after render view
			}
		});

		/**
		 * model project
		 */
		Models.Project = Backbone.Model.extend({
			action: 'ae-project-sync',
			initialize: function() {}
		});
		/**
		 * project collections
		 */
		Collections.Projects = Backbone.Collection.extend({
			model: Models.Project,
			action: 'ae-fetch-projects',
			initialize: function() {
				this.paged = 1;
			}
		});
		/**
		 * define project item view
		 */
		ProjectItem = Views.PostItem.extend({
			tagName: 'li',
			className: 'project-item',
			template: _.template($('#ae-project-loop').html()),
			onItemBeforeRender: function() {
				// before render view
			},
			onItemRendered: function() {
				// after render view
			}
		});

		User_BidItem = Views.PostItem.extend({
			tagName: 'li',
			className: 'user-bid-item',
			template: _.template($('#ae-user-bid-loop').html()),
			onItemBeforeRender: function() {
				// before render view
			},
			onItemRendered: function() {
				// after render view
			}
		});
		/**
		 * list view control project list
		 */
		ListProjects = Views.ListPost.extend({
			tagName: 'ul',
			itemView: ProjectItem,
			itemClass: 'project-item'
		});

		User_ListBids = Views.ListPost.extend({
			tagName: 'ul',
			itemView: User_BidItem,
			itemClass: 'user-bid-item'
		});
		/**
		 * Model profile
		 */
		Models.Profile = Backbone.Model.extend({
			action: 'ae-profile-sync',
			initialize: function() {}
		});
		/**
		 * Profile collection
		 */
		Collections.Profiles = Backbone.Collection.extend({
			model: Models.Profile,
			action: 'ae-fetch-profiles',
			initialize: function() {
				this.paged = 1;
			}
		});
		/**
		 * Define profile item view
		 */
		if($('.tab-profile-home').length > 0){
			var tagname = 'div',
				className = 'col-md-6 col-sm-12 col-xs-12 profile-item fre_profile';
		}else{
			var tagname = 'li',
				className = 'profile-item';
		}
		ProfileItem = Views.PostItem.extend({
			tagName: tagname,
			className: className,
			template: _.template($('#ae-profile-loop').html()),
			onItemBeforeRender: function() {
				//before render item
			},
			onItemRendered: function() {
				//after render view
				var view = this;
				view.$('.rate-it').raty({
					readOnly: true,
					half: true,
					score: function() {
						return view.model.get('rating_score');
					},
					hints: raty.hint
				});
			}
		});
		/**
		 * List view control profiles list
		 */
		ListProfiles = Views.ListPost.extend({
			tagName: 'div',
			itemView: ProfileItem,
			itemClass: 'profile-item'
		});
		/**
		 * Model portfolio
		 */
		Models.Portfolio = Backbone.Model.extend({
			action: 'ae-portfolio-sync',
			initialize: function() {}
		});
		/**
		 * Portfolio collection
		 */
		Collections.Portfolios = Backbone.Collection.extend({
			model: Models.Portfolio,
			action: 'ae-fetch-portfolios',
			initialize: function() {
				this.paged = 1;
			}
		});
		/**
		 * Define portfolio item view
		 */
		PortfolioItem = Views.PostItem.extend({
			initialize: function(){
				this.blockUi    = new Views.BlockUi();
			},
			tagName: 'li',
			className: 'col-md-4 col-sm-6 col-sx-12',
			template: _.template($('#ae-portfolio-loop').html()),
            onItemBeforeRender: function() {
            },
            onItemRendered: function() {
            }
		});
		/**
		 * List view control Portfolios list
		 */
		ListPortfolios = Views.ListPost.extend({
			tagName: 'li',
			itemView: PortfolioItem
			//itemClass: 'portfolio-item'
		});

        /**
         * View more portfolios, projects worked of freelancer, projects posted of employer
         */

        /*$('.author_view_more').click(function () {
            var obj = $(this);
            var paged = obj.attr('data-paged');
            var action = obj.attr('data-action');
			var post_type = obj.attr('data-post_type');
            var query = obj.attr('data-query');
            //console.log(JSON.parse(query));
            var loading = $('<div class="loading-blur loading"><div class="loading-overlay"></div><div class="fre-loading-wrap"><div class="fre-loading"></div></div></div>');

            $.ajax({
                type : "post",
                url : ae_globals.ajaxURL,
                dataType : 'json',
                data : {
                    action : action,
                    query : JSON.parse(query),
                    paged : paged,
                    page : paged
                },
                beforeSend: function () {
                    obj.attr('disabled',true).css('opacity','0.5');
                    loading.insertAfter(obj);
                },
                success: function (data, statusText, xhr) {
                    loading.remove();
                    if(data.success == true){
                        // PortfolioNew model
                        var PortfolioNewModel = Backbone.Model.extend({});

                        // Create Portfolios Collection
                        var PortfoliosCollection = Backbone.Collection.extend({
                            model: PortfolioNewModel
                        });
                        var Portfolios = new PortfoliosCollection;

                        var tag_name ='';
                        var className ='';
                        var template_loop ='';
                        var el_obj ='';
                        switch (post_type){
                            case 'portfolio' :
                                //add data to Portfolios
                                $( data.data ).each(function( k,v) {
                                	var list_skills = '';
                                	var list_image_portfolio = '';
                                	var list_image_portfolio_edit = '';
                                	var list_id_image_portfolio_edit = '';
                                	if(v.tax_input.skill && v.tax_input.skill.length > 0){
                                        $( v.tax_input.skill ).each(function( ks,vs) {
                                            list_skills += '<span class="fre-label">'+vs.name +'</span>';
                                        });
									}
									if(v.list_image_portfolio && v.list_image_portfolio.length > 0){
                                        $( v.list_image_portfolio ).each(function( kip,vip) {
                                            list_image_portfolio += '<img src="'+vip.image+'">';
                                        });
									}
									if(v.list_image_portfolio && v.list_image_portfolio.length > 0){
                                        $( v.list_image_portfolio ).each(function( kip,vip) {
                                            list_image_portfolio_edit += '<li class="col-sm-3 col-xs-12"> ' +
												'<div class="portfolio-thumbs-wrap"> ' +
												'<div class="portfolio-thumbs"> ' +
												'<img src="'+vip.image+'"> ' +
											'</div> ' +
											'<div class="portfolio-thumbs-action"> ' +
											'<a href="javascript:void(0)" class="remove_image_in_portfolio"' +
											'data-image_id="'+vip.id+'"> ' +
											'<i class="fa fa-trash-o"></i>Remove' +
											'</a> ' +
											'</div> ' +
											'</div> ' +
											'</li>';
                                        });
									}

									if(v.list_image_portfolio && v.list_image_portfolio.length > 0){
                                        $( v.list_image_portfolio ).each(function( kip,vip) {
                                            list_id_image_portfolio_edit += '<input type="hidden" name="post_thumbnail['+vip.id+']"' +
											'value="'+vip.id+'"' +
											'class="input_thumbnail_'+vip.id+'">';
                                        });
									}
                                    var rs = new PortfolioNewModel({
                                        post_title: v.post_title,
                                        post_content: v.post_content,
                                        the_post_thumbnail: v.the_post_thumbnail,
                                        the_post_thumbnail_full : v.the_post_thumbnail_full,
                                        list_skills :list_skills,
                                        html_edit_select_skill :v.html_edit_select_skill,
                                        list_image_portfolio :list_image_portfolio,
                                        list_image_portfolio_edit :list_image_portfolio_edit,
                                        list_id_image_portfolio_edit :list_id_image_portfolio_edit,
                                        et_ajaxnonce : v.et_ajaxnonce,
                                        unfiltered_content : v.unfiltered_content,
                                        is_edit : v.is_edit,
                                        ID: v.ID
                                    });
                                    Portfolios.add(rs);
                                });

                                tag_name = 'li';
                                className = 'col-md-4 col-sm-6 col-sx-12';
                                template_loop = '#ae-portfolio-loop';
                                el_obj = '.freelance-portfolio-list';
                                break;
                            case 'bid':
                                $( data.data ).each(function( k,v) {
                                    var rs = new PortfolioNewModel({
                                        bid_budget: v.bid_budget,
                                        project_link: v.project_link,
                                        project_post_date: v.project_post_date,
                                        project_comment: v.project_comment,
                                        author_url : v.author_url ,
                                        project_author_avatar : v.project_author_avatar ,
                                        project_title: v.project_title,
                                        rating_score: v.rating_score
                                    });
                                    Portfolios.add(rs);
                                });
                                tag_name = 'li';
                                className = '';
                                template_loop = '#ae-freelancer-history-loop';
                                el_obj = '.author-project-list';
                                break;
                            case 'project':
                                $( data.data ).each(function( k,v) {
                                    var rs = new PortfolioNewModel({
                                        permalink: v.permalink,
                                        post_title: v.post_title,
                                        budget: v.budget,
                                        post_date: v.post_date,
                                        rating_score: v.rating_score,
                                        post_status : v.post_status,
                                    });
                                    Portfolios.add(rs);
                                });
                                tag_name = 'li';
                                className = 'bid-item';
                                template_loop = '#ae-employer-history-loop';
                                el_obj = '.author-project-list';
                                break;

                        }

                        // Create Portfolio View
                        var PortfolioView = Backbone.View.extend({
                            tagName: tag_name,
                            className: className,
                            template: _.template($(template_loop).html()),
                            render: function() {
                                this.$el.html(this.template(this.model.attributes));
                                return this;
                            },
                            events: {
                                "click .rate-it": "onItemRenderedRatIt",
                                "click .fre-chosen-multi": "chosenClick"
                            },
                            onItemRenderedRatIt: function() {
                                var view = this;
                                view.$('.rate-it').raty({
                                    readOnly: true,
                                    half: true,
                                    score: function() {
                                        return view.model.get('rating_score');
                                    },
                                    hints: raty.hint
                                });
                            },
                            chosenClick: function (event) {
                            	if($(event.currentTarget).attr('data-first_click') == 'true'){
                                    $(event.currentTarget).attr('data-first_click','false');
                                    $(event.currentTarget).chosen({
                                        width: '100%',
                                        inherit_select_classes: true
                                    });
								}
                            }
                        });
                        // Create Portfolios View
                        var PortfoliosView = Backbone.View.extend({
                            el: el_obj,
                            initialize: function() {
                                this.render();
                            },
                            render: function() {
                                //this.$el.html('');
                                Portfolios.each(function(model) {
                                    var portfolio = new PortfolioView({
                                        model: model
                                    });

                                    this.$el.append(portfolio .render().el);
                                }.bind(this));
                                return this;
                            }
                        });
                        // Launch app
                        var app = new PortfoliosView;

						$('.rate-it').click();

						$('.fre-chosen-multi').click();

                        if(data.max_num_pages <= parseInt(paged)){
							obj.closest('div').remove();
                        }else{
                            obj.attr('data-paged', parseInt(paged) +1);
						}
                    }else {
                    	obj.closest('div').fadeOut();
					}
                    obj.attr('disabled',false).css('opacity','1');
                }
            });
        });*/


		/**
		 *  MODEL WORK HISTORY
		 */
		Models.Bid = Backbone.Model.extend({
			action: 'ae-bid-sync',
			initialize: function() {}
		});
		/**
		 * Bid collection
		 */
		Collections.Bids = Backbone.Collection.extend({
			model: Models.Bid,
			action: 'ae-fetch-bid',
			initialize: function() {
				this.paged = 1;
			}
		});
		if($('#ae-work-history-loop-freelancer').length > 0){
			// Work history of freelancer
	        BidHistoryItem = Views.PostItem.extend({
	                tagName: 'li',
	                className: 'bid-item',
	                template: _.template($('#ae-work-history-loop-freelancer').html()),
	                onItemBeforeRender: function() {
	                    //before render item
	                },
	                onItemRendered: function() {
	                    //after render view
	                    var view = this;
	                    view.$('.rate-it').raty({
	                        readOnly: true,
	                        half: true,
	                        score: function() {
	                            return view.model.get('rating_score');
	                        },
	                        hints: raty.hint
	                    });
	                }
	            });
	    }
		/**
		 * Define profile item view
		 * Currently bid 
		 */
		BidItem = Views.PostItem.extend({
			tagName: 'li',
			className: 'bid-item',
			template: _.template($('#ae-bid-history-loop').html()),
			onItemBeforeRender: function() {
				//before render item
			},
			onItemRendered: function() {
				//after render view
				var view = this;
				view.$('.rate-it').raty({
					readOnly: true,
					half: true,
					score: function() {
						return view.model.get('rating_score');
					},
					hints: raty.hint
				});
			}
		});

		WorkHistoryItem = Views.PostItem.extend({
			tagName: 'li',
			className: 'bid-item',
			template: _.template($('#ae-work-history-loop').html()),
			onItemBeforeRender: function() {
				//before render item
			},
			onItemRendered: function() {
				//after render view
				var view = this,
				user_current = AE.App.user,
				roles = user_current.get('roles');
				view.$('.rate-it').raty({
					readOnly: true,
					half: true,
					score: function() {
						return view.model.get('rating_score');
					},
					hints: raty.hint
				});
				if( view.model.get('post_author') != user_current.get('ID') /*&& roles.indexOf('administrator') == -1 */ ){
					view.$el.find('.post-control').hide();
				}
			}
		});
		/**
		 * List view control bid list
		 */
		ListBids = Views.ListPost.extend({
			tagName: 'li',
			itemView: BidItem,
			itemClass: 'bid-item'
		});
		
        //Freelancer
        AuthorFreelancerHistoryItem = Views.PostItem.extend({
            tagName: 'li',
            className: 'bid-item',
            template: _.template($('#ae-freelancer-history-loop').html()),
            onItemBeforeRender: function() {
                //before render item
            },
            onItemRendered: function() {
                //after render view
                var view = this;
                view.$('.rate-it').raty({
                    readOnly: true,
                    half: true,
                    score: function() {
                        return view.model.get('rating_score');
                    },
                    hints: raty.hint
                });
            }
        });
        AuthorFreelancerHistory = Views.ListPost.extend({
            tagName: 'li',
            itemView: AuthorFreelancerHistoryItem,
            itemClass: 'bid-item'
        });
        // EMployer
        AuthorEmployerHistoryItem = Views.PostItem.extend({
            tagName: 'li',
            className: 'bid-item',
            template: _.template($('#ae-employer-history-loop').html()),
            onItemBeforeRender: function() {
                //before render item
            },
            onItemRendered: function() {
                //after render view
                var view = this,
                user_current = AE.App.user,
                roles = user_current.get('roles');
                view.$('.rate-it').raty({
                    readOnly: true,
                    half: true,
                    score: function() {
                        return view.model.get('rating_score');
                    },
                    hints: raty.hint
                });
                if( view.model.get('post_author') != user_current.get('ID') /*&& roles.indexOf('administrator') == -1 */ ){
                    view.$el.find('.post-control').hide();
                }
               	$('.comment-author-history .comment-viewmore, .comment-author-history .comment-viewless').on('click', function() {
	                $(this).parent().toggleClass('active');
	            });
            }
        });
        AuthorEmployerHistory = Views.ListPost.extend({
            tagName: 'li',
            itemView: AuthorEmployerHistoryItem,
            itemClass: 'bid-item'
        });
        
		/**
		 * List view control bid list
		 */
		ListWorkHistory = Views.ListPost.extend({
			tagName: 'li',
			itemView: WorkHistoryItem,
			itemClass: 'bid-item'
		});

		if($('#ae-bid-loop').length > 0) {
			/* bid item in single project*/
	        SingleBidItem = Views.PostItem.extend({
	            tagName: 'div',
	            className: 'row list-bidding ',
	            template: _.template($('#ae-bid-loop').html()),
	            onItemBeforeRender: function() {
	                //before render item
	            },
	            onItemRendered: function() {
	                //after render view
	                var view = this;
	                view.$('.rate-it').raty({
	                    readOnly: true,
	                    half: true,
	                    score: function() {
	                        return view.model.get('rating_score');
	                    },
	                    hints: raty.hint
	                });
	                $.fn.trimContent();
	            }
	        });
	        /* bid list in single project*/
	        SingleListBids = Views.ListPost.extend({
	            tagName: 'div',
	            itemView: SingleBidItem,
	            itemClass: 'info-bidding',
	            initialize : function(){
	                this.tagName = 'ul';
	            },

	        });
	    }

		/*
		*
		* F R O N T  V I E W S
		*
		*/
		Views.Front = Backbone.View.extend({
			el: 'body',
			model: [],
			events: {
				'click a.login-btn' : 'openModalLogin',
				'click a.register-btn' : 'openModalRegister',
				'click .trigger-notification' : 'updateNotify',
				'click .trigger-notification-2' : 'updateNotify'
			},
			initialize: function(options) {
				_.bindAll(this, 'updateAuthButtons' , 'rejectPost');
				if( $('body').find('.all_skills').length > 0)
					this.all_skills = JSON.parse( $('body').find('.all_skills').html() ) ;

				this.user = this.model;
				/**
	             * unhighlight chosen
	            */
	            $('select.chosen, select.chosen-single').on('change', function(event,params){
	                if(typeof params.selected !== 'undefined') {
	                    var $container = $(this).closest('div');
	                    if ($container.hasClass('error')) {
	                        $container.removeClass('error');
	                    }
	                    $container.find('div.message').remove().end().find('i.fa-exclamation-triangle').remove();
	                }
	            });
				if (typeof $.validator !== 'undefined') {
					$.validator.setDefaults({
						// prevent the form to submit automatically by this plugin
						// so we need to apply handler manually
						onsubmit: true,
						onfocusout: function(element, event) {
							if (!this.checkable(element) && element.tagName.toLowerCase() === 'textarea') {
								this.element(element);
							} else if (!this.checkable(element) && (element.name in this.submitted || !this.optional(element))) {
								this.element(element);
							}
						},
						validClass: "valid", // the classname for a valid element container
						errorClass: "message", // the classname for the error message for any invalid element
						errorElement: 'div', // the tagname for the error message append to an invalid element container
						// append the error message to the element container
						errorPlacement: function(error, element) {
							$(element).closest('div').append(error);
						},
						// error is detected, addClass 'error' to the container, remove validClass, add custom icon to the element
						highlight: function(element, errorClass, validClass) {
							var required_id = $(element).attr('id');
							var $container = $(element).closest('div');
							if (!$container.hasClass('error')) {
								// $container.addClass('error').removeClass(validClass).append('<i class="fa fa-exclamation-triangle" ></i>');
								$container.addClass('error').removeClass(validClass);
							}
							if( required_id == 'skill' || required_id == 'project_category') {
								$('html, body').animate({
						            scrollTop:  0
						        }, 0);
							}
						},
						// remove error when the element is valid, remove class error & add validClass to the container
						// remove the error message & the custom error icon in the element
						unhighlight: function(element, errorClass, validClass) {
							var $container = $(element).closest('div');
							if ($container.hasClass('error')) {
								$container.removeClass('error').addClass(validClass);
							}
							$container.find('div.message').remove().end().find('i.fa-exclamation-triangle').remove();
						}
					});
				}

				this.noti_templates = new _.template('<div class="notification autohide {{= type }}-bg">' + '<div class="main-center">' + '{{= msg }}' + '</div>' + '</div>');
				
				//catch action reject project
				AE.pubsub.on('ae:model:onReject', this.rejectPost, this);

				// event handler for when receiving response from server after requesting login/register
				AE.pubsub.on('ae:user:auth', this.handleAuth, this);
				// event handle notification
				AE.pubsub.on('ae:notification', this.showNotice, this);
				/*
				 * check not is mobile, after user login, update authentication button
				 */
				//if(!parseInt(ae_globals.ae_is_mobile)){
					// render button in header
	            	this.model.on('change:ID', this.updateAuthButtons);
	            //}

	            // $('textarea').autosize();
				$('textarea.field-reply-msg').trigger('autosize.destroy');
                AE.pubsub.on('ae:after:bid', this.afterBidProject, this);

			},
			/**
			 * callback after user ID change and update header authentication button
			 * @since 1.0
			 * @author Dakachi
			 */
	        updateAuthButtons: function(model){
	        	if($('#header_login_template').length > 0 ) {
	        		var header_template = _.template($('#header_login_template').html());
		            if ($('.dropdown-info-acc-wrapper').length > 0) return;
		            this.$('.non-login').remove();
		            this.$('.login-form-header-wrapper').html(header_template(model.attributes));
	        	}
	        },

			openModalLogin: function (event){
				event.preventDefault();
				var view = this;
				this.modalLogin = new Views.Modal_Login({
					el: "#modal_login",
					model : view.model
				});
				this.modalLogin.openModal();
			},
			openModalRegister: function (event){
				event.preventDefault();
				var view = this;
				this.modalRegister = new Views.Modal_Register({
					el: "#modal_register",
					model : view.model
				});
				this.modalRegister.openModal();
			},

			updateNotify : function(event){
				this.user.set('read_notify', 1);
				this.user.save();
				this.$('.avatar .circle-new').remove();
				this.$('.notify-number').remove();
			},
			/*
			 * Show notification
			 */
			showNotice: function(params) {
				var view = this;
				// remove existing notification
				$('div.notification').remove();
				var notification = $(view.noti_templates({
					msg: params.msg,
					type: params.notice_type
				}));
				if ($('#wpadminbar').length !== 0) {
					notification.addClass('having-adminbar');
				}
				notification.hide().prependTo('body').fadeIn('fast').delay(1000).fadeOut(5000, function() {
					$(this).remove();
				});
			},
			handleAuth: function(model, resp, jqXHR) {
				// check if authentication is successful or not
				if (resp.success) {

					AE.pubsub.trigger('ae:notification', {
						msg: resp.msg,
						notice_type: 'success'
					});

					var data = resp.data;
					$('span.avatar').find('.avatar-default').remove();
					$('span.avatar').append(data.avatar);
					//action login
					if(data.do == "login" && !ae_globals.is_submit_project){
						//window.location.reload();
						if( window.location.href == data.redirect_url || data.redirect_url == ae_globals.homeURL || data.redirect_url == ''){
							window.location.reload(true);
						} else {
							window.location.href = data.redirect_url;
						}
					//action register
					} else if( data.do == "register" && !ae_globals.is_submit_project ){
						if( model.get('role') == "freelancer" || model.get('role') == "employer" ){
							if( data.redirect_url == '') {
								window.location.reload(true);
							} else {
								window.location.href = data.redirect_url;
							}
						} else {
							window.location.reload(true);
						}
					}

					if (!ae_globals.user_confirm) this.model.set(resp.data);

				} else {
					AE.pubsub.trigger('ae:notification', {
						msg: resp.msg,
						notice_type: 'error'
					});
				}
			},

			/**
	         * setup reject post modal and trigger event open modal reject
	         */
	        rejectPost: function(model) {
	            if (typeof this.rejectModal === 'undefined') {
	                this.rejectModal = new Views.RejectPostModal({
	                    el: $('#reject_post')
	                });
	            }
	            this.rejectModal.onReject(model);
	        },

            afterBidProject: function(res){
                if( res.success && ae_globals.pay_to_bid == '1'){
                    AE.pubsub.trigger('ae:notification', {
                        msg : res.msg,
                        notice_type: 'success'
                    });
                }
            }
		});
		/*
		*
		* M O D A L  R E G I S T E R  V I E W S
		*
		*/
		Views.Modal_Register = Views.Modal_Box.extend({
		    events: {
		        // user register
		        'submit form.signup_form': 'doRegister',
		    },

		    /**
		     * init view setup Block Ui and Model User
		     */
		    initialize: function() {

		        this.user = AE.App.user;

		        this.blockUi = new Views.BlockUi();
		        this.initValidator();
		        //check button
		        var clickCheckbox = document.querySelector('form.signup_form .sign-up-switch'),
		            roleInput     = $("form.signup_form input#role");
		            hire_text = $('.hire-text').val();
		            work_text = $('.work-text').val();
		            view 		  = this;

	            if( $('.sign-up-switch').length > 0 ){
	            	if($('#modal_register').find('span.user-role').hasClass('hire'))
	            	{
	            		$('form.signup_form .sign-up-switch').parents('.user-type').find('small').css({
	                        "left" :  -5 + "px"
	                    })
	            	}
	                clickCheckbox.onchange = function(event) {
	                    var _this = $(event.currentTarget);
	                    var _switch = _this.parents('.user-type');
	                    if(clickCheckbox.checked){
	                        roleInput.val("freelancer");
	                        $('form.signup_form .user-type span.text').text(work_text).removeClass('hire').addClass('work');
	                        _switch.find('small').css({
	                            "left" :  (_switch.find('.switchery').width() - _switch.find('small').width() + 5) + "px"
	                        })
	                    } else {
	                        roleInput.val("employer");
	                        $('form.signup_form .user-type span.text').text(hire_text).removeClass('work').addClass('hire');
	                        _switch.find('small').css({
	                            "left" :  -5 + "px"
	                        })
	                    }
	                };
	                var moveIt = this.$(".user-role").remove();
        			this.$(".switchery").append(moveIt);
	            }
		    },
		    /**
		     * init form validator rules
		     * can override this function by using prototype
		     */
		    initValidator: function() {
		    	if($('#agreement').length > 0) {
		    		this.register_validator = $("form.signup_form").validate({
			            rules: {
			            	first_name: "required",
                        	last_name: "required",
			                user_login: "required",
			                user_pass: "required",
			                agreement : "required",
			                user_email: {
			                    required: true,
			                    email: true
			                },
			                repeat_pass: {
			                    required: true,
			                    equalTo: "#register_user_pass"
			                }
			            }
			        });
			        return true;
		    	}
		        /**
		         * register rule
		         */
		        this.register_validator = $("form.signup_form").validate({
		            rules: {
		            	first_name: "required",
                        last_name: "required",
		                user_login: "required",
		                user_pass: "required",
		                user_email: {
		                    required: true,
		                    email: true
		                },
		                repeat_pass: {
		                    required: true,
		                    equalTo: "#register_user_pass"

		                }
		            }
		        });
		    },
		    /**
		     * user sign-up catch event when user submit form signup
		     */
		    doRegister: function(event) {
		        event.preventDefault();
		        event.stopPropagation();
		        // *
		        //  * call validator init

		        this.initValidator();
		        var form     = $(event.currentTarget),
		            button   = form.find('button.btn-submit'),
		            view     = this;
		        /**
		         * scan all fields in form and set the value to model user
		         */
		        form.find('input, textarea, select').each(function() {
		            view.user.set($(this).attr('name'), $(this).val());
		        })
		        // check form validate and process sign-up
		        if (this.register_validator.form() && !form.hasClass("processing")) {
		            this.user.set('do', 'register');
		            this.user.request('create', {
		                beforeSend: function() {
		                    view.blockUi.block(button);
		                    form.addClass('processing');
		                },
		                success: function(user, status, jqXHR) {
		                    view.blockUi.unblock();
		                    form.removeClass('processing');
		                    // trigger event process authentication
		                    AE.pubsub.trigger('ae:user:auth', user, status, jqXHR);

		                    if(status.success){
		                        AE.pubsub.trigger('ae:notification', {
		                            msg : status.msg,
		                            notice_type: 'success'
		                        });
		                        view.closeModal();
		                        form.trigger('reset');
		                    } else {
		                        AE.pubsub.trigger('ae:notification', {
		                            msg : status.msg,
		                            notice_type: 'error'
		                        });
		                        if(typeof grecaptcha != 'undefined'){
		                        	grecaptcha.reset();
		                        }
		                    }
		                }
		            });

		        }
		    }
		});
		/*
		*
		* M O D A L  L O G I N  V I E W S
		*
		*/
		Views.Modal_Login = Views.Modal_Box.extend({
			events: {
				// user login
				'submit form.signin_form': 'doLogin',
				// show forgotpass form
				'click a.show-forgot-form': 'showForgot'
			},

			/**
			 * init view setup Block Ui and Model User
			 */
			initialize: function() {
				this.user    = AE.App.user;
				this.blockUi = new Views.BlockUi();
				//validate forms
				this.initValidator();
			},
			/**
			 * init form validator rules
			 * can override this function by using prototype
			 */
			initValidator: function() {
				// login rule
				this.login_validator = this.$("form.signin_form").validate({
					rules: {
						user_login: "required",
						user_pass: "required"
					}
				});
			},
			/**
			 * show modal forgot pass form
			 */
			showForgot: function(event){
				event.preventDefault();
				event.stopPropagation();
				this.forgot = new Views.Modal_Forgot({el: "#modal_forgot"});
				//close sign in form
				this.closeModal();
				//open forgot form
				this.forgot.openModal();
			},
			/**
			 * user login,catch event when user submit login form
			 */
			doLogin: function(event) {
				event.preventDefault();
				event.stopPropagation();
				/**
				 * call validator init
				 */
				this.initValidator();

				var form = $(event.currentTarget),
					button = form.find('button.btn-submit'),
					view = this;

				/**
				 * scan all fields in form and set the value to model user
				 */
				form.find('input, textarea, select').each(function() {
					view.user.set($(this).attr('name'), $(this).val());
				})

				//check form validate and process sign-in
				if (this.login_validator.form() && !form.hasClass("processing")) {
					this.user.set('do', 'login');

					// set redirect_url for user model
					//this.user.set('redirect_url', redirect_url);

					this.user.request('read', {
						beforeSend: function() {
							view.blockUi.block(button);
							form.addClass('processing');
						},
						success: function(user, status, jqXHR) {
							view.blockUi.unblock();
							form.removeClass('processing');
							// trigger event process authentication
							AE.pubsub.trigger('ae:user:auth', user, status, jqXHR);
							if(status.success){
								AE.pubsub.trigger('ae:notification', {
									msg : status.msg,
									notice_type: 'success'
								});
								view.closeModal();

								/**
								 * reload current page instead redirect to homepage
								 */
								//form.trigger('reset');
								if(ae_globals.is_single){
									window.location.reload( true );
								}else{
									if( status.data.redirect_url ){
										if( ae_globals.homeURL == status.data.redirect_url ) {
											window.location.reload(true);
										} else {
											window.location.href = status.data.redirect_url;
										}
									}else {
										window.location.reload( true );
									}
								}
							} else {
								AE.pubsub.trigger('ae:notification', {
									msg : status.msg,
									notice_type: 'error'
								});
							}
						}
					});

				}
			},
		});
		/*
		*
		* M O D A L  L O G I N  V I E W S
		*
		*/
		Views.Modal_Change_Pass = Views.Modal_Box.extend({
			events: {
				// user login
				'submit form.chane_pass_form': 'doChangePass',
			},

			/**
			 * init view setup Block Ui and Model User
			 */
			initialize: function() {
				this.user    = AE.App.user;
				this.blockUi = new Views.BlockUi();
				this.initValidator();
			},
			/**
			 * init form validator rules
			 * can override this function by using prototype
			 */
			initValidator: function() {
				// login rule
				this.changepass_validator = $("form.chane_pass_form").validate({
					rules: {
						old_password: "required",
						new_password: "required",
						renew_password: {
							required: true,
							equalTo: "#new_password"
						}
					}
				});
			},
			/**
			 * user login,catch event when user submit login form
			 */
			doChangePass: function(event) {
				event.preventDefault();
				event.stopPropagation();
				/**
				 * call validator init
				 */
				this.initValidator();

				var form   = $(event.currentTarget),
					button = form.find('.btn-submit'),
					view   = this;

				/**
				 * scan all fields in form and set the value to model user
				 */
				form.find('input, textarea, select').each(function() {
					view.user.set($(this).attr('name'), $(this).val());
				})

				// check form validate and process sign-in
				if (this.changepass_validator.form() && !form.hasClass("processing")) {
					this.user.save('do' ,'changepass', {
						beforeSend: function() {
							view.blockUi.block(button);
							form.addClass('processing');
						},
						success: function(user, status, jqXHR) {
							view.blockUi.unblock();
							form.removeClass('processing');
							// trigger event process after change pass
							AE.pubsub.trigger('ae:user:changepass', user, status, jqXHR);
							if(status.success){
								AE.pubsub.trigger('ae:notification', {
									msg : status.msg,
									notice_type: 'success'
								});
								view.closeModal();
								form.trigger('reset');
								//window.location.href = ae_globals.homeURL;
							} else {
								AE.pubsub.trigger('ae:notification', {
									msg : status.msg,
									notice_type: 'error'
								});
							}
						}
					});

				}
			},
		});
		/*
		 *
		 * M O D A L  F O R G O T  V I E W S
		 *
		 */
		Views.Modal_Forgot = Views.Modal_Box.extend({
			events: {
				// user forgot password
				'submit form.forgot_form': 'doSendPassword',
			},

			/**
			 * init view setup Block Ui and Model User
			 */
			initialize: function() {
				this.user    = AE.App.user;
				this.blockUi = new Views.BlockUi();
				this.initValidator();
			},
			/**
			 * init form validator rules
			 * can override this function by using prototype
			 */
			initValidator: function() {
				/**
				 * forgot pass email rule
				 */
				this.forgot_validator = $("form.forgot_form").validate({
					rules: {
						user_email: {
							required: true,
							email: true
						},
					}
				});
			},
			/**
			 * user forgot password
			 */
			doSendPassword: function(event) {
				event.preventDefault();
				event.stopPropagation();
				/**
				 * call validator init
				 */
				this.initValidator();
				var form   = $(event.currentTarget),
					email  = form.find('input#user_email').val(),
					button = form.find('button.btn-submit'),
					view   = this;

				if (this.forgot_validator.form() && !form.hasClass("processing")) {

					this.user.set('user_login', email);
					this.user.set('do', 'forgot');
					this.user.request('read', {
						beforeSend: function() {
							view.blockUi.block(button);
							form.addClass('processing');
						},
						success: function(user, status, jqXHR) {
							form.removeClass('processing');
							view.blockUi.unblock();
							if(status.success){
								view.closeModal();
								AE.pubsub.trigger('ae:notification', {
									msg : status.msg,
									notice_type: 'success'
								});
								form.trigger('reset');
							}else {
								AE.pubsub.trigger('ae:notification', {
									msg : status.msg,
									notice_type: 'error'
								});
							}

						}
					});

				}
			}
		});
		/*
		*
		* S E A R C H  H E A D E R  V I E W S
		*
		*/
		Views.SearchForm = Backbone.View.extend({
			events: {
				//change search type job or profile?
				'change select.search-filter': 'changeSearchType'
			},
			initialize: function() {
				this.container           = this.$("#search_form");
				this.search_type         = "project";
				this.collection_projects = new Collections.Projects();
				this.collection_profiles = new Collections.Profiles();
				// projects list
				if (typeof ListProjects !== "undefined") {
					//list projects
					new ListProjects({
						itemView: ProjectItem,
						collection: this.collection_projects,
						el: $('#projects_list')
					});
					//list profiles
					new ListProfiles({
						itemView: ProfileItem,
						collection: this.collection_profiles,
						el: $('#profiles_list')
					});
				}
				if (typeof Views.BlockControl !== "undefined") {
					//project control
					SearchProjectControl  = Views.BlockControl.extend({
						onBeforeFetch : function() {
							this.$el.find('.no-result').remove();
						},
						onAfterFetch : function(result, res) {
							this.$el.find('.search-msg').html('');
							if(!res.success) {
								$('#projects_list').append($('#project-no-result').html());
								this.$el.find('.search-msg').html("");
							} else {
								if (parseInt(res.total) > 1) {
									this.$el.find('.search-msg').html(res.total + ae_globals.search_result_msgs + '"' + this.$el.find('input[name="s"]').val() + '"');
								}
								else if(parseInt(res.total) == 1 ) {
									this.$el.find('.search-msg').html(res.total + ae_globals.search_result_msg + '"' + this.$el.find('input[name="s"]').val() + '"');
								}
								else{
									this.$el.find('.search-msg').html('');
								}
								this.$el.find('.no-result').remove();
							}
							var aTag = this.$el.find('ul.list-project a');
							$(aTag).each(function(e){
								$(this).attr({'target': '_Blank', 'rel' : 'nofollow'});
							});
						}
					});
					new SearchProjectControl({
						collection: this.collection_projects,
						el: this.$(".projects-search-container"),
						query: {
							paginate: 'page'
						}
					});

					SearchProfileControl  = Views.BlockControl.extend({
						onBeforeFetch : function() {
							this.$el.find('.no-result').remove();
						},
						onAfterFetch : function(result, res) {
							this.$el.find('.search-msg').html('');
							if(!res.success) {
								$('#profiles_list').append($('#profile-no-result').html());
							} else {
								if(parseInt(res.total) > 1) {
									this.$el.find('.search-msg').html(res.total + ae_globals.search_result_msgs + '"' + this.$el.find('input[name="s"]').val() + '"');
								}
								else if( parseInt(res.total) == 1 ) {
									this.$el.find('.search-msg').html(res.total + ae_globals.search_result_msg + '"' + this.$el.find('input[name="s"]').val() + '"');
								}
								else{
									this.$el.find('.search-msg').html('');
								}
								this.$el.find('.no-result').remove();
							}
						}
					});
					//profile control
					new SearchProfileControl({
						collection: this.collection_profiles,
						el: this.$(".profiles-search-container"),
						query: {
							paginate: 'page'
						}
					});
				}
			},
			changeSearchType: function(event) {
				var target = $(event.currentTarget);
				this.search_type = target.val();
				if (this.search_type == "profile") {
					$(".profiles-search-container").show();
					$(".projects-search-container").hide();
				} else {
					$(".projects-search-container").show();
					$(".profiles-search-container").hide();
				}
			}
		});

		// Fix error wpLink on modal bootstrap
		$('#modal_edit_project').on('shown.bs.modal', function (e) {
			  $('body').removeClass('modal-open');
			  $('body').addClass('modal-open-link');
		});
		$('#modal_edit_project').on('hidden.bs.modal', function (e) {
		   $('body').removeClass('modal-open-link');
		});

		Views.EditProject =  Views.SubmitPost.extend({
		        onAfterInit: function() {
		        	var view = this;
		        	if( $('#edit_postdata').length > 0 ) {
		            	var postdata = JSON.parse($('#edit_postdata').html());
		            	this.model = new Models.Project(postdata);
		                this.setupFields();
		            }else {
		                this.model = new Models.Project();
		            }
		            AE.pubsub.trigger('AE:beforeSetupFields', this.model);
		            view.carousels = new Views.Carousel({
		                el: $('#gallery_container'),
		                model: view.model,
		                extensions : 'pdf,doc,docx,png,jpg,jpeg,gif,zip,xls,xlsx'
		            });
		            if(view.$('.skill-control').length > 0 ) {
		            	//new skills view
			            new Views.Skill_Control({
			                model: this.model,
			                el : view.$('.skill-control'),
			                name : 'skill'
			            });
		            }
		            view.$('.fre-calendar').datetimepicker({
						format: 'MM/DD/YYYY',
						icons: {
							previous: 'fa fa-angle-left',
			    			next: 'fa fa-angle-right'
						}
					});
		            if(!this.isMobile){
		            	$('.fre-chosen-category').chosen({
							width: '100%',
				            max_selected_options: parseInt(ae_globals.max_cat),
				            inherit_select_classes: true
						});
						$('.fre-chosen-skill').chosen({
							width: '100%',
				            max_selected_options: parseInt(ae_globals.max_skill),
				            inherit_select_classes: true
						});
		            }else{
		            	var last_valid_selection = null;
						$('.fre-chosen-category').change(function(event) {
							if ($(this).val().length > ae_globals.max_cat) {
								alert(ae_globals.max_cat_text);
								$(this).val(last_valid_selection);

							} else {
								last_valid_selection = $(this).val();
							}
						});
						$('.fre-chosen-skill').change(function(event) {
							if ($(this).val().length > ae_globals.max_skill) {
								alert(ae_globals.max_skill_text);
								$(this).val(last_valid_selection);
							} else {
								last_valid_selection = $(this).val();
							}
						});
		            }
					
		            if( view.$el.find('input[name="is_submit_project"]').length > 0 ){
		            	view.model.set('is_submit_project', view.$el.find('input[name="is_submit_project"]').val() );
		        	}
		        	AE.pubsub.trigger('AE:afterSetupFields', this.model);
		        	this.reSetupSkills();
		        },
		        setupFields: function() {
		            var view = this,
		                form_field = view.$('.step-post'),
		                location = this.model.get('location');
		            /**
		             * update form value for input, textarea select
		             */
		            form_field.find('input.input-item,input[type="text"],input[type="hidden"], textarea, select').each(function() {
		                var $input = $(this);
		                $input.val(view.model.get($input.attr('name')));
		                // trigger chosen update if is select
		                if ($input.get(0).nodeName === "SELECT") $input.trigger('chosen:updated');
		            });
		            form_field.find('input[type="radio"]').each(function() {
		                var $input = $(this),
		                    name = $input.attr('name');
		                if ($input.val() == view.model.get(name)) {
		                    $input.attr('checked', true);
		                }
		            });
		        },
		        reSetupSkills: function(){
					var view = this,
						form_field = view.$('.fre-input-field');
					form_field.find('select[name="skill"]').each(function() {
						var $input = $(this);
						var tax_input = view.model.get('tax_input'),
							skills = (typeof tax_input !== 'undefined') ? tax_input['skill'] : [];
						skill_list = [];
						for (var i = skills.length - 1; i >= 0; i--) {
							skill_list.push(skills[i].term_id);
						}
						$input.val(skill_list);
						$input.trigger('chosen:updated');
					});
				},
				submitPost: function(event) {
		            event.preventDefault();
		            var $target = $(event.currentTarget),
		                view = this,
		                temp = [];
		            if (view.model.get('uploadingCarousel')) return false;
		            /**
		             * update model data
		             */
		            $target.find('.input-item, .wp-editor-area').each(function() {
		                view.model.set($(this).attr('name'), $(this).val());
		            });
		            $target.find('.tax-item').each(function() {
		                view.model.set($(this).attr('name'), $(this).val());
		            });
		            // trigger method before SubmitPost
		            view.triggerMethod('before:submitPost', view.model, view);
		            /**
		             * update input check box to model
		             */
		            view.$el.find('input[type=checkbox]').each(function() {
		                var name = $(this).attr('name');
		                view.model.set(name, []);
		            });
		            view.$el.find('input[type=checkbox]:checked').each(function() {
		                var name = $(this).attr('name');
		                if (name == "et_claimable_check") return false;
		                if (typeof temp[name] !== 'object') {
		                    temp[name] = new Array();
		                }
		                temp[name].push($(this).val());
		                view.model.set(name, temp[name]);
		            });
		            /**
		             * update input radio to model
		             */
		            view.$el.find('input[type=radio]').each(function() {
		                var name = $(this).attr('name');
		                view.model.set(name, '');
		            });
		            view.$el.find('input[type=radio]:checked').each(function() {
		                view.model.set($(this).attr('name'), $(this).val());
		            });
		            /**
		             * save model
		             */
		            view.model.set('post_author', view.user.get('id'));
		            if (!view.model.get('uploadingCarousel')) {
		                view.model.save('', '', {
		                    beforeSend: function() {
		                        view.blockUi.block($target);
		                    },
		                    success: function(model, res) {
		                        if (res.success) {
		                        	AE.pubsub.trigger('ae:notification', {
		                                msg: res.msg,
		                                notice_type: 'success',
		                            });
		                            window.location.href = res.data.permalink;
		                        } else {
		                        	AE.pubsub.trigger('ae:notification', {
		                                msg: res.msg,
		                                notice_type: 'error',
		                            });
		                            view.blockUi.unblock();
		                        }
		                    }
		                });
		            }
		        }, 
		        onBeforeSubmitPost: function(model, view){
		        	if( view.$el.find('textarea').length > 0 ){
		                view.$el.find('textarea').each(function() {
		                    view.model.set( $(this).attr('name'),  $(this).val() );
		                });
		            }
		            if( view.$el.find('select').length > 0 ){
		            	view.$el.find('select').each(function(){
			            	if( $(this).attr('name') != 'skill' ){
			                    view.model.set($(this).attr('name'), $(this).val());
			                }else{
			                    view.model.set($(this).attr('name'), $(this).val());
			                }
		                });
		            }
		        },
	    });
		/*
	     *
	     * S U M I T  P R O J E C T  V I E W S
	     *
	    */
		Views.SubmitProject = Views.SubmitPost.extend({
	        onAfterInit: function() {
	        	var view = this;
	        	if( $('#edit_postdata').length > 0 ) {
	            	// this.currentStep = 'plan';
	            	// this.showNextStep();
	            	var postdata = JSON.parse($('#edit_postdata').html());
	            	this.model = new Models.Project(postdata);
	                this.model.set('renew', 1);
	                this.setupFields();
	            }else {
	                this.model = new Models.Project();
	            }
	            AE.pubsub.trigger('AE:beforeSetupFields', this.model);
	            view.carousels = new Views.Carousel({
	                el: $('#gallery_container'),
	                model: view.model,
	                extensions : 'pdf,doc,docx,png,jpg,jpeg,gif,zip,xls,xlsx'
	            });
	            if(view.$('.skill-control').length > 0 ) {
	            	//new skills view
		            new Views.Skill_Control({
		                model: this.model,
		                el : view.$('.skill-control'),
		                name : 'skill'
		            });
	            }
	            view.$('.fre-calendar').datetimepicker({
					format: 'MM/DD/YYYY',
					icons: {
						previous: 'fa fa-angle-left',
		    			next: 'fa fa-angle-right'
					}
				});
				if(!this.isMobile){
	            	$('.fre-chosen-category').chosen({
						width: '100%',
			            max_selected_options: parseInt(ae_globals.max_cat),
			            inherit_select_classes: true
					});
					$('.fre-chosen-skill').chosen({
						width: '100%',
			            max_selected_options: parseInt(ae_globals.max_skill),
			            inherit_select_classes: true
					});
	            }else{
	            	var last_valid_selection = null;
					$('.fre-chosen-category').change(function(event) {
						if ($(this).val().length > ae_globals.max_cat) {
							alert(ae_globals.max_cat_text);
							$(this).val(last_valid_selection);

						} else {
							last_valid_selection = $(this).val();
						}
					});
					$('.fre-chosen-skill').change(function(event) {
						if ($(this).val().length > ae_globals.max_skill) {
							alert(ae_globals.max_skill_text);
							$(this).val(last_valid_selection);
						} else {
							last_valid_selection = $(this).val();
						}
					});
	            }

	            if( view.$el.find('input[name="is_submit_project"]').length > 0 ){
                	view.model.set('is_submit_project', view.$el.find('input[name="is_submit_project"]').val() );
            	}
            	AE.pubsub.trigger('AE:afterSetupFields', this.model);
            	this.reSetupSkills();

            	//check input radio empty
            	if( view.$('ul.fre-post-package li input[type="radio"]:checked').length == 0){
            		view.$('ul.fre-post-package li').each(function(key, value){
						if(!$(value).hasClass('disabled')){
							$(value).find('input[type="radio"]').prop('checked', true);
							return false;
					    }
					})
            		// view.$('ul.fre-post-package li input[type="radio"]').first().prop("checked", true);
            	}
	        },
	        setupFields: function() {
	            var view = this,
	                form_field = view.$('.step-post'),
	                location = this.model.get('location');
	            /**
	             * update form value for input, textarea select
	             */
	            form_field.find('input.input-item,input[type="text"],input[type="hidden"], textarea, select').each(function() {
	                var $input = $(this);
	                $input.val(view.model.get($input.attr('name')));
	                // trigger chosen update if is select
	                if ($input.get(0).nodeName === "SELECT") $input.trigger('chosen:updated');
	            });
	            form_field.find('input[type="radio"]').each(function() {
	                var $input = $(this),
	                    name = $input.attr('name');
	                if ($input.val() == view.model.get(name)) {
	                    $input.attr('checked', true);
	                }
	            });
	        },
	        reSetupSkills: function(){
				var view = this,
					form_field = view.$('.fre-input-field');
				form_field.find('select[name="skill"]').each(function() {
					var $input = $(this);
					var tax_input = view.model.get('tax_input'),
						skills = (typeof tax_input !== 'undefined') ? tax_input['skill'] : [];
					skill_list = [];
					for (var i = skills.length - 1; i >= 0; i--) {
						skill_list.push(skills[i].term_id);
					}
					$input.val(skill_list);
					$input.trigger('chosen:updated');
				});
			},
	        onBeforeSubmitPost: function(model, view){
	        	if( view.$el.find('textarea').length > 0 ){
	                view.$el.find('textarea').each(function() {
	                    view.model.set( $(this).attr('name'),  $(this).val() );
	                });
	            }
	            if( view.$el.find('select').length > 0 ){
	            	view.$el.find('select').each(function(){
		            	if( $(this).attr('name') != 'skill' ){
		                    view.model.set($(this).attr('name'), $(this).val());
		                }else{
		                    view.model.set($(this).attr('name'), $(this).val());
		                }
	                });
	            }
	        },
	        onLimitFree: function() {
	            AE.pubsub.trigger('ae:notification', {
	                msg: ae_globals.limit_free_msg,
	                notice_type: 'error',
	            });
	        },
	        onAfterShowNextStep: function(step) {
	            $('.step-heading').find('i.fa-caret-down').removeClass('fa-caret-right fa-caret-down').addClass('fa-caret-right');
	            $('.step-' + step).find('.step-heading i.fa-caret-right').removeClass('fa-caret-right').addClass('fa-caret-down');
	        },
	        onAfterSelectStep: function(step) {
	            $('.step-heading').find('i').removeClass('fa-caret-right fa-caret-down').addClass('fa-caret-right');
	            step.find('i').removeClass('fa-caret-right').addClass('fa-caret-down');
	        },
	        // on after Submit auth fail
	        onAfterAuthFail: function(model, res) {
	            AE.pubsub.trigger('ae:notification', {
	                msg: res.msg,
	                notice_type: 'error',
	            });
	        },
	        onAfterPostFail: function(model, res) {
	            AE.pubsub.trigger('ae:notification', {
	                msg: res.msg,
	                notice_type: 'error',
	            });
	        },
	        onAfterSelectPlan : function($step, $li){
	        	if(ae_globals.user_is_activate == 0) return false;
	        	var label = $li.attr('data-label');
	        	$step.find('.text-heading-step').html(label);
	        },
	        selectStep: function(event) {
	            event.preventDefault();
	            var $target = $(event.currentTarget),
	                $wrapper = $target.parents('.step-wrapper'),
	                view = this,
	                select = $wrapper.attr('id');
	            // step authentication
	            if (select == 'step-auth') {
	                if (this.finishStep.length < 1) return;
	            }
	            // step post
	            if (select == 'step-post') {
	                if ($('#step-auth').length > 0 && this.finishStep.length < 2) return;
	                if ($('#step-auth').length == 0 && this.finishStep.length < 1) return;
	            }
	            // step payment
	            if (select == 'step-payment') {
	                if ($('#step-auth').length > 0 && this.finishStep.length < 3) return;
	                if ($('#step-auth').length == 0 && this.finishStep.length < 2) return;
	            }
	            if(ae_globals.user_is_activate == 0){
	        		AE.pubsub.trigger('ae:notification', {
	                    msg: ae_globals.text_activate,
	                    notice_type: 'error',
	                });
	                return false;
	        	}
	            if (!$target.closest('div').hasClass('current')) {
	                // trigger to call view beforeSelectStep
	                this.triggerMethod('before:selectStep', $target);
	                // toggle content of selected step
	                view.$('.step-wrapper').removeClass('current');
	                this.$('.content').slideUp(500, 'easeOutExpo');
	                $target.closest('div').addClass('current').find('.content').slideDown(500, 'easeOutExpo');
	                // trigger to call view afterSelectStep
	                this.triggerMethod('after:selectStep', $target, this);
	            }
	        },
	        addFinishStep: function(step) {
	        	if(ae_globals.user_is_activate == 0){
	        		$('#'+step).removeClass('complete');
	        		return false;
	        	}
	            if (typeof this.finishStep === 'undefined') {
	                this.finishStep = [];
	            }
	            $('#'+step).find('.number-step').html('<span class="fa fa-check"></span>');
	            this.$('.' + step ).addClass('complete');
	            this.finishStep.push(step);
	        }
	    });


		Views.SubmitBibPlan = Views.SubmitPost.extend({
			onAfterInit: function(){
				var view = this;
				//check input radio empty
            	if( view.$('ul.fre-post-package li input[type="radio"]:checked').length == 0){
            		view.$('ul.fre-post-package li input[type="radio"]').first().prop("checked", true);
            	}
			},
	        onLimitFree: function() {
	            AE.pubsub.trigger('ae:notification', {
	                msg: ae_globals.limit_free_msg,
	                notice_type: 'error',
	            });
	        },
	        // on after Submit auth fail
	        onAfterAuthFail: function(model, res) {
	            AE.pubsub.trigger('ae:notification', {
	                msg: res.msg,
	                notice_type: 'error',
	            });
	        },
	        onAfterPostFail: function(model, res) {
	            AE.pubsub.trigger('ae:notification', {
	                msg: res.msg,
	                notice_type: 'error',
	            });
	        },
	        onAfterSelectPlan : function($step, $li){
	        	if(ae_globals.user_is_activate == 0) return false;
	        	var label = $li.attr('data-label');
	        	$step.find('.text-heading-step').html(label);
	        },
	        selectStep: function(event) {
	            event.preventDefault();
	            var $target = $(event.currentTarget),
	                $wrapper = $target.parents('.step-wrapper'),
	                view = this,
	                select = $wrapper.attr('id');
	            // step authentication
	            if (select == 'step-auth') {
	                if (this.finishStep.length < 1) return;
	            }
	            // step post
	            if (select == 'step-post') {
	                if ($('#step-auth').length > 0 && this.finishStep.length < 2) return;
	                if ($('#step-auth').length == 0 && this.finishStep.length < 1) return;
	            }
	            // step payment
	            if (select == 'step-payment') {
	                if ($('#step-auth').length > 0 && this.finishStep.length < 3) return;
	                if ($('#step-auth').length == 0 && this.finishStep.length < 2) return;
	            }
	            if(ae_globals.user_is_activate == 0){
	        		AE.pubsub.trigger('ae:notification', {
	                    msg: ae_globals.text_activate,
	                    notice_type: 'error',
	                });
	                return false;
	        	}
	            if (!$target.closest('div').hasClass('current')) {
	                // trigger to call view beforeSelectStep
	                this.triggerMethod('before:selectStep', $target);
	                // toggle content of selected step
	                view.$('.step-wrapper').removeClass('current');
	                this.$('.content').slideUp(500, 'easeOutExpo');
	                $target.closest('div').addClass('current').find('.content').slideDown(500, 'easeOutExpo');
	                // trigger to call view afterSelectStep
	                this.triggerMethod('after:selectStep', $target, this);
	            }
	        },
	        addFinishStep: function(step) {
	        	if(ae_globals.user_is_activate == 0){
	        		$('#'+step).removeClass('complete');
	        		return false;
	        	}
	            if (typeof this.finishStep === 'undefined') {
	                this.finishStep = [];
	            }
	            $('#'+step).find('.number-step').html('<span class="fa fa-check"></span>');
	            this.$('.' + step ).addClass('complete');
	            this.finishStep.push(step);
	        }
	    });

		DPGlobal.dates = ae_globals.dates;
		$('.datepicker').datepicker({
			format : 'mm/dd/yyyy'
		});
		// $('#datepicker').on('changeDate', function(ev){
		//     $(this).datepicker('hide');
		// });
		$('.tooltip-style').tooltip();
		$('.image-gallery').magnificPopup({type:'image'});

		$('.trigger-menu').click(function(){
			$('.search-fullscreen').hide();
			$('.notification-fullscreen').hide();
			$('.menu-fullscreen').show();
			$('body').addClass('fre-menu-overflow');
			// $('#video-background-wrapper').hide();
		});

		$('.overlay-close').click(function() {
		   $('body').removeClass('fre-menu-overflow');
		   // $('#video-background-wrapper').show();
		});
		$('.trigger-search').click(function(){
			$('.menu-fullscreen').hide();
			$('.notification-fullscreen	').hide();
			$('.search-fullscreen').show();
			$('body').addClass('fre-menu-overflow');
			//$('#video-background-wrapper').hide();
		});

		$('.trigger-notification, .trigger-notification-2').on('click',function(){
			$('.menu-fullscreen').hide();
			$('.search-fullscreen').hide();
			$('.notification-fullscreen').show();
			$('body').addClass('fre-menu-overflow');
		});

		if($('.menu-fullscreen  li').length > 6) {
			$('.overlay nav').css( {height : '80%'});
			$('.menu-main > li').css({height : '80px'});
		}
		if($('.menu-fullscreen  li').length > 10)  {
			$('.overlay nav').css( {height : '100%'});
		}
		$('.menu-fullscreen ul.sub-menu').each(function(){
			var li = $(this).find('li').length;
			li++;
			$(this).parents('li').css({height : li*60 +10 +'px'});
		});

		// Select style
		var class_chosen = $(".chosen-select");
		$(".chosen-select").each(function(){
			var data_chosen_width = $(this).attr('data-chosen-width'),
				data_chosen_disable_search = $(this).attr('data-chosen-disable-search'),
				max_selected_options = $(this).attr('data-max-select'),
				data_placeholder = $(this).attr('data-placeholder') ;
			$(this).chosen({width: data_chosen_width, disable_search: data_chosen_disable_search, max_selected_options : max_selected_options, placeholder_text_single: data_placeholder });
		});

		// $('.chosen-select-date').chosen({width : '70%', disable_search: true});

		// Resize search input header
		function resizeInput() {
			 var contents = $(this).val(),
			 	charlength = contents.length;
			if(charlength > 0 ) {
				$(this).attr('size', charlength);
				$(this).css('width', 'auto');
			}else{
				$(this).css('width', '530px');
			}

		}
		$('input.field-search-top')
		// event handler
		.keyup(resizeInput)
		// resize on page load
		.each(resizeInput);

		//iOS7 Switcher
		/*var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
		elems.forEach(function(html) {
			var switchery = new Switchery(html,{ color: '#1fbdbd', secondaryColor: '#dbdbdb' });
		});*/

		//iOS7 Switcher for sign-up
		/*$('.sign-up-switch').each(function(index, el) {
			 new Switchery(el,{ color: 'rgba(231,76,60,.9)', secondaryColor: 'rgba(42,62,80,.9)' });
		});*/


		/**
		 * Menu style fixed
		*/
		$(window).scroll(function(e) {
			$el = $('#header-wrapper');
			if( $(window).scrollTop() > $el.height() && ( ( $(document).height() - $(window).height() ) > 2*$el.height() ) ) {
				$el.addClass("sticky");
			} else {
				if($(window).scrollTop() <= $el.height()) {
					$el.removeClass("sticky");
				}
			}
		});


		/**
		 * COUNTER
		 */
		if( $('.odometer').length > 0 ){
			$('.odometer').waypoint(function() {
				var data_number = $(this).attr('data-number');
				$(this).html(data_number);
			}, {offset: '75%'});
		}
		/**
		 * TABS
		*/
		$('#authenticate_tab a').click(function(e) {
			e.preventDefault();
			$(this).tab('show');
		});
		$('#standardmenu .active > a').click(function(){return false;});

		/**
		 * RATE IT
		 */
		$('.rate-it').raty({
			readOnly: true,
			half: true,
			score: function() {
				return $(this).attr('data-score');
			},
			hints: raty.hint
		});
	});

})(jQuery, window.AE.Models, window.AE.Collections, window.AE.Views);
/*=============== Javascript Serialize Object =================== */
jQuery.fn.serializeObject = function() {
	var self = this,
		json = {},
		push_counters = {},
		patterns = {
			"validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
			"key": /[a-zA-Z0-9_]+|(?=\[\])/g,
			"push": /^$/,
			"fixed": /^\d+$/,
			"named": /^[a-zA-Z0-9_]+$/
		};
	this.build = function(base, key, value) {
		base[key] = value;
		return base;
	};
	this.push_counter = function(key) {
		if (push_counters[key] === undefined) {
			push_counters[key] = 0;
		}
		return push_counters[key]++;
	};
	jQuery.each(jQuery(this).serializeArray(), function() {
		// skip invalid keys
		if (!patterns.validate.test(this.name)) {
			return;
		}
		var k,
			keys = this.name.match(patterns.key),
			merge = this.value,
			reverse_key = this.name;
		while ((k = keys.pop()) !== undefined) {
			// adjust reverse_key
			reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');
			// push
			if (k.match(patterns.push)) {
				merge = self.build([], self.push_counter(reverse_key), merge);
			}
			// fixed
			else if (k.match(patterns.fixed)) {
				merge = self.build([], k, merge);
			}
			// named
			else if (k.match(patterns.named)) {
				merge = self.build({}, k, merge);
			}
		}
		json = jQuery.extend(true, json, merge);
	});
	return json;
};