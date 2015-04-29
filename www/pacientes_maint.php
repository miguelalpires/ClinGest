<?php
//Include Common Files @1-B240D529
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "pacientes_maint.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsRecordpacientes { //pacientes Class @2-633F8F80

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

//Class_Initialize Event @2-55FA21E4
    function clsRecordpacientes($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record pacientes/Error";
        $this->DataSource = new clspacientesDataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "pacientes";
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
            $this->Nome = & new clsControl(ccsTextBox, "Nome", "Nome", ccsText, "", CCGetRequestParam("Nome", $Method, NULL), $this);
            $this->Nome->Required = true;
            $this->Morada = & new clsControl(ccsTextBox, "Morada", "Morada", ccsText, "", CCGetRequestParam("Morada", $Method, NULL), $this);
            $this->Morada->Required = true;
            $this->Telefone = & new clsControl(ccsTextBox, "Telefone", "Telefone", ccsInteger, "", CCGetRequestParam("Telefone", $Method, NULL), $this);
            $this->Telefone->Required = true;
            $this->Telemovel = & new clsControl(ccsTextBox, "Telemovel", "Telemovel", ccsInteger, "", CCGetRequestParam("Telemovel", $Method, NULL), $this);
            $this->Email = & new clsControl(ccsTextBox, "Email", "Email", ccsText, "", CCGetRequestParam("Email", $Method, NULL), $this);
            $this->Profissao = & new clsControl(ccsTextBox, "Profissao", "Profissao", ccsText, "", CCGetRequestParam("Profissao", $Method, NULL), $this);
            $this->Convencoes = & new clsControl(ccsTextBox, "Convencoes", "Convencoes", ccsText, "", CCGetRequestParam("Convencoes", $Method, NULL), $this);
            $this->Indicacao = & new clsControl(ccsTextBox, "Indicacao", "Indicacao", ccsText, "", CCGetRequestParam("Indicacao", $Method, NULL), $this);
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

//Validate Method @2-A60FE2BE
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->Nome->Validate() && $Validation);
        $Validation = ($this->Morada->Validate() && $Validation);
        $Validation = ($this->Telefone->Validate() && $Validation);
        $Validation = ($this->Telemovel->Validate() && $Validation);
        $Validation = ($this->Email->Validate() && $Validation);
        $Validation = ($this->Profissao->Validate() && $Validation);
        $Validation = ($this->Convencoes->Validate() && $Validation);
        $Validation = ($this->Indicacao->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->Nome->Errors->Count() == 0);
        $Validation =  $Validation && ($this->Morada->Errors->Count() == 0);
        $Validation =  $Validation && ($this->Telefone->Errors->Count() == 0);
        $Validation =  $Validation && ($this->Telemovel->Errors->Count() == 0);
        $Validation =  $Validation && ($this->Email->Errors->Count() == 0);
        $Validation =  $Validation && ($this->Profissao->Errors->Count() == 0);
        $Validation =  $Validation && ($this->Convencoes->Errors->Count() == 0);
        $Validation =  $Validation && ($this->Indicacao->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-8E6B25B3
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Nome->Errors->Count());
        $errors = ($errors || $this->Morada->Errors->Count());
        $errors = ($errors || $this->Telefone->Errors->Count());
        $errors = ($errors || $this->Telemovel->Errors->Count());
        $errors = ($errors || $this->Email->Errors->Count());
        $errors = ($errors || $this->Profissao->Errors->Count());
        $errors = ($errors || $this->Convencoes->Errors->Count());
        $errors = ($errors || $this->Indicacao->Errors->Count());
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

//Operation Method @2-91E66836
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
        $Redirect = "pacientes_list.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
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

