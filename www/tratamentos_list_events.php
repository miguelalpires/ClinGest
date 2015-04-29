<?php
//BindEvents Method @1-F8DF9B2A
function BindEvents()
{
    global $tratamentos;
    global $CCSEvents;
    $tratamentos->Navigator->CCSEvents["BeforeShow"] = "tratamentos_Navigator_BeforeShow";
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
}
//End BindEvents Method

//tratamentos_Navigator_BeforeShow @28-DD1984D0
function tratamentos_Navigator_BeforeShow(& $sender)
{
    $tratamentos_Navigator_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tratamentos; //Compatibility
//End tratamentos_Navigator_BeforeShow

//Hide-Show Component @29-0DB41530
    $Parameter1 = $Container->DataSource->PageCount();
    $Parameter2 = 2;
    if (((is_array($Parameter1) || strlen($Parameter1)) && (is_array($Parameter2) || strlen($Parameter2))) && 0 >  CCCompareValues($Parameter1, $Parameter2, ccsInteger))
        $Component->Visible = false;
//End Hide-Show Component

//Close tratamentos_Navigator_BeforeShow @28-9D1BF219
    return $tratamentos_Navigator_BeforeShow;
}
//End Close tratamentos_Navigator_BeforeShow

//Page_BeforeShow @1-2192ADB0
function Page_BeforeShow(& $sender)
{
    $Page_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tratamentos_list; //Compatibility
//End Page_BeforeShow

//Custom Code @38-2A29BDB7
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
