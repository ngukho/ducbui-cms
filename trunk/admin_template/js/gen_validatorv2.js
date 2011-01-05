/*
    ast edited by Duc Bui
    Date: 02/08/2007
*/

/*
	isEmpty = function(string)

	isOnlyAlphaNumeric = function(string)
	isOnlyAlphaNumericNoSpace = function(string)
	isOnlyAlphabetic = function(string)
	isValidPasswordCharacters = function(password)
	isOnlyNumeric = function(string)
	isValidInteger = function(string)
	isValidFloatingPoint = function(string)
	isValidAge = function(age)
	isValidTelephoneNum = function(telephoneNum)
	isValidPostalCode = function(postalCode)
	isValidEmail = function(email)
	isValidDate = function(day, month, year)
	isValidDateOfBirth = function(day, month, year)
	isValidCreditCardExpiry = function(expiresMonth, expiresYear)
	isValidCreditCardNumber = function(cardNumber, cardType)

	checkFormValid = function(theForm, theDocument)
	isTextElementValid = function(theElement, validDataTypeRegExp, isCompulsoryElement)
	isElementDataValid = function(elementValue, validDataType)
	isOneRadioButtonInGroupSelected = function(theElement)
	
	showErrorDiv = function (errorDescripDivId, theDocument)
	hideErrorDiv = function (errorDescripDivId, theDocument)

*/

function Validate() {}

Validate.prototype.isUserNameFormat = function(string)
{
  var invalidCharactersRegExp = /[^a-z\d_]/i;
  var isValid = !(invalidCharactersRegExp.test(string));
  return isValid;
}

Validate.prototype.isEmpty = function(string)
{
	var isValid = (string.length == 0 ? true : false);
	return isValid;
}

Validate.prototype.isPrivateNameFormat = function(string)
{
	if(this.isEmpty(string)) return false;
	if(!this.isOnlyAlphaNumeric(string)) return false;
	return true;
}

Validate.prototype.isValidFaxNum = function(faxNum)
{
	/*
		The format is: 00 <Country-Code> <AreaCode> <Local number>. 
			Example: A number in New York, NY, USA will look like: 0012123456789 , where: 
				00 is a constant; 
				1 is the USA country code; 
				212 is New York area code; 
				3456789 is the local number		
	*/			
	if(faxNum.substring(0,2) != "00") return false;
	if(this.isValidTelephoneNum(faxNum) && ((faxNum.length >= 11 && faxNum.length <= 15)))
  {
		return true;
  }
  return false;

}

Validate.prototype.isNameFormat = function(string)
{
  var invalidCharactersRegExp = /[^a-z\d._-]/i;
  var isValid = !(invalidCharactersRegExp.test(string));
  return isValid;
}

Validate.prototype.isOnlyAlphaNumeric = function(string)
{
  var invalidCharactersRegExp = /[^a-z\d ]/i;
  var isValid = !(invalidCharactersRegExp.test(string));
  return isValid;
}

Validate.prototype.isOnlyAlphaNumericNoSpace = function(string)
{
  var invalidCharactersRegExp = /[^a-z\d]/i;
  var isValid = !(invalidCharactersRegExp.test(string));

  return isValid;
}

Validate.prototype.isOnlyAlphabetic = function(string)
{
  invalidCharactersRegExp = /[^a-z ]/i;
  var isValid = !(invalidCharactersRegExp.test(string));

  return isValid;
}

