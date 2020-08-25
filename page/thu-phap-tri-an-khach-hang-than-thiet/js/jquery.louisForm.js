$(document).ready(function(){
;
(($) => {
    'use strict';
    // Create the defaults once
    const pluginName = 'louisForm';
	var defaults = {
			html: 'body',
			isModal: true,
			ajaxSubmit: true,
			successAjax: function(){		
			},
			onModalClose: function() {
            },
			onSuccess: function() {
            },
            onError: function() {
                return true;
            },
            onSubmit: function() {
            },
            onAjaxSuccess: function() {
            },
            beforeAjaxSubmit: function(data, self, f) {
            }
		};

    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }
    
  

    // Avoid Plugin.prototype conflicts
    $.extend(Plugin.prototype, {
        init() {
			const that = this;
			var form = this.element;
		
		/*for (var instanceName in CKEDITOR.instances) {
            CKEDITOR.instances[instanceName].updateElement();
        }
        */
		
			if (that.settings.ajaxSubmit) {
		that._validateForm(form, function(form) {
		var queryString = $(form).serialize(); 

			that.settings.onSubmit();
			$.ajax({
			type: $(form).attr('method'),
			url: $(form).attr('action'),
			data: queryString,
			beforeSend: (e) => {	         
                   that._beforeSend(form);
                   that.settings.beforeAjaxSubmit(form);
                        },
			success: function (result){
				 that.settings.onAjaxSuccess(result);
				  if (that.settings.isModal){
				 appLoader.hide();
				 }
				  if (result.success) {
                           that.settings.onSuccess(result);
                            if (that.settings.isModal) {
                                        if (result.message) {
                                          //  appAlert.success(result.message, {container: '.modal-body', duration: 3000});
                                        }
                               
                            }else {
                                 if (result.message) {
                                      //  appAlert.error(result.message);
                                    }    
                                    }
                                
                            }else{
	                          if (result.message) {
                                       // appAlert.error(result.message);
                                    }  
                            }
				 },
		    error: function(response){
			    console.log(response);
			    that.settings.onError(response);
		    }
		});
		});
		} else {
                that._validateForm(form);
            }
           return false; 
        },
         _beforeSend(e) {
	         const that = this;
	         if (that.settings.isModal){
	        $(e).closest('.modal').modal('toggle');      
			 appLoader.show({
                    container:  this.settings.html,
                    zIndex: "auto",
                    css: "",
                });
                }
        },
         _validateForm(form, customSubmit) {
		 	const that = this;
            //add custom method
            $.validator.addMethod("greaterThanOrEqual",
                    function(value, element, params) {
                        var paramsVal = params;
                        if (params && (params.indexOf("#") === 0 || params.indexOf(".") === 0)) {
                            paramsVal = $(params).val();
                        }
                        if (!/Invalid|NaN/.test(new Date(value))) {
                            return new Date(value) >= new Date(paramsVal);
                        }
                        return isNaN(value) && isNaN(paramsVal)
                                || (Number(value) >= Number(paramsVal));
                    }, 'Must be greater than {0}.');
            
            $.validator.addMethod("oneormorechecked", function(value, element) {
  return $('input[name="' + element.name + '"]:checked').length > 0;
}, "Atleast 1 must be selected");        
                
            $(form).validate({
                submitHandler: function(form) {
                    if (customSubmit) {
                        customSubmit(form);
                    } else {
                        return true;
                    }
                },

                highlight: function(element) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error');
                },
                errorElement: 'span',
                errorClass: 'help-block',
               // ignore: ":hidden:not(.validate-hidden)",
                ignore: function (index, el) {
				var $el = $(el);

				if ($el.hasClass('always-validate')) {
				return false;
   				}

   				// Default behavior
   				return $el.is(':hidden');
				},
               // ignore: [],
               // ignore: "",
                errorPlacement: function(error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
            //handeling the hidden field validation like select2
            $(".validate-hidden").click(function() {
                $(this).closest('.form-group').removeClass('has-error').find(".help-block").hide();
            });
        }
        
        
        
    });
    // Plugin wrapper
    $.fn[pluginName] = function(options) {
        var plugin;
        this.each(function() {
            plugin = $.data(this, 'plugin_' + pluginName);
            if (!plugin) {
                plugin = new Plugin(this, options);
                $.data(this, 'plugin_' + pluginName, plugin);
            }
        });
        return plugin;
    };
})(jQuery, window, document);
});