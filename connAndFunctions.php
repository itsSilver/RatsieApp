<?php
$conn=oci_connect("system","Proiectare123","localhost/orcl");
// if (!$conn)
	// echo 'Failed to connect to Oracle';
// else
	// echo 'Succesfully connected with Oracle DB';
function getOciMessage($error)
{
	$begin = strpos($error,':');
	$cutstr = substr($error,$begin+2);
	$end = strpos($cutstr,"ORA-");
	$message = substr($cutstr,0,$end-1);
	return $message;
}

//-------------------REFRESH RACES----------------
$refreshRaces = ociparse($conn, "CALL REFRESH_RACES()");
oci_execute($refreshRaces);
	// $refreshRaces = ociparse($conn, "SELECT finished FROM races WHERE raceId = 13");
	// if(oci_execute($refreshRaces))
	// {
		// if(oci_fetch($refreshRaces))
		// {
			// echo ' ' . ociresult($refreshRaces, "FINISHED");
		// }
	// }
	
	// $refreshRaces = ociparse($conn, "COMMIT");
	// if(oci_execute($refreshRaces))
	// {
		// echo 'ok';
	// }


//---------------------ROLES-----------------
$_Role = new stdClass();
$_Role->Client = 'client';
$_Role->Admin = 'admin';
?>