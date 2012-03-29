/*
 * jQuery Print Preview plug-in
 *	
 * @author: Tim Connell & Rick Lannan
 * @usage: $('a#example').printPreview();
 *
 */

(function($) {
	
	$.fn.printPreview = function() {
		
		var elements = this;
		
		return(
			elements.each(function(i) {
				
				// Default values
				var printLink = $(this);
				var screenSheet = $("link[media='screen']");
				var printSheet = $("link[media='print']");
				var previewSheet = document.createElement('link');
				
				// Insert alternate stylesheet
				$(previewSheet)
				.attr({
					href: printSheet.attr('href'),
					media: 'screen',
					type: 'text/css',
					rel: 'alternate stylesheet',
					title: 'print preview'
					});
				document.getElementsByTagName("head")[0].appendChild(previewSheet);
				previewSheet = $('link[title=print preview]').load();					  

				previewSheet.each(function(i) {
					this.disabled = true;
				});

				var printPreviewMessage = $('<div id="preview-message"><h3>Print preview</h3><a href="#" id="preview-print">Print this page</a> | <a href="#" id="turnoff-print">Return to the normal view</a></div>');
				$("head").append('<style type="text/css" media="print"> #preview-message { display: none !important; } </style>');
				
				// Print Preview
				printLink.click( function() {
					// Switch to print
					$("body").fadeOut("fast", function() {
						screenSheet.each(function() {
							this.disabled = true;
						});
						previewSheet.each(function() {
							this.disabled = false;
						});
						$(this).fadeIn("slow");
						
						$('html, body').animate({scrollTop:0}, 'fast');
			
						// Create Print Preview Heading
						$("body").prepend(printPreviewMessage);
						$("#preview-message").hide().slideDown("slow");
						
						// Switch Back
						$("a#turnoff-print").bind("click", function(){
							$("body").fadeOut("fast", function() {
								$("#preview-message").remove();
								screenSheet.each(function() {
									this.disabled = false;
								});
								previewSheet.each(function() {
									this.disabled = true;
								});
								$(this).fadeIn("slow");
							});
							return false;
						});
						
						// Print this page
						$("a#preview-print").bind("click", function(){
							window.print();
							return false;
						});
					});
					
					return false;
				});
				
			})
		);
	}
})(jQuery)