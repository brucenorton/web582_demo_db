<?php
  require_once "_includes/db_connect.php";
 /* _v2 adds try / catch / finally error checking */
  $results = [];
  $insertedRows = 0;

  //SQL query copied from phpMyAdmin:
  //INSERT INTO `demo` (`demoID`, `name`, `email`, `tvshow`, `timestamp`) VALUES (NULL, 'Peter Oberlander', 'oberlanp@vanier.college', 'Bewitched', current_timestamp());

  try{
    if(!isset($_REQUEST["full_name"]) || !isset($_REQUEST["email"]) || !isset($_REQUEST["tvshow"])    ){
      throw new Exception('Required data is missing i.e. full_name, email or tvshow');
    }
    $query = "INSERT INTO demo (name, email, tvshow) VALUES (?, ?, ?)";

    if($stmt = mysqli_prepare($link, $query)){
      mysqli_stmt_bind_param($stmt, 'sss', $_REQUEST["full_name"], $_REQUEST["email"], $_REQUEST["tvshow"]);
      mysqli_stmt_execute($stmt);
      $insertedRows = mysqli_stmt_affected_rows($stmt);
  
      if($insertedRows > 0){
        $results[] = [
          "insertedRows"=>$insertedRows,
          "id" => $link->insert_id,
          "full_name" => $_REQUEST["full_name"],
          "tvshow" => $_REQUEST["tvshow"]
        ];
      }else{
        throw new Exception("No rows were inserted");
      }
      //removed the echo from here
      //echo json_encode($results);
    }else{
      throw new Exception("Prepared statement did not insert records.");
    }

  }catch(Exception $error){
    //add to results array rather than echoing out errors
    $results[] = ["error"=>$error->getMessage()];
  }finally{
    //echo out results
    echo json_encode($results);
  }
//example url with $_GET for full_name, email & tvshow
//https://nortonb.web582.com/demo_db/app/insert.php?full_name=Robin&email=robin@dot.com&tvshow=Little House on the Prarie

?>