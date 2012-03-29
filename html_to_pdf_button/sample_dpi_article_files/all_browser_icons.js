  //SCROLL DOWN FOR WORKING VERSION THIS IS KEPT HERE AS BACKUP
  // all browser version icons 
  // tested working 8th JUL 09
  // ©Copyright dpi (kane nelson)
  /* $().ready(function() {
      var fileTypes = {
      doc: './?a=4468',
      xls: './a=4469',
      pdf: './?a=4471',
      zip: './?a=4470',
      ppt: './?a=6316'
      };*/
     
     // this is like $.each() except
     // it iterates over all Anchor elements
     /*$('.bodycolumn a').each(function() {
      var $a = $(this);
      var href = $a.attr('href');
      if((! href.match(document.domain)) && (! href.match('www.dpi.vic.gov.au')) && (! href.match('vic.gov.au')) && (! href.match('dpistore.efirst.com.au')) && (! href.match('www.daff.gov.au')) && (! href.match('www.agribio.com.au')) && (! href.match('email.dpi.vic.gov.au'))  && (! href.match('twitter.com')) && (! href.match('new.dpi.vic.gov.au')) && (! href.match('www.new.dpi.vic.gov.au'))) {
      // use a special image for external links
      var image = 'http://new.dpi.vic.gov.au/__data/assets/image/0020/4466/None.jpg';
        $(this).attr("Title", "This is an external site");
          } else if((href.match(/^mailto:/))) {
          var image = './?a=4472';
              } else {
              // get the extension from the href
              var hrefArray = href.split('.');
              var extension = hrefArray[hrefArray.length - 1];
              var image = fileTypes[extension];
          }
     
      if(image) {
      $a.before('<img src="'+ image +'" alt="" class="icon" />&nbsp;');
      }
     
     });
     });*/


//Updated working version

$(document).ready(function(){
      var fileTypes = {
      doc: './?a=4468',
      xls: './a=4469',
      pdf: './?a=4471',
      zip: './?a=4470',
      ppt: './?a=6316'
      };

  $('.bodycolumn a, #right_content a').each(function(){
    var href = $(this).attr('href');

if(typeof(href ) !== 'undefined'){

    if(href != "" && href != "#" && (! href.match('#')) && (! href.match(document.domain)) && (! href.match('www.dpi.vic.gov.au')) && (! href.match('vic.gov.au')) && (! href.match('dpistore.efirst.com.au')) && (! href.match('www.daff.gov.au')) && (! href.match('www.agribio.com.au')) && (! href.match('email.dpi.vic.gov.au'))  && (! href.match('twitter.com')) && (! href.match('new.dpi.vic.gov.au')) && (! href.match('www.new.dpi.vic.gov.au')) && (! href.match('dpi.vic.gov.au')) && (! href.match('checkout.payments.com.au'))) {
      // use a special image for external links
      var image = './?a=4466';
      $(this).attr("Title", "This is an external site");
    }else if((href.match(/^mailto:/))){
       var image = './?a=4472';
    } else {
       // get the extension from the href
       var hrefArray = href.split('.');
       var extension = hrefArray[hrefArray.length - 1];
       var image = fileTypes[extension];
    }

    if(image) {
      $(this).before('<img src="'+ image +'" alt="" class="icon" />');
    }
}
  });
});