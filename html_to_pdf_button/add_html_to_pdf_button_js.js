  <script type="text/javascript">
   $(document).ready(function () {$('button#getPageAsPdf').click(onClickGetPageAsPdf);});

   function onClickGetPageAsPdf(ev)
   {
      $('textarea#htmlTextarea').attr('value', getEntirePageHtml());
   }

   function getEntirePageHtml()
   {
      var doctypeTag   = getUniqueStartTagHtml('doctype');
      var htmlStartTag = getUniqueStartTagHtml('html'   );
      return doctypeTag + htmlStartTag + $('html').html() + '</html>';
   }

   function getUniqueStartTagHtml(tagName)
   {
      var attributes       = $('html')[0].attributes;  
      var attributesString = '';

      for (var i = 0, len = attributes.length; i < len; ++i)
      {
         var attribute = attributes[i];
         attributesString += ' ' + attribute.name + '="' + attribute.value + '"';
      }

      return '<' + tagName + attributesString + '>';
   }
  </script>
