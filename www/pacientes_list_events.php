<?php
//BindEvents Method @1-6CE2B5C2
function BindEvents()
{
    global $pacientes;
    global $CCSEvents;
    $pacientes->Navigator->CCSEvents["BeforeShow"] = "pacientes_Navigator_BeforeShow";
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
}
//End BindEvents Method

//pacientes_Navigator_BeforeShow @42-0E573D10
function pacientes_Navigator_BeforeShow(& $sender)
{
    $pacientes_Navigator_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $pacientes; //Compatibility
//End pacientes_Navigator_BeforeShow

//Hide-Show Component @43-0DB41530
    $Parameter1 = $Container->DataSource->PageCount();
    $Parameter2 = 2;
    if (((is_array($Parameter1) || strlen($Parameter1)) && (is_array($Parameter2) || strlen($Parameter2))) && 0 >  CCCompareValues($Parameter1, $Parameter2, ccsInteger))
        $Component->Visible = false;
//End Hide-Show Component

//Close pacientes_Navigator_BeforeShow @42-69A70404
    return $pacientes_Navigator_BeforeShow;
}
//End Close pacientes_Navigator_BeforeShow

//Page_BeforeShow @1-4F3D9783
function Page_BeforeShow(& $sender)
{
    $Page_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $pacientes_list; //Compatibility
//End Page_BeforeShow

//Custom Code @46-2A29BDB7
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
