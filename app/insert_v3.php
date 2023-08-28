<?php
  require_once "_includes/db_connect.php";

  // ** v3 adds a select query to see if an email already exists **
  /* pseudo code **
    -receive input
      -check (select) to see if user exists 
        -if yes > update 
        -if no > insert
 */

  $results = [];
  $insertedRows = 0;

// 3 separate functions to select, update and insert
  function selectUser($link){
    // ** must pass in connection $link due to php scope
    $query = "SELECT * FROM demo WHERE email = ?";
    $stmt = mysqli_prepare($link, $query);
    
    mysqli_stmt_bind_param($stmt, "s", $_REQUEST["email"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $results[] = ["mysqli_num_rows" => mysqli_num_rows($result)];
    return mysqli_num_rows($result) > 0;
    
  }

  function updateData($link) {
            //UPDATE `demo` SET `tvshow` = 'Ninja' WHERE `demo`.`demoID` = 8;
    $query = "UPDATE demo SET tvshow = ? WHERE email = ?";
    $stmt = mysqli_prepare($link, $query);
    
    mysqli_stmt_bind_param($stmt, "ss", $_REQUEST["tvshow"], $_REQUEST["email"]);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) <= 0) {
        throw new Exception("Error updating data: " . mysqli_stmt_error($stmt));
    }

    return mysqli_stmt_affected_rows($stmt);
  }


  function insertData($link){
    $query = "INSERT INTO demo (name, email, tvshow) VALUES (?, ?, ?)";

    if($stmt = mysqli_prepare($link, $query)){
      mysqli_stmt_bind_param($stmt, 'sss', $_REQUEST["full_name"], $_REQUEST["email"], $_REQUEST["tvshow"]);
      mysqli_stmt_execute($stmt);
      $insertedRows = mysqli_stmt_affected_rows($stmt);
  
      if($insertedRows > 0){
        //is this in the global space?
        return $results[] = [
          "insertedRows"=>$insertedRows,
          "id" => $link->insert_id,
          "full_name" => $_REQUEST["full_name"]
        ];
      }else{
        throw new Exception("No rows were inserted");
      }
      return $insertedRows;
    }else{
      throw new Exception("Prepared statement did not insert records.");
    }

  }

/** move all the logic to here */
  try{
    if(!isset($_REQUEST["full_name"]) || !isset($_REQUEST["email"]) || !isset($_REQUEST["tvshow"])    ){
      throw new Exception('Required data is missing i.e. full_name, email or tvshow');
    }else{
      //if email exists update tvshow
      if(selectUser($link)){ //this should return either 1 or 0 i.e. true or false
        $results[] = ["selectUser()" => "callupdateData()"];
        $results[] = ["updateData() affected_rows" => updateData($link)];\
      //else insert new record
      }else{
        $results[] = ["selectUser()" => "insertData()"];
        $results[] = ["insertData() affected_rows" => insertData($link)];

      }
    }
  }catch(Exception $error){
    $results[] = ["error"=>$error->getMessage()];
  }finally{
    echo json_encode($results);
  }
//example url with $_GET for full_name, email & tvshow
// https://nortonb.web582.com/demo_db/app/insert_v3.php?full_name=Bobby&email=bobby@dot.com&tvshow=Winning
?>