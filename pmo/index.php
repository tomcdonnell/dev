<?php
$timeUnix = time();
?>
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
<!-- START LAYOUT HEADER SECTION -->
<html>
 <head>
  <title>Performance Management Online</title>
  <meta http-equiv='content-type' content='text/html; charset=UTF-8'>
  <meta http-equiv='Pragma' content='no-cache'>
  <meta http-equiv='cache-control' content='no-cache'>
  <meta http-equiv='Caching' content=''>
  <link rel='stylesheet' type='text/css' href='css/form_validation.css?<?php echo $timeUnix; ?>'/>
  <link rel='stylesheet' type='text/css' href='css/more_header_styles.css?<?php echo $timeUnix; ?>'/>
  <link rel='stylesheet' type='text/css' href='css/ps_webpage.css?<?php echo $timeUnix; ?>'/>
  <link rel='stylesheet' type='text/css' href='css/Styles.css?<?php echo $timeUnix; ?>'/>
  <script src='common/js/utils/utils.js?<?php echo $timeUnix; ?>'></script>
  <script src='js/FormGlobal.js?<?php echo $timeUnix; ?>'></script>
  <script src='js/FormSection2.js?<?php echo $timeUnix; ?>'></script>
  <script src='../../common/js/3rdParty/jquery/1.4/jquery_minified.js?<?php echo $timeUnix; ?>'></script>
  <script src='../../common/js/3rdParty/utils/firebugx.js?<?php echo $timeUnix; ?>'></script>
  <script src='../../common/js/utils/utils.js?<?php echo $timeUnix; ?>'></script>
  <script src='js/initialisation.js?<?php echo $timeUnix; ?>'></script>
  <style type='text/css'>
   *
   {
      margin: 0;
      padding: 0;
   }
  </style>
 </head>
 <body>
  <div id='header'></div>
  <div id='navBackground1' style='width: 0px; float: left; background-color: #f8f8ec;'></div>
  <div id='layoutMain' style='float: right; background-color: #ffffff;'>
<!-- START MAIN SECTION -->
<form action='step_two.php?plan_id=1' method='POST'>
 <table class='layout' cellpadding='0' cellspacing='0' width='100%' height='100%'>
  <tbody>
   <tr height='100%'>
    <td rowspan='2' class='mainContent' valign='top'>
     <input type='submit' value='Save'/>
     <input type='submit' value='Save & Send to Manager'/>
     <input type='submit' value='Opt Out of Progression'/>
     <input type='submit' value='V&B Feedback'/>
     <br><br>
     <span style='margin-left: 10px;'>
      <img src='files/ppms_banner.jpg'>
     </span>
     <br><br>
     <span class='heading1'>DPI Performance Management Plan</span><br><br>
     <span class='heading3'>Period 01/07/2010 - 30/06/2011</span><br><br>
     <table class='ppmsTable' border='0' cellpadding='0' cellspacing='0'>
      <tbody>
       <tr valign='top'>
        <td class='overviewLabel' width='108'><b>Employee</b></td>
        <td class='tableData' style='font-weight: normal;' width='173'>
         Thomas McDonnell
        </td>
        <td class='overviewLabel' width='227'><b>Plan Status</b></td>
        <td class='tableData' style='font-weight: normal;' width='202'>
         No Plan
        </td>
       </tr>
       <tr valign='top'>
        <td class='overviewLabel' width='108'><b>Sign_Sup</b></td><td width='173'>
         2010-07-23 13:28:40
        </td>
        <td class='overviewLabel' width='227'><b>Mid Cycle Review Complete</b></td>
        <td class='tableData' style='font-weight: normal;' width='202'>
         No
        </td>
       </tr>
       <tr valign='top'>
        <td class='overviewLabel' width='108'><b>Division</b></td>
        <td class='tableData' style='font-weight: normal;' width='173'>
         Business & Corporate Services
        </td>
        <td class='overviewLabel' width='227'><b>Grade &amp; Range</b></td>
        <td class='tableData' style='font-weight: normal;' width='202'>
        -
        </td>
       </tr>
       <tr valign='top'>
        <td class='overviewLabel' width='108'><b>Branch</b></td>
        <td class='tableData' style='font-weight: normal;' width='173'>
         Service Delivery Unit
        </td>
        <td class='overviewLabel' width='227'><b>Manager</b></td>
        <td class='tableData' style='font-weight: normal;' width='202'>
         Mark Bryant
        </td>
       </tr>
       <tr valign='top'>
        <td class='overviewLabel' width='108'><b>Job Title</b></td>
        <td class='tableData' style='font-weight: normal;' width='173'>
         Web Support Analyst
        </td>
        <td class='overviewLabel' width='227'><b>Next Level Manager</b></td>
        <td class='tableData' style='font-weight: normal;' width='202'>
         Gordon Caris
        </td>
       </tr>
       <tr valign='top'>
        <td class='overviewLabel' width='108'><b>Opted Out of Progression</b></td>
        <td class='tableData' style='font-weight: normal;' width='173'>
         No
        </td>
        <td class='overviewLabel' width='227'><b>Other Duties Start Date</b></td>
        <td class='tableData' style='font-weight: normal;' width='202'>
        </td>
       </tr>
       <tr valign='top'>
        <td class='overviewLabel ' width='108'><b>Plan Type</b></td>
        <td class='tableData' style='font-weight: normal;' width='173'>
         Substantive        </td>
        <td class='overviewLabel ' width='227'><b>Other Duties End Date</b></td>
        <td class='tableData' style='font-weight: normal;' width='202'>
        </td>
       </tr>
      </tbody>
     </table>
     <div class='progMsg'></div>
     <br>
     <!-- SECTION 1:  Performance Goals and Added Value (PS1) -->
<table class='noBorder'>
 <tbody>
  <tr valign='top'>
   <td class='performanceStandardPlusMinus'>
    <div id='PSMinus1' style='display: none;'>
     <a href=''>
      <img src='files/open_performPlan.gif' alt='Hide Performance Standard 1' border='0'>
     </a>
    </div>
    <div id='PSPlus1' style='display: inline;'>
     <a href=''>
      <img src='files/closed_performPlan.gif' alt='Show Performance Standard 1' border='0'>
     </a>
    </div>
   </td>
  </tr>
 </tbody>
