//JS Functions @0-F6A911E7
var isNN = (navigator.appName.indexOf("Netscape") != -1);
var isIE = (navigator.appName.indexOf("Microsoft") != -1);
var IEVersion = (isIE ? getIEVersion() : 0);
var NNVersion = (isNN ? getNNVersion() : 0);
var EditableGrid = false;
var disableValidation = false;

// Date formatting functions begin ---------------------------------------------------


var arrayLocaleInfo = "{res:CCS_FormatInfo}".split("|");

function getLocaleInfo(id)
{
	switch (id)
	{
	case "LanguageAndCountry":	return arrayLocaleInfo[0];
	case "language":			return arrayLocaleInfo[1];
	case "country":				return arrayLocaleInfo[2];
	case "booleanFormat":		return arrayLocaleInfo[3];
	case "decimalDigits":		return arrayLocaleInfo[4];
	case "decimalSeparator":	return arrayLocaleInfo[5];
	case "groupSeparator":		return arrayLocaleInfo[6];
	case "monthNames":			return arrayLocaleInfo[7];
	case "monthShortNames":		return arrayLocaleInfo[8];
	case "weekdayNames":		return arrayLocaleInfo[9];
	case "weekdayShortNames":	return arrayLocaleInfo[10];
	case "shortDate":			return arrayLocaleInfo[11];
	case "longDate":			return arrayLocaleInfo[12];
	case "shortTime":			return arrayLocaleInfo[13];
	case "longTime":			return arrayLocaleInfo[14];
	case "firstWeekDay":		return arrayLocaleInfo[15];
	case "AMDesignator":		return arrayLocaleInfo[16];
	case "PMDesignator":		return arrayLocaleInfo[17];
	}
	return "";
}



var listMonths = String(getLocaleInfo("monthNames")).split(";");
var listShortMonths = String(getLocaleInfo("monthShortNames")).split(";");
var firstWeekDay = getLocaleInfo("firstWeekDay");
var listWeekdays = String(getLocaleInfo("weekdayNames")).split(";");
var listShortWeekdays = String(getLocaleInfo("weekdayShortNames")).split(";");
firstWeekDay = listShortWeekdays[parseInt(firstWeekDay)];


function isInArray(strValue, arrArray)
{
  var intResult = -1;
  for ( var j = 0; j < arrArray.length && (strValue != arrArray[j]); j++ );
  if ( j != arrArray.length )
    intResult = j;
  return intResult;
}

function parseDateFormat(strMask)
{

 if (strMask=="LongDate")
      return  parseDateFormat(getLocaleInfo("longDate"));
 else if (strMask=="LongTime")
      return  parseDateFormat(getLocaleInfo("longTime"));
 else if (strMask=="ShortDate")
      return  parseDateFormat(getLocaleInfo("shortDate"));
 else if (strMask=="ShortTime")
      return  parseDateFormat(getLocaleInfo("shortTime"));
 else if (strMask=="GeneralDate")
      return  parseDateFormat(getLocaleInfo("shortDate")+" "+getLocaleInfo("longTime"));

  var UNDEF;
  var arrResult = new Array();
  if (strMask == "" || typeof(strMask) == "undefined")
    return arrResult;
  var arrMaskTokens = new Array(
  "d", "w", "m", "M", "q", "y", "h", "H", "n", "s",
  "dd", "ww", "mm", "MM", "yy", "hh", "HH", "nn", "ss", "S",
  "ddd", "mmm", "MMM", "A/P", "a/p", "dddd", "mmmm", "MMMM",
  "yyyy", "tt", "AM/PM", "am/pm", "LongDate", "LongTime",
  "ShortDate", "ShortTime", "GeneralDate");
  var arrMaskTokensFirstLetters = new Array("d", "w", "m", "M",
  "q", "y", "h", "H", "n", "s", "A", "a", "L", "S", "G", "t");
  var strMaskLength = strMask.length;
  var i = 0, intMaskPosition = 0;
  var arrMask = new Array();
  var strToken = "";
  while (i < strMaskLength)
  {
  if (strMask.charAt(i) == "\\")
  {
    strToken += strMask.charAt(++i);
    i ++;
  }
  else if (strMask.charAt(i) == "'")
  {
    i ++;
    while ((i < strMask.length) && (strMask.charAt(i) != "'"))
    strToken += strMask.charAt(i++);
    i ++;
  }
  else
  {
    var j = isInArray(strMask.charAt(i), arrMaskTokensFirstLetters);
    if ( j != -1 )
    {
    var k;
    for (k = (arrMaskTokens.length - 1); k >= 0 &&
      strMask.slice(i, i + arrMaskTokens[k].length) != arrMaskTokens[k]; k--);
    if (k != -1)
    {
      if (strToken.length > 0)
      {
      if ( isInArray(strToken, arrMaskTokens) == -1)
        arrMask[intMaskPosition ++] = strToken;
      else
        arrMask[intMaskPosition ++] = "\\" + strToken;
      strToken = "";
      }
      arrMask[intMaskPosition ++] = arrMaskTokens[k];
      i += arrMaskTokens[k].length;
    }
    else
    {
      strToken = strMask.charAt(i);
      i ++;
    }
    }
    else
    {
    strToken += strMask.charAt(i);
    i ++;
    }
  }
  }
  if (strToken.length > 0)
  {

  if ( isInArray(strToken, arrMaskTokens) == -1)
    arrMask[intMaskPosition ++] = strToken;

  else
    arrMask[intMaskPosition ++] = "\\" + strToken;

  strToken = "";
  }
  arrResult = arrMask;
  return arrResult;
}

