<?php
  //connect to db - $link
  require_once "_includes/db_connect.php";

  //prepare the statement passing the db $link and the SQL
  $stmt = mysqli_prepare($link, "SELECT name, email, tvshow, timestamp FROM demo");

  //execute the statement / query from abobe
  mysqli_stmt_execute($stmt);

  //get results
  $result = mysqli_stmt_get_result($stmt);

  //loop through
  while($row = mysqli_fetch_assoc($result)){
    $results[] = $row;
  }

  //encode & display json
  echo json_encode($results);

  //close the link to the db
  mysqli_close($link);

?>