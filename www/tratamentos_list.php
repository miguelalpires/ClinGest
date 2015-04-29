<?php
//Include Common Files @1-CA40577C
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "tratamentos_list.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsRecordtratamentosSearch { //tratamentosSearch Class @2-3D8199AC

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

//Class_Initialize Event @2-8ED6AB19
    function clsRecordtratamentosSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record tratamentosSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "tratamentosSearch";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_DoSearch = & new clsButton("Button_DoSearch", $Method, $this);
            $this->s_Paciente_id = & new clsControl(ccsListBox, "s_Paciente_id", "s_Paciente_id", ccsInteger, "", CCGetRequestParam("s_Paciente_id", $Method, NULL), $this);
            $this->s_Paciente_id->DSType = dsTable;
            $this->s_Paciente_id->DataSource = new clsDBConnection1();
            $this->s_Paciente_id->ds = & $this->s_Paciente_id->DataSource;
            $this->s_Paciente_id->DataSource->SQL = "SELECT * \n" .
"FROM pacientes {SQL_Where} {SQL_OrderBy}";
            $this->s_Paciente_id->DataSource->Order = "Nome";
            list($this->s_Paciente_id->BoundColumn, $this->s_Paciente_id->TextColumn, $this->s_Paciente_id->DBFormat) = array("Id", "Nome", "");
            $this->s_Paciente_id->DataSource->Order = "Nome";
        }
    }
//End Class_Initialize Event

//Validate Method @2-9B7A43E5
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_Paciente_id->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_Paciente_id->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-F696E2FE
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_Paciente_id->Errors->Count());
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

//Operation Method @2-2E559BB7
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
        $Redirect = "tratamentos_list.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "tratamentos_list.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @2-17EA3B20
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

        $this->s_Paciente_id->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_Paciente_id->Errors->ToString());
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
        $this->s_Paciente_id->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End tratamentosSearch Class @2-FCB6E20C

class clsGridtratamentos { //tratamentos class @5-2964795D

//Variables @5-11D2DDC8

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
    var $Sorter_Data;
    var $Sorter_Valor;
    var $Sorter_Factura;
    var $Sorter_Descricao;
    var $Sorter_Nome;
//End Variables

//Class_Initialize Event @5-B11BF0BE
    function clsGridtratamentos($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "tratamentos";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid tratamentos";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clstratamentosDataSource($this);
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
        $this->SorterName = CCGetParam("tratamentosOrder", "");
        $this->SorterDirection = CCGetParam("tratamentosDir", "");

        $this->Detail = & new clsControl(ccsLink, "Detail", "Detail", ccsText, "", CCGetRequestParam("Detail", ccsGet, NULL), $this);
        $this->Detail->Page = "tratamentos_maint.php";
        $this->Id = & new clsControl(ccsLabel, "Id", "Id", ccsInteger, "", CCGetRequestParam("Id", ccsGet, NULL), $this);
        $this->Data = & new clsControl(ccsLabel, "Data", "Data", ccsDate, array("dd", "-", "mm", "-", "yyyy"), CCGetRequestParam("Data", ccsGet, NULL), $this);
        $this->Valor = & new clsControl(ccsLabel, "Valor", "Valor", ccsSingle, "", CCGetRequestParam("Valor", ccsGet, NULL), $this);
        $this->Factura = & new clsControl(ccsCheckBox, "Factura", "Factura", ccsInteger, "", CCGetRequestParam("Factura", ccsGet, NULL), $this);
        $this->Factura->CheckedValue = $this->Factura->GetParsedValue(1);
        $this->Factura->UncheckedValue = $this->Factura->GetParsedValue(0);
        $this->Descricao = & new clsControl(ccsLabel, "Descricao", "Descricao", ccsText, "", CCGetRequestParam("Descricao", ccsGet, NULL), $this);
        $this->Nome = & new clsControl(ccsLabel, "Nome", "Nome", ccsText, "", CCGetRequestParam("Nome", ccsGet, NULL), $this);
        $this->Sorter_Id = & new clsSorter($this->ComponentName, "Sorter_Id", $FileName, $this);
        $this->Sorter_Data = & new clsSorter($this->ComponentName, "Sorter_Data", $FileName, $this);
        $this->Sorter_Valor = & new clsSorter($this->ComponentName, "Sorter_Valor", $FileName, $this);
        $this->Sorter_Factura = & new clsSorter($this->ComponentName, "Sorter_Factura", $FileName, $this);
        $this->Navigator = & new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
        $this->Sorter_Descricao = & new clsSorter($this->ComponentName, "Sorter_Descricao", $FileName, $this);
        $this->Sorter_Nome = & new clsSorter($this->ComponentName, "Sorter_Nome", $FileName, $this);
    }
//End Class_Initialize Event

//Initialize Method @5-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @5-25DDBA72
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urls_Paciente_id"] = CCGetFromGet("s_Paciente_id", NULL);

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
            $this->ControlsVisible["Data"] = $this->Data->Visible;
            $this->ControlsVisible["Valor"] = $this->Valor->Visible;
            $this->ControlsVisible["Factura"] = $this->Factura->Visible;
            $this->ControlsVisible["Descricao"] = $this->Descricao->Visible;
            $this->ControlsVisible["Nome"] = $this->Nome->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                if(!is_array($this->Factura->Value) && !strlen($this->Factura->Value) && $this->Factura->Value !== false)
                    $this->Factura->SetValue(false);
                $this->Detail->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                $this->Detail->Parameters = CCAddParam($this->Detail->Parameters, "Id", $this->DataSource->f("tratamentos_Id"));
                $this->Id->SetValue($this->DataSource->Id->GetValue());
                $this->Data->SetValue($this->DataSource->Data->GetValue());
                $this->Valor->SetValue($this->DataSource->Valor->GetValue());
                $this->Factura->SetValue($this->DataSource->Factura->GetValue());
                $this->Descricao->SetValue($this->DataSource->Descricao->GetValue());
                $this->Nome->SetValue($this->DataSource->Nome->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->Detail->Show();
                $this->Id->Show();
                $this->Data->Show();
                $this->Valor->Show();
                $this->Factura->Show();
                $this->Descricao->Show();
                $this->Nome->Show();
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
        $this->Sorter_Data->Show();
        $this->Sorter_Valor->Show();
        $this->Sorter_Factura->Show();
        $this->Navigator->Show();
        $this->Sorter_Descricao->Show();
        $this->Sorter_Nome->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @5-B3718B94
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Data->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Valor->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Factura->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Descricao->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Nome->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End tratamentos Class @5-FCB6E20C

class clstratamentosDataSource extends clsDBConnection1 {  //tratamentosDataSource Class @5-42DF3A75

//DataSource Variables @5-314887A4
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $Id;
    var $Data;
    var $Valor;
    var $Factura;
    var $Descricao;
    var $Nome;
//End DataSource Variables

//DataSourceClass_Initialize Event @5-F3014BFC
    function clstratamentosDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid tratamentos";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->Data = new clsField("Data", ccsDate, array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        
        $this->Valor = new clsField("Valor", ccsSingle, "");
        
        $this->Factura = new clsField("Factura", ccsInteger, "");
        
        $this->Descricao = new clsField("Descricao", ccsText, "");
        
        $this->Nome = new clsField("Nome", ccsText, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @5-FA57AD58
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "Data desc";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_Id" => array("tratamentos.Id", ""), 
            "Sorter_Data" => array("Data", ""), 
            "Sorter_Valor" => array("Valor", ""), 
            "Sorter_Factura" => array("Factura", ""), 
            "Sorter_Descricao" => array("Descricao", ""), 
            "Sorter_Nome" => array("Nome", "")));
    }
//End SetOrder Method

//Prepare Method @5-0EDF5226
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_Paciente_id", ccsInteger, "", "", $this->Parameters["urls_Paciente_id"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "tratamentos.Paciente_id", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @5-1D38E861
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM tratamentos LEFT JOIN pacientes ON\n\n" .
        "tratamentos.Paciente_id = pacientes.Id";
        $this->SQL = "SELECT tratamentos.Id AS tratamentos_Id, pacientes.Nome, tratamentos.Data, tratamentos.Valor, tratamentos.Factura, Descricao \n" .
        "FROM tratamentos LEFT JOIN pacientes ON\n" .
        "tratamentos.Paciente_id = pacientes.Id {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        if ($this->CountSQL) 
            $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        else
            $this->RecordsCount = "CCS not counted";
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @5-AF076911
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("tratamentos_Id")));
        $this->Data->SetDBValue(trim($this->f("Data")));
        $this->Valor->SetDBValue(trim($this->f("Valor")));
        $this->Factura->SetDBValue(trim($this->f("Factura")));
        $this->Descricao->SetDBValue($this->f("Descricao"));
        $this->Nome->SetDBValue($this->f("Nome"));
    }
