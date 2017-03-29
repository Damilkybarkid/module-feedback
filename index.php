<?php
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

