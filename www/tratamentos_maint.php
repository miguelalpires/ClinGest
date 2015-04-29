<?php
//Include Common Files @1-9A2AD29A
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "tratamentos_maint.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsRecordtratamentos { //tratamentos Class @2-25F0DE48

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

//Class_Initialize Event @2-7BF040B2
    function clsRecordtratamentos($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record tratamentos/Error";
        $this->DataSource = new clstratamentosDataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "tratamentos";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_Insert = & new clsButton("Button_Insert", $Method, $this);
            $this->Button_Update = & new clsButton("Button_Update", $Method, $this);
            $this->Button_Delete = & new clsButton("Button_Delete", $Method, $this);
            $this->Paciente_id = & new clsControl(ccsListBox, "Paciente_id", "Paciente Id", ccsInteger, "", CCGetRequestParam("Paciente_id", $Method, NULL), $this);
            $this->Paciente_id->DSType = dsTable;
            $this->Paciente_id->DataSource = new clsDBConnection1();
            $this->Paciente_id->ds = & $this->Paciente_id->DataSource;
            $this->Paciente_id->DataSource->SQL = "SELECT * \n" .
"FROM pacientes {SQL_Where} {SQL_OrderBy}";
            list($this->Paciente_id->BoundColumn, $this->Paciente_id->TextColumn, $this->Paciente_id->DBFormat) = array("Id", "Nome", "");
            $this->Paciente_id->Required = true;
            $this->Data = & new clsControl(ccsTextBox, "Data", "Data", ccsDate, array("dd", "-", "mm", "-", "yyyy"), CCGetRequestParam("Data", $Method, NULL), $this);
            $this->Data->Required = true;
            $this->DatePicker_Data = & new clsDatePicker("DatePicker_Data", "tratamentos", "Data", $this);
            $this->Valor = & new clsControl(ccsTextBox, "Valor", "Valor", ccsSingle, "", CCGetRequestParam("Valor", $Method, NULL), $this);
            $this->Valor->Required = true;
            $this->Factura = & new clsControl(ccsCheckBox, "Factura", "Factura", ccsInteger, "", CCGetRequestParam("Factura", $Method, NULL), $this);
            $this->Factura->CheckedValue = $this->Factura->GetParsedValue(1);
            $this->Factura->UncheckedValue = $this->Factura->GetParsedValue(0);
            $this->Descricao = & new clsControl(ccsTextArea, "Descricao", $CCSLocales->GetText("Text1"), ccsText, "", CCGetRequestParam("Descricao", $Method, NULL), $this);
            $this->Descricao->Required = true;
        }
    }
//End Class_Initialize Event

//Initialize Method @2-4F76030F
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlId"] = CCGetFromGet("Id", NULL);
    }
//End Initialize Method

//Validate Method @2-F46FD944
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->Paciente_id->Validate() && $Validation);
        $Validation = ($this->Data->Validate() && $Validation);
        $Validation = ($this->Valor->Validate() && $Validation);
        $Validation = ($this->Factura->Validate() && $Validation);
        $Validation = ($this->Descricao->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->Paciente_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->Data->Errors->Count() == 0);
        $Validation =  $Validation && ($this->Valor->Errors->Count() == 0);
        $Validation =  $Validation && ($this->Factura->Errors->Count() == 0);
        $Validation =  $Validation && ($this->Descricao->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-DA10A766
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Paciente_id->Errors->Count());
        $errors = ($errors || $this->Data->Errors->Count());
        $errors = ($errors || $this->DatePicker_Data->Errors->Count());
        $errors = ($errors || $this->Valor->Errors->Count());
        $errors = ($errors || $this->Factura->Errors->Count());
        $errors = ($errors || $this->Descricao->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
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

//Operation Method @2-9FD7D84D
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->DataSource->Prepare();
        if(!$this->FormSubmitted) {
            $this->EditMode = $this->DataSource->AllParametersSet;
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Insert";
            if($this->Button_Insert->Pressed) {
                $this->PressedButton = "Button_Insert";
            } else if($this->Button_Update->Pressed) {
                $this->PressedButton = "Button_Update";
            } else if($this->Button_Delete->Pressed) {
                $this->PressedButton = "Button_Delete";
            }
        }
        $Redirect = "tratamentos_list.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick", $this->Button_Delete) || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick", $this->Button_Insert) || !$this->InsertRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button_Update") {
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick", $this->Button_Update) || !$this->UpdateRow()) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
        if ($Redirect)
            $this->DataSource->close();
    }
