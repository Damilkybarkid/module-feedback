<?php
print "<head>";

# Set styles
print "<style>";
print "body{
		font-family:'Arial';
	   }";
print "#title{
		width:17ex;
		margin-left:auto;
		margin-right:auto;
	     }";
print ".module{
		border:solid thin grey;
		padding:1ex;
		margin:2ex;
	      }";
print "#screen{
		position:relative;
	      }";
print "</style>";

# Adjust for mobiles
print "<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'>";

print "<title>CW2</title>";
print "</head>";
print "<body>";
print "<h1 id=title>Module Feedback</h1>";
if (!array_key_exists('u',$_REQUEST))
{
	print "Who are you <form><input name=u value='50200036'/><input type=submit value='Submit'/></form>";
	exit();
}

# Set up connection to database
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

# Display student name
print "<div id=welcome>";
print "<span>";
print "Welcome student: ".$row[0].' '.$row[1];
print "</span>";
print "</div>";

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

# Create three modules
while ($row = $cur->fetch_row())
{
	print "<div class=module>";
	print "<h2>Please answer questions about $row[0]</h2>";
	for($i=0;$i<19;$i++)
	{
		$questionnumber=$i+1;
		$question=str_replace(array('[', ']'), '', htmlspecialchars(json_encode($q1[$i]), ENT_NOQUOTES));
		$question=str_replace(array('"', '"'), '', htmlspecialchars($question, ENT_NOQUOTES));
		print "<p>$question</p>";
		print "
			<input type='radio' name='$row[0]_Q1d$questionnumber' value='5'> DA
			<input type='radio' name='$row[0]_Q1d$questionnumber' value='4'> MA
			<input type='radio' name='$row[0]_Q1d$questionnumber' value='3'> N
			<input type='radio' name='$row[0]_Q1d$questionnumber' value='2'> MD
			<input type='radio' name='$row[0]_Q1d$questionnumber' value='1'> DD
	      	      ";
	}	
	print "</div>";
}
print "<input type=hidden name=u value=$_REQUEST[u]>";
print "<input type=submit class=submit>";
print "</form>";
print "</body>";
