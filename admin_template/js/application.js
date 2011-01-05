/*
	Su dung cho chon nhieu doi tuong checkbox
	Ham doCheckAll(frmObj) -> Tham so la doi tuong form 
	Ham doCheckOne(frmObj) -> Tham so la doi tuong form 
	Doi tuong checkbox All phai co id = "checkboxAll"
	Doi tuong cac checkbox con lai co id = "checkbox"
*/
function doCheckAll(frmObj)
{
	var isChecked = frmObj.checkboxAll.checked;
	var alen = frmObj.elements.length;
	if(alen > 0)
	{
		for(var i=0;i<alen;i++)
		{
			if(frmObj.elements[i].type == "checkbox")
				if(frmObj.elements[i].id == "checkboxAll" || frmObj.elements[i].id == "checkbox")
					frmObj.elements[i].checked = isChecked
		}
	}
}

function doCheckOne(frmObj)
{
	var alen = frmObj.elements.length;
	var isChecked = true;
	if(alen > 0)
	{
		for(var i=0;i<alen;i++)
		{
			if(frmObj.elements[i].type == "checkbox")
				if(frmObj.elements[i].id != "checkboxAll")
					if(frmObj.elements[i].id == "checkbox")
						if(frmObj.elements[i].checked == false)
							isChecked = false;
		}
	}
	frmObj.checkboxAll.checked = isChecked;
}

// Thuc hien cac chuc nang mutil

function cmsCheckboxChecked(frmObj,nameObj)
{
	for(var i=0;i<frmObj.length;i++)
	{
		if(frmObj[i].type == "checkbox" && frmObj[i].name == nameObj);
			if(frmObj[i].checked == true)
				return true;
	}
	return false;
	
}

// Hien thong bao khi muon delete 1 muc
function deleteRow(Url,strNote)
{
	var url = Url;
		
	if (confirm(strNote)) {
   		document.location = url 
	}
}

function isChecked(frmObj)
{
	for(var i=0;i<frmObj.length;i++)
	{
		if(frmObj[i].type == "checkbox" && frmObj[i].name != "checkboxAll" && frmObj[i].id == "checkbox")
			if(frmObj[i].checked == true)
				return true;
	}
	return false;
	
}


/*function CheckForm(objForm,actionButton)
{
	if(actionButton == "cmdExecute")
	{
		if(CheckFormMultiExecute(objForm) == false) return;
		objForm.Action = "<?= $this->baseUrl ?>/member/index/editForm";
		
	}
	else if(actionButton == "cmdSearch")
	{
		if(CheckFormSearch(objForm) == false) return;
		objForm.Action = "<?= $this->baseUrl ?>/member";
	}
	objForm.submit();
}

function CheckFormSearch(objForm)
{
	return true;
}

function CheckFormMultiExecute(objForm)
{
	var action = $("#action").val();
	var arrInput = $("input");
	var result = true;
	switch(action)
	{
		case "none":
			alert("<?= $this->translate->_('member_Notify_Choose_Action') ?>");
    		result = false;
			break;			
		case "inactive":
		case "active":
			result = isChecked(arrInput);
			if(result == false)
				alert("<?= $this->translate->_('member_Notify_Choose_Checkbox') ?>");
			break;
		case "delete":
			result = isChecked(arrInput);
			if(result == false)
				alert("<?= $this->translate->_('member_Notify_Choose_Checkbox') ?>");					
			else				
				result = confirm("<?= $this->translate->_('member_Notify_Comfirm_Delete') ?>") ? true : false ; 
			break;
	}
	return result;	
}

function isChecked(frmObj)
{
	for(var i=0;i<frmObj.length;i++)
	{
		if(frmObj[i].type == "checkbox" && frmObj[i].name != "checkboxAll");
			if(frmObj[i].checked == true)
				return true;
	}
	return false;
	
}*/

//===== Ho ten ===========
function CheckUnicodeCharLastFirstnameRegister(strName)
{
	if ( strName.indexOf("<") != '-1' || strName.indexOf(">") != '-1' || !CheckChar( strName ) )
	{
		return false;
	}	
	return true;
}
//===== Ho ten ===========

//===== Pass ===========
function CheckUnicodeCharPassRegister(strpass)
{
	if ( strpass.indexOf("<") != '-1' || strpass.indexOf(">") != '-1' || !Checkchar1( strpass ))
	{
		return false;
	}	
	return true;
}
//===== Pass ===========

//kiem tra unicode cho password
function Checkchar1(stringIn)
{
	retval = false
    var i;
    for (i=0;i<=stringIn.length-1;i++) 
	{
    	if (((stringIn.charCodeAt(i) >= 8)&&(stringIn.charCodeAt(i) <= 127)) && (stringIn.charCodeAt(i)!=34) && (stringIn.charCodeAt(i)!=39) && (stringIn.charCodeAt(i)!=32))
		{
        	retval = true;
        }
		else
		{
			retval = false;
			break;
		}
	}
    return retval;
}

