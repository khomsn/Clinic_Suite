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

<td>

<input name="check1" type="checkbox" id="checkBoxes" value="1" />
<input name="check2" type="checkbox" id="checkBoxes" value="1" />
<input name="check3" type="checkbox" id="checkBoxes" value="1" />
<input name="check4" type="checkbox" id="checkBoxes" value="1" />
<input name="check5" type="checkbox" id="checkBoxes" value="1" />
<input name="check6" type="checkbox" id="checkBoxes" value="1" />
</td>

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