Validate.prototype.isValidPasswordCharacters = function(password)
{
//  return !this.isEmpty(password);        
  var invalidCharactersRegExp = /[^a-z\d_!@#$%&*]/i;
  var isValid = !(invalidCharactersRegExp.test(password));
  if (isValid)
  {
    isValid = (password.length >= 6 && password.length <= 16);
  }

  return isValid;
}

Validate.prototype.isOnlyNumeric = function(string)
{
  var validFormatRegExp = /^\d*(\.\d+)?$/;
  var isValid = validFormatRegExp.test(string);

  return isValid;
}

Validate.prototype.isValidInteger = function(string)
{
  var validFormatRegExp = /^((\+|-)\d)?\d*$/;
  var isValid = validFormatRegExp.test(string);

  return isValid;
}

Validate.prototype.isValidFloatingPoint = function(string)
{
  var validFormatRegExp = /^((\+|-)\d)?\d*(\.\d+)?$/;
  var isValid = validFormatRegExp.test(string);

  return isValid;
}

Validate.prototype.isValidAge = function(age)
{
  var isValid = false;
  if (this.isInteger(age))
  {
    isValid = (parseInt(age) >= 0 && parseInt(age) < 140)
  }

  return isValid;
}

Validate.prototype.isValidTelephoneNum = function(telephoneNum)
{
  var validFormatRegExp = /^(\+\d{1,3} ?)?(\(\d{1,5}\)|\d{1,5}) ?\d{3} ?\d{0,7}( ?(x|xtn|ext|extn|extension)?\.? ?\d{1,5})?$/i
  var isValid = validFormatRegExp.test(telephoneNum);

  return isValid;
}

Validate.prototype.isValidPostalCode = function(postalCode)
{
  var validFormat = /^(\d{5}(-\d{4})?|[a-z][a-z]?\d\d? ?\d[a-z][a-z])$/i
  var isValid = validFormat.test(postalCode);
  return isValid;
}

Validate.prototype.isValidEmail = function(email)
{
  var validFormatRegExp = 
    /^\w(\.?\w)*@\w(\.?[-\w])*\.[a-z]{2,4}$/i;
  var isValid = validFormatRegExp.test(email);

  return isValid;
}

Validate.prototype.isValidDate = function(day, month, year)
{
  var isValid = true;

  var enteredDate = new Date(day + " " + month + " " + year);
  if (enteredDate.getDate() != day)
  {
    isValid = false;
  }

  return isValid;
}

Validate.prototype.isValidDateOfBirth = function(day, month, year)
{
  var isValid = true;
  var nowDate = new Date();
  year = parseInt(year);
  var dateOfBirth = new Date(day + " " + month + " " + year);

  if (!this.isValidDate(day,month,year))
  {
    isValid = false;
  }
  else if (dateOfBirth > nowDate || (year + 140) < nowDate.getFullYear())
  {
    isValid = false;
  }

  return isValid;
}

Validate.prototype.isValidCreditCardExpiry = function(expiresMonth, expiresYear)
{
  var isValid = true;
  var nowDate = new Date();
  if (expiresMonth < (nowDate.getMonth() + 1) && 
      expiresYear == nowDate.getFullYear())
  {
    isValid = false;
  }
  else if (expiresYear < nowDate.getFullYear())
  {
    isValid = false;
  }

  return isValid;
}

Validate.prototype.isValidCreditCardNumber = function(cardNumber, cardType)
{
  var isValid = false;
  var ccCheckRegExp = /[^\d ]/;
  isValid = !ccCheckRegExp.test(cardNumber);

  if (isValid)
  {
    var cardNumbersOnly = cardNumber.replace(/ /g,"");
    var cardNumberLength = cardNumbersOnly.length;
    var lengthIsValid = false;
    var prefixIsValid = false;
    var prefixRegExp;

    switch(cardType)
    {
      case "mastercard":
        lengthIsValid = (cardNumberLength == 16);
        prefixRegExp = /^5[1-5]/;
        break;

      case "visa":
        lengthIsValid = (cardNumberLength == 16 || cardNumberLength == 13);
        prefixRegExp = /^4/;
        break;

      case "amex":
        lengthIsValid = (cardNumberLength == 15);
        prefixRegExp = /^3(4|7)/;
        break;

      default:
        prefixRegExp = /^$/;
        alert("Card type not found");
    }

    prefixIsValid = prefixRegExp.test(cardNumbersOnly);
    isValid = prefixIsValid && lengthIsValid;
  }

  if (isValid)
  {
    var numberProduct;
    var numberProductDigitIndex;
    var checkSumTotal = 0;

    for (digitCounter = cardNumberLength - 1; 
      digitCounter >= 0; 
      digitCounter--)
    {
      checkSumTotal += parseInt (cardNumbersOnly.charAt(digitCounter));
      if (digitCounter > 0)
      {
      digitCounter--;      
      numberProduct = String((cardNumbersOnly.charAt(digitCounter) * 2));
      for (var productDigitCounter = 0;
        productDigitCounter < numberProduct.length; 
        productDigitCounter++)
      {
        checkSumTotal += 
          parseInt(numberProduct.charAt(productDigitCounter));
      }
      }
    }

    isValid = (checkSumTotal % 10 == 0);
  }

  return isValid;
}

Validate.prototype.checkFormValid = function(theForm, theDocument)
{
  var isWholeFormValid = true;
  var isValid = true;
  var theElement;
  var isToBeValidatedElementRegExp = /(_Compulsory)|(_NotCompulsory)/i;
  var isCompulsoryRegExp = /(_Compulsory)/i;
  var validDataTypeRegExp = /_[a-zA-Z]+$/i;
  var invalidDataType;
  var elementName;
  var errorDivId;
  var isCompulsoryElement;
  var isToBeCheckedElement;
  var isTextBoxElement;

  // Check Text boxes completed and/or correct data type
  for (var formElementCounter = 0; formElementCounter < theForm.length;
       formElementCounter++)
  {
    theElement = theForm.elements[formElementCounter];
    elementName = theElement.name;

    isCompulsoryElement = isCompulsoryRegExp.test(elementName);
    isToBeValidatedElement = isToBeValidatedElementRegExp.test(elementName);

    if (isToBeValidatedElement)
    {
      errorDivId = theElement.name;
      errorDivId = errorDivId.slice(3,errorDivId.indexOf("_")) + "Error";
      this.hideErrorDiv(errorDivId, theDocument);

      isTextBoxElement =  theElement.type == "text" || 
                          theElement.type == "password" || 
                          theElement.type == "file";

      if ( isTextBoxElement )
      {
        isValid = this.isTextElementValid(theElement,
                                          validDataTypeRegExp,
                                          isCompulsoryElement);

        if ( !isValid )
        {
          this.showErrorDiv(errorDivId,theDocument);
          isWholeFormValid = false;
        }
      }

      //Check Compulsory Radio Buttons Completed
      else if (theElement.type == "radio")
      {
        if (isCompulsoryElement)
        {
          elementName = theElement.name;
          theElement = theForm.elements[theElement.name];
          isValid = this.isOneRadioButtonInGroupSelected(theElement);

          if (isValid == false)
          {
            this.showErrorDiv(errorDivId,theDocument);
            isWholeFormValid = false;
          }

          do 
          {
            formElementCounter++;
            theElement = theForm.elements[formElementCounter];
          }
          while (theElement.name == elementName && 
            formElementCounter < theForm.length)
          formElementCounter--;
        }
      }
    }
  }

  return isWholeFormValid;
}

Validate.prototype.isTextElementValid = function(theElement, 
                                                 validDataTypeRegExp, 
                                                 isCompulsoryElement)
{
  var isValid = true;
  var validDataType;

  if (isCompulsoryElement && theElement.value == "")
  {
    isValid = false;
  }
  else
  {
 		validDataType = validDataTypeRegExp.exec(theElement.name)[0];
		validDataType = validDataType.toLowerCase();
		if (validDataType != "_compulsory")
		{
			isValid = this.isElementDataValid(theElement.value,validDataType)
		}

  }

  return isValid;
}

Validate.prototype.isElementDataValid = function(elementValue, validDataType)
{
  var isValid = false;

  switch (validDataType)
  {
    case "_alphanumeric":
      isValid = this.isOnlyAlphaNumeric(elementValue);
      break;

    case "_alphanumericnospace":
      isValid = this.isOnlyAlphaNumericNoSpace (elementValue);
      break;

    case "_alphabetic":
      isValid = this.isOnlyAlphabetic(elementValue);
      break;

    case "_numeric":
      isValid = this.isOnlyNumeric(elementValue);
      break;

    case "_integer":
      isValid = this.isValidInteger(elementValue);
      break;

    case "_floatingpoint":
      isValid = this.isValidFloatingPoint(elementValue);
      break;

    case "_age":
      isValid = this.isValidAge(elementValue);
      break;

    case "_password":
      isValid = this.isValidPassword(elementValue);
      break;

    case "_telephone":
      isValid = this.isValidTelephoneNum(elementValue);
      break;

    case "_postcode":
      isValid = this.isValidPostalCode(elementValue);
      break;

    case "_email":
      isValid = this.isValidEmail(elementValue);
      break;

    default:
      alert("Error unidentified element data type");
  }

  return isValid;
}

Validate.prototype.isOneRadioButtonInGroupSelected = function(theElement)
{
  var radioCounter;
  var isValid = false;

  for (radioCounter = theElement.length - 1; radioCounter >= 0; radioCounter--)
  {
    isValid = theElement[radioCounter].checked;
    if (isValid)
    {
      break;
    }
  }

  return isValid;
}

Validate.prototype.showErrorDiv = function (errorDescripDivId, 
                                            theDocument)
{
  if (document.layers)
  {
    theDocument.layers[errorDescripDivId].visibility = "visible";
  }
  else if (document.all)
  {
    theDocument.all[errorDescripDivId].style.visibility = "visible";
  }
  else
  {
    theDocument.getElementById(errorDescripDivId).style.visibility = "visible";
  }
}

Validate.prototype.hideErrorDiv = function (errorDescripDivId, theDocument)
{
  if (theDocument.layers)
  {
    theDocument.layers[errorDescripDivId].visibility = "hidden";
  }
  else if (document.all)
  {
    theDocument.all[errorDescripDivId].style.visibility = "hidden"
  }
  else
  {
    theDocument.getElementById(errorDescripDivId).style.visibility = "hidden"
  }
}

var MyTotalValidate = new Validate();

/*
  -------------------------------------------------------------------------
	                    JavaScript Form Validator 
                                Version 2.0.2
	Copyright 2003 JavaScript-coder.com. All rights reserved.
	You use this script in your Web pages, provided these opening credit
    lines are kept intact.
	The Form validation script is distributed free from JavaScript-Coder.com

	You may please add a link to JavaScript-Coder.com, 
	making it easy for others to find this script.
	Checkout the Give a link and Get a link page:
	http://www.javascript-coder.com/links/how-to-link.php

    You may not reprint or redistribute this code without permission from 
    JavaScript-Coder.com.
	
	JavaScript Coder
	It precisely codes what you imagine!
	Grab your copy here:
		http://www.javascript-coder.com/
    -------------------------------------------------------------------------  
*/

var dtCh= "/";
var minYear=1900;
var maxYear=2100;

function isInteger(s){
	var i;
    for (i = 0; i < s.length; i++){   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag){
	var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++){   
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
		if (i==2) {this[i] = 29}
   } 
   return this
}

function isDate(dtStr){
	var returnval=true;
	var daysInMonth = DaysArray(12)
	var pos1=dtStr.indexOf(dtCh)
	var pos2=dtStr.indexOf(dtCh,pos1+1)
	
	//var strMonth=dtStr.substring(0,pos1)
	var strDay=dtStr.substring(0,pos1)
	//var strDay=dtStr.substring(pos1+1,pos2)
	var strMonth=dtStr.substring(pos1+1,pos2)
	var strYear=dtStr.substring(pos2+1)
	strYr=strYear
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
	}
	month=parseInt(strMonth)
	day=parseInt(strDay)
	year=parseInt(strYr)
	
	if (pos1==-1 || pos2==-1){
		returnval = false;		
	}
	if (strMonth.length<1 || month<1 || month>12){
		returnval = false;
	}
	if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
		returnval = false;
	}
	if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
		returnval = false;
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
		returnval = false;
	}
	return returnval;
}