</table>
<div id='PerformanceStandard1' style='display: none;'>
 <span style='padding: 10px; margin-left: 10px; width: 760px;'>
  All MANAGERS with one or more fixed term or ongoing staff are required
  to have a mandatory PEOPLE AND RESOURCE MANAGEMENT goal related to
  compliance responsibilities including performance management,
  orientation, OHS and financial responsibilities. Managers are required
  to write an expectation measure under this goal.
 </span>
 <span style='padding: 5px 10px 10px; margin-left: 10px; width: 760px; font-size: 11px;'>
  (Select a minimum of 3 and a maximum of 6 goals)
 </span>
 <br>
 <table class='ppmsTable'><tbody><tr class='scores'></tr></tbody></table>
 <table class='ppmsTable' border='1' width='100%'>
  <tbody>
   <tr valign='top'>
    <td class='th1' width='33%'>Goals</td>
    <td class='th1' width='67%'>Performance Measures</td>
   </tr>
   <tr valign='top'>
    <td class='bgColour tableData' width='33%'><b>Goal 1</b></td>
    <td class='bgColour' width='67%'><b>Expectations</b></td>
   </tr>
   <tr valign='top'>
    <td class='tableData' width='33%'>
     <textarea name='s1_goal_1'class='S1_Goal'rows='7'cols='50'>asdfga</textarea>
    </td>
    <td class='tableData' width='67%'>
     <textarea name='s1_expectations_1'class='S1_Expectations'rows='7'cols='50'>asdfga</textarea>
    </td>
   </tr>
   <tr valign='top'>
    <td class='bgColour tableData' width='33%'><b>Goal 2</b></td>
    <td class='bgColour' width='67%'><b>Expectations</b></td>
   </tr>
   <tr valign='top'>
    <td class='tableData' width='33%'>
     <textarea name='s1_goal_2'class='S1_Goal invalidValue'rows='7'cols='50'></textarea>
    </td>
    <td class='tableData' width='67%'>
     <textarea name='s1_expectations_2'class='S1_Expectations invalidValue'rows='7'cols='50'></textarea>
    </td>
   </tr>
   <tr valign='top'>
    <td class='bgColour tableData' width='33%'><b>Goal 3</b></td>
    <td class='bgColour' width='67%'><b>Expectations</b></td>
   </tr>
   <tr valign='top'>
    <td class='tableData' width='33%'>
     <textarea name='s1_goal_3'class='S1_Goal invalidValue'rows='7'cols='50'></textarea>
    </td>
    <td class='tableData' width='67%'>
     <textarea name='s1_expectations_3'class='S1_Expectations invalidValue'rows='7'cols='50'></textarea>
    </td>
   </tr>
   <tr valign='top'>
    <td class='bgColour tableData' width='33%'><b>Goal 4</b></td>
    <td class='bgColour' width='67%'><b>Expectations</b></td>
   </tr>
   <tr valign='top'>
    <td class='tableData' width='33%'>
     <textarea name='s1_goal_4'class='S1_Goal invalidValue'rows='7'cols='50'></textarea>
    </td>
    <td class='tableData' width='67%'>
     <textarea name='s1_expectations_4'class='S1_Expectations invalidValue'rows='7'cols='50'></textarea>
    </td>
   </tr>
   <tr valign='top'>
    <td class='bgColour tableData' width='33%'><b>Goal 5</b></td>
    <td class='bgColour' width='67%'><b>Expectations</b></td>
   </tr>
   <tr valign='top'>
    <td class='tableData' width='33%'>
     <textarea name='s1_goal_5'class='S1_Goal invalidValue'rows='7'cols='50'></textarea>
    </td>
    <td class='tableData' width='67%'>
     <textarea name='s1_expectations_5'class='S1_Expectations invalidValue'rows='7'cols='50'></textarea>
    </td>
   </tr>
   <tr valign='top'>
    <td class='bgColour tableData' width='33%'><b>Goal 6</b></td>
    <td class='bgColour' width='67%'><b>Expectations</b></td>
   </tr>
   <tr valign='top'>
    <td class='tableData' width='33%'>
     <textarea name='s1_goal_6'class='S1_Goal invalidValue'rows='7'cols='50'></textarea>
    </td>
    <td class='tableData' width='67%'>
     <textarea name='s1_expectations_6'class='S1_Expectations invalidValue'rows='7'cols='50'></textarea>
    </td>
   </tr>
  </tbody>
 </table>
 <table class='ppmsTable' border='1' width='100%'>
  <tbody>
   <tr valign='top'>
    <td class='th1' colspan='2' width='100%'>
     Added Value Measures<br>
     <span style='font-size: 11px; text-transform: none;'>
      To access progression, all eligible staff are required to have added value measures in PS1
     </span>
    </td>
   </tr>
   <tr valign='top'>
    <td class='bgColour' colspan='2' width='100%'>
     <b>Added Value Measure 1</b>
    </td>
   </tr>
   <tr valign='top'>
    <td class='tableData' colspan='2' width='100%'>
     <span>
     <textarea name='s1_addedValueMeasure_1'class='S1_AddedValue invalidValue'rows='7'cols='50'></textarea>
     </span>
    </td>
   </tr>
   <tr valign='top'>
    <td class='bgColour' colspan='2' width='100%'>
     <b>Added Value Measure 2</b>
    </td>
   </tr>
   <tr valign='top'>
    <td class='tableData' colspan='2' width='100%'>
     <span>
     <textarea name='s1_addedValueMeasure_2'class='S1_AddedValue invalidValue'rows='7'cols='50'></textarea>
     </span>
    </td>
   </tr>
   <tr valign='top'>
    <td class='bgColour' colspan='2' width='100%'>
     <b>Added Value Measure 3</b>
    </td>
   </tr>
   <tr valign='top'>
    <td class='tableData' colspan='2' width='100%'>
     <span>
     <textarea name='s1_addedValueMeasure_3'class='S1_AddedValue invalidValue'rows='7'cols='50'></textarea>
     </span>
    </td>
   </tr>
  </tbody>
 </table>
 <br>
 <table class='ppmsTable' border='1' width='100%'>
  <tbody>
   <tr valign='top'>
    <td class='tableData' width='100%'>
     <b>Additional/Alternate goals for higher Grade/Secondment position:</b><br>
     Add Goal, Expectations and Added Value Measures if in higher
     duties/secondment position for at least 3 continuous months (89 Days).
     Any relevant goals for positions occcupied for less than 3 months
     should be incorporated in normal plan
    </td>
   </tr>
   <tr valign='top'>
    <td class='tableHead' width='100%'>
     <textarea name='s1_additionalGoals'class='S1_Goal invalidValue'rows='7'cols='50'></textarea>
    </td>
   </tr>
  </tbody>
 </table>
 <br>
 <table class='ppmsTable' border='1' width='100%'>
  <tbody>
   <tr valign='top'>
    <td class='tableData' width='50%'>
     <b>Employee Comments:</b><b> </b><br>
     <span>
     <textarea name='s1_employeeComments'class='Comments invalidValue'rows='7'cols='50'></textarea>
     </span>
    </td>
    <td class='tableData' width='50%'><b>Manager Comments:</b><br></td>
   </tr>
  </tbody>
 </table>
