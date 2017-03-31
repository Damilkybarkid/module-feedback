<?php
print "<link rel=stylesheet href=mf.css>";
print "<h1>Module feedback</h1>";
if (!array_key_exists('u',$_REQUEST))
{
	print "Who are you <form><input name=u value='50200036' type=submit></form>";
	exit();
}
$con = new mysqli('localhost','scott','tiger','gisq');
if ($con ->connect_error)
{
	die('Connection failure');
}

# Get student name
$sql = "select SPR_FNM1,SPR_SURN from INS_SPR where SPR_CODE=?";
$stmt = $con->prepare($sql)
	or die($con->error);
$stmt->bind_param('s',$_REQUEST['u'])
	or die('Bind error');
$stmt->execute()
	or die('Execute error');
$cur = $stmt->get_result();
if (!($row = $cur->fetch_array()))
{
	echo 'Invalid matric number';
	exit();
}
print "Welcome student: ".$row[0].' '.$row[1];
$stmt->close();

# Get a list of modules
$sql = "select CAM_SMO.MOD_CODE, MOD_NAME, INS_MOD.PRS_CODE, PRS_FNM1, PRS_SURN
        from CAM_SMO join INS_MOD ON (CAM_SMO.MOD_CODE = INS_MOD.MOD_CODE)
                left join INS_PRS ON (INS_MOD.PRS_CODE = INS_PRS.PRS_CODE)
        where SPR_CODE=? AND AYR_CODE='2016/7' AND PSL_CODE='TR1'";
$stmt = $con->prepare($sql)
	or die($con->error);
$stmt->bind_param('s',$_REQUEST['u'])
	or die('Bind error');
$stmt->execute()
	or die('Execute error');
$cur = $stmt->get_result();
while ($row = $cur->fetch_row())
{
	print "<div class=module>";
	print "<h2>Please answer questions about $row[0]</h2>";
	print "<p>What did you think of it?</p>";
	print "</div>";
}
