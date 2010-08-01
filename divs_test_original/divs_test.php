<?php
/**************************************************************************************************\
*
* Filename: "divs_test.php"
*
* Purpose: Test for layout of Bergamot.
*
* Author: Tom McDonnell 2007.
*
\**************************************************************************************************/
?>
<!DOCTYPE html PUBLIC
 "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
 <head>
  <link rel="stylesheet" href="style.css" />
  <script src="../utils/utils.js"></script>
  <script src="../utils/utils_math.js"></script>
  <script src="../utils/utils_DOM.js"></script>
  <script src="../utils/dom_builder.js"></script>
  <script src="movable_dividers.js"></script>
  <script src="bottom_drawer.js"></script>
  <script src="tabs.js"></script>
  <script src="divs_test.js"></script>
  <title>Divs Test</title>
 </head>
 <body>
  <div id="titleBar"><h1>Bergamot</h1></div>
  <div id="framesWrapper">
   <div id="top">
    <div id="topLeft">
     <div class="tabHeadings">
      <h2 class="selected">Tab Zero</h2><h2>Tab One</h2><h2>Tab Two</h2>
     </div>
     <div class="tabContents">
      <div class="selected">
       <table class="module">
        <tbody>
         <tr>
          <td class="c1"></td>
          <td class="c2"><input type="button" value="e"></td>
          <td class="c3"><input type="button" value="r"></td>
          <td class="c4">
           <select>
            <option>Option One ---------------------------</option>
            <option>Option Two ---------------------------</option>
            <option>Option Three -------------------------</option>
            <option>Option Four --------------------------</option>
            <option>Option Five --------------------------</option>
            <option>Option Six ---------------------------</option>
            <option>Option Seven -------------------------</option>
            <option>Option Eight -------------------------</option>
           </select>
          </td>
          <td class="c5">
           <select>
            <option>Option One ---------------------------</option>
            <option>Option Two ---------------------------</option>
            <option>Option Three -------------------------</option>
            <option>Option Four --------------------------</option>
            <option>Option Five --------------------------</option>
            <option>Option Six ---------------------------</option>
            <option>Option Seven -------------------------</option>
            <option>Option Eight -------------------------</option>
           </select>
          </td>
         </tbody>
        </table>
       <div id="testModuleDiv"><p>Tab Zero, Module One.</p></div>
       <div><p>Tab Zero, Module Two.</p></div>
       <div><p>Tab Zero, Module Three.</p></div>
       <div><p>Tab Zero, Module Four.</p></div>
       <div><p>Tab Zero, Module Five.</p></div>
       <div><p>Tab Zero, Module Six.</p></div>
       <div><p>Tab Zero, Module Seven.</p></div>
       <div><p>Tab Zero, Module Eight.</p></div>
      </div>
      <div>
       <div><p>Tab One, Module Zero.</p></div>
       <div><p>Tab One, Module One.</p></div>
       <div><p>Tab One, Module Two.</p></div>
      </div>
      <div>
       <div><p>Tab Two, Module Zero.</p></div>
       <div><p>Tab Two, Module One.</p></div>
       <div><p>Tab Two, Module Two.</p></div>
      </div>
     </div>
    </div>
    <div id="vDivider"></div>
    <div id="topRight">
     <div class="tabHeadings">
      <h2 class="selected">Tab Zero</h2><h2>Tab One</h2><h2>Tab Two</h2>
     </div>
     <div class="tabContents">
      <div class="selected">
       <div><p>Tab Zero, Module Zero.</p></div>
       <div><p>Tab Zero, Module One.</p></div>
       <div><p>Tab Zero, Module Two.</p></div>
       <div><p>Tab Zero, Module Three.</p></div>
       <div><p>Tab Zero, Module Four.</p></div>
       <div><p>Tab Zero, Module Five.</p></div>
       <div><p>Tab Zero, Module Six.</p></div>
       <div><p>Tab Zero, Module Seven.</p></div>
       <div><p>Tab Zero, Module Eight.</p></div>
      </div>
      <div>
       <div><p>Tab One, Module Zero.</p></div>
       <div><p>Tab One, Module One.</p></div>
       <div><p>Tab One, Module Two.</p></div>
      </div>
      <div>
       <div><p>Tab Two, Module Zero.</p></div>
       <div><p>Tab Two, Module One.</p></div>
       <div><p>Tab Two, Module Two.</p></div>
      </div>
     </div>
    </div>
    <div class="clearFloats"></div>
   </div>
   <div id="hDivider"></div>
   <div id="bottom">
    <div class="tabHeadings">
     <h2 class="selected">Tab Zero</h2><h2>Tab One</h2><h2>Tab Two</h2>
    </div>
    <div class="tabContents">
     <div class="selected">
      <div><p>Tab Zero, Module Zero.</p></div>
      <div><p>Tab Zero, Module One.</p></div>
      <div><p>Tab Zero, Module Two.</p></div>
      <div><p>Tab Zero, Module Three.</p></div>
      <div><p>Tab Zero, Module Four.</p></div>
      <div><p>Tab Zero, Module Five.</p></div>
      <div><p>Tab Zero, Module Six.</p></div>
      <div><p>Tab Zero, Module Seven.</p></div>
      <div><p>Tab Zero, Module Eight.</p></div>
     </div>
     <div>
      <div><p>Tab One, Module Zero.</p></div>
      <div><p>Tab One, Module One.</p></div>
      <div><p>Tab One, Module Two.</p></div>
     </div>
     <div>
      <div><p>Tab Two, Module Zero.</p></div>
      <div><p>Tab Two, Module One.</p></div>
      <div><p>Tab Two, Module Two.</p></div>
     </div>
    </div>
   </div>
<?php
/*
   <div id="bottomDrawers"><div class="tabHeadings"><h2 class="selected">Test Drawer</h2></div><div class="tabContents"><div class="selected"><div><p>Drawer Content</p><input type="text" /></div></div></div></div>
*/
?>
  </div>
  <div id="bottomBar"></div>
  <div id="ghostOne"></div>
  <div id="ghostTwo"></div>
  <div id="drawerGhost"></div>
 </body>
</html>
<?php
/*******************************************END*OF*FILE********************************************/
?>