/* Nguyen mau cua ham*/
function Validator(frmname)
{
  this.formobj=document.forms[frmname];
	if(!this.formobj)
	{
	  alert("BUG: couldnot get Form object "+frmname);
		return;
	}
	if(this.formobj.onsubmit)
	{
	 this.formobj.old_onsubmit = this.formobj.onsubmit;
	 this.formobj.onsubmit=null;
	}
	else
	{
	 this.formobj.old_onsubmit = null;
	}
	this.formobj.onsubmit=form_submit_handler;
	this.addValidation = add_validation;
	this.setAddnlValidationFunction=set_addnl_vfunction;
	this.clearAllValidations = clear_all_validations;
}
function set_addnl_vfunction(functionname)
{
  this.formobj.addnlvalidation = functionname;
}
function clear_all_validations()
{
	for(var itr=0;itr < this.formobj.elements.length;itr++)
	{
		this.formobj.elements[itr].validationset = null;
	}
}
function form_submit_handler()
{
	for(var itr=0;itr < this.elements.length;itr++)
	{
		if(this.elements[itr].validationset &&
	   !this.elements[itr].validationset.validate())
		{
		  return false;
		}
	}
	if(this.addnlvalidation)
	{
	  str =" var ret = "+this.addnlvalidation+"()";
	  eval(str);
    if(!ret) return ret;
	}
	return true;
}
function add_validation(itemname,descriptor,errstr)
{
  if(!this.formobj)
	{
	  alert("BUG: the form object is not set properly");
		return;
	}//if
	var itemobj = this.formobj[itemname];
  if(!itemobj)
	{
	  alert("BUG: Couldnot get the input object named: "+itemname);
		return;
	}
	if(!itemobj.validationset)
	{
	  itemobj.validationset = new ValidationSet(itemobj);
	}
  itemobj.validationset.add(descriptor,errstr);
}
function ValidationDesc(inputitem,desc,error)
{
  this.desc=desc;
	this.error=error;
	this.itemobj = inputitem;
	this.validate=vdesc_validate;
}
function vdesc_validate()
{
 if(!V2validateData(this.desc,this.itemobj,this.error))
 {
    this.itemobj.focus();
		return false;
 }
 return true;
}
function ValidationSet(inputitem)
{
    this.vSet=new Array();
	this.add= add_validationdesc;
	this.validate= vset_validate;
	this.itemobj = inputitem;
}
function add_validationdesc(desc,error)
{
  this.vSet[this.vSet.length]= 
	  new ValidationDesc(this.itemobj,desc,error);
}
function vset_validate()
{
   for(var itr=0;itr<this.vSet.length;itr++)
	 {
	   if(!this.vSet[itr].validate())
		 {
		   return false;
		 }
	 }
	 return true;
}
function validateEmailv2(email)
{
// a very simple email validation checking. 
// you can add more complex email checking if it helps 
    if(email.length <= 0)
	{
	  return true;
	}
    var splitted = email.match("^(.+)@(.+)$");
    if(splitted == null) return false;
    if(splitted[1] != null )
    {
      var regexp_user=/^\"?[\w-_\.]*\"?$/;
      if(splitted[1].match(regexp_user) == null) return false;
    }
    if(splitted[2] != null)
    {
      var regexp_domain=/^[\w-\.]*\.[A-Za-z]{2,4}$/;
      if(splitted[2].match(regexp_domain) == null) 
      {
	    var regexp_ip =/^\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]$/;
	    if(splitted[2].match(regexp_ip) == null) return false;
      }// if
      return true;
    }
