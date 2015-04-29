<?php
//Include Common Files @1-A2FF7A44
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "pacientes_list.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsRecordpacientesSearch { //pacientesSearch Class @2-9F70EC2C

//Variables @2-D6FF3E86

    // Public variables
    var $ComponentType = "Record";
    var $ComponentName;
    var $Parent;
    var $HTMLFormAction;
    var $PressedButton;
    var $Errors;
    var $ErrorBlock;
    var $FormSubmitted;
    var $FormEnctype;
    var $Visible;
    var $IsEmpty;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $RelativePath = "";

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $ReadAllowed   = false;
    var $EditMode      = false;
    var $ds;
    var $DataSource;
    var $ValidatingControls;
    var $Controls;
    var $Attributes;

    // Class variables
//End Variables

//Class_Initialize Event @2-938F2297
    function clsRecordpacientesSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record pacientesSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "pacientesSearch";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_DoSearch = & new clsButton("Button_DoSearch", $Method, $this);
            $this->s_Nome = & new clsControl(ccsTextBox, "s_Nome", "s_Nome", ccsText, "", CCGetRequestParam("s_Nome", $Method, NULL), $this);
            $this->s_Telefone = & new clsControl(ccsTextBox, "s_Telefone", "s_Telefone", ccsInteger, "", CCGetRequestParam("s_Telefone", $Method, NULL), $this);
            $this->s_Telemovel = & new clsControl(ccsTextBox, "s_Telemovel", "s_Telemovel", ccsInteger, "", CCGetRequestParam("s_Telemovel", $Method, NULL), $this);
        }
    }
//End Class_Initialize Event