</div>
<!-- END SECTION 1 -->
<!-- SECTION 2:  Capability Assessment -->
<span class='psSeparator'>&nbsp;</span>
<table class='noBorder'>
 <tbody>
  <tr valign='top'>
   <td class='performanceStandardPlusMinus'>
    <div id='PSMinusCapabilityAssessment' style='display: none;'>
     <a href=''>
      <img src='files/open_capAssess.gif' alt='Hide Capability Assessment' border='0'>
     </a>
    </div>
    <div id='PSPlusCapabilityAssessment' style='display: inline;'>
     <a href=''>
      <img src='files/closed_capAssess.gif' alt='Show Capability Assessment' border='0'>
     </a>
    </div>
   </td>
  </tr>
 </tbody>
</table>
<div id='PerformanceStandardCapabilityAssessment' style='display: none;'>
 <br>
 <table id='ppmsTableOne' class='ppmsTable' border='1' width='100%'>
  <tbody>
   <tr valign='top'>
    <td class='th1' colspan='2' width='33%'>Capability Name</td>
    <td class='th1' width='17%'>Personal Capability Level</td>
    <td class='th1' colspan='2' width='33%'>Capability Name</td>
    <td class='th1' width='17%'>Personal Capability Level</td>
   </tr>
   <tr valign='top'>
    <td width='6%'>1</td>
    <td id='cap_1' width='27%'>Formulating Strategies & Concepts</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_1'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='6%'>13</td>
    <td id='cap_13' width='27%'>Leadership</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_13'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
   </tr>
   <tr valign='top'>
    <td width='6%'>2</td>
    <td id='cap_2' width='27%'>Researching</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_2'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='6%'>14</td>
    <td id='cap_14' width='27%'>Stakeholder and Relationship Focus</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_14'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
   </tr>
   <tr valign='top'>
    <td width='6%'>3</td>
    <td id='cap_3' width='27%'>Creating & Innovating</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_3'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='6%'>15</td>
    <td id='cap_15' width='27%'>Consulting & Advice</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_15'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
   </tr>
   <tr valign='top'>
    <td width='6%'>4</td>
    <td id='cap_4' width='27%'>Environmental Scanning</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_4'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='6%'>16</td>
    <td id='cap_16' width='27%'>Decision Making</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_16'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
   </tr>
   <tr valign='top'>
    <td width='6%'>5</td>
    <td id='cap_5' width='27%'>Continuous Improvement & Innovation</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_5'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='6%'>17</td>
    <td id='cap_17' width='27%'>Managing Change</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_17'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
   </tr>
   <tr valign='top'>
    <td width='6%'>6</td>
    <td id='cap_6' width='27%'>Analysis & Problem Solving</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_6'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='6%'>18</td>
    <td id='cap_18' width='27%'>Negotiating</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_18'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
   </tr>
   <tr valign='top'>
    <td width='6%'>7</td>
    <td id='cap_7' width='27%'>Manage Self</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_7'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='6%'>19</td>
    <td id='cap_19' width='27%'>Managing Resources</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_19'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
   </tr>
   <tr valign='top'>
    <td width='6%'>8</td>
    <td id='cap_8' width='27%'>Personal Resilience</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_8'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='6%'>20</td>
    <td id='cap_20' width='27%'>Policy</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_20'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
   </tr>
   <tr valign='top'>
    <td width='6%'>9</td>
    <td id='cap_9' width='27%'>Achievement Focus</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_9'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='6%'>21</td>
    <td id='cap_21' width='27%'>Managing Projects</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_21'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
   </tr>
   <tr valign='top'>
    <td width='6%'>10</td>
    <td id='cap_10' width='27%'>Communicating with Influence</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_10'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='6%'>22</td>
    <td id='cap_22' width='27%'>Applying Technical Expertise</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_22'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
   </tr>
   <tr valign='top'>
    <td width='6%'>11</td>
    <td id='cap_11' width='27%'>Collaboration</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_11'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='6%'>23</td>
    <td id='cap_23' width='27%'>Managing Knowledge & Information</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_23'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
   </tr>
   <tr valign='top'>
    <td width='6%'>12</td>
    <td id='cap_12' width='27%'>Delivering Results</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_12'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='6%'>24</td>
    <td id='cap_24' width='27%'>Managing Risk</td>
    <td class='tableData' width='17%'>
     <select name='s2_capLevel_24'class='invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
   </tr>
  </tbody>
 </table>
 <br>
 <table id='ppmsTableTwo' class='ppmsTable' border='1' width='100%'>
  <tbody>
   <tr valign='top'>
    <td class='th1' colspan='2' width='46%'>
     Capability Name<br>
     <span style='text-transform: none; font-size: 11px;'>
      Do not select the same capability more than once.
     </span>
    </td>
    <td class='th1' width='19%'>Required Role Capability Level</td>
    <td class='th1' width='17%'>Personal Capability Level</td>
    <td class='th1' width='18%'>Development Required Y/N</td>
   </tr>
   <tr valign='top'>
    <td class='bgColour' width='6%'>1</td>
    <td id='capSelected_1' width='40%'>
     <select name='s2_capNameSelected_1'class=' invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Formulating Strategies & Concepts</option>
      <option value='13'>Leadership</option>
      <option value='2'>Researching</option>
      <option value='14'>Stakeholder and Relationship Focus</option>
      <option value='3'>Creating & Innovating</option>
      <option value='15'>Consulting & Advice</option>
      <option value='4'>Environmental Scanning</option>
      <option value='16'>Decision Making</option>
      <option value='5'>Continuous Improvement & Innovation</option>
      <option value='17'>Managing Change</option>
      <option value='6'>Analysis & Problem Solving</option>
      <option value='18'>Negotiating</option>
      <option value='7'>Manage Self</option>
      <option value='19'>Managing Resources</option>
      <option value='8'>Personal Resilience</option>
      <option value='20'>Policy</option>
      <option value='9'>Achievement Focus</option>
      <option value='21'>Managing Projects</option>
      <option value='10'>Communicating with Influence</option>
      <option value='22'>Applying Technical Expertise</option>
      <option value='11'>Collaboration</option>
      <option value='23'>Managing Knowledge & Information</option>
      <option value='12'>Delivering Results</option>
      <option value='24'>Managing Risk</option>
     </select>
    </td>
    <td width='19%'>
     <select name='s2_capLevelRequired_1'class=' invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='17%'>
     <input name='s2_surrogate_capLevelPossessed_1' value='1' type='hidden'>
     <select name='s2_capLevelPossessed_1'disabled='disabled'class=''>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='18%'>
     <input name='s2_surrogate_developmentRequired_1' value='1' type='hidden'>
     <label>
      <input value='Yes' checked='checked' id='s2_developmentRequiredY_1'
       style='border: medium none ;' disabled='disabled' type='radio'>
      Yes
     </label>
     <label>
      <input value='No' id='s2_developmentRequiredN_1'
       style='border: medium none ;' disabled='disabled' type='radio'>
      No
     </label>
    </td>
   </tr>
   <tr valign='top'>
    <td class='bgColour' width='6%'>2</td>
    <td id='capSelected_1' width='40%'>
     <select name='s2_capNameSelected_2'class=' invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Formulating Strategies & Concepts</option>
      <option value='13'>Leadership</option>
      <option value='2'>Researching</option>
      <option value='14'>Stakeholder and Relationship Focus</option>
      <option value='3'>Creating & Innovating</option>
      <option value='15'>Consulting & Advice</option>
      <option value='4'>Environmental Scanning</option>
      <option value='16'>Decision Making</option>
      <option value='5'>Continuous Improvement & Innovation</option>
      <option value='17'>Managing Change</option>
      <option value='6'>Analysis & Problem Solving</option>
      <option value='18'>Negotiating</option>
      <option value='7'>Manage Self</option>
      <option value='19'>Managing Resources</option>
      <option value='8'>Personal Resilience</option>
      <option value='20'>Policy</option>
      <option value='9'>Achievement Focus</option>
      <option value='21'>Managing Projects</option>
      <option value='10'>Communicating with Influence</option>
      <option value='22'>Applying Technical Expertise</option>
      <option value='11'>Collaboration</option>
      <option value='23'>Managing Knowledge & Information</option>
      <option value='12'>Delivering Results</option>
      <option value='24'>Managing Risk</option>
     </select>
    </td>
    <td width='19%'>
     <select name='s2_capLevelRequired_2'class=' invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='17%'>
     <input name='s2_surrogate_capLevelPossessed_2' value='1' type='hidden'>
     <select name='s2_capLevelPossessed_2'disabled='disabled'class=''>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='18%'>
     <input name='s2_surrogate_developmentRequired_2' value='1' type='hidden'>
     <label>
      <input value='Yes' checked='checked' id='s2_developmentRequiredY_2'
       style='border: medium none ;' disabled='disabled' type='radio'>
      Yes
     </label>
     <label>
      <input value='No' id='s2_developmentRequiredN_2'
       style='border: medium none ;' disabled='disabled' type='radio'>
      No
     </label>
    </td>
   </tr>
   <tr valign='top'>
    <td class='bgColour' width='6%'>3</td>
    <td id='capSelected_1' width='40%'>
     <select name='s2_capNameSelected_3'class=' invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Formulating Strategies & Concepts</option>
      <option value='13'>Leadership</option>
      <option value='2'>Researching</option>
      <option value='14'>Stakeholder and Relationship Focus</option>
      <option value='3'>Creating & Innovating</option>
      <option value='15'>Consulting & Advice</option>
      <option value='4'>Environmental Scanning</option>
      <option value='16'>Decision Making</option>
      <option value='5'>Continuous Improvement & Innovation</option>
      <option value='17'>Managing Change</option>
      <option value='6'>Analysis & Problem Solving</option>
      <option value='18'>Negotiating</option>
      <option value='7'>Manage Self</option>
      <option value='19'>Managing Resources</option>
      <option value='8'>Personal Resilience</option>
      <option value='20'>Policy</option>
      <option value='9'>Achievement Focus</option>
      <option value='21'>Managing Projects</option>
      <option value='10'>Communicating with Influence</option>
      <option value='22'>Applying Technical Expertise</option>
      <option value='11'>Collaboration</option>
      <option value='23'>Managing Knowledge & Information</option>
      <option value='12'>Delivering Results</option>
      <option value='24'>Managing Risk</option>
     </select>
    </td>
    <td width='19%'>
     <select name='s2_capLevelRequired_3'class=' invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='17%'>
     <input name='s2_surrogate_capLevelPossessed_3' value='1' type='hidden'>
     <select name='s2_capLevelPossessed_3'disabled='disabled'class=''>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='18%'>
     <input name='s2_surrogate_developmentRequired_3' value='1' type='hidden'>
     <label>
      <input value='Yes' checked='checked' id='s2_developmentRequiredY_3'
       style='border: medium none ;' disabled='disabled' type='radio'>
      Yes
     </label>
     <label>
      <input value='No' id='s2_developmentRequiredN_3'
       style='border: medium none ;' disabled='disabled' type='radio'>
      No
     </label>
    </td>
   </tr>
   <tr valign='top'>
    <td class='bgColour' width='6%'>4</td>
    <td id='capSelected_1' width='40%'>
     <select name='s2_capNameSelected_4'class=' invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Formulating Strategies & Concepts</option>
      <option value='13'>Leadership</option>
      <option value='2'>Researching</option>
      <option value='14'>Stakeholder and Relationship Focus</option>
      <option value='3'>Creating & Innovating</option>
      <option value='15'>Consulting & Advice</option>
      <option value='4'>Environmental Scanning</option>
      <option value='16'>Decision Making</option>
      <option value='5'>Continuous Improvement & Innovation</option>
      <option value='17'>Managing Change</option>
      <option value='6'>Analysis & Problem Solving</option>
      <option value='18'>Negotiating</option>
      <option value='7'>Manage Self</option>
      <option value='19'>Managing Resources</option>
      <option value='8'>Personal Resilience</option>
      <option value='20'>Policy</option>
      <option value='9'>Achievement Focus</option>
      <option value='21'>Managing Projects</option>
      <option value='10'>Communicating with Influence</option>
      <option value='22'>Applying Technical Expertise</option>
      <option value='11'>Collaboration</option>
      <option value='23'>Managing Knowledge & Information</option>
      <option value='12'>Delivering Results</option>
      <option value='24'>Managing Risk</option>
     </select>
    </td>
    <td width='19%'>
     <select name='s2_capLevelRequired_4'class=' invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='17%'>
     <input name='s2_surrogate_capLevelPossessed_4' value='1' type='hidden'>
     <select name='s2_capLevelPossessed_4'disabled='disabled'class=''>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='18%'>
     <input name='s2_surrogate_developmentRequired_4' value='1' type='hidden'>
     <label>
      <input value='Yes' checked='checked' id='s2_developmentRequiredY_4'
       style='border: medium none ;' disabled='disabled' type='radio'>
      Yes
     </label>
     <label>
      <input value='No' id='s2_developmentRequiredN_4'
       style='border: medium none ;' disabled='disabled' type='radio'>
      No
     </label>
    </td>
   </tr>
   <tr valign='top'>
    <td class='bgColour' width='6%'>5</td>
    <td id='capSelected_1' width='40%'>
     <select name='s2_capNameSelected_5'class=' invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Formulating Strategies & Concepts</option>
      <option value='13'>Leadership</option>
      <option value='2'>Researching</option>
      <option value='14'>Stakeholder and Relationship Focus</option>
      <option value='3'>Creating & Innovating</option>
      <option value='15'>Consulting & Advice</option>
      <option value='4'>Environmental Scanning</option>
      <option value='16'>Decision Making</option>
      <option value='5'>Continuous Improvement & Innovation</option>
      <option value='17'>Managing Change</option>
      <option value='6'>Analysis & Problem Solving</option>
      <option value='18'>Negotiating</option>
      <option value='7'>Manage Self</option>
      <option value='19'>Managing Resources</option>
      <option value='8'>Personal Resilience</option>
      <option value='20'>Policy</option>
      <option value='9'>Achievement Focus</option>
      <option value='21'>Managing Projects</option>
      <option value='10'>Communicating with Influence</option>
      <option value='22'>Applying Technical Expertise</option>
      <option value='11'>Collaboration</option>
      <option value='23'>Managing Knowledge & Information</option>
      <option value='12'>Delivering Results</option>
      <option value='24'>Managing Risk</option>
     </select>
    </td>
    <td width='19%'>
     <select name='s2_capLevelRequired_5'class=' invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='17%'>
     <input name='s2_surrogate_capLevelPossessed_5' value='1' type='hidden'>
     <select name='s2_capLevelPossessed_5'disabled='disabled'class=''>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='18%'>
     <input name='s2_surrogate_developmentRequired_5' value='1' type='hidden'>
     <label>
      <input value='Yes' checked='checked' id='s2_developmentRequiredY_5'
       style='border: medium none ;' disabled='disabled' type='radio'>
      Yes
     </label>
     <label>
      <input value='No' id='s2_developmentRequiredN_5'
       style='border: medium none ;' disabled='disabled' type='radio'>
      No
     </label>
    </td>
   </tr>
   <tr valign='top'>
    <td class='bgColour' width='6%'>6</td>
    <td id='capSelected_1' width='40%'>
     <select name='s2_capNameSelected_6'class=' invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Formulating Strategies & Concepts</option>
      <option value='13'>Leadership</option>
      <option value='2'>Researching</option>
      <option value='14'>Stakeholder and Relationship Focus</option>
      <option value='3'>Creating & Innovating</option>
      <option value='15'>Consulting & Advice</option>
      <option value='4'>Environmental Scanning</option>
      <option value='16'>Decision Making</option>
      <option value='5'>Continuous Improvement & Innovation</option>
      <option value='17'>Managing Change</option>
      <option value='6'>Analysis & Problem Solving</option>
      <option value='18'>Negotiating</option>
      <option value='7'>Manage Self</option>
      <option value='19'>Managing Resources</option>
      <option value='8'>Personal Resilience</option>
      <option value='20'>Policy</option>
      <option value='9'>Achievement Focus</option>
      <option value='21'>Managing Projects</option>
      <option value='10'>Communicating with Influence</option>
      <option value='22'>Applying Technical Expertise</option>
      <option value='11'>Collaboration</option>
      <option value='23'>Managing Knowledge & Information</option>
      <option value='12'>Delivering Results</option>
      <option value='24'>Managing Risk</option>
     </select>
    </td>
    <td width='19%'>
     <select name='s2_capLevelRequired_6'class=' invalidValue'>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='17%'>
     <input name='s2_surrogate_capLevelPossessed_6' value='1' type='hidden'>
     <select name='s2_capLevelPossessed_6'disabled='disabled'class=''>
      <option value='0' selected="selected"></option>
      <option value='1'>Do not possess</option>
      <option value='2'>Basic</option>
      <option value='3'>Contribute</option>
      <option value='4'>Lead</option>
      <option value='5'>Expert</option>
     </select>
    </td>
    <td width='18%'>
     <input name='s2_surrogate_developmentRequired_6' value='1' type='hidden'>
     <label>
      <input value='Yes' checked='checked' id='s2_developmentRequiredY_6'
       style='border: medium none ;' disabled='disabled' type='radio'>
      Yes
     </label>
     <label>
      <input value='No' id='s2_developmentRequiredN_6'
       style='border: medium none ;' disabled='disabled' type='radio'>
      No
     </label>
    </td>
   </tr>
  </tbody>
 </table>
 <br>
 <table class='ppmsTable' border='1' width='100%'>
  <tbody>
   <tr valign='top'>
    <td class='tableData' width='50%'>
     <b>Employee Comments:</b><br>
     <span>
      <textarea name='s2_employeeComments'class='Comments invalidValue'rows='7'cols='50'></textarea>
     </span>
    </td>
    <td class='tableData' width='50%'><b>Manager Comments:</b><br></td>
   </tr>
  </tbody>
 </table>
