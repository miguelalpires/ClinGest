<?php
//BindEvents Method @1-D40060DD
function BindEvents()
{
    global $CCSEvents;
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
}
//End BindEvents Method

//Page_BeforeShow @1-8F19A6BB
function Page_BeforeShow(& $sender)
{
    $Page_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $pacientes_maint; //Compatibility
//End Page_BeforeShow

//Custom Code @17-2A29BDB7
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