//Validate Method @2-C59B55B5
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_Nome->Validate() && $Validation);
        $Validation = ($this->s_Telefone->Validate() && $Validation);
        $Validation = ($this->s_Telemovel->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_Nome->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_Telefone->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_Telemovel->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-FE26C580
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_Nome->Errors->Count());
        $errors = ($errors || $this->s_Telefone->Errors->Count());
        $errors = ($errors || $this->s_Telemovel->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//MasterDetail @2-ED598703
function SetPrimaryKeys($keyArray)
{
    $this->PrimaryKeys = $keyArray;
}
function GetPrimaryKeys()
{
    return $this->PrimaryKeys;
}
function GetPrimaryKey($keyName)
{
    return $this->PrimaryKeys[$keyName];
}
//End MasterDetail

//Operation Method @2-40FD4887
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        if(!$this->FormSubmitted) {
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = "Button_DoSearch";
            if($this->Button_DoSearch->Pressed) {
                $this->PressedButton = "Button_DoSearch";
            }
        }
        $Redirect = "pacientes_list.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "pacientes_list.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @2-4D937F40
    function Show()
    {
        global $CCSUseAmp;
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_Nome->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_Telefone->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_Telemovel->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->Button_DoSearch->Show();
        $this->s_Nome->Show();
        $this->s_Telefone->Show();
        $this->s_Telemovel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End pacientesSearch Class @2-FCB6E20C

class clsGridpacientes { //pacientes class @7-DD04FD36

//Variables @7-D4444F6C

    // Public variables
    var $ComponentType = "Grid";
    var $ComponentName;
    var $Visible;
    var $Errors;
    var $ErrorBlock;
    var $ds;
    var $DataSource;
    var $PageSize;
    var $IsEmpty;
    var $ForceIteration = false;
    var $HasRecord = false;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;
    var $RowNumber;
    var $ControlsVisible = array();

    var $CCSEvents = "";
    var $CCSEventResult;

    var $RelativePath = "";
    var $Attributes;

    // Grid Controls
    var $StaticControls;
    var $RowControls;
    var $Sorter_Id;
    var $Sorter_Nome;
    var $Sorter_Morada;
    var $Sorter_Telefone;
    var $Sorter_Telemovel;
    var $Sorter_Email;
    var $Sorter_Profissao;
    var $Sorter_Convencoes;
    var $Sorter_Indicacao;
//End Variables

//Class_Initialize Event @7-61CF8C47
    function clsGridpacientes($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "pacientes";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid pacientes";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clspacientesDataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 20;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;
        $this->SorterName = CCGetParam("pacientesOrder", "");
        $this->SorterDirection = CCGetParam("pacientesDir", "");

        $this->Detail = & new clsControl(ccsLink, "Detail", "Detail", ccsText, "", CCGetRequestParam("Detail", ccsGet, NULL), $this);
        $this->Detail->Page = "pacientes_maint.php";
        $this->Id = & new clsControl(ccsLabel, "Id", "Id", ccsInteger, "", CCGetRequestParam("Id", ccsGet, NULL), $this);
        $this->Nome = & new clsControl(ccsLabel, "Nome", "Nome", ccsText, "", CCGetRequestParam("Nome", ccsGet, NULL), $this);
        $this->Morada = & new clsControl(ccsLabel, "Morada", "Morada", ccsText, "", CCGetRequestParam("Morada", ccsGet, NULL), $this);
        $this->Telefone = & new clsControl(ccsLabel, "Telefone", "Telefone", ccsInteger, "", CCGetRequestParam("Telefone", ccsGet, NULL), $this);
        $this->Telemovel = & new clsControl(ccsLabel, "Telemovel", "Telemovel", ccsInteger, "", CCGetRequestParam("Telemovel", ccsGet, NULL), $this);
        $this->Email = & new clsControl(ccsLabel, "Email", "Email", ccsText, "", CCGetRequestParam("Email", ccsGet, NULL), $this);
        $this->Profissao = & new clsControl(ccsLabel, "Profissao", "Profissao", ccsText, "", CCGetRequestParam("Profissao", ccsGet, NULL), $this);
        $this->Convencoes = & new clsControl(ccsLabel, "Convencoes", "Convencoes", ccsText, "", CCGetRequestParam("Convencoes", ccsGet, NULL), $this);
        $this->Indicacao = & new clsControl(ccsLabel, "Indicacao", "Indicacao", ccsText, "", CCGetRequestParam("Indicacao", ccsGet, NULL), $this);
        $this->Sorter_Id = & new clsSorter($this->ComponentName, "Sorter_Id", $FileName, $this);
        $this->Sorter_Nome = & new clsSorter($this->ComponentName, "Sorter_Nome", $FileName, $this);
        $this->Sorter_Morada = & new clsSorter($this->ComponentName, "Sorter_Morada", $FileName, $this);
        $this->Sorter_Telefone = & new clsSorter($this->ComponentName, "Sorter_Telefone", $FileName, $this);
        $this->Sorter_Telemovel = & new clsSorter($this->ComponentName, "Sorter_Telemovel", $FileName, $this);
        $this->Sorter_Email = & new clsSorter($this->ComponentName, "Sorter_Email", $FileName, $this);
        $this->Sorter_Profissao = & new clsSorter($this->ComponentName, "Sorter_Profissao", $FileName, $this);
        $this->Sorter_Convencoes = & new clsSorter($this->ComponentName, "Sorter_Convencoes", $FileName, $this);
        $this->Sorter_Indicacao = & new clsSorter($this->ComponentName, "Sorter_Indicacao", $FileName, $this);
        $this->Navigator = & new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
    }
//End Class_Initialize Event

//Initialize Method @7-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @7-5B0C0560
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urls_Telefone"] = CCGetFromGet("s_Telefone", NULL);
        $this->DataSource->Parameters["urls_Telemovel"] = CCGetFromGet("s_Telemovel", NULL);
        $this->DataSource->Parameters["urls_Nome"] = CCGetFromGet("s_Nome", NULL);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $this->DataSource->Prepare();
        $this->DataSource->Open();
        $this->HasRecord = $this->DataSource->has_next_record();
        $this->IsEmpty = ! $this->HasRecord;
        $this->Attributes->Show();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        if (!$this->IsEmpty) {
            $this->ControlsVisible["Detail"] = $this->Detail->Visible;
            $this->ControlsVisible["Id"] = $this->Id->Visible;
            $this->ControlsVisible["Nome"] = $this->Nome->Visible;
            $this->ControlsVisible["Morada"] = $this->Morada->Visible;
            $this->ControlsVisible["Telefone"] = $this->Telefone->Visible;
            $this->ControlsVisible["Telemovel"] = $this->Telemovel->Visible;
            $this->ControlsVisible["Email"] = $this->Email->Visible;
            $this->ControlsVisible["Profissao"] = $this->Profissao->Visible;
            $this->ControlsVisible["Convencoes"] = $this->Convencoes->Visible;
            $this->ControlsVisible["Indicacao"] = $this->Indicacao->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->Detail->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                $this->Detail->Parameters = CCAddParam($this->Detail->Parameters, "Id", $this->DataSource->f("Id"));
                $this->Id->SetValue($this->DataSource->Id->GetValue());
                $this->Nome->SetValue($this->DataSource->Nome->GetValue());
                $this->Morada->SetValue($this->DataSource->Morada->GetValue());
                $this->Telefone->SetValue($this->DataSource->Telefone->GetValue());
                $this->Telemovel->SetValue($this->DataSource->Telemovel->GetValue());
                $this->Email->SetValue($this->DataSource->Email->GetValue());
                $this->Profissao->SetValue($this->DataSource->Profissao->GetValue());
                $this->Convencoes->SetValue($this->DataSource->Convencoes->GetValue());
                $this->Indicacao->SetValue($this->DataSource->Indicacao->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->Detail->Show();
                $this->Id->Show();
                $this->Nome->Show();
                $this->Morada->Show();
                $this->Telefone->Show();
                $this->Telemovel->Show();
                $this->Email->Show();
                $this->Profissao->Show();
                $this->Convencoes->Show();
                $this->Indicacao->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
            }
        }
        else { // Show NoRecords block if no records are found
            $this->Attributes->Show();
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $this->Navigator->PageNumber = $this->DataSource->AbsolutePage;
        $this->Navigator->PageSize = $this->PageSize;
        if ($this->DataSource->RecordsCount == "CCS not counted")
            $this->Navigator->TotalPages = $this->DataSource->AbsolutePage + ($this->DataSource->next_record() ? 1 : 0);
        else
            $this->Navigator->TotalPages = $this->DataSource->PageCount();
        if ($this->Navigator->TotalPages <= 1) {
            $this->Navigator->Visible = false;
        }
        $this->Sorter_Id->Show();
        $this->Sorter_Nome->Show();
        $this->Sorter_Morada->Show();
        $this->Sorter_Telefone->Show();
        $this->Sorter_Telemovel->Show();
        $this->Sorter_Email->Show();
        $this->Sorter_Profissao->Show();
        $this->Sorter_Convencoes->Show();
        $this->Sorter_Indicacao->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @7-3DE68695
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Nome->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Morada->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Telefone->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Telemovel->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Email->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Profissao->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Convencoes->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Indicacao->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End pacientes Class @7-FCB6E20C

class clspacientesDataSource extends clsDBConnection1 {  //pacientesDataSource Class @7-D43A7CDD

//DataSource Variables @7-2842FE1F
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $Id;
    var $Nome;
    var $Morada;
    var $Telefone;
    var $Telemovel;
    var $Email;
    var $Profissao;
    var $Convencoes;
    var $Indicacao;
//End DataSource Variables

//DataSourceClass_Initialize Event @7-05E10F00
    function clspacientesDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid pacientes";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->Nome = new clsField("Nome", ccsText, "");
        
        $this->Morada = new clsField("Morada", ccsText, "");
        
        $this->Telefone = new clsField("Telefone", ccsInteger, "");
        
        $this->Telemovel = new clsField("Telemovel", ccsInteger, "");
        
        $this->Email = new clsField("Email", ccsText, "");
        
        $this->Profissao = new clsField("Profissao", ccsText, "");
        
        $this->Convencoes = new clsField("Convencoes", ccsText, "");
        
        $this->Indicacao = new clsField("Indicacao", ccsText, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @7-B1A9F804
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "Nome";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_Id" => array("Id", ""), 
            "Sorter_Nome" => array("Nome", ""), 
            "Sorter_Morada" => array("Morada", ""), 
            "Sorter_Telefone" => array("Telefone", ""), 
            "Sorter_Telemovel" => array("Telemovel", ""), 
            "Sorter_Email" => array("Email", ""), 
            "Sorter_Profissao" => array("Profissao", ""), 
            "Sorter_Convencoes" => array("Convencoes", ""), 
            "Sorter_Indicacao" => array("Indicacao", "")));
    }
//End SetOrder Method

//Prepare Method @7-0733897D
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_Telefone", ccsInteger, "", "", $this->Parameters["urls_Telefone"], "", false);
        $this->wp->AddParameter("2", "urls_Telemovel", ccsInteger, "", "", $this->Parameters["urls_Telemovel"], "", false);
        $this->wp->AddParameter("3", "urls_Nome", ccsText, "", "", $this->Parameters["urls_Nome"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "Telefone", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "Telemovel", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opContains, "Nome", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->Where = $this->wp->opAND(
             false, $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]), 
             $this->wp->Criterion[3]);
    }
//End Prepare Method

//Open Method @7-A1A48589
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM pacientes";
        $this->SQL = "SELECT Id, Nome, Morada, Telefone, Telemovel, Email, Profissao, Convencoes, Indicacao \n" .
        "FROM pacientes {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        if ($this->CountSQL) 
            $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        else
            $this->RecordsCount = "CCS not counted";
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @7-7738BA12
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("Id")));
        $this->Nome->SetDBValue($this->f("Nome"));
        $this->Morada->SetDBValue($this->f("Morada"));
        $this->Telefone->SetDBValue(trim($this->f("Telefone")));
        $this->Telemovel->SetDBValue(trim($this->f("Telemovel")));
        $this->Email->SetDBValue($this->f("Email"));
        $this->Profissao->SetDBValue($this->f("Profissao"));
        $this->Convencoes->SetDBValue($this->f("Convencoes"));
        $this->Indicacao->SetDBValue($this->f("Indicacao"));
    }