</div>
<!-- END SECTION 2 -->
<!-- SECTION 3:  Values and Behaviours -->
<span class='psSeparator'>&nbsp;</span>
<table class='noBorder'>
 <tbody>
  <tr valign='top'>
   <td class='performanceStandardPlusMinus'>
    <div id='PSMinus2' style='display: none;'>
     <a href=''>
      <img src='files/open_valuesBeh.gif' alt='Hide Performance Standard 2' border='0'>
     </a>
    </div>
    <div id='PSPlus2' style='display: inline;'>
     <a href=''>
      <img src='files/closed_valuesBeh.gif' alt='Show Performance Standard 2' border='0'>
     </a>
    </div>
   </td>
  </tr>
 </tbody>
</table>
<div id='PerformanceStandard2' style='display: none;'>
 <br>
 <table class='ppmsTable'>
  <tbody>
   <tr class='scores'></tr>
  </tbody>
 </table>
 <table class='ppmsTable' border='1' width='100%'>
  <tbody>
   <tr valign='top'><td class='th1' colspan='3' width='100%'>DPI Values &amp; Behaviours</td></tr>
   <tr valign='top'>
    <td class='th2' colspan='2' width='88%'>
     Select two 'I' Statements to focus on for next performance cycle<br>
     <span style='font-weight: normal; font-size: 11px;'>
      (DPI staff should demonstrate all six Values and Behaviours and all six are weighted
      <em>equally</em>)
     </span>
    </td>
    <td class='th2' width='12%'>Area of Focus</td>
   </tr>
   <tr valign='top'>
    <td class='checkboxRows' width='18%'>
     <b>Be Open</b>
    </td>
    <td class='textBulletPoints ' width='69%'>
     <ul>
      <li class='textBulletPoints'>I am genuine in my dealing with others</li>
      <li class='textBulletPoints'>I openly share knowledge, information and skills</li>
      <li class='textBulletPoints'>I seek and provide feedback constructively</li>
      <li class='textBulletPoints'>I listen to and consider other's point of view</li>
     </ul>
    </td>
    <td class='checkboxRows invalidValue' width='12%'>
     <input type='checkbox' name='s3_iStatement_0-0'class='invalidValue'/>
     <br>
     <input type='checkbox' name='s3_iStatement_0-1'class='invalidValue'/>
     <br>
     <input type='checkbox' name='s3_iStatement_0-2'class='invalidValue'/>
     <br>
     <input type='checkbox' name='s3_iStatement_0-3'class='invalidValue'/>
     <br>
    </td>
   </tr>
   <tr valign='top'>
    <td class='checkboxRows' width='18%'>
     <b>Respect Others</b>
    </td>
    <td class='textBulletPoints ' width='69%'>
     <ul>
      <li class='textBulletPoints'>I seek out and consider different and diverse approaches</li>
      <li class='textBulletPoints'>I act with honesty and integrity</li>
      <li class='textBulletPoints'>I am conscious of how my behaviour impacts others</li>
      <li class='textBulletPoints'>I am mindful of other's feeling and beliefs in my interactions</li>
     </ul>
    </td>
    <td class='checkboxRows invalidValue' width='12%'>
     <input type='checkbox' name='s3_iStatement_1-0'class='invalidValue'/>
     <br>
     <input type='checkbox' name='s3_iStatement_1-1'class='invalidValue'/>
     <br>
     <input type='checkbox' name='s3_iStatement_1-2'class='invalidValue'/>
     <br>
     <input type='checkbox' name='s3_iStatement_1-3'class='invalidValue'/>
     <br>
    </td>
   </tr>
   <tr valign='top'>
    <td class='checkboxRows' width='18%'>
     <b>Working Together</b>
    </td>
    <td class='textBulletPoints ' width='69%'>
     <ul>
      <li class='textBulletPoints'>I foster positive work relationships</li>
      <li class='textBulletPoints'>I actively support my colleagues and managers</li>
      <li class='textBulletPoints'>I contribute and collaborate to identify and achieve common goals</li>
      <li class='textBulletPoints'>I actively promote trust in the workplace</li>
     </ul>
    </td>
    <td class='checkboxRows invalidValue' width='12%'>
     <input type='checkbox' name='s3_iStatement_2-0'class='invalidValue'/>
     <br>
     <input type='checkbox' name='s3_iStatement_2-1'class='invalidValue'/>
     <br>
     <input type='checkbox' name='s3_iStatement_2-2'class='invalidValue'/>
     <br>
     <input type='checkbox' name='s3_iStatement_2-3'class='invalidValue'/>
     <br>
    </td>
   </tr>
   <tr valign='top'>
    <td class='checkboxRows' width='18%'>
     <b>Make a difference</b>
    </td>
    <td class='textBulletPoints ' width='69%'>
     <ul>
      <li class='textBulletPoints'>I am solution-focussed in my work and approach challenges with a positive outlook</li>
      <li class='textBulletPoints'>I have a strong sense of personal accountability in my workplace</li>
      <li class='textBulletPoints'>I strive to continually learn, improve and innovate</li>
      <li class='textBulletPoints'>I endeavour to use my skills and abilities to deliver the best outcome</li>
     </ul>
    </td>
    <td class='checkboxRows invalidValue' width='12%'>
     <input type='checkbox' name='s3_iStatement_3-0'class='invalidValue'/>
     <br>
     <input type='checkbox' name='s3_iStatement_3-1'class='invalidValue'/>
     <br>
     <input type='checkbox' name='s3_iStatement_3-2'class='invalidValue'/>
     <br>
     <input type='checkbox' name='s3_iStatement_3-3'class='invalidValue'/>
     <br>
    </td>
   </tr>
   <tr valign='top'>
    <td class='checkboxRows' width='18%'>
     <b>Lead with Purpose</b>
    </td>
    <td class='textBulletPoints ' width='69%'>
     <ul>
      <li class='textBulletPoints'>I help others to connect their work to the DPI Strategic Plan</li>
      <li class='textBulletPoints'>I am accountable for my actions and decisions and help empower others to be responsible and accountable for theirs</li>
      <li class='textBulletPoints'>I am consistent and fair in my decision making</li>
      <li class='textBulletPoints'>I actively seek opportunities to develop my self and others</li>
     </ul>
    </td>
    <td class='checkboxRows invalidValue' width='12%'>
     <input type='checkbox' name='s3_iStatement_4-0'class='invalidValue'/>
     <br>
     <input type='checkbox' name='s3_iStatement_4-1'class='invalidValue'/>
     <br>
     <input type='checkbox' name='s3_iStatement_4-2'class='invalidValue'/>
     <br>
     <input type='checkbox' name='s3_iStatement_4-3'class='invalidValue'/>
     <br>
    </td>
   </tr>
   <tr valign='top'>
    <td class='checkboxRows' width='18%'>
     <b>Focus on Safety</b>
    </td>
    <td class='textBulletPoints ' width='69%'>
     <ul>
      <li class='textBulletPoints'>I work safely and, if I am unsure, I ask</li>
      <li class='textBulletPoints'>I actively manage hazards and risks to my self and others</li>
      <li class='textBulletPoints'>I care for my health and wellbeing and that of my colleagues</li>
      <li class='textBulletPoints'>I consider the OHS risks in every decision I make</li>
     </ul>
    </td>
    <td class='checkboxRows invalidValue' width='12%'>
     <input type='checkbox' name='s3_iStatement_5-0'class='invalidValue'/>
     <br>
     <input type='checkbox' name='s3_iStatement_5-1'class='invalidValue'/>
     <br>
     <input type='checkbox' name='s3_iStatement_5-2'class='invalidValue'/>
     <br>
     <input type='checkbox' name='s3_iStatement_5-3'class='invalidValue'/>
     <br>
    </td>
   </tr>
  </tbody>
 </table>
