<?php
//BindEvents Method @1-71844A70
function BindEvents()
{
    global $marcacoes;
    global $CCSEvents;
    $marcacoes->Navigator->CCSEvents["BeforeShow"] = "marcacoes_Navigator_BeforeShow";
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
}
//End BindEvents Method

//marcacoes_Navigator_BeforeShow @18-88039D28
function marcacoes_Navigator_BeforeShow(& $sender)
{
    $marcacoes_Navigator_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $marcacoes; //Compatibility
//End marcacoes_Navigator_BeforeShow

//Hide-Show Component @19-0DB41530
    $Parameter1 = $Container->DataSource->PageCount();
    $Parameter2 = 2;
    if (((is_array($Parameter1) || strlen($Parameter1)) && (is_array($Parameter2) || strlen($Parameter2))) && 0 >  CCCompareValues($Parameter1, $Parameter2, ccsInteger))
        $Component->Visible = false;
//End Hide-Show Component

//Close marcacoes_Navigator_BeforeShow @18-F551201F
    return $marcacoes_Navigator_BeforeShow;
}
//End Close marcacoes_Navigator_BeforeShow

//Page_BeforeShow @1-62E5235A
function Page_BeforeShow(& $sender)
{
    $Page_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $marcacoes_list; //Compatibility
//End Page_BeforeShow

//Custom Code @25-2A29BDB7
// -------------------------
    // Write your own code here.
	if(!isset($_SESSION['UserID']) || !isset($_SESSION['UserLogin']) || !isset($_SESSION['GroupID']))
	{
		header("location: login.php");
	}
// -------------------------
//End Custom Code

//Close Page_BeforeShow @1-4BC230CD
    return $Page_BeforeShow;
}
//End Close Page_BeforeShow


?>