function parseParams(text,substitutions)
{
  // replace the {0}, ... with corresponded substitution string and return the result
  var resString = text;
  if (resString!="" && substitutions!=null)
  {
    var array = (typeof(substitutions)!="object")?(new Array(substitutions)):substitutions;
    var icount = array.length;
    for (var i=0; i<icount; i++)
      resString = resString.replace("{"+i+"}", array[i]);
    delete array;
    array = null;
  }
  return resString;
}

function functionExists(functionName)
{
  var exists = true;
  try{
    exists = typeof(eval(functionName))=="function";
  }catch(e){
    exists = false;
  }
  return exists;
}

function ccsShowError(control, msg)
{
  alert(msg);
  control.focus();
  return false;
}

function getNNVersion()
{
  var userAgent = window.navigator.userAgent;
  var isMajor = parseInt(window.navigator.appVersion);
  var isMinor = parseFloat(window.navigator.appVersion);
  if (isMajor == 2) return 2;
  if (isMajor == 3) return 3;
  if (isMajor == 4) return 4;
  if (isMajor == 5)
  {
    if (userAgent.toLowerCase().indexOf('netscape')!=-1)
    {
      isMajor = parseInt(userAgent.substr(userAgent.toLowerCase().indexOf('netscape')+9));
      if (isMajor>0) return isMajor;
    }
    if (userAgent.toLowerCase().indexOf('firefox')!=-1) return 7;
    return 6;
  }
  return isMajor;
}

function getIEVersion()
{
  var userAgent = window.navigator.userAgent;
  var MSIEPos = userAgent.indexOf("MSIE");
  return (MSIEPos > 0 ? parseInt(userAgent.substring(MSIEPos+5, userAgent.indexOf(".", MSIEPos))) : 0);
}

