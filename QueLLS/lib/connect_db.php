<?php
require_once(BASE.'lib/config.php');

function OpenDB() {
  global $db_type;
  switch (strtoupper($db_type)) {
    case 'SQLITE':
        open_sqlite_connection();
        break;
    case 'MYSQL':
        open_mysql_connection();
        break;
    default:
        exit "Erreur : type de base de donnée incorrecte !<br>";
  }
}

function open_sqlite_connection() {
	global $debug, $db_file, $conn;
	$cnx_err=false;	// erreur de connexion
	
  if ($conn = sqlite_open($db_file, 0755, $sqliteerror)) {
    // Tester ici sir la DB existe, sinon, la créer ...
  } else {
    $cnx_err=true;
  }
	return $cnx_err;
}

function open_mysql_connection() {
	global $debug, $db_hostname, $db_login, $db_pwd, $db_name, $conn;
	$cnx_err=false;	// erreur de connexion
	
	if ($debug) echo "Ouverture de la connexion MySql<br />";
	$conn = mysql_connect($db_hostname, $db_login, $db_pwd);
	if (!$conn) {
		$cnx_err=true;
		$msg = "Echec de la connexion au serveur : ".mysql_error();
		if ($debug) echo $msg."<br>";
	}
	$db = mysql_select_db($db_name, $conn);
	if (!$db) {
		$cnx_err=true;
		$msg = "Echec de la connexion à la base : ".mysql_error();
		if ($debug) echo $msg."<br>";
	}
	return $cnx_err;
}

function closeDB() {
  global $db_type;
  switch (strtoupper($db_type)) {
    case 'SQLITE':
        close_sqlite_connection();
        break;
    case 'MYSQL':
        close_mysql_connection();
        break;
    default:
        exit "Erreur : type de base de donnée incorrecte !<br>";
  }
}

function close_sqlite_connection() {
  global $debug, $conn;
	if ($debug) echo "Fermeture de la connexion SQLite<br>";  
  sqlite_close($conn);
}

function close_mysql_connection() {
	global $debug, $conn;
	if ($debug) echo "Fermeture de la connexion MySql<br>";
	mysql_close($conn);
}

function open_odbc_connection() {
	global $debug, $ze_hostname, $ze_login, $ze_pwd, $conn_o;
	$cnx_err=false;	// erreur de connexion
	
	if ($debug) echo "Ouverture de la connexion ODBC<br />";
	$conn_o = odbc_connect($ze_hostname, $ze_login, $ze_pwd);

	if (!$conn_o) {
		$cnx_err=true;
		$msg = "Echec de la connexion au serveur : ".odbc_error();
		if ($debug) echo $msg."<br>";
	}
	return $cnx_err;
}

// Query
function DB_query($sql) {
  global $db_type, $conn;
  switch (strtoupper($db_type)) {
    case 'SQLITE':
        $res = sqlite_query($sql, $conn);
        break;
    case 'MYSQL':
        $res = mysql_query($sql, $conn);
        break;
    default:
        exit "Erreur : type de base de donnée incorrecte !<br>";
  }
  return $res;
}

// Afficher les erreurs spécifiques
function DB_error() {
  global $db_type, $conn;
  switch (strtoupper($db_type)) {
    case 'SQLITE':
        $error = sqlite_error_string(sqlite_last_error($conn));
        break;
    case 'MYSQL':
        $error = mysql_error();
        break;
    default:
        exit "Erreur : type de base de donnée incorrecte !<br>";
  }
  return $error;
}

// Nombre d'enregistrement d'un résultat
function DB_num_rows($result) {
  global $db_type;
  switch (strtoupper($db_type)) {
    case 'SQLITE':
        $count = sqlite_num_rows($result);
        break;
    case 'MYSQL':
        $count = mysql_num_rows($result);
        break;
    default:
        exit "Erreur : type de base de donnée incorrecte !<br>";
  }
  return $count;
}

// Résultat sous forme de tableau
function DB_fetch_assoc($result) {
  global $db_type;
  switch (strtoupper($db_type)) {
    case 'SQLITE':
        $array = sqlite_fetch_array($result);
        break;
    case 'MYSQL':
        $array = mysql_fetch_assoc($result);
        break;
    default:
        exit "Erreur : type de base de donnée incorrecte !<br>";
  }
  return $array;
}

?>
