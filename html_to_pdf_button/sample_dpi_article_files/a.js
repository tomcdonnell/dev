var page_url = document.location.href;
page_url = page_url.split("?")[0];

if(page_url.indexOf("www.") != -1){
  page_url = page_url.replace("www.dpi.vic.gov.au", "m.dpi.vic.gov.au");
}
else{
  if(page_url.indexOf("m.dpi") == -1){
    page_url = page_url.replace("dpi.vic.gov.au", "m.dpi.vic.gov.au");
  }
}

function querySt(ji) {
hu = window.location.search.substring(1);
gy = hu.split("&");
for (i=0;i<gy.length;i++) {
ft = gy[i].split("=");
if (ft[0] == ji) {
return ft[1];
}
}
}

if(querySt("full") == "true"){
  $.cookie('full_version', 'yes', { expires: 7, path: '/' } );
}

if ( (navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || ( navigator.userAgent.match(/Android/i) && navigator.userAgent.match(/Mobile/i) ) || ( navigator.userAgent.match(/Windows/i) && navigator.userAgent.match(/Mobile/i) ) || ( navigator.userAgent.match(/Opera Mini/i)|| ( navigator.userAgent.match(/Opera Mobile/i)))) && ($.cookie('full_version') != "yes"))
{
window.location   = page_url + "?SQ_DESIGN_NAME=mobile&SQ_ACTION=set_design_name";
}

$(document).ready(function(){
if ($.cookie('full_version') == "yes")
{
$('#footer_left').append("<br/><br/><a href=\"" + page_url + "?SQ_DESIGN_NAME=mobile&SQ_ACTION=set_design_name\"><img src=\"http://dpi.vic.gov.au/__data/assets/image/0011/132968/btn-mobile-site.gif\" alt=\"View mobile site\" /></a>");
}
});