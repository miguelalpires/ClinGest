<?php

class clsMenuHeaderMenu1 extends clsMenu { //Menu1 class @5-ECDE38DF

//Class_Initialize Event @5-D00C14C6
    function clsMenuHeaderMenu1($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "Menu1";
        $this->Visible = True;
        $this->controls = array();
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->ErrorBlock = "Menu Menu1";

        $this->StaticItems = array();
        $this->StaticItems[] = array("item_id" => "MenuItem5", "item_id_parent" => null, "item_caption" => $CCSLocales->GetText("Início"), "item_url" => array("Page" => $this->RelativePath . "index.php", "Parameters" => null), "item_target" => "_self", "item_title" => $CCSLocales->GetText(""));
        $this->StaticItems[] = array("item_id" => "MenuItem4", "item_id_parent" => null, "item_caption" => $CCSLocales->GetText("Agenda"), "item_url" => array("Page" => $this->RelativePath . "agenda.php", "Parameters" => null), "item_target" => "_self", "item_title" => $CCSLocales->GetText(""));
        $this->StaticItems[] = array("item_id" => "MenuItem3", "item_id_parent" => null, "item_caption" => $CCSLocales->GetText("Marcações"), "item_url" => array("Page" => $this->RelativePath . "marcacoes_list.php", "Parameters" => null), "item_target" => "_self", "item_title" => $CCSLocales->GetText(""));
        $this->StaticItems[] = array("item_id" => "MenuItem3Item1", "item_id_parent" => "MenuItem3", "item_caption" => $CCSLocales->GetText("Consultar"), "item_url" => array("Page" => $this->RelativePath . "marcacoes_list.php", "Parameters" => null), "item_target" => "_self", "item_title" => $CCSLocales->GetText(""));
        $this->StaticItems[] = array("item_id" => "MenuItem3Item2", "item_id_parent" => "MenuItem3", "item_caption" => $CCSLocales->GetText("Adicionar"), "item_url" => array("Page" => $this->RelativePath . "marcacoes_maint.php", "Parameters" => null), "item_target" => "_self", "item_title" => $CCSLocales->GetText(""));
        $this->StaticItems[] = array("item_id" => "MenuItem1", "item_id_parent" => null, "item_caption" => $CCSLocales->GetText("Pacientes"), "item_url" => array("Page" => $this->RelativePath . "pacientes_list.php", "Parameters" => null), "item_target" => "_self", "item_title" => $CCSLocales->GetText(""));
        $this->StaticItems[] = array("item_id" => "MenuItem1Item1", "item_id_parent" => "MenuItem1", "item_caption" => $CCSLocales->GetText("Consultar"), "item_url" => array("Page" => $this->RelativePath . "pacientes_list.php", "Parameters" => null), "item_target" => "_self", "item_title" => $CCSLocales->GetText(""));
        $this->StaticItems[] = array("item_id" => "MenuItem1Item2", "item_id_parent" => "MenuItem1", "item_caption" => $CCSLocales->GetText("Adicionar"), "item_url" => array("Page" => $this->RelativePath . "pacientes_maint.php", "Parameters" => null), "item_target" => "_self", "item_title" => $CCSLocales->GetText(""));
        $this->StaticItems[] = array("item_id" => "MenuItem2", "item_id_parent" => null, "item_caption" => $CCSLocales->GetText("Tratamentos"), "item_url" => array("Page" => $this->RelativePath . "tratamentos_list.php", "Parameters" => null), "item_target" => "_self", "item_title" => $CCSLocales->GetText(""));
        $this->StaticItems[] = array("item_id" => "MenuItem2Item1", "item_id_parent" => "MenuItem2", "item_caption" => $CCSLocales->GetText("Consultar"), "item_url" => array("Page" => $this->RelativePath . "tratamentos_list.php", "Parameters" => null), "item_target" => "", "item_title" => $CCSLocales->GetText(""));
        $this->StaticItems[] = array("item_id" => "MenuItem2Item2", "item_id_parent" => "MenuItem2", "item_caption" => $CCSLocales->GetText("Adicionar"), "item_url" => array("Page" => $this->RelativePath . "tratamentos_maint.php", "Parameters" => null), "item_target" => "_self", "item_title" => $CCSLocales->GetText(""));

        $this->DataSource = new clsHeaderMenu1DataSource($this);
        $this->ds = & $this->DataSource;
        $this->DataSource->SetProvider(array("DBLib" => "Array"));

        parent::clsMenu("item_id_parent", "item_id", null);

        $this->ItemLink = & new clsControl(ccsLink, "ItemLink", "ItemLink", ccsText, "", CCGetRequestParam("ItemLink", ccsGet, NULL), $this);
        $this->controls["ItemLink"] = & $this->ItemLink;
        $this->ItemLink->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
        $this->ItemLink->Page = "";
        $this->LinkStartParameters = $this->ItemLink->Parameters;
    }
//End Class_Initialize Event

//SetControlValues Method @5-B7BF812B
    function SetControlValues() {
        $this->ItemLink->SetValue($this->DataSource->ItemLink->GetValue());
        $LinkUrl = $this->DataSource->f("item_url");
        $this->ItemLink->Page = $LinkUrl["Page"];
        $this->ItemLink->Parameters = $this->SetParamsFromDB($this->LinkStartParameters, $LinkUrl["Parameters"]);
    }
//End SetControlValues Method

//ShowAttributes @5-17684C76
    function ShowAttributes() {
        $this->Attributes->SetValue("MenuType", "menu_htb");
        $this->Attributes->Show();
    }
//End ShowAttributes

} //End Menu1 Class @5-FCB6E20C

