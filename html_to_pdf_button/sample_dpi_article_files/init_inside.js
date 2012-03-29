$().ready(function() {

    $('h1.portalHeading').each(function(){
         $(this).replaceWith("<h2 class='portalHeading'>" + $(this).html() + "</h2>")
    });
    $('h2.h2_portal').each(function(){
         $(this).replaceWith("<h3 class='h3_portal'>" + $(this).html() + "</h3>")
    });

    $('p.featured_heading').each(function(){
         $(this).replaceWith("<h3 class='quick_links'>" + $(this).html() + "</h3>")
    });
    $('.featuredBox').each(function(){
         $(this).replaceWith("<div class='quick_links_box'>" + $(this).html() + "</div>")
    });
    $('.featuredList').each(function(){
         $(this).replaceWith("<ul class='quick_links'>" + $(this).html() + "</ul>")
    });



    $('a#link-print-preview').printPreview();
	$('a#link-print-preview-form').printPreview();

//Colours alternating rows it a table unless the no_alternating class is supplied to the table
	$('table.tableStyle').each(function(){
		if($(this).hasClass('no_alternating')){
			return false;
		}
		else{
			$(this).find('tr:odd').addClass('tr1');
		}
	});

//Adds class to table if it doesnt have a thead so that th can be styled correctly
$('table').each(function(){
	if($(this).children('thead').length){
		return false;
	}
	else{
		$(this).addClass('no-thead');
	}
});

$.fn.search = function() {
	return this.focus(function() {
		if( this.value == this.defaultValue ) {
			this.value = "";
		}
	}).blur(function() {
		if( !this.value.length ) {
			this.value = this.defaultValue;
		}
	});
};
$("#search_bar").search();

});




