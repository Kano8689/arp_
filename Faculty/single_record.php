<?php
header('Content-Type: application/json');
include_once("../DB/db.php");

$year = $_SESSION['year'];
$semNo = $_SESSION['sem'];
// $year = $year+1;
$jsonInput = json_decode(file_get_contents('php://input'), true);



if (isset($jsonInput['students'])) {
      $id        = $jsonInput['students']['id'] ?? 0;
      $stdDtlId  = $jsonInput['students']['stdDtlId'] ?? 0;
      $stdCrseId = $jsonInput['students']['stdCrseId'] ?? 0;
      $resultId  = $jsonInput['students']['resultId'] ?? 0;
      $semyr  = $jsonInput['students']['semYear'] ?? 0;
      $semtyp  = $jsonInput['students']['semType'] ?? 0;
      
      $marks = $jsonInput['students']['marks'] ?? 0;
      $remarks   = $jsonInput['students']['remarks'] ?? '';

      $field = $_SESSION["MarksEnteredField"];
      
      if ($resultId <= 0) {
            $sql = "INSERT INTO $resultSemesterTable ($resultSemesterStdCrseId, $resultSemesterStdDtlId, $field, $resultSemesterResultRemarks, $resultSemesterResultYear, $resultSemesterResultSemType) VALUES ('$stdCrseId', '$stdDtlId', '$marks','$remarks', '$semyr', '$semtyp')";
            mysqli_query($conn, $sql);
            // unset($_SESSION['MarksEnteredField']);
      } else {
            $sql = "UPDATE $resultSemesterTable SET 
                $field = '$marks',
                $resultSemesterResultRemarks = '$remarks'
                WHERE $resultSemesterId = '$resultId'";
            mysqli_query($conn, $sql);
      }
}
