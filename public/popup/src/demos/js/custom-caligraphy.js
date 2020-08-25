$('.notificationBanner').SlickModals({
                restrict_cookieSet: false,
               // restrict_cookieName: 'sm-notificationBannerHide',
               // restrict_cookieScope: 'page',
                //restrict_cookieDays: '20',
               // restrict_cookieSetClass: 'sm-setNotificationBannerCookie-1',
               // popup_type: 'instant',
                popup_type: 'delayed',
				popup_delayedTime: '15s',
                popup_animation: 'zoomIn',
                popup_position: 'center',
                popup_css: {
                    'width': '700px',
                    'background': '#333',
                    'padding': '0',
                    'margin': 'auto'
                },
                overlay_css: {
                    'background': 'rgba(0,0,0,0.8)'
                },
                mobile_show      : true,
                mobile_breakpoint: '480px',
                mobile_position: 'center',
                mobile_css: {
                    'width'             : '100%',
					'height'            : 'auto',
                    'background': '#333',
                    'padding': '0',
                    'margin': 'auto'
                },
                callback_afterInit: function () {
                    sm_countDown('.notificationBanner');
                }
            });