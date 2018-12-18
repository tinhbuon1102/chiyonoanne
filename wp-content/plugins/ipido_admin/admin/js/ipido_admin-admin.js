(function( $, window, undefined ) {
	'use strict';

	var $document 	= $(document),
		$window 	= $(window),
		$body 		= $(document.body);

	window._IPIDO_ADMIN = {};
	var _IPIDO_ADMIN = window._IPIDO_ADMIN;

	_IPIDO_ADMIN.settings = {};
	var settings = _IPIDO_ADMIN.settings;

	
	/**
	 * DEBUG Log
	 * 
	 * @since 1.2.0
	 */
	settings.debug = true;
	var CS_DEBUG = function(msg,obj = false){
		if (settings.debug){
			var version = settings.general.plugin_version;
			console.log('[>] IPIDO Admin '+version+': '+msg);
			if (obj){
				console.log(obj);
			}
		}
	};


	_IPIDO_ADMIN.general = {
		init: function(){
			CS_DEBUG('General Settings Init');

			this.brand();
			this.network_admin_menu();
			this.submenu();
			this.tooltips();
			this.body_scrollbar();
		},
		brand: function(){
			var sidebar 		= $('#adminmenuwrap'),
				brand_type		= settings.logo.type,
				brand_wrapper 	= $('<div />',{class: 'sidebar-brand-wrapper'}),
				brand_anchor	= $('<a />',{href: settings.logo.url}),
				brand_brand 	= $('<div />',{class: 'sidebar-brand_brand'}),
				brand_logo 		= $('<div />',{class: 'sidebar-brand_logo'}),
				brand_ico 		= $('<i />',{class: settings.logo.icon}),
				brand_icon 		= $('<div />',{class: 'sidebar-brand_icon'}).append(brand_ico),
				brand_title 	= $('<div />',{class: 'sidebar-brand_text'}).html(settings.logo.text);

			if (brand_type == 'image'){
				brand_brand.append(brand_logo);
			} else if (brand_type == 'text') {
				brand_brand.append(brand_icon).append(brand_title);
			}
			brand_anchor.append(brand_brand);
			brand_wrapper.append(brand_anchor);
			
			brand_wrapper.prependTo(sidebar);
			var brand_animate = setTimeout(function(){
				brand_brand.addClass('sidebar-brand_brand--visible');
			},0);
		},
		submenu: function(){
			var sidebar 	= $('#adminmenu'),
				menuClass 	= 'wp-has-submenu-expanded';

			$('li.wp-has-current-submenu',sidebar).addClass(menuClass);
		},
		tooltips: function(){
			if (settings.navbar.tooltips){
				CS_DEBUG('Initializing Tooltips');
				tippy('.cs-ipido-header-toolbar a',{
					arrow: true,
					arrowType: 'round',
					zIndex: 10002,
				});
			}
		},
		/*+
		 * Body Custom Scrollbars
		 * @since 
		 **/
		body_scrollbar: function(){
			if (settings.general.body_scrollbar){
				$('body').overlayScrollbars({
					scrollbars: {
						autoHide: 'leave'
					}
				});
			}
		},
		/**
		 * Network Admin Menu Item
		 * @since 2.0.0
		 */
		network_admin_menu: function(){
			var is_multisite 		= settings.general.is_multisite,
				is_super_admin		= settings.general.is_super_admin,
				is_network_admin	= settings.general.is_network_admin;

			if (is_multisite && is_super_admin){
				var my_sites 	= $('#wp-admin-bar-my-sites .ab-sub-wrapper'),
					super_admin	= $('#wp-admin-bar-my-sites-super-admin .ab-sub-wrapper > .ab-submenu',my_sites),
					sites_list 	= $('#wp-admin-bar-my-sites-list',my_sites);
					
				if (!is_network_admin){
					var menu_item 		= $('<li />',{id: 'cs-ipido-network-menu-toggle', class: 'wp-has-submenu wp-not-current-submenu menu-top'}),
						icon 			= $('<i />',{class: 'cli cli-home'}),
						icon_wrapper	= $('<div />',{class: 'wp-menu-image', html: icon}),
						name 			= 'Network Menu',
						name_wrapper 	= $('<div />',{class: 'wp-menu-name', html: name}),
						anchor 			= $('<a />',{class: 'wp-has-submenu cs-ipido-network', attr: {
							title: name,
						}}).append(icon_wrapper).append(name_wrapper).appendTo(menu_item),
						submenu 		= $('<ul />',{class: 'wp-submenu wp-submenu-wrap',html: super_admin}).appendTo(menu_item);
				} else {
					var main_site_url 	= $('li > a',sites_list).eq(0).attr('href');
					var menu_item 		= $('<li />',{id: 'cs-ipido-network-menu-toggle', class: 'menu-top'}),
						icon 			= $('<i />',{class: 'cli cli-corner-up-left'}),
						icon_wrapper	= $('<div />',{class: 'wp-menu-image', html: icon}),
						name 			= 'Back to Main Site',
						name_wrapper 	= $('<div />',{class: 'wp-menu-name', html: name}),
						anchor 			= $('<a />',{class: 'cs-ipido-network', attr: {
							href: main_site_url,
							title: name,
						}}).append(icon_wrapper).append(name_wrapper).appendTo(menu_item);
				}
				menu_item.prependTo($('#adminmenu'));
			}
		}
	}


	_IPIDO_ADMIN.topNavbar = {
		init: function(){
			CS_DEBUG("TopNavbar Init");

			this.toolbar();
			this.submenu();
			this.position();
			this.screenTabs();
			this.networkSites();
			this.sidebarToggle();
		},
		toolbar: function(){
			var navbar_unify 			= settings.navbar.unify,
				$navbar_title 			= $('#wpbody-content > .wrap > h1'),
				$navbar_default_wrapper = $('#wpcontent > .cs-ipido-header'),
				$navbar_wrapper 		= $navbar_default_wrapper,
				$navbar_toolbar 		= $('.cs-ipido-header-toolbar',$navbar_default_wrapper),
				toolbar 				= $('#wp-toolbar'),
				site 					= $('#wp-admin-bar-site-name > .ab-item'),
				updates 				= $('#wp-admin-bar-updates > .ab-item'),
				comments 				= $('#wp-admin-bar-comments > .ab-item'),
				newcontent 				= $('#wp-admin-bar-new-content'),
				account 				= $('#wp-admin-bar-my-account');
			
			// WP Localize
			var navbar_help 				= settings.navbar.help,
				navbar_help_title			= settings.navbar.help_title,
				navbar_screen 				= settings.navbar.screen,
				navbar_screen_title			= settings.navbar.screen_title,
				navbar_notifications 		= settings.navbar.notifications,
				navbar_notifications_title	= settings.navbar.notifications_title,
				navbar_site 				= settings.navbar.site,
				navbar_site_title			= settings.navbar.site_title,
				navbar_updates 				= settings.navbar.updates,
				navbar_comments 			= settings.navbar.comments,
				navbar_newcontent 			= settings.navbar.newcontent,
				navbar_account 				= settings.navbar.account,
				navbar_networksites			= settings.navbar.networksites,
				navbar_networksites_title 	= settings.navbar.networksites_title,
				navbar_flexiblespace		= settings.navbar.flexiblespace,
				navbar_pagetitle			= settings.navbar.pagetitle,
				navbar_sidebartoggle		= settings.navbar.sidebartoggle;

			if (navbar_unify){
				$navbar_wrapper 	= $navbar_title;
				$navbar_toolbar 	= $('<div />',{class: 'cs-ipido-header-toolbar'});
				$navbar_title 		= $('<div />',{class: 'cs-ipido-header-title'});

				$navbar_wrapper.wrapInner($navbar_title);
			}

			var $navbar_order = settings.navbar.order;
			if ($navbar_order){
				$.each($navbar_order,function($item,$element){

					if (navbar_help && 'help' == $element){
						var anchor 	= $('<a />',{class: 'cs-ipido-header-toolbar-item cs-ipido-header-toolbar-item_helptab', attr: {
								title: navbar_help_title
							}}),
							icon 	= $('<i />',{class: 'cli cli-help-circle'}).appendTo(anchor);
						anchor.prependTo($navbar_toolbar);
					}
					if (navbar_screen && 'screen' == $element){
						var anchor 	= $('<a />',{class: 'cs-ipido-header-toolbar-item cs-ipido-header-toolbar-item_screenoptionstab',attr: {
								title: navbar_screen_title
							}}),
							icon 	= $('<i />',{class: 'cli cli-monitor'}).appendTo(anchor);
						anchor.prependTo($navbar_toolbar);
					}
					if (navbar_notifications && 'notifications' == $element){
						var anchor 	= $('<a />',{class: 'cs-ipido-dropdown cs-ipido-header-toolbar-item cs-ipido-header-toolbar-item_notifications',attr: {
								title: navbar_notifications_title
							}}),
							icon 	= $('<i />',{class: 'cli cli-bell'}).appendTo(anchor);
						anchor.prependTo($navbar_toolbar);
					}
					if (navbar_site && 'site' == $element){
						var url 	= site.prop('href'),
							anchor 	= $('<a />',{class: 'cs-ipido-header-toolbar-item cs-ipido-header-toolbar-item_site',attr: {
								href: url,
								title: navbar_site_title,
							}}),
							icon 	= $('<i />',{class: 'cli cli-globe'}).appendTo(anchor);
						anchor.prependTo($navbar_toolbar);
					}
					if (navbar_updates && 'updates' == $element){
						var url 	= updates.prop('href'),
							title 	= $('.screen-reader-text',updates).text(),
							count 	= $('.ab-label',updates).text(),
							anchor 	= $('<a />',{class: 'cs-ipido-header-toolbar-item cs-ipido-header-toolbar-item_updates',attr: {
								href: url,
								title: title
							}}),
							icon 	= $('<i />',{class: 'cli cli-refresh-cw'}).appendTo(anchor),
							badge 	= $('<span />',{class: 'cs-badge',text: count}).appendTo(anchor);
						anchor.prependTo($navbar_toolbar);
					}
					if (navbar_comments && 'comments' == $element){
						var url 	= comments.prop('href'),
							title 	= $('.screen-reader-text',comments).text(),
							count 	= $('.ab-label',comments).text(),
							anchor 	= $('<a />',{class: 'cs-ipido-header-toolbar-item cs-ipido-header-toolbar-item_comments',attr: {
								href: url,
								title: title
							}}),
							icon 	= $('<i />',{class: 'cli cli-message-circle'}).appendTo(anchor),
							badge 	= $('<span />',{class: 'cs-badge',text: count}).appendTo(anchor);
						anchor.prependTo($navbar_toolbar);
					}
					if (navbar_newcontent && 'newcontent' == $element){
						var parent 	= $('.ab-item',newcontent).eq(0),
							title 	= $('.ab-label',parent).text(),
							anchor 	= $('<a />',{class: 'cs-ipido-dropdown cs-ipido-header-toolbar-item cs-ipido-header-toolbar-item_new',attr: {
								title: title
							}}),
							icon 	= $('<i />',{class: 'cli cli-plus-circle'}).appendTo(anchor),
							submenu = $('.ab-sub-wrapper > .ab-submenu',newcontent).clone(),
							new_submenu = $('<div />',{class: 'cs-ipido-header-toolbar-item_submenu',html: submenu}).appendTo(anchor);
						anchor.prependTo($navbar_toolbar);
					}
					if (navbar_account && 'account' == $element){
						var parent 	= $('.ab-sub-wrapper > .ab-submenu',account),
							title 	= $('.display-name',parent).text(),
							anchor 	= $('<a />',{class: 'cs-ipido-dropdown cs-ipido-header-toolbar-item cs-ipido-header-toolbar-item_account',attr: {
								title: title
							}}),
							img 	= $('#wp-admin-bar-user-info .avatar',parent),
							avatar 	= $('<div />',{class: 'cs-ipido-header-toolbar-item_avatar',html: img}).appendTo(anchor),
							submenu = $('.ab-sub-wrapper > .ab-submenu',account).clone(),
							new_submenu = $('<div />',{class: 'cs-ipido-header-toolbar-item_submenu',html: submenu}).appendTo(anchor);
						anchor.prependTo($navbar_toolbar);
					}
					if (navbar_networksites && 'networksites' == $element && settings.general.is_multisite){
						var anchor 	= $('<a />',{class: 'cs-ipido-header-toolbar-item cs-ipido-header-toolbar-item_networksites', attr: {
								title: navbar_networksites_title
							}}),
							icon 	= $('<i />',{class: 'cli cli-network-alt1'}).appendTo(anchor);
						anchor.prependTo($navbar_toolbar);
					}
					if (navbar_flexiblespace && 'flexiblespace' == $element){
						var div 	= $('<div />',{class: 'cs-ipido-header-toolbar-item cs-ipido-header-toolbar-item_flexiblespace'});
						div.prependTo($navbar_toolbar);
					}
					if (navbar_pagetitle && 'pagetitle' == $element){
						var pagetitle 	= $('.wrap h1').first().text(),
							div 		= $('<div />',{class: 'cs-ipido-header-toolbar-item cs-ipido-header-toolbar-item_pagetitle', html: pagetitle});
						div.prependTo($navbar_toolbar);
					}
					if (navbar_sidebartoggle && 'sidebartoggle' == $element){
						var anchor 	= $('<a />',{class: 'cs-ipido-header-toolbar-item cs-ipido-header-toolbar-item_sidebartoggle', attr: {
								title: 'Show/Hide Sidebar'
							}}),
							icon 	= $('<i />',{class: 'cli cli-menu'}).appendTo(anchor);
						anchor.prependTo($navbar_toolbar);
					}
				});
			}

			if (navbar_unify){
				$navbar_toolbar.appendTo($navbar_wrapper);
			} else {

			}
		},
		submenu: function(){
			var toolbar 	= $('.cs-ipido-header-toolbar'),
				dropdown 	= $('.cs-ipido-dropdown',toolbar);

			dropdown.on('click',function(e){
				var actualDropdown 	= $(this),
					submenu 		= $('.cs-ipido-header-toolbar-item_submenu',actualDropdown);
				actualDropdown.toggleClass('cs-submenu-visible');
			}).on('click','a',function(e){
				e.stopPropagation();
			});
		},
		position: function(){
			if (settings.navbar.position == 'fixed'){
				this.fixedTitle();
			}
		},
		fixedTitle: function(){
			$body = $('body');
			if (!settings.fixedTitle){
				settings.fixedTitle = true;
				$body.addClass('cs-ipido-fixed-title');
			}
		},
		unfixedTitle: function(){
			$body = $('body');
			if (settings.fixedTitle){
				settings.fixedTitle = false;
				$body.removeClass('cs-ipido-fixed-title');
			}
		},
		screenTabs: function(){
			CS_DEBUG('Initializing Screen Tabs');

			// Replace Screen Options / Help button events with Fancybox popup
			var screenTabs 	= {
				screenOptionsTitle: 	settings.navbar.screen_title,
				helpTabsTitle:	 		settings.navbar.help_title,
			};
			window.screenMeta = {
				init: function() {}
			};
			if ($('.cs-ipido-header-toolbar a' ).length){
				// Screen options
				$('.cs-ipido-header-toolbar-item_screenoptionstab').on( 'click', function() {
					if (typeof tb_show === "function") {
						var screen_options = $('#screen-options-wrap').length;
						tb_show( screenTabs.screenOptionsTitle, '#TB_inline?inlineId=screen-options-wrap' );
					} else {
						_IPIDO_ADMIN.notificationCenter.newToast('This page does not have Screen Options');
					}
				});
				// Help
				$('.cs-ipido-header-toolbar-item_helptab').on('click',function(){
					var screen_help_tabs = $('#contextual-help-wrap .contextual-help-tabs-wrap').children().length;
					if (screen_help_tabs){
						if (typeof tb_show === "function") {
							tb_show( screenTabs.helpTabsTitle, '#TB_inline?inlineId=contextual-help-wrap' );
						} else {
							_IPIDO_ADMIN.notificationCenter.newToast('Oh no! We can\'t display the help panel!');
						}
					} else {
						_IPIDO_ADMIN.notificationCenter.newToast('This page does not have Help Options');
					}
				});
			}
		},
		networkSites: function(){
			$('.cs-ipido-header-toolbar-item_networksites').on('click',function(e){
				e.preventDefault();
				_IPIDO_ADMIN.networkSidebar.sidebarToggle();
			});
		},
		sidebarToggle: function(){
			var self = this;
			$('.cs-ipido-header-toolbar-item_sidebartoggle').on('click',function(e){
				e.preventDefault();

				self.sidebarExpandCollapse('auto');
			});
			
			$('#adminmenu a.wp-has-submenu','body').on('click',function(e){
				e.preventDefault();

				self.sidebarExpandCollapse('submenu');
			});

		},
		sidebarExpandCollapse: function(type){
			var self 				= this,
				$body 				= $('body'),
				settings_status 	= settings.general.sidebar_status,
				is_toggle_always 	= settings.navbar.sidebartoggle_button,
				_status 			= $body.data('sidebarToggle'),
				status 				= (_status) ? _status : settings_status,
				current_status 		= status,
				is_folded 			= ($body.hasClass('folded') || $body.hasClass('auto-fold')) ? true : false,
				is_folded 			= ($body.hasClass('folded')) ? true : false;
			
			// Auto-fold
			if (!is_folded){
				var windowsize = $window.width();
				// 782
        		if (windowsize <= 960){
					is_folded = true;
					if (settings_status == 'expanded' && !_status){
						current_status = 'collapsed';
					}
				}
			}
				
			// Always toggle sidebar
			if (is_toggle_always){
				if (status == 'collapsed'){
					current_status = (is_folded) ? 'collapsed' : 'expanded';
				} else if (status == 'expanded'){
					current_status = (is_folded) ? 'collapsed' : 'expanded';
				}

				if (type == 'auto'){
					if (current_status == 'collapsed'){
						$body.removeClass('folded');
						self.sidebarExpand();
					} else if (current_status == 'expanded'){
						$body.addClass('folded');
						self.sidebarCollapse();
					}
				} else if (type == 'submenu') {
					if (current_status == 'collapsed'){
						$body.removeClass('folded');
						self.sidebarExpand();
					}
				}
				
			// Toggle only when is folded
			} else if (!is_toggle_always && is_folded){
				if (type == 'auto'){
					if (current_status == 'collapsed'){
						self.sidebarExpand();
					} else if (current_status == 'expanded'){
						self.sidebarCollapse();
					}
				} else if (type == 'submenu') {
					if (current_status == 'collapsed'){
						self.sidebarExpand();
					}
				}
			}
		},
		sidebarExpand: function(){
			var $body = $('body');
			
			$body.data('sidebarToggle','expanded').addClass('cs-ipido-expanded-folded-menu');
		},
		sidebarCollapse: function(){
			var $body = $('body');

			$body.data('sidebarToggle','collapsed').removeClass('cs-ipido-expanded-folded-menu');
			$('#adminmenu li.wp-has-submenu.wp-has-submenu-expanded','body').each(function(index,element){
				// a.wp-has-submenu
				$(element).removeClass('wp-has-submenu-expanded');
				$('.wp-submenu').slideUp();
			});
		}
	}


	_IPIDO_ADMIN.networkSidebar = {
		init: function(){
			if (settings.general.is_multisite){
				_IPIDO_ADMIN._debug("Network Sidebar Init");
	
				this.sidebar = $('#cs-ipido-network-sites-sidebar'); 
				this.submenu();
				this.scrollbar();
				this.searchfield();
				this.closeListener();
			}
		},
		scrollbar: function(){
			$('#cs_wp-admin-bar-my-sites-list-wrapper').overlayScrollbars({
				scrollbars: {
					autoHide: 'leave'
				}
			});
		},
		searchfield: function(){
			var sidebar 	= this.sidebar,
				$search 	= sidebar.find('.cs-network-site-search'),
				$sites_data = sidebar.find('#cs_wp-admin-bar-my-sites-list');

			$search.keyup(function() {
				var value = $(this).val(),
				$sites = $sites_data.find('li.cs-network-site');
				$sites.each(function() {
					var $site = $(this);
					if ($site.data('name').search(new RegExp(value, 'i')) < 0) {
						$site.hide();
					} else {
						$site.show();
					}
				});
			});
		},
		sidebarToggle: function(){
			var sidebar = this.sidebar;
			sidebar.toggleClass('cs-sites-sidebar-visible');
		},
		submenu: function(){
			var sidebar 	= this.sidebar,
				menuClass 	= 'wp-has-submenu-expanded';

			$('a.wp-has-submenu',sidebar).on('click',function(e){
				e.preventDefault();

				var parent 		= $(this).parents('li'),
					submenu 	= $('.wp-submenu',parent);

				// if (settings.adminmenu.accordion){
					var target 	= parent,
						menus 	= $('li.wp-has-submenu-expanded',sidebar);
					
					$.each(menus,function(){
						var parent 	= $(this),
							submenu = $('.wp-submenu',parent);

						if (target[0] !== parent[0]){
							submenu.slideUp();
							parent.removeClass(menuClass);
						}
					});
				// }

				if (!parent.hasClass(menuClass)){
					parent.addClass(menuClass);
					submenu.slideDown(400);
				} else {
					submenu.slideUp(400,function(){
						parent.removeClass(menuClass);
					});
				}
			})
		},
		closeListener: function(){
			var self 	= this,
				sidebar = this.sidebar,
				wrapper = $('.cs-ipido-network-sites-sidebar-wrapper',sidebar),
				menu 	= $('#cs_wp-admin-bar-my-sites-list',sidebar);
			
			sidebar.on('click',function(e){
				self.sidebarToggle();
			});
			wrapper.on('click',function(e){
				e.stopPropagation();
			});
		}
	};


	_IPIDO_ADMIN.notificationCenter = {
		init: function(){
			if (settings.notifications.status){
				CS_DEBUG('Notifications Center Init');
				_IPIDO_ADMIN.notificationCenter.toastQueue = [];
				_IPIDO_ADMIN.notificationCenter.toastIndex = 1;
				_IPIDO_ADMIN.notificationCenter.toastIsVisible = false;

				this.notifications();
				this.notificationPopup();
			}
		},
		notificationPopup: function(){
			var top_navbar 	= $('.cs-ipido-header .cs-ipido-header-toolbar'),
				notify		= $('.cs-ipido-header-toolbar-item_notifications',top_navbar);
			
			if (notify.length){
				CS_DEBUG('Notification Popup Ready');
				var notification_popup 	= $('<div />',{ class: 'cs-ipido-notification-center-popup cs-ipido-header-toolbar-item_submenu'});
					// new_header 			= $('<div />',{ class: 'noty-title',html: 'Nuevas'}).appendTo(notification_popup);
				
				var noty_item = $("\
					<div class='noty-title'>Nuevas</div>\
					<div class='noty-container'>\
						<ul>\
							<li class='noty-item'>\
								<div class='noty-item-container'>\
									<div class='noti-item--icon'></div>\
									<div class='noti-item--body'></div>\
								</div>\
							</li>\
							<li class='noty-item'>\
								<div class='noty-item-container'>\
									<div class='noti-item--icon'></div>\
									<div class='noti-item--body'></div>\
								</div>\
							</li>\
						</ul>\
					</div>\
					<div class='noty-title'>Nuevas</div>\
					<div class='noty-container'>\
						<ul>\
							<li class='noty-item'>\
								<div class='noty-item-container'>\
									<div class='noti-item--icon'></div>\
									<div class='noti-item--body'></div>\
								</div>\
							</li>\
							<li class='noty-item'>\
								<div class='noty-item-container'>\
									<div class='noti-item--icon'></div>\
									<div class='noti-item--body'></div>\
								</div>\
							</li>\
						</ul>\
					</div>\
				");

				noty_item.appendTo(notification_popup);

				notify.append(notification_popup);
			}
		},
		notifications: function(){
			var self = this;
			var notification_count = 0;
			var important_flag = false;
			var alerts = [];
			
			var alert_classes = '.update-nag, .notice, .notice-success, .updated, .settings-error, .error, .notice-error, .notice-warning, .notice-info';
			var $alerts = $( alert_classes )
				// .not( '.inline, .theme-update-message, .hidden, .hide-if-js' )
				.not( '.hidden, .hide-if-js' )
				.not( '#gadwp-notice, .rs-update-notice-wrap' );

			var greens = [ 'updated', 'notice-success' ];
			var reds = [ 'error', 'notice-error', 'settings-error' ];
			var blues = [ 'update-nag', 'notice', 'notice-info', 'update-nag', 'notice-warning' ];
	
			$alerts.each(function(i){
				var $alert = $(this);

				// Skip if alert is empty
				if ( ! $alert.html().replace( /^\s+|\s+$/g, '' ).length ) {
					return true;
				}
	
				// Determine the priority
				var j;
				var priority = 'neutral';
				// Red
				for ( j = 0; j < reds.length; j += 1 ) {
					if ( $alert.hasClass( reds[ j ] ) ) {
						if ( ! $alert.hasClass( 'updated' ) ) { // Because of .settings-error.updated
							priority = 'red';
						}
					}
				}

				var alert = {
					msg: 		$alert.html(),
					priority:	priority,
				};

				alerts.push({alert});
	
				// Add it to the notification list
				notification_count += 1;
			});

			if ( notification_count ) {
				// $alerts.remove();
				
				$.each(alerts, function(alert){
					var msg = alerts[alert].alert.msg;
					self.newToast(msg);
				});

				// Add Top Navbar Badge
				// cs-ipido-dropdown cs-ipido-header-toolbar-item cs-ipido-header-toolbar-item_notifications
				var top_navbar 	= $('.cs-ipido-header .cs-ipido-header-toolbar'),
					notify		= $('.cs-ipido-header-toolbar-item_notifications',top_navbar);
				
				if (notify.length){
					var badge = $('<div />',{class: 'cs-badge',html: notification_count});
					badge.appendTo(notify);
				}
			}
		},
		newToast: function(msg){
			if (settings.notifications.status){
				CS_DEBUG('Showing a notification');

				var index = _IPIDO_ADMIN.notificationCenter.toastIndex++;
				_IPIDO_ADMIN.notificationCenter.toastQueue.push({
					index:  index,
					msg: 	msg,
				});
	
				this.nextToast();
			} else {
				CS_DEBUG('Notification Center disabled.');
			}
		},
		nextToast: function(){
			var toasts = _IPIDO_ADMIN.notificationCenter.toastQueue;
			var status = _IPIDO_ADMIN.notificationCenter.toastIsVisible;
			if (!status){
				_IPIDO_ADMIN.notificationCenter.toastIsVisible = true;
				if (toasts.length >= 1){
					var toast = toasts[0];
					var msg = toast.msg;
					this.showToast(msg);
					var newQueue = toasts.splice(1, 1);
					_IPIDO_ADMIN.notificationCenter.toastQueue = newQueue;
				} else {
					_IPIDO_ADMIN.notificationCenter.toastIsVisible = false;
				}
			} else {
				// showing alert
			}
		},
		showToast: function(msg){
			var self = this;
			$.toast({
				text: 		msg,
				hideAfter: 	settings.notifications.duration,
				stack: 		1,
				position: 	'bottom-right',
				beforeHide:		function(){
					_IPIDO_ADMIN.notificationCenter.toastIsVisible = false;
				},
				afterHidden: 	function(){
					self.nextToast();
				},
			});
		}
	}
	
	
	_IPIDO_ADMIN.selectBox = function(){
		CS_DEBUG("Selectbox Init");

		$('.tablenav select, #typeselector, #cs-menu-manager_user-role-selector select').select2({
			minimumResultsForSearch: -1
		});

		// DEPRECATED
		// Revisar en su reemplazo: MutationObserver
		$('body').on('DOMNodeInserted', 'select', function () {
			$(this).select2();
		});

		var selectTimeout = setTimeout(function(){
			$('.attachment-filters').select2({
				minimumResultsForSearch: -1
			});
			// $('#media-attachment-filters').select2({
			// 	minimumResultsForSearch: -1
			// });
			// $('#media-attachment-date-filters').select2({
			// 	minimumResultsForSearch: -1
			// });
		},0);
	}


	_IPIDO_ADMIN.userProfileSettings = function(){
		CS_DEBUG("UserProfile Init");

		var adminTab 		= $('#csf-tab-user_profile'),
			allSwitch 		= $('.csf-field-switcher',adminTab),
			settings 		= $('.csf-field-checkbox',adminTab),
			_fromAllSwitch 	= false;
		
		$('input[type=checkbox]',settings).on('change',function(){
			if (!_fromAllSwitch){
				var checks 	= $('input[type=checkbox]',settings),
					checked = $('input[type=checkbox]:checked',settings);
				
				if (checks.length == checked.length){
					$('input[type=checkbox]',allSwitch).prop('checked',true);
				} else {
					if ($('input[type=checkbox]:checked',allSwitch).length){
						$('input[type=checkbox]',allSwitch).prop('checked',false);
					}
				}
			}
		});
		$('input[type=checkbox]',allSwitch).on('change',function(e){
			var theSwitch 	= $(this),
				checks 		= $('input[type=checkbox]',settings);
			_fromAllSwitch 	= true;
			if (theSwitch.is(':checked')){
				$.each(checks,function(){
					$(this).prop('checked', true).trigger('change');
				});
			} else {
				$.each(checks,function(){
					$(this).prop('checked', false).trigger('change');
				});
			}
			_fromAllSwitch 	= false;
		});
		
	}


	_IPIDO_ADMIN.themeLiveUpdate = {
		init: function(){
			CS_DEBUG("Theme Live Update Init");
			this.liveUpdate();
		},
		liveUpdate: function(){
			var self = this;

			$('.csf-field-color_theme').on('csf-color_theme-update',function(event,field_id,color){
				var css_var;
	
				switch(field_id) {
					// Top Navbar
					case 'header-bg':
						css_var = '--cs-ipido-theme_navbar-background';
						break;
					case 'header-border':
						css_var = '--cs-ipido-theme_navbar-border-color';
						break;
					case 'header-text':
						css_var = '--cs-ipido-theme_navbar-text';
						break;
					case 'header-toolbar-text':
						css_var = '--cs-ipido-theme_navbar-toolbar-text';
						break;
					case 'header-toolbar-text-hover':
						css_var = '--cs-ipido-theme_navbar-toolbar-text-hover';
						break;
					
					// Admin Menu Sidebar Brand
					case 'header-brand-bg':
						css_var = '--cs-ipido-theme_brand-background';
						break;
					case 'header-brand-text':
						css_var = '--cs-ipido-theme_brand-text';
						break;
					case 'header-brand-subtitle-text':
						css_var = '--cs-ipido-theme_brand-subtitle-text';
						break;
					case 'header-brand-border':
						css_var = '--cs-ipido-theme_brand-border';
						break;
	
					// Brand Icon Logo
					case 'header-brand-icon-bg':
						css_var = '--cs-ipido-theme_brand-icon-background';
						break;
					case 'header-brand-icon-color':
						css_var = '--cs-ipido-theme_brand-icon-color';
						break;
					
					// Sidebar
					case 'sidebar-bg':
						css_var = '--cs-ipido-theme_sidebar-background';
						break;
					case 'sidebar-text':
						css_var = '--cs-ipido-theme_sidebar-text';
						break;
					case 'sidebar-hover-bg':
						css_var = '--cs-ipido-theme_sidebar-hover-background';
						break;
					case 'sidebar-hover-text':
						css_var = '--cs-ipido-theme_sidebar-hover-text';
						break;
					case 'sidebar-active-bg':
						css_var = '--cs-ipido-theme_sidebar-active-background';
						break;
					case 'sidebar-active-text':
						css_var = '--cs-ipido-theme_sidebar-active-text';
						break;
					case 'sidebar-active-hover-text':
						css_var = '--cs-ipido-theme_sidebar-active-hover-text';
						break;
					case 'sidebar-active-highlight':
						css_var = '--cs-ipido-theme_sidebar-active-highlight';
						break;
					case 'sidebar-current-text':
						css_var = '--cs-ipido-theme_sidebar-current-text';
						break;
					case 'sidebar-current-bg':
						css_var = '--cs-ipido-theme_sidebar-current-background';
						break;
					case 'sidebar-current-highlight':
						css_var = '--cs-ipido-theme_sidebar-current-highlight';
						break;
					case 'sidebar-current-hover-text':
						css_var = '--cs-ipido-theme_sidebar-current-hover-text';
						break;
					case 'sidebar-current-hover-bg':
						css_var = '--cs-ipido-theme_sidebar-current-hover-background';
						break;
					case 'sidebar-current-subitem-text':
						css_var = '--cs-ipido-theme_sidebar-current-subitem-text';
						break;
					case 'sidebar-current-subitem-hover-text':
						css_var = '--cs-ipido-theme_sidebar-current-subitem-hover-text';
						break;
					case 'sidebar-current-subitem-current-text':
						css_var = '--cs-ipido-theme_sidebar-current-subitem-current-text';
						break;
	
					// General Colors
					case 'primary-normal':
						css_var = '--cs-ipido-theme_color-primary';
						break;
					case 'primary-light':
						css_var = '--cs-ipido-theme_color-primary-light';
						break;
					case 'accent':
						css_var = '--cs-ipido-theme_color-accent';
						break;
	
					// UI Elements
					case 'body-bg':
						css_var = '--cs-ipido-theme_body-background';
						break;
					case 'body-text':
						css_var = '--cs-ipido-theme_body-text';
						break;
					
					case 'card-border':
						css_var = '--cs-ipido-theme_card-border-color';
						break;
					case 'card-bg':
						css_var = '--cs-ipido-theme_card-background';
						break;
					case 'card-title-bg':
						css_var = '--cs-ipido-theme_card-title-background';
						break;
	
					case 'dropdown-border':
						css_var = '--cs-ipido-theme_dropdown-border-color';
						break;
					case 'dropdown-bg':
						css_var = '--cs-ipido-theme_dropdown-background';
						break;
					case 'dropdown-text':
						css_var = '--cs-ipido-theme_dropdown-text';
						break;
					case 'dropdown-hover-bg':
						css_var = '--cs-ipido-theme_dropdown-hover-background';
						break;
					case 'dropdown-hover-text':
						css_var = '--cs-ipido-theme_dropdown-hover-text';
						break;
	
					case 'input-border':
						css_var = '--cs-ipido-theme_input-border-color';
						break;
					case 'input-border-focus':
						css_var = '--cs-ipido-theme_input-border-color-focus';
						break;
					case 'input-bg':
						css_var = '--cs-ipido-theme_input-background';
						break;
					case 'input-text':
						css_var = '--cs-ipido-theme_input-text';
						break;
	
					// Button Primary
					case 'button-primary-ini':
						css_var = '--cs-ipido-theme_button-primary-normal-ini';
						break;
					case 'button-primary-end':
						css_var = '--cs-ipido-theme_button-primary-normal-end';
						break;
					case 'button-primary-border':
						css_var = '--cs-ipido-theme_button-primary-normal-border';
						break;
					case 'button-primary-text':
						css_var = '--cs-ipido-theme_button-primary-normal-text';
						break;
	
					// Button Base
					case 'button-base-ini':
						css_var = '--cs-ipido-theme_button-base-normal-ini';
						break;
					case 'button-base-end':
						css_var = '--cs-ipido-theme_button-base-normal-end';
						break;
					case 'button-base-border':
						css_var = '--cs-ipido-theme_button-base-normal-border';
						break;
					case 'button-base-text':
						css_var = '--cs-ipido-theme_button-base-normal-text';
						break;
	
					default:
						css_var = false;
				}
	
				if (css_var){
					var style = {};
					style[css_var] = color;
	
					self.setStyle(":root",style);
				}
			});
		},
		/**
		 * HELPER FUNCTIONS
		 */
		setStyle: function( element, propertyObject ){
			var elem = document.querySelector(element).style;
			for (var property in propertyObject){
				elem.setProperty(property, propertyObject[property]);
			}
		},
		removeStyle: function( element, propertyObject){
			var elem = document.querySelector(element).style;
			for (var property in propertyObject){
				elem.removeProperty(propertyObject[property]);
			}
		}
	}


	$(document).ready(function() {
		_IPIDO_ADMIN.topNavbar.init();
		_IPIDO_ADMIN.networkSidebar.init();
		_IPIDO_ADMIN.notificationCenter.init();
		_IPIDO_ADMIN.selectBox();
		_IPIDO_ADMIN.userProfileSettings();
		_IPIDO_ADMIN.general.init();
		_IPIDO_ADMIN.themeLiveUpdate.init();

		// Comentado porque no permitia el "drag&drop" de los widgets del dashboard
		// var t = setTimeout(function(){
		// 	window.wpResponsive.activate();
		// },0);

	});

	/**
	 * Define Public API
	 */
	_IPIDO_ADMIN._debug = CS_DEBUG;

})( jQuery, window );