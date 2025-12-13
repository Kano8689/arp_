<?php
header('Content-Type: application/json');
include_once("../DB/db.php");

$year = $_SESSION['year'];
$semNo = $_SESSION['sem'];
$jsonInput = json_decode(file_get_contents('php://input'), true);

if (isset($jsonInput['students']) && is_array($jsonInput['students'])) {
      foreach ($jsonInput['students'] as $row) {
            $stdDtlId  = $row['stdDtlId'] ?? 0;
            $stdCrseId = $row['stdCrseId'] ?? 0;
            $resultId  = $row['resultId'] ?? 0;
            $semyr  = $row['semYear'] ?? 0;
            $semtyp  = $row['semType'] ?? 0;

            $marks = $row['marks'] ?? 0;
            $remarks   = $row['remarks'] ?? '';

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
      }
      // header("Location: faculty_marks.php");
}
//  else {
//       $id        = $POSTt['id'] ?? 0;
//       $stdDtlId  = $POST['stdDtlId'] ?? 0;
//       $stdCrseId = $POST['stdCrseId'] ?? 0;
//       $resultId  = $POST['resultId'] ?? 0;
//       $semyr  = $POST['semYear'] ?? 0;
//       $semtyp  = $POST['semType'] ?? 0;

//       $marks = $POST['marks'] ?? 0;
//       $remarks   = $POST['remarks'] ?? '';

//       $field = $_SESSION["MarksEnteredField"];

//       if ($resultId <= 0) {
//             $sql = "INSERT INTO $_resultTable ($_resultStdDtlId, $_resultStdCrseId, $field, $_resultResultYear, $_resultResultSemType, $_resultResultRemarks) VALUES ('$stdDtlId', '$stdCrseId', '$marks', '$semyr', '$semtyp')";
//             mysqli_query($conn, $sql);
//       } else {
//             $sql = "UPDATE $_resultTable SET 
//                 $field = '$marks',
//                 $_resultResultRemarks= '$remarks'
//                 WHERE $_resultId = '$resultId'";
//             mysqli_query($conn, $sql);
//       }
//       // header("Location: faculty_marks.php");
// }