//InsertRow Method @2-B70CCB98
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->Nome->SetValue($this->Nome->GetValue(true));
        $this->DataSource->Morada->SetValue($this->Morada->GetValue(true));
        $this->DataSource->Telefone->SetValue($this->Telefone->GetValue(true));
        $this->DataSource->Telemovel->SetValue($this->Telemovel->GetValue(true));
        $this->DataSource->Email->SetValue($this->Email->GetValue(true));
        $this->DataSource->Profissao->SetValue($this->Profissao->GetValue(true));
        $this->DataSource->Convencoes->SetValue($this->Convencoes->GetValue(true));
        $this->DataSource->Indicacao->SetValue($this->Indicacao->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @2-E2B68E7D
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->Nome->SetValue($this->Nome->GetValue(true));
        $this->DataSource->Morada->SetValue($this->Morada->GetValue(true));
        $this->DataSource->Telefone->SetValue($this->Telefone->GetValue(true));
        $this->DataSource->Telemovel->SetValue($this->Telemovel->GetValue(true));
        $this->DataSource->Email->SetValue($this->Email->GetValue(true));
        $this->DataSource->Profissao->SetValue($this->Profissao->GetValue(true));
        $this->DataSource->Convencoes->SetValue($this->Convencoes->GetValue(true));
        $this->DataSource->Indicacao->SetValue($this->Indicacao->GetValue(true));
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

//Show Method @2-709E542B
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
        if($this->EditMode) {
            if($this->DataSource->Errors->Count()){
                $this->Errors->AddErrors($this->DataSource->Errors);
                $this->DataSource->Errors->clear();
            }
            $this->DataSource->Open();
            if($this->DataSource->Errors->Count() == 0 && $this->DataSource->next_record()) {
                $this->DataSource->SetValues();
                if(!$this->FormSubmitted){
                    $this->Nome->SetValue($this->DataSource->Nome->GetValue());
                    $this->Morada->SetValue($this->DataSource->Morada->GetValue());
                    $this->Telefone->SetValue($this->DataSource->Telefone->GetValue());
                    $this->Telemovel->SetValue($this->DataSource->Telemovel->GetValue());
                    $this->Email->SetValue($this->DataSource->Email->GetValue());
                    $this->Profissao->SetValue($this->DataSource->Profissao->GetValue());
                    $this->Convencoes->SetValue($this->DataSource->Convencoes->GetValue());
                    $this->Indicacao->SetValue($this->DataSource->Indicacao->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->Nome->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Morada->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Telefone->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Telemovel->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Email->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Profissao->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Convencoes->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Indicacao->Errors->ToString());
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
        $this->Nome->Show();
        $this->Morada->Show();
        $this->Telefone->Show();
        $this->Telemovel->Show();
        $this->Email->Show();
        $this->Profissao->Show();
        $this->Convencoes->Show();
        $this->Indicacao->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End pacientes Class @2-FCB6E20C

class clspacientesDataSource extends clsDBConnection1 {  //pacientesDataSource Class @2-D43A7CDD

//DataSource Variables @2-329D5508
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
    var $Nome;
    var $Morada;
    var $Telefone;
    var $Telemovel;
    var $Email;
    var $Profissao;
    var $Convencoes;
    var $Indicacao;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-D1A28DB7
    function clspacientesDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record pacientes/Error";
        $this->Initialize();
        $this->Nome = new clsField("Nome", ccsText, "");
        
        $this->Morada = new clsField("Morada", ccsText, "");
        
        $this->Telefone = new clsField("Telefone", ccsInteger, "");
        
        $this->Telemovel = new clsField("Telemovel", ccsInteger, "");
        
        $this->Email = new clsField("Email", ccsText, "");
        
        $this->Profissao = new clsField("Profissao", ccsText, "");
        
        $this->Convencoes = new clsField("Convencoes", ccsText, "");
        
        $this->Indicacao = new clsField("Indicacao", ccsText, "");
        

        $this->InsertFields["Nome"] = array("Name" => "Nome", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["Morada"] = array("Name" => "Morada", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["Telefone"] = array("Name" => "Telefone", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["Telemovel"] = array("Name" => "Telemovel", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["Email"] = array("Name" => "Email", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["Profissao"] = array("Name" => "Profissao", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["Convencoes"] = array("Name" => "Convencoes", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["Indicacao"] = array("Name" => "Indicacao", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["Nome"] = array("Name" => "Nome", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["Morada"] = array("Name" => "Morada", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["Telefone"] = array("Name" => "Telefone", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["Telemovel"] = array("Name" => "Telemovel", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["Email"] = array("Name" => "Email", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["Profissao"] = array("Name" => "Profissao", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["Convencoes"] = array("Name" => "Convencoes", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["Indicacao"] = array("Name" => "Indicacao", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
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

//Open Method @2-412175EC
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n" .
        "FROM pacientes {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->PageSize = 1;
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @2-BFE5C8EC
    function SetValues()
    {
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

//Insert Method @2-BD858787
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["Nome"]["Value"] = $this->Nome->GetDBValue(true);
        $this->InsertFields["Morada"]["Value"] = $this->Morada->GetDBValue(true);
        $this->InsertFields["Telefone"]["Value"] = $this->Telefone->GetDBValue(true);
        $this->InsertFields["Telemovel"]["Value"] = $this->Telemovel->GetDBValue(true);
        $this->InsertFields["Email"]["Value"] = $this->Email->GetDBValue(true);
        $this->InsertFields["Profissao"]["Value"] = $this->Profissao->GetDBValue(true);
        $this->InsertFields["Convencoes"]["Value"] = $this->Convencoes->GetDBValue(true);
        $this->InsertFields["Indicacao"]["Value"] = $this->Indicacao->GetDBValue(true);
        $this->SQL = CCBuildInsert("pacientes", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @2-8D0A0CBD
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["Nome"]["Value"] = $this->Nome->GetDBValue(true);
        $this->UpdateFields["Morada"]["Value"] = $this->Morada->GetDBValue(true);
        $this->UpdateFields["Telefone"]["Value"] = $this->Telefone->GetDBValue(true);
        $this->UpdateFields["Telemovel"]["Value"] = $this->Telemovel->GetDBValue(true);
        $this->UpdateFields["Email"]["Value"] = $this->Email->GetDBValue(true);
        $this->UpdateFields["Profissao"]["Value"] = $this->Profissao->GetDBValue(true);
        $this->UpdateFields["Convencoes"]["Value"] = $this->Convencoes->GetDBValue(true);
        $this->UpdateFields["Indicacao"]["Value"] = $this->Indicacao->GetDBValue(true);
        $this->SQL = CCBuildUpdate("pacientes", $this->UpdateFields, $this);
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

//Delete Method @2-5F907302
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM pacientes";
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

} //End pacientesDataSource Class @2-FCB6E20C

//Include Page implementation @15-3DD2EFDC
include_once(RelativePath . "/Header.php");
//End Include Page implementation

//Include Page implementation @16-58DBA1E3
include_once(RelativePath . "/Footer.php");
//End Include Page implementation

//Initialize Page @1-683D34B1
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
$TemplateFileName = "pacientes_maint.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-2835599F
include_once("./pacientes_maint_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-40AADB52
$DBConnection1 = new clsDBConnection1();
$MainPage->Connections["Connection1"] = & $DBConnection1;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$pacientes = & new clsRecordpacientes("", $MainPage);
$Header = & new clsHeader("", "Header", $MainPage);
$Header->Initialize();
$Footer = & new clsFooter("", "Footer", $MainPage);
$Footer->Initialize();
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

//Execute Components @1-607D9FEC
$pacientes->Operation();
$Header->Operations();
$Footer->Operations();
//End Execute Components

//Go to destination page @1-D3E80917
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBConnection1->close();
    header("Location: " . $Redirect);
    unset($pacientes);
    $Header->Class_Terminate();
    unset($Header);
    $Footer->Class_Terminate();
    unset($Footer);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-992E37CB
$pacientes->Show();
$Header->Show();
$Footer->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$ITKIJL7L4A6N9P4H = array("<center><font face=\"Aria","l\"><small>Ge&#110;&#101;r","&#97;t&#101;&#100; <!-- CCS"," -->&#119;i&#116;&#104; <!-- ","CCS -->&#67;&#111;d&#101;&","#67;&#104;a&#114;ge <!--"," SCC -->S&#116;&#117;di&#111",";.</small></font></center>","");
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", join($ITKIJL7L4A6N9P4H,"") . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", join($ITKIJL7L4A6N9P4H,"") . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= join($ITKIJL7L4A6N9P4H,"");
}
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-B0D08298
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBConnection1->close();
unset($pacientes);
$Header->Class_Terminate();
unset($Header);
$Footer->Class_Terminate();
unset($Footer);
unset($Tpl);
//End Unload Page


?>