function inputMasking(evt)
{
  if (isIE && IEVersion > 4)
  {
    if (window.event.altKey) return false;
    if (window.event.ctrlKey) return false;
    if (typeof(this.ccsInputMask) == "string")
    {
      var mask = this.ccsInputMask;
      var keycode = window.event.keyCode;
      this.value = applyMask(keycode, mask, this.value);
    }
    return (window.event.keyCode==13?true:false);
  } else if (isNN && NNVersion<6)
  {
    if (evt.ALT_MASK) return false;
    if (evt.CONTROL_MASK) return false;
    if (typeof(this.ccsInputMask) == "string")
    {
      var mask = this.ccsInputMask;
      var keycode = evt.which;
      this.value = applyMask(keycode, mask, this.value);
    }
    return (evt.which==13?true:false);
  } else if (isNN && NNVersion==6)
  {
    if (evt.ctrlKey) return false;
    var cancelKey = evt.which < 32;
    if (typeof(this.ccsInputMask) == "string")
    {
      var mask = this.ccsInputMask;
      var keycode = evt.which;
      if (keycode >= 32)
        this.value = applyMask(keycode, mask, this.value);
    }
    return cancelKey;
  } else if (isNN && NNVersion==7)
  {
    if (evt.altKey) return false;
    if (evt.ctrlKey) return false;
    var cancelKey = evt.which==13;
    if (typeof(this.ccsInputMask) == "string")
    {
      var mask = this.ccsInputMask;
      var keycode = evt.which;
      cancelKey = keycode < 32;
      if (!cancelKey)
        this.value = applyMask(keycode, mask, this.value);
    }
    return cancelKey || evt.which==13;
  } else
    return true;
}

function applyMaskToValue(mask, value)
{
  var oldValue = String(value);
  var newValue = "";
  var icount = oldValue.length;
  for (var i=0; i<icount; i++)
  {
    newValue = applyMask(oldValue.charCodeAt(i), mask, newValue);
  }
  return newValue;
}

function applyMask(keycode, mask, value)
{
  var digit = (keycode >= 48 && keycode <= 57);
  var plus = (keycode == 43);
  var dash = (keycode == 45);
  var space = (keycode == 32);
  var uletter = (keycode >= 65 && keycode <= 90);
  var lletter = (keycode >= 97 && keycode <= 122);

  var pos = value.length;
  switch(mask.charAt(pos))
  {
    case "0":
      if (digit)
        value += String.fromCharCode(keycode);
      break;
    case "L":
      if (uletter || lletter)
        value += String.fromCharCode(keycode);
      break;
    default:
      var isMatchMask = (String.fromCharCode(keycode) == mask.charAt(pos));
      while (pos < mask.length && mask.charAt(pos) != "0" && mask.charAt(pos) != "L")
        value += mask.charAt(pos++);
      if (!isMatchMask && pos < mask.length)
        value = applyMask(keycode, mask, value);
  }
  return value;
}

function validate_control(control)
{
/*
ccsCaption - string
ccsErrorMessage - string

ccsRequired - boolean
ccsMinLength - integer
ccsMaxLength - integer
ccsRegExp - string

ccsValidator - validation function

ccsInputMask - string
*/
  if (disableValidation) return true;
  var errorMessage = control.ccsErrorMessage;
  var customErrorMessage = (typeof(errorMessage) != "undefined");

  if (typeof(control.ccsRequired) == "boolean" && control.ccsRequired)
    if (control.value == "")
      return ccsShowError(control, customErrorMessage ? errorMessage :
        parseParams("{res:CCS_RequiredField}", control.ccsCaption));

  if (typeof(control.ccsMinLength) == "number")
    if (control.value != "" && control.value.length < parseInt(control.ccsMinLength))
      return ccsShowError(control, customErrorMessage ? errorMessage :
        parseParams("{res:CCS_MinimumLength}", Array(control.ccsCaption,parseInt(control.ccsMinLength))));

  if (typeof(control.ccsMaxLength) == "number")
    if (control.value != "" && control.value.length > parseInt(control.ccsMaxLength))
      return ccsShowError(control, customErrorMessage ? errorMessage :
        parseParams("{res:CCS_MaximumLength}", Array(control.ccsCaption,parseInt(control.ccsMaxLength))));

  if (typeof(control.ccsInputMask) == "string")
  {
    var mask = control.ccsInputMask;
    var maskRE = new RegExp(stringToRegExp(mask).replace(/0/g,"\\d").replace(/L/g,"[A-Za-z]"), "i");
    if (control.value != "" && (control.value.search(maskRE) == -1))
      return ccsShowError(control, customErrorMessage ? errorMessage :
        parseParams("{res:CCS_IncorrectValue}", control.ccsCaption));
  }

  if (typeof(control.ccsRegExp) == "string")
    if (control.value != "" && (control.value.search(new RegExp(control.ccsRegExp, "i")) == -1))
      return ccsShowError(control, customErrorMessage ? errorMessage :
        parseParams("{res:CCS_IncorrectValue}", control.ccsCaption));

  if (typeof(control.ccsDateFormat) == "string")
  {
    if (control.value != "" && !checkDate(control.value, parseDateFormat(control.ccsDateFormat).join("")))
      return ccsShowError(control, customErrorMessage ? errorMessage :
        parseParams("{res:CCS_IncorrectFormat}", Array(control.ccsCaption, parseDateFormat(control.ccsDateFormat).join(""))));
  }

  if (typeof(control.ccsValidator) == "function")
    if (!control.ccsValidator())
      return ccsShowError(control, customErrorMessage ? errorMessage :
        parseParams("{res:CCS_IncorrectValue}", control.ccsCaption));

  return true;
}


