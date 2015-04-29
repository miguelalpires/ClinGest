<?php
//Include Common Files @1-8900E372
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "marcacoes_list.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsGridmarcacoes { //marcacoes class @2-FF2CCFAC

//Variables @2-14039C03

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
    var $Sorter_Data;
    var $Sorter1;
//End Variables

//Class_Initialize Event @2-661A23CB
    function clsGridmarcacoes($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "marcacoes";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid marcacoes";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsmarcacoesDataSource($this);
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
        $this->SorterName = CCGetParam("marcacoesOrder", "");
        $this->SorterDirection = CCGetParam("marcacoesDir", "");

        $this->Detail = & new clsControl(ccsLink, "Detail", "Detail", ccsText, "", CCGetRequestParam("Detail", ccsGet, NULL), $this);
        $this->Detail->Page = "marcacoes_maint.php";
        $this->Id = & new clsControl(ccsLabel, "Id", "Id", ccsInteger, "", CCGetRequestParam("Id", ccsGet, NULL), $this);
        $this->Nome = & new clsControl(ccsLabel, "Nome", "Nome", ccsText, "", CCGetRequestParam("Nome", ccsGet, NULL), $this);
        $this->Data = & new clsControl(ccsLabel, "Data", "Data", ccsDate, array("dd", "-", "mm", "-", "yyyy"), CCGetRequestParam("Data", ccsGet, NULL), $this);
        $this->Hora = & new clsControl(ccsLabel, "Hora", "Hora", ccsText, "", CCGetRequestParam("Hora", ccsGet, NULL), $this);
        $this->Sorter_Id = & new clsSorter($this->ComponentName, "Sorter_Id", $FileName, $this);
        $this->Sorter_Nome = & new clsSorter($this->ComponentName, "Sorter_Nome", $FileName, $this);
        $this->Sorter_Data = & new clsSorter($this->ComponentName, "Sorter_Data", $FileName, $this);
        $this->Navigator = & new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
        $this->Sorter1 = & new clsSorter($this->ComponentName, "Sorter1", $FileName, $this);
    }
//End Class_Initialize Event

//Initialize Method @2-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @2-1185250C
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;


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
            $this->ControlsVisible["Data"] = $this->Data->Visible;
            $this->ControlsVisible["Hora"] = $this->Hora->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->Detail->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                $this->Detail->Parameters = CCAddParam($this->Detail->Parameters, "Id", $this->DataSource->f("marcacoes_Id"));
                $this->Id->SetValue($this->DataSource->Id->GetValue());
                $this->Nome->SetValue($this->DataSource->Nome->GetValue());
                $this->Data->SetValue($this->DataSource->Data->GetValue());
                $this->Hora->SetValue($this->DataSource->Hora->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->Detail->Show();
                $this->Id->Show();
                $this->Nome->Show();
                $this->Data->Show();
                $this->Hora->Show();
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
        $this->Sorter_Data->Show();
        $this->Navigator->Show();
        $this->Sorter1->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-3B46667D
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Nome->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Data->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Hora->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End marcacoes Class @2-FCB6E20C

class clsmarcacoesDataSource extends clsDBConnection1 {  //marcacoesDataSource Class @2-82988756

//DataSource Variables @2-EEFCEF1B
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
    var $Data;
    var $Hora;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-7DBD6E59
    function clsmarcacoesDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid marcacoes";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->Nome = new clsField("Nome", ccsText, "");
        
        $this->Data = new clsField("Data", ccsDate, array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        
        $this->Hora = new clsField("Hora", ccsText, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-C137AEDA
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "Data desc, Hora desc";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_Id" => array("marcacoes.Id", ""), 
            "Sorter_Nome" => array("Nome", ""), 
            "Sorter_Data" => array("Data", ""), 
            "Sorter1" => array("Hora", "")));
    }
//End SetOrder Method

//Prepare Method @2-14D6CD9D
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
    }
//End Prepare Method

//Open Method @2-5AAF5B88
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM marcacoes LEFT JOIN pacientes ON\n\n" .
        "marcacoes.Paciente_id = pacientes.Id";
        $this->SQL = "SELECT marcacoes.Id AS marcacoes_Id, pacientes.Nome, marcacoes.Data, Hora \n" .
        "FROM marcacoes LEFT JOIN pacientes ON\n" .
        "marcacoes.Paciente_id = pacientes.Id {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        if ($this->CountSQL) 
            $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        else
            $this->RecordsCount = "CCS not counted";
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @2-570491F6
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("marcacoes_Id")));
        $this->Nome->SetDBValue($this->f("Nome"));
        $this->Data->SetDBValue(trim($this->f("Data")));
        $this->Hora->SetDBValue($this->f("Hora"));
    }
//End SetValues Method

} //End marcacoesDataSource Class @2-FCB6E20C

//Include Page implementation @20-3DD2EFDC
include_once(RelativePath . "/Header.php");
//End Include Page implementation

//Include Page implementation @21-58DBA1E3
include_once(RelativePath . "/Footer.php");
//End Include Page implementation

//Initialize Page @1-202ED42E
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
$TemplateFileName = "marcacoes_list.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-CD31BEBF
include_once("./marcacoes_list_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-8EA9E307
$DBConnection1 = new clsDBConnection1();
$MainPage->Connections["Connection1"] = & $DBConnection1;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$marcacoes = & new clsGridmarcacoes("", $MainPage);
$Header = & new clsHeader("", "Header", $MainPage);
$Header->Initialize();
$Footer = & new clsFooter("", "Footer", $MainPage);
$Footer->Initialize();
$MainPage->marcacoes = & $marcacoes;
$MainPage->Header = & $Header;
$MainPage->Footer = & $Footer;
$marcacoes->Initialize();

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

//Go to destination page @1-FB850003
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBConnection1->close();
    header("Location: " . $Redirect);
    unset($marcacoes);
    $Header->Class_Terminate();
    unset($Header);
    $Footer->Class_Terminate();
    unset($Footer);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-FE71C191
$marcacoes->Show();
$Header->Show();
$Footer->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$ENLLIPD3E2S3P = array("<center><font face=\"Arial\"><smal","l>&#71;&#101;&#110;e&#114;at&","#101;&#100; <!-- CCS -->w&#105;","t&#104; <!-- SCC -->C&#111;","de&#67;&#104;ar&#103;e <!-- SC","C -->&#83;tudio.</small></font><","/center>");
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", join($ENLLIPD3E2S3P,"") . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", join($ENLLIPD3E2S3P,"") . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= join($ENLLIPD3E2S3P,"");
}
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-0669FCA9
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBConnection1->close();
unset($marcacoes);
$Header->Class_Terminate();
unset($Header);
$Footer->Class_Terminate();
unset($Footer);
unset($Tpl);
//End Unload Page


?>