//HeaderMenu1DataSource Class @5-C327DCF6
class clsHeaderMenu1DataSource extends DB_Adapter {
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;
    var $wp;
    var $Record = array();
    var $Index;
    var $FieldsList = array();

    function clsHeaderMenu1DataSource($parent) {
        $this->Parent = & $parent;
        $this->ErrorBlock = "Menu Menu1";
        $this->ItemLink = new clsField("ItemLink", ccsText, "");
        $this->FieldsList["ItemLink"] = & $this->ItemLink;
    }

    function Prepare()
    {
    }

    function Open()
    {
        $this->query($this->Parent->StaticItems);
    }

    function SetValues()
    {
        $this->ItemLink->SetDBValue($this->f("item_caption"));
    }
}
//End HeaderMenu1DataSource Class

class clsHeader { //Header class @1-CC982CB1

//Variables @1-9721D5A2
    var $ComponentType = "IncludablePage";
    var $Connections = array();
    var $FileName = "";
    var $Redirect = "";
    var $Tpl = "";
    var $TemplateFileName = "";
    var $BlockToParse = "";
    var $ComponentName = "";
    var $Attributes = "";

    // Events;
    var $CCSEvents = "";
    var $CCSEventResult = "";
    var $RelativePath;
    var $Visible;
    var $Parent;
//End Variables

//Class_Initialize Event @1-5C4FA0A2
    function clsHeader($RelativePath, $ComponentName, & $Parent)
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = $ComponentName;
        $this->RelativePath = $RelativePath;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->FileName = "Header.php";
        $this->Redirect = "";
        $this->TemplateFileName = "Header.html";
        $this->BlockToParse = "main";
        $this->TemplateEncoding = "CP1252";
        $this->ContentType = "text/html";
    }
//End Class_Initialize Event

//Class_Terminate Event @1-D27CC112
    function Class_Terminate()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUnload", $this);
        unset($this->Menu1);
    }
//End Class_Terminate Event

//BindEvents Method @1-0DAD0D56
    function BindEvents()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInitialize", $this);
    }
//End BindEvents Method

//Operations Method @1-7E2A14CF
    function Operations()
    {
        global $Redirect;
        if(!$this->Visible)
            return "";
    }
//End Operations Method

//Initialize Method @1-1F7E78FA
    function Initialize()
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInitialize", $this);
        if(!$this->Visible)
            return "";
        $this->Attributes = & $this->Parent->Attributes;

        // Create Components
        $this->Menu1 = & new clsMenuHeaderMenu1($this->RelativePath, $this);
        $this->BindEvents();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnInitializeView", $this);
    }
//End Initialize Method

//Show Method @1-B4AFCB2C
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        $block_path = $Tpl->block_path;
        $Tpl->LoadTemplate("/" . $this->TemplateFileName, $this->ComponentName, $this->TemplateEncoding, "remove");
        $Tpl->block_path = $Tpl->block_path . "/" . $this->ComponentName;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) {
            $Tpl->block_path = $block_path;
            $Tpl->SetVar($this->ComponentName, "");
            return "";
        }
        $this->Attributes->Show();
        $this->Menu1->Show();
        $Tpl->Parse();
        $Tpl->block_path = $block_path;
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeOutput", $this);
        $Tpl->SetVar($this->ComponentName, $Tpl->GetVar($this->ComponentName));
    }
//End Show Method

} //End Header Class @1-FCB6E20C


?>