function stringToRegExp(string, arg)
{
  var str = String(string);
  str = str.replace(/\\/g,"\\\\");
  str = str.replace(/\//g,"\\/");
  str = str.replace(/\./g,"\\.");
  str = str.replace(/\(/g,"\\(");
  str = str.replace(/\)/g,"\\)");
  str = str.replace(/\[/g,"\\[");
  str = str.replace(/\]/g,"\\]");
  return str;
}

function checkDate(dateValue, dateFormat)
{
  dateFormat = dateFormat.replace("AM/PM","f1").replace("A/P","f2").replace("am/pm","f3").replace("a/p","f4");

  var DateMasks = new Array(
                    new Array("MMMM", "[a-z]+"),
                    new Array("mmmm", "[a-z]+"),
                    new Array("yyyy", "[0-9]{4}"),
                    new Array("MMM", "[a-z]+"),
                    new Array("mmm", "[a-z]+"),
                    new Array("HH", "([0-1][0-9]|2[0-4])"),
                    new Array("hh", "(0[1-9]|1[0-2])"),
                    new Array("dd", "([0-2][0-9]|3[0-1])"),
                    new Array("MM", "(0[1-9]|1[0-2])"),
                    new Array("mm", "(0[1-9]|1[0-2])"),
                    new Array("yy", "[0-9]{2}"),
                    new Array("nn", "[0-5][0-9]"),
                    new Array("ss", "[0-5][0-9]"),
                    new Array("w", "[1-7]"),
                    new Array("d", "([1-9]|[1-2][0-9]|3[0-1])"),
                    new Array("y", "([1-2][0-9]{0,2}|3([0-5][0-9]|6[0-5]))"),
                    new Array("H", "(00|0?[1-9]|1[0-9]|2[0-4])"),
                    new Array("h", "(0?[1-9]|1[0-2])"),
                    new Array("M", "(0?[1-9]|1[0-2])"),
                    new Array("m", "(0?[1-9]|1[0-2])"),
                    new Array("n", "[0-5]?[0-9]"),
                    new Array("s", "[0-5]?[0-9]"),
                    new Array("q", "[1-4]"),
                    new Array("tt", "("+getLocaleInfo("AMDesignator")+"|"+getLocaleInfo("PMDesignator")+")")
                  );
  var regExp = "^"+stringToRegExp(dateFormat)+"$";
  var icount = DateMasks.length;
  for (var i=0; i<icount; i++)
  {
    regExp = regExp.replace(DateMasks[i][0], DateMasks[i][1]);
  }
  regExp=regExp.replace("f1","(AM|PM)").replace("f2","(A|P)").replace("f3","(am|fm)").replace("f4","(a|f)");

  var regExp = new RegExp(regExp,"i");
  return String(dateValue).search(regExp)!=-1;
}

function validate_row(rowId, form)
{
  var result = true;
  var isInsert = false;
  if (disableValidation) return true;
  if(typeof(eval(form + "EmptyRows")) == "number")
    if(eval(form + "Elements").length - rowId <= eval(form + "EmptyRows"))
      isInsert = true;
  var icount = eval(form + "Elements")[rowId].length;
  for (var i = 0; i < icount && isInsert; i++)
    isInsert = GetValue(eval(form + "Elements")[rowId][i]) == "";
  if(isInsert) return true;

  if(typeof(eval(form + "DeleteControl")) == "number")
    {
      var control = eval(form + "Elements")[rowId][eval(form + "DeleteControl")];
      if(control.type == "checkbox")
        if(control.checked == true ) return true;
      if(control.type == "hidden")
        if(control.value != "" ) return true;
    }


  for (var i = 0; i < icount && (result = validate_control(eval(form + "Elements")[rowId][i])); i++);
  return result;
}

function GetValue(control) {
    if (typeof(control.value) == "string") {
        return control.value;
    }
    if (typeof(control.tagName) == "undefined" && typeof(control.length) == "number") {
        var j;
        var jcount = control.length;
        for (j=0; j < jcount; j++) {
            var inner = control[j];
            if (typeof(inner.value) == "string" && (inner.type != "radio" || inner.status == true)) {
                return inner.value;
            }
        }
    }
    else {
        return GetValueRecursive(control);
    }
    return "";
}

function GetValueRecursive(control)
{
    if (typeof(control.value) == "string" && (control.type != "radio" || control.status == true)) {
        return control.value;
    }
    var i, val;
    var icount = control.children.length;
    for (i = 0; i<icount; i++) {
        val = GetValueRecursive(control.children[i]);
        if (val != "") return val;
    }
    return "";
}


function validate_form(form)
{
	var result = true;
	if (disableValidation) return true;
	if(typeof(form) == "object" && String(form.tagName).toLowerCase()!="form" && form.form!=null) form = form.form;
	if(typeof(form) == "object" && document.getElementById(form.name + "Elements")) {
		if (typeof(eval(form.name + "Elements")) == "object")
    {
			var jcount = eval(form.name + "Elements").length;
			for (var j = 0; j < jcount && result; j++) result = validate_row(j, form.name);
    }else
    {
			var icount = form.elements.length;
			for (var i = 0; i < icount && (result = validate_control(form.elements[i])); i++);
		}
  }else if(typeof(form) == "string" && document.getElementById(form.name + "Elements"))
  {
    if(typeof(eval(form + "Elements")) == "object"){
			var jcount = eval(form + "Elements").length;
			for (var j = 0; j < jcount && result; j++)
				result = validate_row(j, form);
		}
  }else if (typeof(form) == "object")
  {
		var icount = form.elements.length;
		for (var i = 0; i < icount && (result = validate_control(form.elements[i])); i++);
	}
	else
	{
		var icount = document.forms[form].elements.length;
		for (var i = 0; i < icount && (result = validate_control(document.forms[form].elements[i])); i++);
	}
	return result;
}

function forms_onload()
{
  var forms = document.forms;
  var i, j, elm, form;
  var icount = forms.length;
  var arrElementsOnLoad = new Array();

  for(i = 0; i < icount; i++)
  {
    form = forms[i];
    if (typeof(form.onLoad) == "function") form.onLoad();
    var jcount = form.elements.length;

    for (j = 0; j < jcount; j++)
    {
      elm = form.elements[j];
      if (typeof(elm.onLoad) == "function") arrElementsOnLoad[arrElementsOnLoad.length] = elm;
    }
  }

  for (i = 0; i < arrElementsOnLoad.length; i++)
	arrElementsOnLoad[i].onLoad();

  return true;
}

function all_onload()
{
  var element = null;
  var elements = new Array();
  var all = document.all || document.getElementsByTagName("*");
  var icount = all.length;

  for(var i = 0; i < icount; i++)
  {
    element = all[i] || (all.item && all.item(i));
    if (typeof(element.onLoad) == "function") elements[elements.length] = element;
  }

  for (var i = 0; i < elements.length; i++)
    elements[i].onLoad();

  return true;
}

//
// If element exist than bind function func to element on event.
// Example: check_and_bind('document.NewRecord1.Delete1','onclick',page_NewRecord1_Delete1_OnClick);
//
function check_and_bind(element,event,func,iterate_id) {
  if (iterate_id)
  {
    var i = 1;
    var next_element = null;

    do {
      var next_id = element + i;
      next_element = document.getElementById(next_id);
      if (next_element) check_and_bind("document.getElementById(\"" + next_id + "\")", event, func);
      i++;
    } while (next_element)

    return;
  }
  var htmlElement = eval(element);
  if (!htmlElement)
  {
    var index = element.lastIndexOf(".");
    var form = element.substr(0,index);
    var control = element.substr(index+1);
    var htmlForm = eval(form);
    if (htmlForm)
    {
      var list = document.getElementsByName(control);
      var icount = list.length;
      for (var i=0; i<icount; i++)
      {
        if (list[i].form && list[i].form.name==htmlForm.name)
        {
          eval("document.getElementsByName(\""+control+"\")["+i+'].'+event+'='+func);
        }
      }
    }
  }else{
    if (htmlElement) {
      if (typeof(htmlElement)=="object" && !htmlElement.tagName && htmlElement.length > 0)
      {
        var icount = htmlElement.length;
        for (var i=0; i < icount; i++)
          eval(element+"["+i+'].'+event+'='+func);
      }else eval(element+'.'+event+'='+func);
    }
  }
}

function getElement(elementId, rowNumber, existingElement) {
  var control = document.getElementById(elementId);
  if (control == null) {
    control = document.getElementById(elementId + "_" + rowNumber);
  }
  if (control == null)
  {
    var controlName = elementId;
    if (existingElement && existingElement.form && existingElement.form.id && controlName.indexOf(existingElement.form.id) == 0)
      controlName = controlName.replace(existingElement.form.id, "");
    var controls = document.getElementsByName(controlName);
    for (var i = 0; i < controls.length; i++)
      if (controls[i].checked == true)
      {
        control = controls[i];
        break;
      }
  }
  return control;
}

function getRowFromId(elementId) {
  var lastUnderscore = elementId.lastIndexOf("_");
  if (lastUnderscore != -1) {
    return elementId.substring(lastUnderscore + 1);
  }
  return null;
}

function getSameLevelCtl(elementName, existingElement) {
  var rowNumber = null;
  if (existingElement != null && existingElement['id'] != null) {
    rowNumber = getRowFromId(existingElement.id);
  }
  return getElement(elementName, rowNumber, existingElement);
}

function addEventHandler(elementId, event, handler) {
  var rowNum = 0;
  var loadCalled = false;
  do {
    var rowElementId = rowNum > 0 ? elementId + "_" + rowNum : elementId;
    var element = document.getElementById(rowElementId);
    if (element != null) {
      var handlerWithSender = function(evt) {
          var ret = true;
          if (window.event) {
              ret = handler.apply(window.event.srcElement, [window.event.srcElement]);
              window.event.returnValue = ret;
          } else {
              ret = handler.apply(this, [this]);
              if (evt && !ret) evt.preventDefault();
          }
          return ret;
      };
      if (event == "load") {
        handler.apply(element, [element]);
        loadCalled = true;
      } else if (event == "click" && element.tagName.toLowerCase() == "input" && element.type && (element.type == "submit" || element.type == "image")) {
        element.onclick = handler;
      } else {
        if (element.addEventListener){
          element.addEventListener(event, handlerWithSender, false);
        } else if (element.attachEvent){
          element.attachEvent("on" + event, handlerWithSender);
        }
      }
    }
    rowNum++;
  } while (element != null || rowNum == 1);
  if (event == "load" && loadCalled == false) {
    handler.apply(window, [window]);
  }
}

function addEventHandler2(element, event, handler) {
  if (typeof(element) == "string") {
    return bindEventHandler(element, event, handler);
  }
  if (element) {
    var oldHandler = (element['on'+event]) ? element['on'+event] : function () {};
    element['on'+event] = function () {
      oldHandler.apply(element, [element]);
      handler.apply(element, [element])
    };
    return true;
  }
  return false;
}

function bindEventHandler(elementName, event, handler) {
  var element = getElement(elementName);
  if (event == 'load' && elementName == "") { //Page
    handler.apply(element, [element]);
  }
  if (element != null) {
    if (event != 'load') {
      addEventHandler(element, event, handler);
    } else {
      handler.apply(element, [element]);
    }
  } else {
    var currentRow = 1;
    while (element = getElement(elementName, currentRow)) {
      if (event != 'load') {
        addEventHandler(element, event, handler);
      } else {
        handler.apply(element, [element]);
      }
      currentRow++;
    }
  }
}

function CCGetParam(strParamName) {
  var strReturn = "";
  var strHref = window.location.href;
  if ( strHref.indexOf("?") > -1 ) {
    var strQueryString = strHref.substr(strHref.indexOf("?")).toLowerCase();
    var aQueryString = strQueryString.split("&");
    for ( var iParam = 0; iParam < aQueryString.length; iParam++ ) {
      if (aQueryString[iParam].indexOf(strParamName.toLowerCase() + "=") > -1 ) {
        var aParam = aQueryString[iParam].split("=");
        strReturn = aParam[1];
        break;
      }
    }
  }
  return strReturn;
}

function CCGetCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++)
  {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}

