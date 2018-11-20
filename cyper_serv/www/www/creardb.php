<?php 

    // These variables define the connection information for your MySQL database 
    $file_db = new PDO('sqlite:../rw/db/messaging.sqlite3');
    $file_db->setAttribute(PDO::ATTR_ERRMODE, 
                            PDO::ERRMODE_EXCEPTION);
 
    // Create new database in memory
    $flags_db = new PDO('sqlite:../rw/db/flags.sqlite3');
    // Set errormode to exceptions
    $flags_db->setAttribute(PDO::ATTR_ERRMODE, 
                              PDO::ERRMODE_EXCEPTION);
    
    
   /**************************************
    * Create tables                       *
    **************************************/
 
    // Create table messages
    $file_db->exec("CREATE TABLE IF NOT EXISTS usuarios (
                    id INTEGER PRIMARY KEY, 
                    username TEXT,
                    nombre TEXT, 
                    apellido TEXT,
                    password TEXT,
                    salt TEXT,
                    foto TEXT 
                    )");
 
    // Create table messages with different time format
    $flags_db->exec("CREATE TABLE flags (
                      id INTEGER PRIMARY KEY, 
                      flag_id TEXT,
                      flag_token TEXT,
                      flag_content TEXT, 
                      time TEXT)");
                      
                      
     $messages = array(
                  array('nombre' => 'Guillermo',
                        'apellido' => 'Barros Scheloto',
                        'password' => 'cebollita',
                        'foto' => 'pepe.png'),
                  array('nombre' => 'Ramon',
                        'apellido' => 'Diaz',
                        'password' => 'Just testing...',
                        'foto' => 'pepe.png'),
                  array('nombre' => 'Sebastian',
                        'apellido' => 'Veron',
                        'password' => 'Just testing...',
                        'foto' => 'pepe.png'),
                );
 
 
    /**************************************
    * Play with databases and tables      *
    **************************************/
 
    // Prepare INSERT statement to SQLite3 file db
    $insert = "INSERT INTO usuarios (username,nombre, apellido, password,foto,salt) 
                VALUES (:username,:nombre, :apellido, :password,:foto, :salt)";
    $stmt = $file_db->prepare($insert);
 
    // Bind parameters to statement variables
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':nombre', $title);
    $stmt->bindParam(':apellido', $message);
    $stmt->bindParam(':password', $password);
	$stmt->bindParam(':foto', $foto);
	$stmt->bindParam(':salt', $salt);
    
    // Loop thru all messages and execute prepared insert statement
    foreach ($messages as $m) {
      // Set values to bound variables
      echo $username;
      $username=$m['nombre'];
      $title = $m['nombre'];
      $message = $m['apellido'];
      $salt="sadhkjashdjksaf";
      $password = hash('sha256', $m['password'].$salt);
	  $foro= $m['foto'];
	  
      // Execute statement
      $stmt->execute();
    }    
 ?>
