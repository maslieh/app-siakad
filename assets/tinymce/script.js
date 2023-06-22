tinymce.init({
			selector: "#subadar-wysiwyg",
			skin: "light",
			plugins: [
				"advlist autolink link image lists charmap print preview hr anchor pagebreak",
				"searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
				"table contextmenu directionality emoticons paste textcolor responsivefilemanager",
                "code fullscreen youtube autoresize"
			],
			menubar : false,
			toolbar1: "fontsizeselect | styleselect",
            toolbar2: "| undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent table | link unlink anchor | responsivefilemanager image media youtube | forecolor backcolor | fullscreen ",
			image_advtab: true,
            fontsize_formats: "8px 10px 12px 14px 18px 24px 36px",
			relative_urls: false,
			remove_script_host: false,
			external_filemanager_path: "assets/filemanager/",
			filemanager_title: "File Manager",
			external_plugins: {
				"filemanager" : "../../assets/filemanager/plugin.min.js"
			},
			convert_fonts_to_spans: true,
			paste_word_valid_elements: "b,strong,i,em,h1,h2,u,p,ol,ul,li,a[href],span,color,font-size,font-color,font-family,mark,table,tr,td",
			paste_retain_style_properties: "all",
            setup: function (theEditor) {
				theEditor.on("init", function() {
					$(window).scroll(function() {
						var sticky = $(theEditor.contentAreaContainer.parentElement).find("div.mce-toolbar-grp");
                        var btnfull = $(theEditor.contentAreaContainer.parentElement).find("#mce_57");
                        btnfull.addClass('mce_btnfull');
						var scroll = $(window).scrollTop();
						if (scroll > 650) {
							sticky.addClass('mce-toolbar-grp-fixed');
							 
                            btnfull.removeClass('mce_btnfull');
                            btnfull.addClass('mce_btnfull_full');
						} else {
							sticky.removeClass('mce-toolbar-grp-fixed');
                            btnfull.removeClass('mce_btnfull_full');
                            btnfull.addClass('mce_btnfull');
							
						}
					});
				});
			}
		});

$(document).ready(function() {
						   

	

	$('#tiny-text').click(function (e) {
		e.stopPropagation();
		tinymce.EditorManager.execCommand('mceRemoveEditor',true, 'subadar-wysiwyg');
	});
	
	$('#tiny-visual').click(function (e) {
		e.stopPropagation();
		tinymce.EditorManager.execCommand('mceAddEditor',true, 'subadar-wysiwyg');
	});						   
						   
});