</div>
<div id='justification' style='display: none;'>
 <br>
 <table class='ppmsTable' border='1' width='100%'>
  <tbody>
   <tr valign='top'>
    <td class='bgColour' width='100%'>
     <b>
      For the employee - Please explain how you have demonstrated the organisational Values and
      Behaviours to an exceptional standard
     </b>
    </td>
   </tr>
   <tr valign='top'>
    <td width='100%'><br>
     <span>
      <textarea name='s3_demonstrationExplanation'class='Comments invalidValue'rows='7'cols='50'></textarea>
     </span>
    </td>
   </tr>
  </tbody>
 </table>
 <br>
 <table class='ppmsTable' border='1' width='100%'>
  <tbody>
   <tr valign='top'>
    <td class='bgColour' width='100%'>
     <b>
      For the manager - Please explain how the employee has demonstrated the
      organisational Values and Behaviours to an exceptional standard
     </b>
    </td>
   </tr>
   <tr valign='top'><td width='100%'></td></tr>
  </tbody>
 </table>
 <br>
 <table class='ppmsTable' border='1' width='100%'>
  <tbody>
   <tr valign='top'>
    <td class='tableData' width='50%'><b>Employee Comments:</b><br>
     <span>
      <textarea name='s3_employeeComments'class='Comments invalidValue'rows='7'cols='50'></textarea>
     </span>
    </td>
    <td class='tableData' width='50%'><b>Manager Comments:</b><br></td>
   </tr>
  </tbody>
 </table>
