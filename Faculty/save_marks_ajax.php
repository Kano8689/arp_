<?php
header('Content-Type: application/json');
include_once("../DB/db.php");

$year = $_SESSION['year'];
$semNo = $_SESSION['sem'];

// --- Bulk mode: Handle AJAX JSON data ---
$jsonInput = json_decode(file_get_contents('php://input'), true);

if (isset($jsonInput['students']) && is_array($jsonInput['students'])) {
      foreach ($jsonInput['students'] as $row) {
            // $id        = $row['id'] ?? 0;
            // $stdDtlId  = $row['stdDtlId'] ?? 0;
            // $stdCrseId = $row['stdCrseId'] ?? 0;
            // $resultId  = $row['resultId'] ?? 0;
            // $ca1       = $row['ca1'] ?? 0;
            // $ca2       = $row['ca2'] ?? 0;
            // $ca3       = $row['ca3'] ?? 0;
            // $practical = $row['practical'] ?? 0;
            // $internal  = $row['internal'] ?? 0;
            // $ttlCredit = $row['ttlCredit'] ?? 0;
            // $remarks   = $row['remarks'] ?? '';


            // if ($resultId <= 0) {
            //       // INSERT
            //       $addResult = "INSERT INTO $resultSemesterTable ($resultSemesterStdDtlId, $resultSemesterStdCrseId, $resultSemesterCa1, $resultSemesterCa2, $resultSemesterCa3, $resultSemesterPracticalMarks, $resultSemesterInternalMarks, $_resultSemesterTotalCredit, $resultSemesterResultYear, $resultSemesterResultSemType, $resultSemesterResultRemarks) VALUES('$stdDtlId', '$stdCrseId', '$ca1', '$ca2', '$ca3', '$practical', '$internal', '$ttlCredit', '$year', '$semNo', '$remarks')";
            //       mysqli_query($conn, $addResult);
            // } else {
            //       // UPDATE
            //       $editResult = "UPDATE $resultSemesterTable SET 
            //     $resultSemesterStdDtlId = '$stdDtlId',
            //     $resultSemesterStdCrseId = '$stdCrseId',
            //     $resultSemesterCa1 = '$ca1',
            //     $resultSemesterCa2 = '$ca2',
            //     $resultSemesterCa3 = '$ca3',
            //     $resultSemesterPracticalMarks = '$practical',
            //     $resultSemesterInternalMarks = '$internal',
            //     $_resultSemesterTotalCredit = '$ttlCredit',
            //     $resultSemesterResultYear = '$year',
            //     $resultSemesterResultSemType = '$semNo',
            //     $resultSemesterResultRemarks = '$remarks'
            //     WHERE $resultSemesterId = '$resultId'";
            //       mysqli_query($conn, $editResult);
            // }

            $id        = $row['id'] ?? 0;
            $stdDtlId  = $row['stdDtlId'] ?? 0;
            $stdCrseId = $row['stdCrseId'] ?? 0;
            $resultId  = $row['resultId'] ?? 0;
            $semyr  = $row['semYear'] ?? 0;
            $semtyp  = $row['semType'] ?? 0;

            $marks = $row['marks'] ?? 0;
            $remarks   = $row['remarks'] ?? '';

            $field = $_SESSION["MarksEnteredField"];
            // $remarks = $_SESSION["MarksEnteredField"] . " = $marks.|";
            // $remarks = gettype($_SESSION["MarksEnteredField"]);
            // if ($_SESSION["MarksEnteredField"] == ""){
            //       $remarks = "1";
            // }
            // else{
            //       $remarks = "0hhf";
            // }

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
      // header("Location: faculty_marks.php");
} else {

      // --- Single record mode: Handle POST from form button ---
      // $id        = $_POST['id'] ?? 0;
      // $stdDtlId  = $_POST['stdDtlId'] ?? 0;
      // $stdCrseId = $_POST['stdCrseId'] ?? 0;
      // $resultId  = $_POST['resultId'] ?? 0;
      // $ca1       = $_POST['ca1'] ?? 0;
      // $ca2       = $_POST['ca2'] ?? 0;
      // $ca3       = $_POST['ca3'] ?? 0;
      // $practical = $_POST['practical'] ?? 0;
      // $internal  = $_POST['internal'] ?? 0;
      // $ttlCredit = $_POST['ttlCredit'] ?? 0;
      // $remarks   = $_POST['remarks'] ?? '';

      // // if ($stdDtlId && $stdCrseId) {
      // if ($resultId <= 0) {
      //       // INSERT
      //       $addResult = "INSERT INTO $resultSemesterTable ($resultSemesterStdDtlId, $resultSemesterStdCrseId, $resultSemesterCa1, $resultSemesterCa2, $resultSemesterCa3, $resultSemesterPracticalMarks, $resultSemesterInternalMarks, $_resultSemesterTotalCredit, $resultSemesterResultYear, $resultSemesterResultSemType, $resultSemesterResultRemarks) VALUES('$stdDtlId', '$stdCrseId', '$ca1', '$ca2', '$ca3', '$practical', '$internal', '$ttlCredit', '$year', '$semNo', '$remarks')";
      //       mysqli_query($conn, $addResult);
      // } else {
      //       // UPDATE
      //       $editResult = "UPDATE $resultSemesterTable SET 
      //       $resultSemesterStdDtlId = '$stdDtlId',
      //       $resultSemesterStdCrseId = '$stdCrseId',
      //       $resultSemesterCa1 = '$ca1',
      //       $resultSemesterCa2 = '$ca2',
      //       $resultSemesterCa3 = '$ca3',
      //       $resultSemesterPracticalMarks = '$practical',
      //       $resultSemesterInternalMarks = '$internal',
      //       $_resultSemesterTotalCredit = '$ttlCredit',
      //       $resultSemesterResultYear = '$year',
      //       $resultSemesterResultSemType = '$semNo',
      //       $resultSemesterResultRemarks = '$remarks'
      //       WHERE $resultSemesterId = '$resultId'";
      //       mysqli_query($conn, $editResult);
      // }

      $id        = $POSTt['id'] ?? 0;
      $stdDtlId  = $POST['stdDtlId'] ?? 0;
      $stdCrseId = $POST['stdCrseId'] ?? 0;
      $resultId  = $POST['resultId'] ?? 0;
      $semyr  = $POST['semYear'] ?? 0;
      $semtyp  = $POST['semType'] ?? 0;

      $marks = $POST['marks'] ?? 0;
      $remarks   = $POST['remarks'] ?? '';

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
      // }
      // header("Location: faculty_marks.php");
}
