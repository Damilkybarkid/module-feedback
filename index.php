<?php
print "<link rel=stylesheet href=mf.css>";
print "<h1>Module Feedback</h1>";
if (!array_key_exists('u',$_REQUEST))
{
	print "Who are you <form><input name=u value='50200036'/><input type=submit value='Submit'/></form>";
	exit();
}
$con = new mysqli('localhost','40200288','FRJPhjN/','40200288');
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
$stmt->close();

# Get questions
$sql = "select QUE_TEXT from INS_QUE";
$stmt = $con->prepare($sql)
	or die($con->error);
$stmt->execute()
	or die('Execute error');
$cur = $stmt->get_result();
$q1 = $cur->fetch_all();
$stmt->close();

print "Welcome student: ".$row[0].' '.$row[1];

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
print "<form action=store_fb.php>";
while ($row = $cur->fetch_row())
{
	print "<div class=module>";
	print "<h2>Please answer questions about $row[0]</h2>";
	for($i=0;$i<19;$i++)
	{
		$question=str_replace(array('[', ']'), '', htmlspecialchars(json_encode($q1[$i]), ENT_NOQUOTES));
		$question=str_replace(array('"', '"'), '', htmlspecialchars($question, ENT_NOQUOTES));
		print "<p>$question</p>";
		print "
			<input type='radio' name='$row[0]_Q1d1' value='5'> DA
			<input type='radio' name='$row[0]_Q1d1' value='4'> MA
			<input type='radio' name='$row[0]_Q1d1' value='3'> N
			<input type='radio' name='$row[0]_Q1d1' value='2'> MD
			<input type='radio' name='$row[0]_Q1d1' value='1'> DD
	      	      ";
	}	
	print "</div>";
}
print "<input type=hidden name=u value=$_REQUEST[u]>";
print "<input type=submit>";
print "</form>";
