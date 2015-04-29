<?php
//Include Common Files @1-75B4FA9B
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "agenda.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
include_once(RelativePath . "/CalendarNavigator.php");
//End Include Common Files

//Include Page implementation @2-3DD2EFDC
include_once(RelativePath . "/Header.php");
//End Include Page implementation

//Include Page implementation @3-58DBA1E3
include_once(RelativePath . "/Footer.php");
//End Include Page implementation

//SELECT_Data_Hora_Nome_FRO clsEvent @4-877B19B9
class clsEventSELECT_Data_Hora_Nome_FRO {
    var $_Time;
    var $EventTime;
    var $EventDescription;

}
//End SELECT_Data_Hora_Nome_FRO clsEvent

class clsCalendarSELECT_Data_Hora_Nome_FRO { //SELECT_Data_Hora_Nome_FRO Class @4-71646D42

//SELECT_Data_Hora_Nome_FRO Variables @4-E61C7B8A

    var $ComponentType = "Calendar";
    var $ComponentName;
    var $Visible;
    var $Errors;
    var $DataSource;
    var $ds;
    var $Type;
    //Calendar variables
    var $CurrentDate;
    var $CurrentProcessingDate;
    var $NextProcessingDate;
    var $PrevProcessingDate;
    var $CalendarStyles = array();
    var $CurrentStyle;
    var $FirstWeekDay;
    var $Now;
    var $IsCurrentMonth;
    var $MonthsInRow;
    var $CCSEvents = array();
    var $CCSEventResult;
    var $Parent;
    var $StartDate;
    var $EndDate;
    var $MonthsCount;
    var $FirstProcessingDate;
    var $LastProcessingDate;
    var $Attributes;
//End SELECT_Data_Hora_Nome_FRO Variables

//SELECT_Data_Hora_Nome_FRO Class_Initialize Event @4-95636266
    function clsCalendarSELECT_Data_Hora_Nome_FRO($RelativePath, & $Parent) {
        global $CCSLocales;
        global $DefaultDateFormat;
        global $FileName;
        global $Redirect;
        $this->ComponentName = "SELECT_Data_Hora_Nome_FRO";
        $this->Type = "1";
        $this->Visible = True;
        $this->RelativePath = $RelativePath;
        $this->Parent = & $Parent;
        $this->Errors = new clsErrors();
        $CCSForm = CCGetFromGet("ccsForm", "");
        if ($CCSForm == $this->ComponentName) {
            $Redirect = FileName . "?" .  CCGetQueryString("All", array("ccsForm"));
            $this->Visible = false;
            return;
        }
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsSELECT_Data_Hora_Nome_FRODataSource($this);
        $this->ds = & $this->DataSource;
        $this->FirstWeekDay = $CCSLocales->GetFormatInfo("FirstWeekDay");
        $this->MonthsInRow = 1;
        $this->MonthsCount = 1;


        $this->Navigator = & new clsCalendarNavigator($this->ComponentName, "Navigator", $this->Type, 10, $this);
        $this->DayOfWeek = & new clsControl(ccsLabel, "DayOfWeek", "DayOfWeek", ccsDate, array("wi"), CCGetRequestParam("DayOfWeek", ccsGet, NULL), $this);
        $this->MonthDate = & new clsControl(ccsLabel, "MonthDate", "MonthDate", ccsDate, array("mmmm", ", ", "yyyy"), CCGetRequestParam("MonthDate", ccsGet, NULL), $this);
        $this->DayNumber = & new clsControl(ccsLabel, "DayNumber", "DayNumber", ccsDate, array("d"), CCGetRequestParam("DayNumber", ccsGet, NULL), $this);
        $this->EventTime = & new clsControl(ccsLabel, "EventTime", "EventTime", ccsDate, array("HH", ":", "nn"), CCGetRequestParam("EventTime", ccsGet, NULL), $this);
        $this->EventDescription = & new clsControl(ccsLabel, "EventDescription", "EventDescription", ccsText, "", CCGetRequestParam("EventDescription", ccsGet, NULL), $this);
        $this->Now = CCGetDateArray();
        $this->CalendarStyles["WeekdayName"] = "class=\"CalendarWeekdayName\"";
        $this->CalendarStyles["WeekendName"] = "class=\"CalendarWeekendName\"";
        $this->CalendarStyles["Day"] = "class=\"CalendarDay\"";
        $this->CalendarStyles["Weekend"] = "class=\"CalendarWeekend\"";
        $this->CalendarStyles["Today"] = "class=\"CalendarToday\"";
        $this->CalendarStyles["WeekendToday"] = "class=\"CalendarWeekendToday\"";
        $this->CalendarStyles["OtherMonthDay"] = "class=\"CalendarOtherMonthDay\"";
        $this->CalendarStyles["OtherMonthToday"] = "class=\"CalendarOtherMonthToday\"";
        $this->CalendarStyles["OtherMonthWeekend"] = "class=\"CalendarOtherMonthWeekend\"";
        $this->CalendarStyles["OtherMonthWeekendToday"] = "class=\"CalendarOtherMonthWeekendToday\"";
    }
//End SELECT_Data_Hora_Nome_FRO Class_Initialize Event

//Initialize Method @4-24A58114
    function Initialize()
    {
        if(!$this->Visible) return;
        $this->DataSource->SetOrder("", "");
        $this->CurrentDate = $this->Now;
        if ($FullDate = CCGetFromGet($this->ComponentName . "Date", "")) {
            @list($year,$month) = split("-", $FullDate, 2);
        } else {
            $year = CCGetFromGet($this->ComponentName . "Year", "");
            $month = CCGetFromGet($this->ComponentName . "Month", "");
        }
        if (is_numeric($year) &&  $year >=101 && $year <=9999)
            $this->CurrentDate[ccsYear] = $year;
        if (is_numeric($month) &&  $month >=1 && $month <=12)
            $this->CurrentDate[ccsMonth] = $month;
        $this->CurrentDate[ccsDay] = 1;
        $this->CalculateCalendarPeriod();
    }
//End Initialize Method

//Show Method @4-DE84CFE8
    function Show () {
        global $Tpl;
        global $CCSLocales;
        global $DefaultDateFormat;
        if(!$this->Visible) return;

        $this->CalculateCalendarPeriod();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->DataSource->Prepare();
        $this->DataSource->Open();

        while ($this->DataSource->next_record()) {
            $DateField = CCParseDate($this->DataSource->f("Data"), array("yyyy", "-", "mm", "-", "dd"));
            if (!is_array($DateField)) continue;
            if (CCCompareValues($DateField, $this->StartDate, ccsDate) >= 0 && CCCompareValues($DateField, $this->EndDate , ccsDate) <= 0) {
                $this->DataSource->SetValues();
                $Event = new clsEventSELECT_Data_Hora_Nome_FRO();
                $Event->_Time = CCParseDate($this->DataSource->f("Hora"), array("HH", ":", "nn", ":", "ss"));
                $Event->EventTime = $this->DataSource->EventTime->GetValue();
                $Event->EventDescription = $this->DataSource->EventDescription->GetValue();
                $Event->Attributes = $this->Attributes->GetAsArray();
                $datestr = CCFormatDate($DateField, array("yyyy","mm","dd"));
                if(!isset($this->Events[$datestr])) $this->Events[$datestr] = array();
                $this->Events[$datestr][] = $Event;
            }
        }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) return;
        $this->Attributes->Show();

