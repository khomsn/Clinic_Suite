<html>

<head><title>How to automatically check multiple check boxes using JavaScript </title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<script language="JavaScript" type="text/javascript" src="../js/jquery-1.3.2.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../js/jquery.validate.js"></script>
	<script language="JavaScript" type="text/javascript" src="../js/checkthemall.js"></script>
</head>
<body>
<form id="formMultipleCheckBox" name="formMultipleCheckBox"> 
<table border="0">

<tr>

<td colspan="9">Select at least one programming language </td>

</tr>

<tr>

<td><label>

<input name="check" type="checkbox" id="checkBoxes" value="1" />
0 n mkhy fg

Java</label></td>

<td><label>

<input name="checkBoxes" type="checkbox" id="checkBoxes" value="12" />

C++</label></td>

<td>&nbsp;</td>

<td><label>

<input name="checkBoxesProgramming" type="checkbox" id="checkBoxesProgrammingLanguages[]" value="3" />

Python</label></td>

<td>&nbsp;</td>

<td><label>

<input name="checkBoxesProgrammingLanguages[]" type="checkbox" id="checkBoxesProgrammingLanguages[]" value="4" />

Perl</label></td>

<td>&nbsp;</td>

<td>&nbsp;</td>

<td rowspan="6">&nbsp;</td>

</tr>

<tr>

<td><label>

<input name="checkBoxesProgrammingLanguages[]" type="checkbox" id="checkBoxesProgrammingLanguages[]" value="5" />

PHP </label></td>

<td><label>

<input name="checkBoxesProgrammingLanguages[]" type="checkbox" id="checkBoxesProgrammingLanguages[]" value="6" />

VB.net</label></td>

<td>&nbsp;</td>

<td><label>

<input name="checkBoxesProgrammingLanguages[]" type="checkbox" id="checkBoxesProgrammingLanguages[]" value="7" />

C#</label></td>

<td>&nbsp;</td>

<td><label>

<input name="checkBoxesProgrammingLanguages[]" type="checkbox" id="checkBoxesProgrammingLanguages[]" value="8" />

Ruby</label></td>

<td>&nbsp;</td>

<td>&nbsp;</td>

</tr>

<tr>

<td colspan="2" align="left" id="display_status">&nbsp;</td>

<td align="left"><label></label></td>

<td colspan="2" align="center">&nbsp;</td>

<td align="center">&nbsp;</td>

<td>&nbsp;</td>

<td>&nbsp;</td>

</tr>

<tr>

<td colspan="5" align="left" id="display_status">Check/Uncheck using checkbox </td>

<td>&nbsp;<input name="checkAll" type="checkbox" id="checkAll" value="1" onclick="javascript:checkThemAll(this);" />

Check All </td>

<td>&nbsp;</td>

<td>&nbsp;</td>

</tr></table></form>
</body>

</html>