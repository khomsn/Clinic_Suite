<!--

function checkThemAll(chk){

var formName = "formMultipleCheckBox";

var checkBoxName="checkBoxes";

var form = document.forms[formName];

var noOfCheckBoxes = form[checkBoxName].length;

for(var x=0;x<noOfCheckBoxes;x++){

if(chk.checked==true){

if(form[checkBoxName][x].checked==false){

form[checkBoxName][x].checked=true;

}

}else{

if(form[checkBoxName][x].checked==true){

form[checkBoxName][x].checked=false;

}

}

}

}

-->