function CCChangeSize(sender, formName, pageSize) {
    var currentElement = sender;
    while (!currentElement.filterId && currentElement != document) {
        currentElement = currentElement.parentNode;
    }
    var insidePanel = false;
    if (currentElement != document) {
        insidePanel = currentElement;
    }
    var oldLocation = null;
    if (insidePanel) {
        oldLocation = insidePanel.location;
    } else {
        oldLocation = location.href.toString();
    }
    var newLocation = CCAddParam(oldLocation, formName + 'PageSize', sender.value);
    var newLocation = CCAddParam(newLocation, formName + 'Page', 1);
    if (insidePanel) {
        insidePanel.location = newLocation;
        AjaxPanel.reload(insidePanel);
    } else {
        window.open(newLocation, '_self');
    }
}

function CCChangePage(sender, formName, pageSize) {
    var currentElement = sender;
    while (!currentElement.filterId && currentElement != document) {
        currentElement = currentElement.parentNode;
    }
    var insidePanel = false;
    if (currentElement != document) {
        insidePanel = currentElement;
    }
    var oldLocation = null;
    if (insidePanel) {
        oldLocation = insidePanel.location;
    } else {
        oldLocation = location.href.toString();
    }
    var newLocation = CCAddParam(oldLocation, formName + 'Page', sender.previousSibling.value);
    if (insidePanel) {
        insidePanel.location = newLocation;
        AjaxPanel.reload(insidePanel);
    } else {
        window.open(newLocation, '_self');
    }
}

