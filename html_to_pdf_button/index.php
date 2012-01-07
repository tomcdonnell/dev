<!DOCTYPE html>
<html lang="en">
 <head>
  <title>dompdf - The PHP 5 HTML to PDF Converter</title>
  <link rel="stylesheet" href="style.css" type="text/css"/>
  <link rel="SHORTCUT ICON" href="images/favicon.ico"/>
  <script type="text/javascript">
   $(document).ready(function () {$('button#getPageAsPdf').click(onClickGetPageAsPdf);});
   
  </script>
 </head>
 <body>
 <div id="header" class="bar">
  <a href="index.php"><img id="logo" src="images/title.gif" alt="dompdf"/></a>
  <a href="http://www.dompdf.com/" target="_blank">www.dompdf.com</a>
  &mdash; 
  Send bug reports to <a href="http://code.google.com/p/dompdf/issues/list">the bug tracker</a> 
  &amp; support questions to <a href="http://groups.google.com/group/dompdf">Google Groups</a>.
 </div>
 <div id="left_col">
  <ul>
   <li style="list-style-image: url('images/star_02.gif');"><a href="index.php">Overview</a></li>
   <li style="list-style-image: url('images/star_02.gif');"><a href="examples.php">Examples</a></li>
   <li style="list-style-image: url('images/star_02.gif');"><a href="demo.php">Demo</a></li>
   <li style="list-style-image: url('images/star_02.gif');"><a href="setup.php">Setup / Config</a></li>
  </ul>
 </div>
 <div id="content">
  <a name="demo"> </a>
  <h2>Demo</h2>
  <p>
   Enter your html snippet in the text box below to see it rendered as a
   PDF: (Note by default, remote stylesheets, images &amp; inline PHP are disabled.)
  </p>
  <form action="/html_to_pdf_button/dompdf/www/demo.php" method="post">
   <p>Paper size and orientation:
    <select name="paper">
     <option value="4a0">4a0</option>
     <option value="2a0">2a0</option>
     <option value="a0">a0</option>
     <option value="a1">a1</option>
     <option value="a2">a2</option>
     <option value="a3">a3</option>
     <option value="a4">a4</option>
     <option value="a5">a5</option>
     <option value="a6">a6</option>
     <option value="a7">a7</option>
     <option value="a8">a8</option>
     <option value="a9">a9</option>
     <option value="a10">a10</option>
     <option value="b0">b0</option>
     <option value="b1">b1</option>
     <option value="b2">b2</option>
     <option value="b3">b3</option>
     <option value="b4">b4</option>
     <option value="b5">b5</option>
     <option value="b6">b6</option>
     <option value="b7">b7</option>
     <option value="b8">b8</option>
     <option value="b9">b9</option>
     <option value="b10">b10</option>
     <option value="c0">c0</option>
     <option value="c1">c1</option>
     <option value="c2">c2</option>
     <option value="c3">c3</option>
     <option value="c4">c4</option>
     <option value="c5">c5</option>
     <option value="c6">c6</option>
     <option value="c7">c7</option>
     <option value="c8">c8</option>
     <option value="c9">c9</option>
     <option value="c10">c10</option>
     <option value="ra0">ra0</option>
     <option value="ra1">ra1</option>
     <option value="ra2">ra2</option>
     <option value="ra3">ra3</option>
     <option value="ra4">ra4</option>
     <option value="sra0">sra0</option>
     <option value="sra1">sra1</option>
     <option value="sra2">sra2</option>
     <option value="sra3">sra3</option>
     <option value="sra4">sra4</option>
     <option selected value="letter">letter</option>
     <option value="legal">legal</option>
     <option value="ledger">ledger</option>
     <option value="tabloid">tabloid</option>
     <option value="executive">executive</option>
     <option value="folio">folio</option>
     <option value="commercial #10 envelope">commercial #10 envelope</option>
     <option value="catalog #10 1/2 envelope">catalog #10 1/2 envelope</option>
     <option value="8.5x11">8.5x11</option>
     <option value="8.5x14">8.5x14</option>
     <option value="11x17">11x17</option>
    </select>
    <select name="orientation">
     <option value="portrait">portrait</option>
     <option value="landscape">landscape</option>
    </select>
   </p>
   <textarea id="htmlTextarea" name="html" cols="60" rows="20">
&lt;html&gt;
&lt;head&gt;
&lt;style&gt;

/* Type some style rules here */

&lt;/style&gt;
&lt;/head&gt;

&lt;body&gt;

&lt;!-- Type some HTML here --&gt;

&lt;/body&gt;
&lt;/html&gt;
   </textarea>
   <div style="text-align: center; margin-top: 1em;">
    <button id='getPageAsPdf' type="submit">Get page as PDF</button>
   </div>
  </form>
  <p style="font-size: 0.65em; text-align: center;">
   (Note: if you use a KHTML based browser and are having difficulties
   loading the sample output, try saving it to a file first.)
  </p>
  </div>
  <div id="footer">
   <div class="badges">
    <a href="http://www.php.net"><img src="images/php5-power-micro.png" alt="php5 logo"/></a>
    <a href="http://validator.w3.org"><img src="images/xhtml10.png" alt="valid xhtml"/></a>
    <a href="http://jigsaw.w3.org/css-validator"><img src="images/css2.png" alt="valid css2"/></a>
   </div>
  </div>
 </body>
</html>
