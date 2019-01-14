<SCRIPT TYPE="text/javascript">
function popup(mylink, windowname,w,h,scroll)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
  href=mylink;
else
  href=mylink.href;

  LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
  TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
  settings ='height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable,toolbar=no,menubar=no';
window.open(href, windowname, settings);
return false;
}
</SCRIPT>
