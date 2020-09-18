<?php
	$dsn  = 'mysql:host=localhost; dbname=tweety';
	$user = 'root';
	$pass = '';
	
	try
	{
		$pdo = new PDO($dsn, $user, $pass);
		$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING ); 
        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	catch(PDOException $e)
	{
		echo 'Connnection error! ' . $e->getMessage();
	}
?>