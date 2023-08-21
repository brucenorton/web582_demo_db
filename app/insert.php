<?php

  require_once "_includes/db_connect.php";

  $results = [];
  $insertedRows = 0;
  //INSERT INTO `demo` (`demoID`, `name`, `email`, `tvshow`, `timestamp`) VALUES (NULL, 'Peter Oberlander', 'oberlanp@vanier.college', 'Bewitched', current_timestamp());

  $query = "INSERT INTO demo (name, email, tvshow) VALUES (?, ?, ?)";

  if($stmt = mysqli_prepare($link, $query)){
    mysqli_stmt_bind_param($stmt, 'sss', $_REQUEST["full_name"], $_REQUEST["email"], $_REQUEST["tvshow"]);
    mysqli_stmt_execute($stmt);
    $insertedRows = mysqli_stmt_affected_rows($stmt);

    if($insertedRows > 0){
      $results[] = [
        "insertedRows"=>$insertedRows,
        "id" => $link->insert_id,
        "full_name" => $_REQUEST["full_name"]
      ];
    }
    echo json_encode($results);
  }
//https://nortonb.web582.com/demo_db/app/insert.php?full_name=Robin&email=robin@dot.com&tvshow=Little House on the Prarie

?>