//End Operation Method

//InsertRow Method @2-3D1CAB4C
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->Paciente_id->SetValue($this->Paciente_id->GetValue(true));
        $this->DataSource->Data->SetValue($this->Data->GetValue(true));
        $this->DataSource->Valor->SetValue($this->Valor->GetValue(true));
        $this->DataSource->Factura->SetValue($this->Factura->GetValue(true));
        $this->DataSource->Descricao->SetValue($this->Descricao->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @2-BF04681E
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->Paciente_id->SetValue($this->Paciente_id->GetValue(true));
        $this->DataSource->Data->SetValue($this->Data->GetValue(true));
        $this->DataSource->Valor->SetValue($this->Valor->GetValue(true));
        $this->DataSource->Factura->SetValue($this->Factura->GetValue(true));
        $this->DataSource->Descricao->SetValue($this->Descricao->GetValue(true));
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @2-299D98C3
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete", $this);
        if(!$this->DeleteAllowed) return false;
        $this->DataSource->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete", $this);
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @2-7F34B81C
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

        $this->Paciente_id->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if($this->EditMode) {
            if($this->DataSource->Errors->Count()){
                $this->Errors->AddErrors($this->DataSource->Errors);
                $this->DataSource->Errors->clear();
            }
            $this->DataSource->Open();
            if($this->DataSource->Errors->Count() == 0 && $this->DataSource->next_record()) {
                $this->DataSource->SetValues();
                if(!$this->FormSubmitted){
                    $this->Paciente_id->SetValue($this->DataSource->Paciente_id->GetValue());
                    $this->Data->SetValue($this->DataSource->Data->GetValue());
                    $this->Valor->SetValue($this->DataSource->Valor->GetValue());
                    $this->Factura->SetValue($this->DataSource->Factura->GetValue());
                    $this->Descricao->SetValue($this->DataSource->Descricao->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->Paciente_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Data->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DatePicker_Data->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Valor->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Factura->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Descricao->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DataSource->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Paciente_id->Show();
        $this->Data->Show();
        $this->DatePicker_Data->Show();
        $this->Valor->Show();
        $this->Factura->Show();
        $this->Descricao->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End tratamentos Class @2-FCB6E20C

class clstratamentosDataSource extends clsDBConnection1 {  //tratamentosDataSource Class @2-42DF3A75

//DataSource Variables @2-04B3A7BC
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;

    var $InsertFields = array();
    var $UpdateFields = array();

    // Datasource fields
    var $Paciente_id;
    var $Data;
    var $Valor;
    var $Factura;
    var $Descricao;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-E6017072
    function clstratamentosDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record tratamentos/Error";
        $this->Initialize();
        $this->Paciente_id = new clsField("Paciente_id", ccsInteger, "");
        
        $this->Data = new clsField("Data", ccsDate, array("yyyy", "-", "mm", "-", "dd"));
        
        $this->Valor = new clsField("Valor", ccsSingle, "");
        
        $this->Factura = new clsField("Factura", ccsInteger, "");
        
        $this->Descricao = new clsField("Descricao", ccsText, "");
        

        $this->InsertFields["Paciente_id"] = array("Name" => "Paciente_id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["Data"] = array("Name" => "Data", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["Valor"] = array("Name" => "Valor", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->InsertFields["Factura"] = array("Name" => "Factura", "Value" => "", "DataType" => ccsInteger);
        $this->InsertFields["Descricao"] = array("Name" => "Descricao", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["Paciente_id"] = array("Name" => "Paciente_id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["Data"] = array("Name" => "Data", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["Valor"] = array("Name" => "Valor", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->UpdateFields["Factura"] = array("Name" => "Factura", "Value" => "", "DataType" => ccsInteger);
        $this->UpdateFields["Descricao"] = array("Name" => "Descricao", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
    }
//End DataSourceClass_Initialize Event

//Prepare Method @2-F755E9A7
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlId", ccsInteger, "", "", $this->Parameters["urlId"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "Id", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-A37A6747
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n" .
        "FROM tratamentos {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->PageSize = 1;
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @2-8D957032
    function SetValues()
    {
        $this->Paciente_id->SetDBValue(trim($this->f("Paciente_id")));
        $this->Data->SetDBValue(trim($this->f("Data")));
        $this->Valor->SetDBValue(trim($this->f("Valor")));
        $this->Factura->SetDBValue(trim($this->f("Factura")));
        $this->Descricao->SetDBValue($this->f("Descricao"));
    }
//End SetValues Method

//Insert Method @2-CDE7B54E
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["Paciente_id"]["Value"] = $this->Paciente_id->GetDBValue(true);
        $this->InsertFields["Data"]["Value"] = $this->Data->GetDBValue(true);
        $this->InsertFields["Valor"]["Value"] = $this->Valor->GetDBValue(true);
        $this->InsertFields["Factura"]["Value"] = $this->Factura->GetDBValue(true);
        $this->InsertFields["Descricao"]["Value"] = $this->Descricao->GetDBValue(true);
        $this->SQL = CCBuildInsert("tratamentos", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @2-FF6FAC5F
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["Paciente_id"]["Value"] = $this->Paciente_id->GetDBValue(true);
        $this->UpdateFields["Data"]["Value"] = $this->Data->GetDBValue(true);
        $this->UpdateFields["Valor"]["Value"] = $this->Valor->GetDBValue(true);
        $this->UpdateFields["Factura"]["Value"] = $this->Factura->GetDBValue(true);
        $this->UpdateFields["Descricao"]["Value"] = $this->Descricao->GetDBValue(true);
        $this->SQL = CCBuildUpdate("tratamentos", $this->UpdateFields, $this);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        if (!strlen($this->Where) && $this->Errors->Count() == 0) 
            $this->Errors->addError($CCSLocales->GetText("CCS_CustomOperationError_MissingParameters"));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

//Delete Method @2-A531A052
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM tratamentos";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        if (!strlen($this->Where) && $this->Errors->Count() == 0) 
            $this->Errors->addError($CCSLocales->GetText("CCS_CustomOperationError_MissingParameters"));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete", $this->Parent);
        }
    }
//End Delete Method

} //End tratamentosDataSource Class @2-FCB6E20C

//Include Page implementation @12-3DD2EFDC
include_once(RelativePath . "/Header.php");
//End Include Page implementation

//Include Page implementation @13-58DBA1E3
include_once(RelativePath . "/Footer.php");
//End Include Page implementation

//Initialize Page @1-07CF5103
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
$TemplateFileName = "tratamentos_maint.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-D5925B3B
include_once("./tratamentos_maint_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-8242C05C
$DBConnection1 = new clsDBConnection1();
$MainPage->Connections["Connection1"] = & $DBConnection1;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$tratamentos = & new clsRecordtratamentos("", $MainPage);
$Header = & new clsHeader("", "Header", $MainPage);
$Header->Initialize();
$Footer = & new clsFooter("", "Footer", $MainPage);
$Footer->Initialize();
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

//Execute Components @1-3A5A2F13
$tratamentos->Operation();
$Header->Operations();
$Footer->Operations();
//End Execute Components

//Go to destination page @1-DA783945
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBConnection1->close();
    header("Location: " . $Redirect);
    unset($tratamentos);
    $Header->Class_Terminate();
    unset($Header);
    $Footer->Class_Terminate();
    unset($Footer);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-36BB6B33
$tratamentos->Show();
$Header->Show();
$Footer->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", implode(array("<center><font face=\"Arial\"><small>&", "#71;e&#110;&#101;&#114;&#97;t&#10", "1;d <!-- CCS -->&#119;&#105;t&#104; ", "<!-- SCC -->Cod&#101;Charg&#101; <!", "-- CCS -->&#83;&#116;u&#100;&#1", "05;&#111;.</small></font></cente", "r>"), "") . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", implode(array("<center><font face=\"Arial\"><small>&", "#71;e&#110;&#101;&#114;&#97;t&#10", "1;d <!-- CCS -->&#119;&#105;t&#104; ", "<!-- SCC -->Cod&#101;Charg&#101; <!", "-- CCS -->&#83;&#116;u&#100;&#1", "05;&#111;.</small></font></cente", "r>"), "") . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= implode(array("<center><font face=\"Arial\"><small>&", "#71;e&#110;&#101;&#114;&#97;t&#10", "1;d <!-- CCS -->&#119;&#105;t&#104; ", "<!-- SCC -->Cod&#101;Charg&#101; <!", "-- CCS -->&#83;&#116;u&#100;&#1", "05;&#111;.</small></font></cente", "r>"), "");
}
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-89A5FCD5
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBConnection1->close();
unset($tratamentos);
$Header->Class_Terminate();
unset($Header);
$Footer->Class_Terminate();
unset($Footer);
unset($Tpl);
//End Unload Page


?>