return false;
}



function V2validateData(strValidateStr,objValue,strError) 
{ 
    var epos = strValidateStr.search("="); 
    var  command  = ""; 
    var  cmdvalue = ""; 
    if(epos >= 0) 
    { 
     command  = strValidateStr.substring(0,epos); 
     cmdvalue = strValidateStr.substr(epos+1); 
    } 
    else 
    { 
     command = strValidateStr; 
    } 
    switch(command) 
    { 
        case "req": 
        case "required": 
         { 
           if(eval(objValue.value.length) == 0) 
           { 
              if(!strError || strError.length ==0) 
              { 
                strError = objValue.name + " : Required Field"; 
              }//if 
              alert(strError); 
              return false; 
           }//if 
           break;             
         }//case required 
        case "maxlength": 
        case "maxlen": 
          { 
             if(eval(objValue.value.length) >  eval(cmdvalue)) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : "+cmdvalue+" characters maximum "; 
               }//if 
               alert(strError + "\n[Current length = " + objValue.value.length + " ]"); 
               return false; 
             }//if 
             break; 
          }//case maxlen 
        case "minlength": 
        case "minlen": 
           { 
             if(eval(objValue.value.length) <  eval(cmdvalue)) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : " + cmdvalue + " characters minimum  "; 
               }//if               
               alert(strError + "\n[Current length = " + objValue.value.length + " ]"); 
               return false;                 
             }//if 
             break; 
            }//case minlen 
        case "alnum": 
        case "alphanumeric": 
           { 
              var charpos = objValue.value.search("[^A-Za-z0-9]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
               if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": Only alpha-numeric characters allowed "; 
                }//if 
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 
              break; 
           }//case alphanumeric 
        case "num": 
        case "numeric": 
           { 
              var charpos = objValue.value.search("[^0-9]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": Only digits allowed "; 
                }//if               
                alert(strError); 
                return false; 
              }//if 
              break;               
           }//numeric 
        case "alphabetic": 
        case "alpha": 
           { 
              var charpos = objValue.value.search("[^A-Za-z]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": Only alphabetic characters allowed "; 
                }//if                             
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 
              break; 
           }//alpha 
		case "alnumhyphen":
			{
              var charpos = objValue.value.search("[^A-Za-z0-9\-_]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": characters allowed are A-Z,a-z,0-9,- and _"; 
                }//if                             
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 			
			break;
			}
        case "email": 
          { 
               if(!validateEmailv2(objValue.value)) 
               { 
                 if(!strError || strError.length ==0) 
                 { 
                    strError = objValue.name+": Enter a valid Email address "; 
                 }//if                                               
                 alert(strError); 
                 return false; 
               }//if 
           break; 
          }//case email 
          
        case "dd/mm/yyyy": 
          { 
          		if(eval(objValue.value.length) != 0) 
          		{
               		if(!isDate(objValue.value)) 
               		{ 
                 		if(!strError || strError.length ==0) 
                 		{ 
                    		strError = objValue.name+": Enter a valid Email address "; 
                 		}//if                                               
                 		alert(strError); 
                 		return false; 
               		}//if 
          		}//if
           		break; 
          }//case "dd/mm/yyyy"
          
        case "lt": 
        case "lessthan": 
         { 
            if(isNaN(objValue.value)) 
            { 
              alert(objValue.name+": Should be a number "); 
              return false; 
            }//if 
            if(eval(objValue.value) >=  eval(cmdvalue)) 
            { 
              if(!strError || strError.length ==0) 
              { 
                strError = objValue.name + " : value should be less than "+ cmdvalue; 
              }//if               
              alert(strError); 
              return false;                 
             }//if             
            break; 
         }//case lessthan 
        case "gt": 
        case "greaterthan": 
         { 
            if(isNaN(objValue.value)) 
            { 
              alert(objValue.name+": Should be a number "); 
              return false; 
            }//if 
             if(eval(objValue.value) <=  eval(cmdvalue)) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : value should be greater than "+ cmdvalue; 
               }//if               
               alert(strError); 
               return false;                 
             }//if             
            break; 
         }//case greaterthan 
        case "regexp": 
         { 
		 	if(objValue.value.length > 0)
			{
	            if(!objValue.value.match(cmdvalue)) 
	            { 
	              if(!strError || strError.length ==0) 
	              { 
	                strError = objValue.name+": Invalid characters found "; 
	              }//if                                                               
	              alert(strError); 
	              return false;                   
	            }//if 
			}
           break; 
         }//case regexp 
        case "dontselect": 
         { 
            if(objValue.selectedIndex == null) 
            { 
              alert("BUG: dontselect command for non-select Item"); 
              return false; 
            } 
            if(objValue.selectedIndex == eval(cmdvalue) || objValue.selectedValue == 'none') 
            { 
             if(!strError || strError.length ==0) 
              { 
              strError = objValue.name + ": Please Select one option "; 
              }//if                                                               
              alert(strError); 
              return false;                                   
             } 
             break; 
         }//case dontselect 
        case "OnlyAlphaNumericNoSpace": 
         { 
           if(!MyTotalValidate.isOnlyAlphaNumericNoSpace(objValue.value)) 
           { 
              if(!strError || strError.length ==0) 
              { 
                strError = objValue.name + " : Only alpha word or numeric , not space in this field"; 
              }//if 
              alert(strError); 
              return false; 
           }//if 
           break;             
         }//OnlyAlphaNumericNoSpace

         
    }//switch 
    return true; 
}
/*
	Copyright 2003 JavaScript-coder.com. All rights reserved.
*/