//kiem tra cac ky tu dat biet @,<,>,!,$,%,(,),=,#,{,},[,],",^,~,`,,/,\,|,*,.,+,: cho fullname
function CheckChar(stringIn) 
{
	if ((stringIn.indexOf("@") >= 0)||(stringIn.indexOf("<") >= 0)||(stringIn.indexOf(">") >= 0)||(stringIn.indexOf("!") >= 0)||(stringIn.indexOf("$") >= 0)||(stringIn.indexOf("%") >= 0)||(stringIn.indexOf("(") >= 0)||(stringIn.indexOf(")") >= 0)||(stringIn.indexOf("=") >= 0)||(stringIn.indexOf("#") >= 0)||(stringIn.indexOf("{") >= 0)||(stringIn.indexOf("}") >= 0)||(stringIn.indexOf("[") >= 0)||(stringIn.indexOf("]") >= 0)||(stringIn.indexOf("|") >= 0)||(stringIn.indexOf('"') >= 0) ||(stringIn.indexOf(".") >= 0) ||(stringIn.indexOf(";") >= 0) ||(stringIn.indexOf("?") >= 0) ||(stringIn.indexOf(",") >= 0) ||(stringIn.indexOf("+") >= 0) ||(stringIn.indexOf("&") >= 0) ||(stringIn.indexOf(":") >= 0) ||(stringIn.indexOf("\\") >= 0) ||(stringIn.indexOf("/") >= 0) ||(stringIn.indexOf("*") >= 0) ||(stringIn.indexOf("`") >= 0) ||(stringIn.indexOf("~") >= 0) ||(stringIn.indexOf("^") >= 0) ||(stringIn.indexOf("-") >= 0)||(stringIn.indexOf("_") >= 0))
	{
		return false;
	}
	return true;
}

function ShowPermissionForm()
{
	//Hide (Collapse) the toggle containers on load
	$(".toggle_container").hide(); 

	//Switch the "Open" and "Close" state per click
	$("a.trigger").toggle(function(){
		$(this).addClass("active");
		}, function () {
		$(this).removeClass("active");
	});

	//Slide up and down on click
	$("a.trigger").click(function(){
		$(this).next(".toggle_container").slideToggle("fast");
	});
		
	//Show default
	$('a.trigger.default:first').trigger('click');		
}
/*******************************************************************************
  CREATE DROP DOWN MENU
*******************************************************************************/
var menu=function(){
	var t=15,z=50,s=3,a;
	function dd(n){this.n=n; this.h=[]; this.c=[]}
	dd.prototype.init=function(p,c){
		a=c; var w=document.getElementById(p), s=w.getElementsByTagName('ul'), l=s.length, i=0;
		for(i;i<l;i++){
			var h=s[i].parentNode; this.h[i]=h; this.c[i]=s[i];
			h.onmouseover=new Function(this.n+'.st('+i+',true)');
			h.onmouseout=new Function(this.n+'.st('+i+')');
		}
	}
	dd.prototype.st=function(x,f){
		var c=this.c[x], h=this.h[x], p=h.getElementsByTagName('a')[0];
		clearInterval(c.t); c.style.overflow='hidden';
		if(f){
			p.className+=' '+a;
			if(!c.mh){c.style.display='block'; c.style.height=''; c.mh=c.offsetHeight; c.style.height=0}
			if(c.mh==c.offsetHeight){c.style.overflow='visible'}
			else{c.style.zIndex=z; z++; c.t=setInterval(function(){sl(c,1)},t)}
		}else{p.className=p.className.replace(a,''); c.t=setInterval(function(){sl(c,-1)},t)}
	}
	function sl(c,f){
		var h=c.offsetHeight;
		if((h<=0&&f!=1)||(h>=c.mh&&f==1)){
			if(f==1){c.style.filter=''; c.style.opacity=1; c.style.overflow='visible'}
			clearInterval(c.t); return
		}
		var d=(f==1)?Math.ceil((c.mh-h)/s):Math.ceil(h/s), o=h/c.mh;
		c.style.opacity=o; c.style.filter='alpha(opacity='+(o*100)+')';
		c.style.height=h+(d*f)+'px'
	}
	return{dd:dd}
}();


/*******************************************************************************/

/*******************************************************************************
  	Vertigo Tip by www.vertigo-project.com
	Requires jQuery
*******************************************************************************/
this.vtip = function() {    
    this.xOffset = -10; // x distance from mouse
    this.yOffset = 27; // y distance from mouse       
    
    $(".vtip").unbind().hover(    
        function(e) {
            this.t = this.title;
            this.title = ''; 
            this.top = (e.pageY + yOffset); this.left = (e.pageX + xOffset);
            
            $('body').append( '<p id="vtip"><img id="vtipArrow" />' + this.t + '</p>' );
                        
            $('p#vtip #vtipArrow').attr("src", 'images/vtip_arrow.png');
            $('p#vtip').css("top", this.top+"px").css("left", this.left+"px").fadeIn("fast");
            
        },
        function() {
            this.title = this.t;
            $("p#vtip").fadeOut("fast").remove();
        }
    ).mousemove(
        function(e) {
            this.top = (e.pageY + yOffset);
            this.left = (e.pageX + xOffset);
                         
            $("p#vtip").css("top", this.top+"px").css("left", this.left+"px");
        }
    );            
    
};

jQuery(document).ready(function($){vtip();}) 
/*******************************************************************************/