//End SetValues Method

} //End pacientesDataSource Class @7-FCB6E20C

//Include Page implementation @44-3DD2EFDC
include_once(RelativePath . "/Header.php");
//End Include Page implementation

//Include Page implementation @45-58DBA1E3
include_once(RelativePath . "/Footer.php");
//End Include Page implementation

//Initialize Page @1-21D101FA
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
$TemplateFileName = "pacientes_list.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-F6DD91D4
include_once("./pacientes_list_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-6091FA68
$DBConnection1 = new clsDBConnection1();
$MainPage->Connections["Connection1"] = & $DBConnection1;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$pacientesSearch = & new clsRecordpacientesSearch("", $MainPage);
$pacientes = & new clsGridpacientes("", $MainPage);
$Header = & new clsHeader("", "Header", $MainPage);
$Header->Initialize();
$Footer = & new clsFooter("", "Footer", $MainPage);
$Footer->Initialize();
$MainPage->pacientesSearch = & $pacientesSearch;
$MainPage->pacientes = & $pacientes;
$MainPage->Header = & $Header;
$MainPage->Footer = & $Footer;
$pacientes->Initialize();

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

//Execute Components @1-5776F143
$pacientesSearch->Operation();
$Header->Operations();
$Footer->Operations();
//End Execute Components

//Go to destination page @1-85204BF4
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBConnection1->close();
    header("Location: " . $Redirect);
    unset($pacientesSearch);
    unset($pacientes);
    $Header->Class_Terminate();
    unset($Header);
    $Footer->Class_Terminate();
    unset($Footer);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-577CC49C
$pacientesSearch->Show();
$pacientes->Show();
$Header->Show();
$Footer->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$ILSH6B2O1B = explode("|", "<center><font face=\"Arial\"><small>&#71;en&|#101;&#114;&#97;&#116;&#101;&#100; <!-- CCS --|>&#119;i&#116;&#104; <!-- CCS -->C&#111;de&#67;|h&#97;r&#103;e <!-- SCC -->S&#116;udi&#111|;.</small></font></center>");
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", join($ILSH6B2O1B,"") . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", join($ILSH6B2O1B,"") . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= join($ILSH6B2O1B,"");
}
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-18C73949
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBConnection1->close();
unset($pacientesSearch);
unset($pacientes);
$Header->Class_Terminate();
unset($Header);
$Footer->Class_Terminate();
unset($Footer);
unset($Tpl);
//End Unload Page


?>
