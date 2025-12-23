<?php

function isUniqueOrNot($conn, $tableName, $where){
    $sql = "SELECT * FROM $tableName WHERE $where";
    $res = mysqli_query($conn, $sql);

    if ($res === false) {
        // Query failed, output error for debugging
      //   echo "$sql";
      //   echo "<br>";
      //   echo "SQL Error: " . mysqli_error($conn);
        return false; // or handle error as needed
    }

//     exit;\
// echo mysqli_num_rows($res);
      //   exit;

    return mysqli_num_rows($res) <= 0;
}


// require '../vendor/autoload.php';
// use PhpOffice\PhpSpreadsheet\IOFactory;

function FieldStringSetter($conn, $tableName, $Fields, $Data, $whereData = null)
{
      $fieldsStr = "(" . implode(", ", $Fields) . ")";
      // echo "FFF WhereData: " . $whereData . "<br>";
      if ($whereData == null)
            $whereData = $Fields[0] . " = '" . $Data[0] . "'";

      $dataStr = "(";
      for ($i = 0; $i < count($Fields); $i++) {
            $dataStr .= "'" . $Data[$i] . "'";
            if ($i != count($Fields) - 1) $dataStr .= ", ";
      }
      $dataStr .= ")";


      $setFieldDataStr = "";
      for ($i = 0; $i < count($Fields); $i++) {
            $setFieldDataStr .= $Fields[$i] . "='" . $Data[$i] . "'";
            if ($i != count($Fields) - 1) $setFieldDataStr .= ", ";
      }

      OperationDecider($conn, $tableName, $whereData, $fieldsStr, $dataStr, $setFieldDataStr);
}

function OperationDecider($conn, $table, $whereData, $fields, $data, $setFieldData)
{
      // echo "<br>1. $data<br>";
      // $isUpdate = ;
      // echo "<br>2. $data<br>";
      if (SelectData($conn, $table, $whereData) > 0) {
            // echo "<br>3. $data<br>";
            // echo "Updating data...<br>";
            // exit;
            UpdateData($conn, $table, $setFieldData, $whereData);
      } else {
            // echo "<br>4. $data<br>";
            // echo "Inserting data...<br>";
            InsertData($conn, $table, $fields, $data);
      }
      // echo "<br><br><br>";
      // exit;
      // exit;
}

function SelectData($conn, $table, $whereData)
{
      $select = "SELECT * FROM $table WHERE $whereData";
      // echo "<br>Select query: " . $select . "<br>"; 
      // $n = mysqli_num_rows($res);
      // echo "Select query: " . $n . "<br>";
      $res = mysqli_query($conn, $select);

      return mysqli_num_rows($res);
}

function InsertData($conn, $table, $fields, $data)
{     
      $insert = "INSERT INTO $table $fields VALUES $data";
      // echo "Insert query: " . $insert . "<br>";
      // exit;

      mysqli_query($conn, $insert);
}

function UpdateData($conn, $table, $setFieldData, $whereData)
{
      $update = "UPDATE $table SET $setFieldData WHERE $whereData";
      // echo $update;
      // exit;
      mysqli_query($conn, $update);
}


function LoginTableInsert($conn, $tableName, $field, $data)
{
      $loginAdd = "INSERT INTO $tableName ($field[0],$field[1],$field[2]) VALUES ('$data[0]','$data[1]','$data[2]')";
      // echo "Miira... ".$loginAdd."<br>";

      //     echo "Miira... ".$un;
      $q = mysqli_query($conn, $loginAdd);
      // echo "Miira... ".$q."<br>";
      // exit;
}

function LoginTableUpdate($conn, $tableName, $field, $data)
{
      $loginEdit = "UPDATE $tableName SET $field[1] = '$data[1]' WHERE $field[0] = '$data[0]'";
      // echo "$loginEdit";
      // exit;
      mysqli_query($conn, $loginEdit);
}

function LoginTableDelete($conn, $table, $loginUsername, $userNameValue)
{
      $loginDelete = "DELETE FROM $table WHERE $loginUsername = '$userNameValue'";
      mysqli_query($conn, $loginDelete);
}
