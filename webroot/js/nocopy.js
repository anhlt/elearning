function killCopy(e){
	return false
}
function reEnable(){
	return true
}
document.onselectstart=new Function ("return false")
if (window.sidebar){
	document.onmousedown=killCopy
	document.onclick=reEnable
}

function ehan( evnt )
{
  if( evnt.which == 3 )
  {
    alert( "Không được Copy" );
    return false;
  }
  return true;
}
function ocmh()
{
  alert( "Không được Copy" );
  return false;
}
document.oncontextmenu = ocmh;
document.captureEvents( Event.MOUSEDOWN );
if( document.layers ) document.onmousedown = ehan;