function CCAddParam(location, paramName, paramValue) {
	if (location.indexOf('?') == -1) {
        return location + '?' + paramName + '=' + paramValue;
    }
    return location.replace(new RegExp(paramName + '=[^&]*[&]?', 'gi'), '')
        .replace(new RegExp('[&]?' + paramName + '=[^&]*', 'gi'), '')
        .replace(/\?/, '?' + paramName + '=' + paramValue + '&')
        .replace(/[&]+$/m, '');
}

function isIncluded(href1, href2)
{
    if (href1 == null || href2 == null)
        return href1 == href2;
    if (href1.indexOf("?") == -1 || href1.split("?")[1] == "")
        return href1.split("?")[0] == href2.split("?")[0];
    if (href2.indexOf("?") == -1 || href2.split("?")[1] == "")
        return href1.replace("?","") == href2.replace("?","");
    if (href1.split("?")[0] != href2.split("?")[0])
        return false;
    var params = href1.split("?")[1];
    params = params.split("&");
    var i,par1,par2,nv;
    par1 = new Array();
    for (i in params)
    {
        if (typeof(params[i]) == "function")
            continue;
        nv = params[i].split("=");
        if (nv[0]!="FormFilter")
            par1[nv[0]] = nv[1];
    }
    params = href2.split("?")[1];
    params = params.split("&");
    par2 = new Array();
    for (i in params)
    {
        if (typeof(params[i]) == "function")
            continue;
        nv = params[i].split("=");
        if (nv[0]!="FormFilter")
            par2[nv[0]] = nv[1];
    }
    /*if (par1.length != par2.length)
        return false;*/
    for (i in par1)
        if (par1[i]!=par2[i])
            return false;
    return true;
}


//End JS Functions

