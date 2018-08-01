/* ----------------------------------------------------------------------------
// Ezdz [izy-dizy]
// Licensed under the MIT license.
// http://github.com/jaysalvat/ezdz/
// ----------------------------------------------------------------------------
// Copyright (C) 2014 Jay Salvat
// http://jaysalvat.com/
// --------------------------------------------------------------------------*/

/* global define: true, require: true, jQuery */

(function (factory) {
    "use strict";

    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        factory(require('jquery'));
    } else {
        factory(jQuery);
    }
}(function ($) {
    "use strict";

    // Default settings
    var defaults = {
        className:     '',
        text:          'Drop a file',
        previewImage:  true,
        value:         null,
        classes: {
            main:      'ezdz-dropzone',
            enter:     'ezdz-enter',
            reject:    'ezdz-reject',
            accept:    'ezdz-accept',
            focus:     'ezdz-focus'
        },
        validators: {
            maxSize:   null,
            width:     null,
            maxWidth:  null,
            minWidth:  null,
            height:    null,
            maxHeight: null,
            minHeight: null,
            maxNumber: null
        },
        init:   function() {},
        enter:  function() {},
        leave:  function() {},
        reject: function() {},
        accept: function() {},
        format: function(filename) {
            return filename;
        }
    };

    // Main plugin
    $.ezdz = function(element, options) {
        this.settings = $.extend(true, {}, defaults, $.ezdz.defaults, options);
        this.$input   = $(element);

        var self      = this,
            settings  = self.settings,
            $input    = self.$input;

        if (!$input.is('input[type="file"]')) {
            return;
        }

        // Stop if not compatible with HTML5 file API
        if (!$.ezdz.isBrowserCompatible()) {
            return;
        }

        // private: Init the plugin
        var init = function() {
            var $ezdz, $container, value;

            // Build the container
            $container = $('<div class="' + settings.classes.main + '" />')

            .on('dragover.ezdz', function() {
                $(this).addClass(settings.classes.enter);

                if ($.isFunction(settings.enter)) {
                     settings.enter.apply(this);
                }
            })

            .on('dragleave.ezdz', function() {
                $(this).removeClass(settings.classes.enter);

                if ($.isFunction(settings.leaved)) {
                    settings.leaved.apply(this);
                }
            })

            .addClass(settings.className);

            // Build the whole dropzone
            $input
                .wrap($container)
                .before('<div>' + settings.text + '</div>');

            $ezdz = $input.parent('.' + settings.classes.main);

            // Preview a file at start if it's defined
            value = settings.value || $input.data('value');

            if (value) {
                self.preview(value);
            }

            // Trigger the init callback
            if ($.isFunction(settings.init)) {
                 settings.init.apply($input, [ value ]);
            }

            // Events on the input
            $input

            .on('focus.ezdz', function() {
                $ezdz.addClass(settings.classes.focus);
            })

            .on('blur.ezdz', function() {
                $ezdz.removeClass(settings.classes.focus);
            })

            .on('change.ezdz', function() {
               
                
                // No file, so user has cancelled
                if (this.files.length == 0) {
                    return;
                }

                // Mime-Types
                var allowed  = $input.attr('accept'),
                    accepted = false,
                    valid    = true,
                    errors   = {
                        'mimeType':  false,
                        'maxSize':   false,
                        'width':     false,
                        'minWidth':  false,
                        'maxWidth':  false,
                        'height':    false,
                        'minHeight': false,
                        'maxHeight': false,
                        'maxNumber': false
                    };
                
                //Check the maximum number of files
                
                if (settings.validators.maxNumber && this.files.length > settings.validators.maxNumber) {
                    valid = false;
                    errors.maxNumber = true;
                }
                
                var imgArr = new Array();
                var imgName = new Array();//added by Murali
                var notimgArr = new Array();
                var isImage = true;
                
                var i=0;
                for(i=0;i<this.files.length;i++){
                	
                	 var file = this.files[i];
                	// Info about the dropped or selected file
                     var basename  = file.name.replace(/\\/g,'/').replace( /.*\//, ''),
                         extension = file.name.split('.').pop(),
                         formatted = settings.format(basename);
						 file.extension = extension;
                     
                     // Check the accepted Mime-Types from the input file
                     if (allowed) {
                         var types = allowed.split(/[,|]/);

                         $.each(types, function(i, type) {
                             type = $.trim(type);

                             if (file.type === type) {
                                 accepted = true;
                                 return false;
                             }

                             // Mime-Type with wildcards ex. image/*
                             if (type.indexOf('/*') !== false) {
                                 var a = type.replace('/*', ''),
                                     b = file.type.replace(/(\/.*)$/g, '');

                                 if (a === b) {
                                     accepted = true;
                                     return false;
                                 }
                             }
                         });

                         if (accepted === false) {
                             errors.mimeType = true;
                         }
                     } else {
                         accepted = true;
                     }

                     // Reset the accepted / rejected classes
                     $ezdz.removeClass(settings.classes.reject + ' ' + settings.classes.accept);

                     // If the Mime-Type is not accepted
                     if (accepted !== true) {
                         $input.val('');

                         $ezdz.addClass(settings.classes.reject);

                         // Trigger the reject callback
                         if ($.isFunction(settings.reject)) {
                              settings.reject.apply($input, [ file, errors ]);
                         }
                         return false;
                     }

                     // Read the added file
                     var reader = new FileReader(file);

                     reader.readAsDataURL(file);

					reader.onload = (function(theFile){
						var fileName = theFile.name;
						return function(e){
						 //console.log(fileName);
                         var img = new Image();
                         imgName.push(fileName); //added by murali   
                         imgArr.push(img);
                         notimgArr.push(fileName);
                         
                         file.data = e.target.result;
                         img.src   = file.data;
                         setTimeout(function() {
                             if(img.width && img.height){
                            	 
                             }else{
                            	 isImage = false;
                             }

                             // Validator
                             if (settings.validators.maxSize && file.size > settings.validators.maxSize) {
                                 valid = false;
                                 errors.maxSize = true;
                             }

                             if (isImage) {
                                 file.width  = img.width;
                                 file.height = img.height;

                                 if (settings.validators.width && img.width !== settings.validators.width) {
                                     valid = false;
                                     errors.width = true;
                                 }

                                 if (settings.validators.maxWidth && img.width > settings.validators.maxWidth) {
                                     valid = false;
                                     errors.maxWidth = true;
                                 }

                                 if (settings.validators.minWidth && img.width < settings.validators.minWidth) {
                                     valid = false;
                                     errors.minWidth = true;
                                 }

                                 if (settings.validators.height && img.height !== settings.validators.height) {
                                     valid = false;
                                     errors.height = true;
                                 }

                                 if (settings.validators.maxHeight && img.height > settings.validators.maxHeight) {
                                     valid = false;
                                     errors.maxHeight = true;
                                 }

                                 if (settings.validators.minHeight && img.height < settings.validators.minHeight) {
                                     valid = false;
                                     errors.minHeight = true;
                                 }
                             }

                            
                           }, 100);

						};
					})(file);   
                }
                
                setTimeout(function() {
                	
                    // The file is validated, so added to input
                     if (valid === true) {
                        $ezdz.find('img').remove();
                        if (isImage && settings.previewImage === true) {
                        	var $image_wrapper = $("<ul></ul>");
                        	$image_wrapper.addClass("image-g");
							var i;
							for (i=0;i<imgArr.length;i++){
                        		var img_obj = imgArr[i];
								var s = imgName[i].replace(/\./g, '_'); // Added By Parag
								s = s.replace(/\ /g, '_');  // Added By Parag
								//alert(s);
								var $image_item = $("<li></li>").append(img_obj);
								$image_item.attr('class', s);  // Added By Parag
								$image_wrapper.append($image_item);
                        	}
							$ezdz.find('div').html($image_wrapper.fadeIn());
                        } else {
                        	var i;
							var $filenames="";
                        	for (i=0;i<notimgArr.length;i++){
								var s = notimgArr[i].replace(/\./g, '_');  // Added By Parag
								s = s.replace(/\ /g, '_');  // Added By Parag
								$filenames += '<span id="notimage" class="'+s+'">' + notimgArr[i] + '</span><br/>';  // Modified By Parag
							}
                            $ezdz.find('div').html($filenames);
                        }

                        $ezdz.addClass(settings.classes.accept);

                        // Trigger the accept callback
                        if ($.isFunction(settings.accept)) {
                             settings.accept.apply($input, [ file ]);
                        }
                    // The file is invalidated, so rejected
                    } else {
                        $input.val('');

                        $ezdz.addClass(settings.classes.reject);

                        // Trigger the reject callback
                        if ($.isFunction(settings.reject)) {
                             settings.reject.apply($input, [ file, errors ]);
                        }
                    }
                	
                }, 1000);
                
  
            });
        };

        init();
    };

    // Inject a file or image in the preview
    $.ezdz.prototype.preview = function(path, callback) {
        var settings  = this.settings,
            $input    = this.$input,
            $ezdz     = $input.parent('.' + settings.classes.main),
            basename  = path.replace(/\\/g,'/').replace( /.*\//, ''),
            formatted = settings.format(basename);

        var img = new Image();
        img.src = path;

        // Is an image
        img.onload = function() {
            $ezdz.find('div').html($(img).fadeIn());
        
            if ($.isFunction(callback)) {
                 callback.apply(this);
            }
        };

        // Is not an image
        img.onerror = function() {
            $ezdz.find('div').html('<span>' + formatted + '</span>');

            if ($.isFunction(callback)) {
                 callback.apply(this);
            }
        };

        $ezdz.addClass(settings.classes.accept);
    };

    // Destroy ezdz
    $.ezdz.prototype.destroy = function() {
        var settings = this.settings,
            $input   = this.$input;

        $input.parent('.' + settings.classes.main).replaceWith($input);
        $input.off('*.ezdz');
        $input.removeData('ezdz');
    };

    // Extend settings
    $.ezdz.prototype.options = function(options) {
        var settings = this.settings;

        if (!options) {
            return settings;
        }

        $.extend(true, this.settings, options);
    };

    // Get input container
    $.ezdz.prototype.container = function() {
        var settings = this.settings,
            $input   = this.$input;

        return $input.parent('.' + settings.classes.main);
    };

    // Is browser compatible
    $.ezdz.isBrowserCompatible = function() {
        return !!(window.File && window.FileList && window.FileReader);
    };

    // Default options
    $.ezdz.defaults = defaults;

    // jQuery plugin
    $.fn.ezdz = function(options) {
        var args = arguments,
            plugin = $(this).data('ezdz');

        if (!plugin) {
            return $(this).data('ezdz', new $.ezdz(this, options));
        } if (plugin[options]) {
            return plugin[options].apply(plugin, Array.prototype.slice.call(args, 1));
        } else {
            $.error('Ezdz error - Method ' +  options + ' does not exist.');
        }
    };
}));