</div>
<!-- END SECTION 3 -->
<!-- SECTION 4:  Career Planning -->
<span class="psSeparator">&nbsp;</span>
<table class="noBorder">
 <tbody>
  <tr valign="top">
   <td class="performanceStandardPlusMinus">
    <div id="PSMinusCareerPlanning" style="display: none;">
     <a href=""><img src="files/open_careerPlan.gif" alt="Hide Career Planning" border="0"></a>
    </div>
    <div id="PSPlusCareerPlanning" style="display: inline;">
     <a href=""><img src="files/closed_careerPlan.gif" alt="Show Career Planning" border="0"></a>
    </div>
   </td>
  </tr>
 </tbody>
</table>
<div id="PerformanceStandardCareerPlanning" style="display: none;">
 <br>
 <table class="ppmsTable" border="1" width="100%">
  <tbody>
   <tr valign="top">
    <td class="tableData" style="border: medium none ;" width="100%">
     <b>
      The aim of the career planning conversation is to gain a shared
      understanding of your career goals and aspirations and to assist in
      identifying both personal and professional development opportunities
      that will enable career growth to occur. The questions below are only a
      suggested guide to the conversation.
     </b>
     <br>
    </td>
   </tr>
   <tr valign="top">
    <td style="border: medium none ; font-size: 9px;" font-size:9px;="" width="100%">
     <span style="font-size: 11px;">
      **Please Note: This section is not assessable and does not impact on progression.
     </span>
    </td>
   </tr>
  </tbody>
 </table>
