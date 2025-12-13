<?php
header('Content-Type: application/json');
include_once("../DB/db.php");

$year = $_SESSION['year'];
$semNo = $_SESSION['sem'];
$jsonInput = json_decode(file_get_contents('php://input'), true);

if (isset($jsonInput['students'])) {
      $stdDtlId  = $jsonInput['students']['stdDtlId'] ?? 0;
      $stdCrseId = $jsonInput['students']['stdCrseId'] ?? 0;
      $resultId  = $jsonInput['students']['resultId'] ?? 0;
      $semyr  = $jsonInput['students']['semYear'] ?? 0;
      $semtyp  = $jsonInput['students']['semType'] ?? 0;
      
      $marks = $jsonInput['students']['marks'] ?? 0;
      $remarks   = $jsonInput['students']['remarks'] ?? '';

      $field = $_SESSION["MarksEnteredField"];
      
      if ($resultId <= 0) {
            $sql = "INSERT INTO $_resultTable ($_resultStdDtlId, $_resultStdCrseId, $field, $_resultResultYear, $_resultResultSemType, $_resultResultRemarks) VALUES ('$stdDtlId', '$stdCrseId', '$marks', '$semyr', '$semtyp','$remarks')";
            mysqli_query($conn, $sql);
      } else {
            $sql = "UPDATE $_resultTable SET 
                $field = '$marks',
                $_resultResultRemarks= '$remarks'
                WHERE $_resultId = '$resultId'";
            mysqli_query($conn, $sql);
      }
      // echo "$resultId<br>";
      // echo "$sql<br>";
      // exit;
      // header("Location: faculty_marks.php");
}