//End SetValues Method

} //End tratamentosDataSource Class @5-FCB6E20C

//Include Page implementation @30-3DD2EFDC
include_once(RelativePath . "/Header.php");
//End Include Page implementation

//Include Page implementation @31-58DBA1E3
include_once(RelativePath . "/Footer.php");
//End Include Page implementation

//Initialize Page @1-63C3522C
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
$TemplateFileName = "tratamentos_list.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-33264FCE
include_once("./tratamentos_list_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-1C51E197
$DBConnection1 = new clsDBConnection1();
$MainPage->Connections["Connection1"] = & $DBConnection1;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$tratamentosSearch = & new clsRecordtratamentosSearch("", $MainPage);
$tratamentos = & new clsGridtratamentos("", $MainPage);
$Header = & new clsHeader("", "Header", $MainPage);
$Header->Initialize();
$Footer = & new clsFooter("", "Footer", $MainPage);
$Footer->Initialize();
$MainPage->tratamentosSearch = & $tratamentosSearch;
$MainPage->tratamentos = & $tratamentos;
$MainPage->Header = & $Header;
$MainPage->Footer = & $Footer;
$tratamentos->Initialize();

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

//Execute Components @1-FFBB6E7A
$tratamentosSearch->Operation();
$Header->Operations();
$Footer->Operations();
//End Execute Components

//Go to destination page @1-0D1AD23D
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBConnection1->close();
    header("Location: " . $Redirect);
    unset($tratamentosSearch);
    unset($tratamentos);
    $Header->Class_Terminate();
    unset($Header);
    $Footer->Class_Terminate();
    unset($Footer);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-5AA739FD
$tratamentosSearch->Show();
$tratamentos->Show();
$Header->Show();
$Footer->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$JCCQF1G4D3J = "<center><font face=\"Arial\"><small>&#71;&#101;n&#101;r&#97;&#116;e&#100; <!-- SCC -->&#119;it&#104; <!-- CCS -->&#67;o&#100;&#101;&#67;ha&#114;&#103;e <!-- SCC -->&#83;tu&#100;i&#111;.</small></font></center>";
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", $JCCQF1G4D3J . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", $JCCQF1G4D3J . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= $JCCQF1G4D3J;
}
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-D2941EB8
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBConnection1->close();
unset($tratamentosSearch);
unset($tratamentos);
$Header->Class_Terminate();
unset($Header);
$Footer->Class_Terminate();
unset($Footer);
unset($Tpl);
//End Unload Page


?>