<table class="ppmsTable" border="1" width="100%">
 <tbody>
  <tr valign="top">
   <td class="bgColour" width="30%">
    How do you think your current role fits in with your career goals?
   </td>
   <td class="tableData" width="70%">
      <textarea name='s4_howCurrentRoleFits'class='Comments invalidValue'rows='7'cols='50'></textarea>
   </td>
  </tr>
  <tr valign="top">
   <td class="bgColour" width="30%">What other work or roles are you interested in at DPI?</td>
   <td class="tableData" width="70%">
      <textarea name='s4_whatOtherRolesInterestedIn'class='Comments invalidValue'rows='7'cols='50'></textarea>
   </td>
  </tr>
  <tr valign="top">
   <td class="bgColour" width="30%">What support do you need to reach your career aspirations?</td>
   <td class="tableData" width="70%">
      <textarea name='s4_supportNeeded'class='Comments invalidValue'rows='7'cols='50'></textarea>
   </td>
  </tr>
  <tr valign="top">
   <td class="bgColour" width="30%">
    What factors influence when and what your next career move might be?
   </td>
   <td class="tableData" width="70%">
      <textarea name='s4_influencingFactors'class='Comments invalidValue'rows='7'cols='50'></textarea>
   </td>
  </tr>
 </tbody>
</table>
<br>
<table class="ppmsTable" border="1" width="100%">
 <tbody>
  <tr valign="top">
   <td class="tableData" width="50%"><b>Employee Comments:</b><br>
    <span>
      <textarea name='s4_employeeComments'class='Comments invalidValue'rows='7'cols='50'></textarea>
    </span>
   </td>
   <td class="tableData" width="50%"><b>Manager Comments:</b><br></td>
  </tr>
 </tbody>
</table>
</div>
<!-- END SECTION 4 -->
<!-- SECTION 5:  Learning and Development Plan -->
<span class="psSeparator">&nbsp;</span>
<a name="ps3"></a>
<table class="noBorder">
 <tbody>
  <tr valign="top">
   <td class="performanceStandardPlusMinus">
    <div id="PSMinus3" style="display: none;">
     <a href=""><img src="files/open_devPlan.gif" alt="Hide Performance Standard 3" border="0"></a>
    </div>
    <div id="PSPlus3" style="display: inline;">
     <a href="">
      <img src="files/closed_devPlan.gif" alt="Show Performance Standard 3" border="0">
     </a>
    </div>
   </td>
  </tr>
 </tbody>
