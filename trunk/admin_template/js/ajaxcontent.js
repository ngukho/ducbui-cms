

/*********************************** Use for popup div ****************************************/


/*********************************************** Class Ajax ***************************************************/    
function AjaxContent()
{
	var xmlhttp, bComplete = false;
	try { xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); }
	catch (e) { try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
	catch (e) { try { xmlhttp = new XMLHttpRequest(); }
	catch (e) { xmlhttp = false; }}}
	if (!xmlhttp) return null;
	this.connect = function(sURL, sMethod, sVars, fnDone)
	{
        if (!xmlhttp) return false;
        bComplete = false;
        sMethod = sMethod.toUpperCase();
        sMethod = '' + sMethod;
        if (sMethod == '' || sMethod == 'undefined')
            sMethod = "GET";
        try {
        var url = sURL;
          if (sMethod == "GET")
          {
	        if ( url.indexOf("?") == -1)
	          url = url+"?sid="+Math.random();
	        else
	        	url = url+"&sid="+Math.random();
	        	
	        url = url+"&"+sVars;
          
			xmlhttp.open(sMethod, url, true);
			sVars = "";
          }
          else
          {
            xmlhttp.open(sMethod, sURL, true);
            xmlhttp.setRequestHeader("Method", "POST "+sURL+" HTTP/1.1");
            xmlhttp.setRequestHeader("Content-Type",
              "application/x-www-form-urlencoded");
          }
          
          xmlhttp.onreadystatechange = function(){
            if (xmlhttp.readyState == 4 && !bComplete)
            {
              bComplete = true;
              //alert(xmlhttp.responseText)
              fnDone(xmlhttp);
              
            }else{
              //document.getElementById(divID).innerHTML = '<center><font size=2>Loading ...</font></center>';
            }
          };
          xmlhttp.send(sVars);
        }
        catch(z) { return false; }
        return true;
      };
	return this;
}

/*
 * sMethod: GET or POST
 * sVars these parameter send to your browser
 * ex: username=abc&password=1
 * this function define in your phtml
 * use: 
 * var ajaxConn = new AjaxContent();
 * ajaxConn.connect(sURL, sMethod, sVars, functionWhenDone)
 */

/*
function functionWhenDone(xmlhttp) {
	//alert(xmlhttp.responseText);
}
*/