        $CalendarBlock = "Calendar " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $CalendarBlock;
        $this->Errors->AddErrors($this->DataSource->Errors);
        if($this->Errors->Count()) {
            $Tpl->replaceblock("", $this->Errors->ToString());
            $Tpl->block_path = $ParentPath;
            return;
        } else {
            $month = 0;
            $this->CurrentProcessingDate = $this->FirstProcessingDate;
            $this->NextProcessingDate = CCDateAdd($this->CurrentProcessingDate, "1month");
            $this->PrevProcessingDate = CCDateAdd($this->CurrentProcessingDate, "-1month");
            $Tpl->block_path = $ParentPath . "/" . $CalendarBlock . "/Month";
            while ($this->MonthsCount > $month++) {
                $this->ShowMonth();
                if(($this->MonthsCount != $month) && ($month % $this->MonthsInRow == 0)) {
                    $this->Attributes->Show();
                    $Tpl->SetVar("MonthsInRow", $this->MonthsInRow);
                    $Tpl->block_path = $ParentPath . "/" . $CalendarBlock;
                    $Tpl->ParseTo("MonthsRowSeparator", true, "Month");
                    $Tpl->block_path = $ParentPath . "/" . $CalendarBlock . "/Month";
                }
                $Tpl->SetBlockVar("Week", "");
                $Tpl->SetBlockVar("Week/Day", "");
                $this->ProcessNextDate(CCDateAdd($this->NextProcessingDate, "+1month"));
            }
            $this->CurrentProcessingDate = $this->FirstProcessingDate;
            $this->NextProcessingDate = CCDateAdd($this->CurrentProcessingDate, "1month");
            $this->PrevProcessingDate = CCDateAdd($this->CurrentProcessingDate, "-1month");
            $Tpl->SetVar("MonthsInRow", $this->MonthsInRow);
            $Tpl->block_path = $ParentPath . "/" . $CalendarBlock;
            $this->Navigator->CurrentDate = $this->CurrentDate;
            $this->Navigator->PrevProcessingDate = $this->PrevProcessingDate;
            $this->Navigator->NextProcessingDate = $this->NextProcessingDate;
            $this->Navigator->Show();
            $Tpl->Parse();
        }
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

//SELECT_Data_Hora_Nome_FRO ShowMonth Method @4-F7520BAE
    function ShowMonth () {
        global $Tpl;
        global $CCSLocales;
        global $DefaultDateFormat;
        $ParentPath = $Tpl->block_path;
        $OldCurrentProcessingDate = $this->CurrentProcessingDate;
        $OldNextProcessingDate = $this->NextProcessingDate;
        $OldPrevProcessingDate = $this->PrevProcessingDate;
        $FirstMonthDate = CCParseDate(CCFormatDate($this->CurrentProcessingDate, array("yyyy", "-", "mm","-01 00:00:00")), array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $LastMonthDate = CCDateAdd($FirstMonthDate, "+1month -1second");
        $Days = (CCFormatDate($FirstMonthDate, array("w")) - $this->FirstWeekDay + 6) % 7;
        $FirstShowedDate = CCDateAdd($FirstMonthDate, "-" . $Days . "day");
        $Days += $LastMonthDate[ccsDay];
        $Days += ($this->FirstWeekDay  - CCFormatDate($LastMonthDate, array("w")) + 7) % 7;
        $this->CurrentProcessingDate =  $FirstShowedDate;
        $this->PrevProcessingDate =  CCDateAdd($FirstShowedDate, "-1day");
        $this->NextProcessingDate =  CCDateAdd($FirstShowedDate, "+1day");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowMonth", $this);
        $this->Attributes->Show();
        $ShowedDays = 0;
        $WeekDay = CCFormatDate($this->CurrentProcessingDate, array("w"));
        while($ShowedDays < $Days) {
            if ($ShowedDays % 7 == 0) {
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowWeek", $this);
                $this->Attributes->Show();
            }
            $this->IsCurrentMonth = $this->CurrentProcessingDate[ccsMonth] == $OldCurrentProcessingDate[ccsMonth];
            $this->SetCurrentStyle("Day", $WeekDay);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowDay", $this);
            $this->Attributes->Show();
            $datestr = CCFormatDate($this->CurrentProcessingDate, array("yyyy","mm","dd"));
            $Tpl->block_path = $ParentPath . "/Week/Day/EventRow";
            $Tpl->SetBlockVar("", "");
            if (isset($this->Events[$datestr])) {
                uasort($this->Events[$datestr], array($this, "CompareEventTime"));
                foreach ($this->Events[$datestr] as $key=>$event) {
                    $Tpl->block_path = $ParentPath . "/Week/Day/EventRow";
                    $this->Attributes->AddFromArray($this->Events[$datestr][$key]->Attributes);
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowEvent", $this);
                    $this->EventTime->SetValue($event->EventTime);
                    $this->EventDescription->SetValue($event->EventDescription);
                    $this->EventTime->Show();
                    $this->EventDescription->Show();
                    $this->Attributes->Show();
                    $Tpl->Parse("", true);
                }
            } else {
            }
            $Tpl->block_path = $ParentPath . "/Week/Day";
            $this->DayNumber->SetValue($this->CurrentProcessingDate);
            $this->DayNumber->Show();
            $this->Attributes->Show();
            $Tpl->SetVar("Style", $this->CurrentStyle);
            $Tpl->Parse("", true);
            $ShowedDays++;
            if ($ShowedDays and $ShowedDays % 7 == 0) {
                $Tpl->block_path = $ParentPath . "/Week";
                $this->Attributes->Show();
                $Tpl->Parse("", true);
                $Tpl->SetBlockVar("Day", "");
            }
            $this->ProcessNextDate(CCDateAdd($this->NextProcessingDate, "+1day"));
            $WeekDay = $WeekDay == 7 ? 1 : $WeekDay + 1;
        }
        $Tpl->block_path = $ParentPath . "/WeekDays";
        $Tpl->SetBlockVar("","");
        $WeekDay = CCFormatDate($this->CurrentProcessingDate, array("w"));
        $ShowedDays = 0;
        $this->CurrentProcessingDate =  $FirstShowedDate;
        $this->PrevProcessingDate =  CCDateAdd($FirstShowedDate, "-1day");
        $this->NextProcessingDate =  CCDateAdd($FirstShowedDate, "+1day");
        while($ShowedDays < 7) {
            $this->Attributes->Show();
            $this->DayOfWeek->SetValue($this->CurrentProcessingDate);
            $this->DayOfWeek->Show();
            $this->SetCurrentStyle("WeekDay", $WeekDay);
            $Tpl->SetVar("Style", $this->CurrentStyle);
            $Tpl->Parse("", true);
            $WeekDay = $WeekDay == 7 ? 1 : $WeekDay + 1;
            $this->ProcessNextDate(CCDateAdd($this->NextProcessingDate, "+1day"));
            $ShowedDays++;
        }
        $Tpl->block_path = $ParentPath;
        $this->CurrentProcessingDate = $OldCurrentProcessingDate;
        $this->NextProcessingDate = $OldNextProcessingDate;
        $this->PrevProcessingDate = $OldPrevProcessingDate;
        $this->MonthDate->SetValue($this->CurrentProcessingDate);
        $this->MonthDate->Show();
        $Tpl->Parse("", true);
        $Tpl->block_path = $ParentPath;
    }
//End SELECT_Data_Hora_Nome_FRO ShowMonth Method

//SELECT_Data_Hora_Nome_FRO ProcessNextDate Method @4-67D24A68
    function ProcessNextDate($NewDate) {
        $this->PrevProcessingDate = $this->CurrentProcessingDate;
        $this->CurrentProcessingDate = $this->NextProcessingDate;
        $this->NextProcessingDate = $NewDate;
    }
//End SELECT_Data_Hora_Nome_FRO ProcessNextDate Method

//SELECT_Data_Hora_Nome_FRO CalculateCalendarPeriod Method @4-8917C348
    function CalculateCalendarPeriod() {
        $this->FirstProcessingDate = CCParseDate(CCFormatDate($this->CurrentDate, array("yyyy","-","mm","-01 00:00:00")), array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $Days = (CCFormatDate($this->FirstProcessingDate, array("w")) - $this->FirstWeekDay + 6) % 7;
        $this->StartDate = CCDateAdd($this->FirstProcessingDate, "-" . $Days . "day");
        $this->LastProcessingDate = CCDateAdd($this->FirstProcessingDate, "1month -1second");
        $Days = ($this->FirstWeekDay - CCFormatDate($this->LastProcessingDate, array("w")) + 7) % 7;
        $this->EndDate = CCDateAdd($this->LastProcessingDate, $Days . "day");
    }
//End SELECT_Data_Hora_Nome_FRO CalculateCalendarPeriod Method

//SELECT_Data_Hora_Nome_FRO SetCurrentStyle Method @4-FDD58228
    function SetCurrentStyle ($scope, $weekday="") {
        $Result="";
        switch ($scope) {
            case "WeekDay":
                if ($weekday == 1 || $weekday == 7)
                    $Result = "WeekendName";
                else
                    $Result = "WeekdayName";
                break;
            case "Day":
                $IsWeekend = $weekday == 1 || $weekday == 7;
                $IsCurrentDay = $this->CurrentProcessingDate[ccsYear] == $this->Now[ccsYear] &&
                    $this->CurrentProcessingDate[ccsMonth] == $this->Now[ccsMonth] &&
                    $this->CurrentProcessingDate[ccsDay] == $this->Now[ccsDay];
                if($IsCurrentDay)
                    $Result = "Today";
                if($IsWeekend) 
                    $Result = "Weekend" . $Result;
                elseif (!$Result) 
                    $Result = "Day";
                if (!$this->IsCurrentMonth)
                    $Result = "OtherMonth" . $Result;
                break;
        }
        $this->CurrentStyle = isset($this->CalendarStyles[$Result]) ? $this->CalendarStyles[$Result] : "";
    }
//End SELECT_Data_Hora_Nome_FRO SetCurrentStyle Method

//SELECT_Data_Hora_Nome_FRO CompareEventTime Method @4-47330780
    function CompareEventTime($val1, $val2) {
        $time1 = is_a($val1, "clsEventSELECT_Data_Hora_Nome_FRO") && is_array($val1->_Time) ? $val1->_Time[ccsHour] * 3600 + $val1->_Time[ccsMinute] * 60 + $val1->_Time[ccsSecond] : 0;
        $time2 = is_a($val2, "clsEventSELECT_Data_Hora_Nome_FRO") && is_array($val2->_Time) ? $val2->_Time[ccsHour] * 3600 + $val2->_Time[ccsMinute] * 60 + $val2->_Time[ccsSecond] : 0;
        if ($time1 == $time2)
            return 0;
        return $time1 > $time2 ? 1 : -1;
    }
//End SELECT_Data_Hora_Nome_FRO CompareEventTime Method

} //End SELECT_Data_Hora_Nome_FRO Class @4-FCB6E20C

class clsSELECT_Data_Hora_Nome_FRODataSource extends clsDBConnection1 {  //SELECT_Data_Hora_Nome_FRODataSource Class @4-AEA78C3D

//DataSource Variables @4-24D12370
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $wp;


    // Datasource fields
    var $EventTime;
    var $EventDescription;
//End DataSource Variables

//DataSourceClass_Initialize Event @4-6BAB0F2E
    function clsSELECT_Data_Hora_Nome_FRODataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "";
        $this->Initialize();
        $this->EventTime = new clsField("EventTime", ccsDate, array("HH", ":", "nn", ":", "ss"));
        
        $this->EventDescription = new clsField("EventDescription", ccsText, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @4-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @4-14D6CD9D
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
    }
//End Prepare Method

//Open Method @4-0BB2BE19
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT Data, Hora, Nome\n" .
        "FROM marcacoes, pacientes \n" .
        "WHERE marcacoes.Paciente_id = pacientes.Id";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @4-7DE91FE5
    function SetValues()
    {
        $this->EventTime->SetDBValue(trim($this->f("Hora")));
        $this->EventDescription->SetDBValue($this->f("Nome"));
    }
//End SetValues Method

} //End SELECT_Data_Hora_Nome_FRODataSource Class @4-FCB6E20C

//Initialize Page @1-F07483F5
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";
$Attributes = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = FileName;
$Redirect = "";
$TemplateFileName = "agenda.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-8652D439
include_once("./agenda_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-C8EA01D4
$DBConnection1 = new clsDBConnection1();
$MainPage->Connections["Connection1"] = & $DBConnection1;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$Header = & new clsHeader("", "Header", $MainPage);
$Header->Initialize();
$Footer = & new clsFooter("", "Footer", $MainPage);
$Footer->Initialize();
$SELECT_Data_Hora_Nome_FRO = & new clsCalendarSELECT_Data_Hora_Nome_FRO("", $MainPage);
$MainPage->Header = & $Header;
$MainPage->Footer = & $Footer;
$MainPage->SELECT_Data_Hora_Nome_FRO = & $SELECT_Data_Hora_Nome_FRO;
$SELECT_Data_Hora_Nome_FRO->Initialize();

BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize", $MainPage);

if ($Charset) {
    header("Content-Type: " . $ContentType . "; charset=" . $Charset);
} else {
    header("Content-Type: " . $ContentType);
}
//End Initialize Objects

//Initialize HTML Template @1-0C2E73E8
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView", $MainPage);
$Tpl = new clsTemplate($FileEncoding, $TemplateEncoding);
$Tpl->LoadTemplate(PathToCurrentPage . $TemplateFileName, $BlockToParse, "CP1252", "replace");
$Tpl->block_path = "/$BlockToParse";
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow", $MainPage);
$Attributes->SetValue("pathToRoot", "");
$Attributes->Show();
//End Initialize HTML Template

//Execute Components @1-351F985C
$Header->Operations();
$Footer->Operations();
//End Execute Components

//Go to destination page @1-6949F20C
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBConnection1->close();
    header("Location: " . $Redirect);
    $Header->Class_Terminate();
    unset($Header);
    $Footer->Class_Terminate();
    unset($Footer);
    unset($SELECT_Data_Hora_Nome_FRO);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-04D9B422
$Header->Show();
$Footer->Show();
$SELECT_Data_Hora_Nome_FRO->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", implode(array("<center><font face=\"Aria", "l\"><small>&#71;e&#110;&", "#101;&#114;ated <!-- CCS --", ">&#119;&#105;&#116;&#104;", " <!-- SCC -->&#67;od&#101", ";&#67;&#104;&#97;rge <!-", "- CCS -->St&#117;di&#11", "1;.</small></font></cente", "r>"), "") . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", implode(array("<center><font face=\"Aria", "l\"><small>&#71;e&#110;&", "#101;&#114;ated <!-- CCS --", ">&#119;&#105;&#116;&#104;", " <!-- SCC -->&#67;od&#101", ";&#67;&#104;&#97;rge <!-", "- CCS -->St&#117;di&#11", "1;.</small></font></cente", "r>"), "") . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= implode(array("<center><font face=\"Aria", "l\"><small>&#71;e&#110;&", "#101;&#114;ated <!-- CCS --", ">&#119;&#105;&#116;&#104;", " <!-- SCC -->&#67;od&#101", ";&#67;&#104;&#97;rge <!-", "- CCS -->St&#117;di&#11", "1;.</small></font></cente", "r>"), "");
}
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-F95CBAC7
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBConnection1->close();
$Header->Class_Terminate();
unset($Header);
$Footer->Class_Terminate();
unset($Footer);
unset($SELECT_Data_Hora_Nome_FRO);
unset($Tpl);
//End Unload Page


?>