</table>
<div id="PerformanceStandard3" style="display: none;">
 <br>
 <table class="ppmsTable"><tbody><tr class="scores"></tr></tbody></table>
 <table class="ppmsTable" border="1" width="100%">
  <tbody>
   <tr valign="top">
    <td class="th1" width="45%">Development Focus</td>
    <td class="th1" width="55%">
     Development Activity<br>
     <span style="text-transform: none; font-size: 11px;">(Expectation Measures)</span>
    </td>
   </tr>
   <tr valign="top">
    <td class="bgColour" colspan="2" width="100%">
     <b>Performance goals development (PS1)</b>
    </td>
   </tr>
   <tr valign="top">
    <td class="tableData" width="45%">
      <textarea name='s5_performanceGoalsDevelopment'class='Comments invalidValue'rows='7'cols='50'></textarea>
    </td>
    <td class="tableData" width="55%">
      <textarea name='s5_performanceGoalsDevelopment_M'class='Comments invalidValue'rows='7'cols='50'></textarea>
    </td>
   </tr>
   <tr valign="top"><td class="bgColour" colspan="2" width="100%"><b>Capability development</b></td></tr>
   <tr valign="top">
    <td class="tableData" width="45%">
     <span>
      <textarea name='s5_capabilityDevelopment'class='Comments invalidValue'rows='7'cols='50'></textarea>
     </span>
    </td>
    <td class="tableData" width="55%">
      <textarea name='s5_capabilityDevelopment_M'class='Comments invalidValue'rows='7'cols='50'></textarea>
    </td>
   </tr>
   <tr valign="top">
    <td class="bgColour" colspan="2" width="100%">
     <b>Values &amp; Behaviours development ('I' Statements) (PS2)</b>
    </td>
   </tr>
   <tr valign="top">
    <td class="tableData" width="45%">
     <span>
      <textarea name='s5_behavioursDevelopment'class='Comments invalidValue'rows='7'cols='50'></textarea>
     </span>
    </td>
    <td class="tableData" width="55%">
      <textarea name='s5_behavioursDevelopment_M'class='Comments invalidValue'rows='7'cols='50'></textarea>
    </td>
   </tr>
   <tr valign="top">
    <td class="bgColour" colspan="2" width="100%">
     <b>Career planning development</b>(This is optional and not assessable)
    </td>
   </tr>
   <tr valign="top">
    <td class="tableData" width="45%">
      <textarea name='s5_careerPlanningDevelopment'class='Comments invalidValue'rows='7'cols='50'></textarea>
    </td>
    <td class="tableData" width="55%">
      <textarea name='s5_careerPlanningDevelopment_M'class='Comments invalidValue'rows='7'cols='50'></textarea>
    </td>
   </tr>
  </tbody>
 </table>
 <br>
 <table class="ppmsTable" border="1" width="100%">
  <tbody>
   <tr valign="top">
    <td class="tableData" width="50%">
     <b>Employee Comments:</b><br>
     <span>
      <textarea name='s5_employeeComments'class='Comments invalidValue'rows='7'cols='50'></textarea>
     </span>
    </td>
    <td class="tableData" width="50%"><b>Manager Comments:</b><br></td>
   </tr>
  </tbody>
 </table>
</div>
<!-- END SECTION 5 -->
<!-- SECTION 5:  General Comments -->
<span class="psSeparator">&nbsp;</span>
<table class="noBorder">
 <tbody>
  <tr valign="top">
   <td class="performanceStandardPlusMinus">
    <div id="PSMinusGeneralComments" style="display: none;">
     <a href="">
      <img src="files/open_genComments.gif" alt="Hide General Comments" border="0">
     </a>
    </div>
    <div id="PSPlusGeneralComments" style="display: inline;">
     <a href="">
      <img src="files/closed_genComments.gif" alt="Show General Comments" border="0">
     </a>
    </div>
   </td>
  </tr>
 </tbody>
</table>
<div id="PerformanceStandardGeneralComments" style="display: none;">
 <br>
 <table class="ppmsTable">
  <tbody>
   <tr class="scores"></tr>
  </tbody>
 </table>
 <table class="ppmsTable" border="1" width="100%">
  <tbody>
   <tr valign="top"><td class="bgColour" width="100%"><b>Employee Comments:</b></td></tr>
   <tr valign="top">
    <td class="tableData" width="100%"><br>
     <span>
      <textarea name='s6_employeeComments'class='Comments invalidValue'rows='7'cols='50'></textarea>
     </span>
    </td>
   </tr>
  </tbody>
 </table>
 <br>
 <table class="ppmsTable" border="1" width="100%">
  <tbody>
   <tr valign="top"><td class="bgColour" width="100%"><b>Manager Comments:</b></td></tr>
   <tr valign="top"><td class="tableData" width="100%"></td></tr>
  </tbody>
 </table>
</div>
<!-- END SECTION 6 -->
      <!-- SECTION 7:  Revision History -->
      <span class="psSeparator">&nbsp;</span>
      <table class="noBorder">
         <tbody>
            <tr valign="top">
               <td class="performanceStandardPlusMinus">
                  <div id="PSMinusRevisionHistory" style="display: none;">
                     <a href=""><img src="files/open_revHistory.gif" alt="Hide Revision History" border="0"></a>
                  </div>
                  <div id="PSPlusRevisionHistory" style="display: inline;">
                     <a href=""><img src="files/closed_revHistory.gif" alt="Show Revision History" border="0"></a>
                  </div>
               </td>
            </tr>
         </tbody>
      </table>
      <div id="PerformanceStandardRevisionHistory" style="display: none;">
         <table class="revisionHistoryTable">
            <thead>
               <tr>
                  <th>Person</th>
                  <th>Action</th>
                  <th>Date</th>
               </tr>
            </thead>
            <tbody>
               </tbody>
   </table>
</div>
<!-- END SECTION 7 -->
               </td>
            </tr>
         </tbody>
      </table>
   </form>
<!-- END MAIN SECTION -->
  </div>
  <div id='layoutClearFloats' style='clear: both;'></div>
  <div id='layoutFooter' style='height: 0px; background-color: yellow;'></div>
 </body>
</html>
