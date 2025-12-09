<?php
include_once("RecordsFileUpload.php");
include_once("password.php");
session_start();


class DatabaseConfiguration {
    public $host = "localhost";
    public $user = "root";
    public $pass = "";
    public $dbName = "amity_arp";
}


class CoursesTable {
    public $_tableName = "courses_table";
    public $__id = "_id";
    public $_course_owner = "course_owner_id";
    public $_course_code = "course_code";
    public $_course_name = "course_name";
    public $_course_type = "course_type";
    public $_theory_marks = "theory_marks";
    public $_practical_marks = "practical_marks";
    public $_credit_marks = "credit_marks";
    public $_created_at = "created_at";
    public $_updated_at = "updated_at";
}

class CourseOwner {
    public $_tableName = "course_owner";
    public $__id = "_id";
    public $_course_owner_name = "course_owner_name";
    public $_created_at = "created_at";
    public $_updated_at = "updated_at";
}

class DepartmentTable {
    public $_tableName = "department_table";
    public $__id = "_id";
    public $_department_name = "department_name";
    public $_created_at = "created_at";
    public $_updated_at = "updated_at";
    
}

class FacultiesDetails  {
    public $_tableName = "faculties_details";
    public $__id = "_id";
    public $_faculty_code = "faculty_code";
    public $_faculty_name = "faculty_name";
    public $_faculty_department = "facultie_department";
    public $_faculty_email = "faculty_email";
    public $_faculty_joinDate = "faculty_join_date";
    public $_faculty_password = "faculty_password";
    public $_created_at = "created_at";
    public $_updated_at = "updated_at";
}

class FreezePushPermission {
    public $_tableName = "freeze_push_permission";
    public $_id = "id";
    public $_fac_id = "fac_id";
    public $_course_id = "course_id";
    public $_ca1Freeze = "ca1_freeze";
    public $_ca1Push = "ca1_push";
    public $_ca2Freeze = "ca2_freeze";
    public $_ca2Push = "ca2_push";
    public $_ca3Freeze = "ca3_freeze";
    public $_ca3Push = "ca3_push";
    public $_internalFreeze = "internal_freeze";
    public $_internalPush = "internal_push";
    public $_labFreeze = "lab_freeze";
    public $_labPush = "lab_push";
    public $_academicYear = "academic_year";
    public $_academicSem = "academic_sem";
    public $_created_at = "created_at";
    public $_updated_at = "updated_at";
}

class LoginTable {
    public $_tableName = "login_table";
    public $__id = "_id";
    public $_username = "user_name";
    public $_password = "user_password";
    public $_user_type = "user_type";
    public $_created_at = "created_at";
    public $_updated_at = "updated_at";
}

class MappingFacultyTable {
    public $_tableName = "mapping_faculty";
    public $__id = "_id";
    public $_faculty_id = "faculty_id";
    public $_course_id = "course_id";
    public $_slot_id = "slot_id";
    public $_semester_year = "semester_year";
    public $_semester_type = "semester_type";
    public $_created_at = "created_at";
    public $_updated_at = "updated_at";
}

class MappingStudentTable {
    public $_tableName = "mapping_student";
    public $__id = "_id";
    public $_student_id = "student_id";
    public $_course_id = "course_id";
    public $_slot_id = "slot_id";
    public $_semester_year = "semester_year";
    public $_semester_type = "semester_type";
    public $_created_at = "created_at";
    public $_updated_at = "updated_at";
}

class ProgramTable {
    public $_tableName = "program_table";
    public $__id = "_id";
    public $_program_name = "program_name";
    public $_program_semester = "program_semester";
    public $_program_department = "program_department";
    public $_graduation_type = "graduation_type";
    public $_created_at = "created_at";
    public $_updated_at = "updated_at";
}

class ResultSemester {
    public $_tableName = "result_semester";
    public $__id = "_id";
    public $_std_dtl_id = "std_dtl_id";
    public $_student_course_id = "student_course_id";
    public $_ca_1 = "ca_1";
    public $_ca_2 = "ca_2";
    public $_ca_3 = "ca_3";
    public $_practical_marks = "practical_marks";
    public $_internal_marks = "internal_marks";
    public $_total_credit = "total_credit";
    public $_resultYear = "result_year";
    public $_resultSemType = "result_sem_type";
    public $_remarks = "remarks";
    public $_declare_date = "declare_date";
    public $_created_at = "created_at";
    public $_updated_at = "updated_at";
}

class SlotTable {
    public $_tableName = "slot_table";
    public $__id = "_id";
    public $_slot_name = "slot_name";
    public $_slot_type = "slot_type";
    public $_created_at = "created_at";
    public $_updated_at = "updated_at";
}

class StudentsDetails {
    public $_tableName = "students_details";
    public $__id = "_id";
    public $_enrollment_no = "enrollment_no";
    public $_student_name = "student_name";
    public $_program_id = "program_id";
    public $_year_admitted = "year_admitted";
    public $_student_password = "student_password";
    public $_created_at = "created_at";
    public $_updated_at = "updated_at";
}



$_dbConfiguration = new DatabaseConfiguration();

$_coursesTableObject = new CoursesTable();
$_courseOwnerObject = new CourseOwner();
$_departmentTableObject = new DepartmentTable();
$_facultiesDetailsObject = new FacultiesDetails();
$_freezePushPermission = new FreezePushPermission();
$_loginTableObject = new LoginTable();
$_mappingFacultyTableObject = new MappingFacultyTable();
$_mappingStudentTableObject = new MappingStudentTable();
$_programTableObject = new ProgramTable();
$_resultSemesterObject = new ResultSemester();
$_slotTableObject = new SlotTable();
$_studentsDetailsObject = new StudentsDetails();


$conn = mysqli_connect($_dbConfiguration->host, $_dbConfiguration->user, $_dbConfiguration->pass, $_dbConfiguration->dbName);

$_session_login_type = "loginType";
$_session_login_name = "loginName";
$_session_login_id = "loginName";
$_main_directory = "arp_";
$defaultLoginExtension = "@amity.blr.edu";




// Course Table Table Variables Definations
{
    $_coursesTable = $_coursesTableObject->_tableName;
    $_courseId = $_coursesTableObject->__id;
    $_courseOwnerField = $_coursesTableObject->_course_owner;
    $_courseCodeField = $_coursesTableObject->_course_code;
    $_courseNameField = $_coursesTableObject->_course_name;
    $_courseTypeField = $_coursesTableObject->_course_type;
    $_courseTheoryMarksField = $_coursesTableObject->_theory_marks;
    $_coursePracticalMarksField = $_coursesTableObject->_practical_marks;
    $_courseCreditMarksField = $_coursesTableObject->_credit_marks;
    $_courseCreatedAt = $_coursesTableObject->_created_at;
    $_courseUpdatedAt = $_coursesTableObject->_updated_at;
}

// Course Owner Table Table Variables Definations
{
    $_courseOwnerTable = $_courseOwnerObject->_tableName;
    $_courseOwnerIdField = $_courseOwnerObject->__id;
    $_courseOwnerNameField = $_courseOwnerObject->_course_owner_name;
    $_courseOwnerCreatedAt = $_courseOwnerObject->_created_at;
    $_courseOwnerUpdatedAt = $_courseOwnerObject->_updated_at;
}

// Department Table Table Variables Definations
{
    $_departmentTable = $_departmentTableObject->_tableName;
    $_departmentId = $_departmentTableObject->__id;
    $_departmentName = $_departmentTableObject->_department_name;
    $_departmentCreatedAt = $_departmentTableObject->_created_at;
    $_departmentUpdatedAt = $_departmentTableObject->_updated_at;
}

// Faculty Details Table Table Variables Definations
{
    $_facultyTable = $_facultiesDetailsObject->_tableName;
    $_facultyId = $_facultiesDetailsObject->__id;
    $_facultyCode = $_facultiesDetailsObject->_faculty_code;
    $_facultyName = $_facultiesDetailsObject->_faculty_name;
    $_facultyDepartment = $_facultiesDetailsObject->_faculty_department;
    $_facultyEmail = $_facultiesDetailsObject->_faculty_email;
    $_facultyJoinDate = $_facultiesDetailsObject->_faculty_joinDate;
    $_facultyPassword = $_facultiesDetailsObject->_faculty_password;
    $_facultyCreatedAt = $_facultiesDetailsObject->_created_at;
    $_facultyUpdatedAt = $_facultiesDetailsObject->_updated_at;
}

// Freeze Push Permission Table Variables Definations
{
    $_freezePushPermissionTable = $_freezePushPermission->_tableName;
    $_freezePushPermissionId = $_freezePushPermission->_id;
    $_freezePushPermissionFacId = $_freezePushPermission->_fac_id;
    $_freezePushPermissionCourseId = $_freezePushPermission->_course_id;
    $_freezePushPermissionFreezeCa1 = $_freezePushPermission->_ca1Freeze;
    $_freezePushPermissionPushCa1 = $_freezePushPermission->_ca1Push;
    $_freezePushPermissionFreezeCa2 = $_freezePushPermission->_ca2Freeze;
    $_freezePushPermissionPushCa2 = $_freezePushPermission->_ca2Push;
    $_freezePushPermissionFreezeCa3 = $_freezePushPermission->_ca3Freeze;
    $_freezePushPermissionPushCa3 = $_freezePushPermission->_ca3Push;
    $_freezePushPermissionFreezeInternal = $_freezePushPermission->_internalFreeze;
    $_freezePushPermissionPushInternal = $_freezePushPermission->_internalPush;
    $_freezePushPermissionFreezeLab = $_freezePushPermission->_labFreeze;
    $_freezePushPermissionPushLab = $_freezePushPermission->_labPush;
    $_freezePushPermissionAcademicYear = $_freezePushPermission->_academicYear;
    $_freezePushPermissionAcademicSem = $_freezePushPermission->_academicSem;
    $_freezePushPermissionCreatedAt = $_freezePushPermission->_created_at;
    $_freezePushPermissionUpdatedAt = $_freezePushPermission->_updated_at;
}

// Login Table Variables Definations
{
    $_loginTable = $_loginTableObject->_tableName;
    $_loginId = $_loginTableObject->__id;
    $_loginUsername = $_loginTableObject->_username;
    $_loginPassword = $_loginTableObject->_password;
    $_loginUserType = $_loginTableObject->_user_type;
    $_loginCreatedAt = $_loginTableObject->_created_at;
    $_loginUpdatedAt = $_loginTableObject->_updated_at;
}

// Mapping Faculty Table Table Variables Definations
{
    $_mappingFacultyTable = $_mappingFacultyTableObject->_tableName;
    $_mappingFacultyTblId = $_mappingFacultyTableObject->__id;
    $_mappingFacultyId = $_mappingFacultyTableObject->_faculty_id;
    $_mappingFacultyCourseId = $_mappingFacultyTableObject->_course_id;
    $_mappingFacultySlotId = $_mappingFacultyTableObject->_slot_id;
    $_mappingFacultySemesterYear = $_mappingFacultyTableObject->_semester_year;
    $_mappingFacultySemesterType = $_mappingFacultyTableObject->_semester_type;
    $_mappingFacultyCreatedAt = $_mappingFacultyTableObject->_created_at;
    $_mappingFacultyUpdatedAt = $_mappingFacultyTableObject->_updated_at;
}

// Mapping Student Table Table Variables Definations
{
    $_mappingStudentTable = $_mappingStudentTableObject->_tableName;
    $_mappingStudentTblId = $_mappingStudentTableObject->__id;
    $_mappingStudentId = $_mappingStudentTableObject->_student_id;
    $_mappingStudentCourseId = $_mappingStudentTableObject->_course_id;
    $_mappingStudentSlotId = $_mappingStudentTableObject->_slot_id;
    $_mappingStudentSemesterYear = $_mappingStudentTableObject->_semester_year;
    $_mappingStudentSemesterType = $_mappingStudentTableObject->_semester_type;
    $_mappingStudentCreatedAt = $_mappingStudentTableObject->_created_at;
    $_mappingStudentUpdatedAt = $_mappingStudentTableObject->_updated_at;
}

// Program Table Table Variables Definations
{
    $_programTableName = $_programTableObject->_tableName;
    $_programId = $_programTableObject->__id;
    $_programNameField = $_programTableObject->_program_name;
    $_programSemField = $_programTableObject->_program_semester;
    $_programDeptField = $_programTableObject->_program_department;
    $_graduationTypeField = $_programTableObject->_graduation_type;
    $_programCreatedAt = $_programTableObject->_created_at;
    $_programUpdatedAt = $_programTableObject->_updated_at;
}

// Result Semester Table Table Variables Definations
{
    $_resultTable = $_resultSemesterObject->_tableName;
    $_resultId = $_resultSemesterObject->__id;
    $_resultStdDtlId = $_resultSemesterObject->_std_dtl_id;
    $_resultStdCrseId = $_resultSemesterObject->_student_course_id;
    $_resultCa1 = $_resultSemesterObject->_ca_1;
    $_resultCa2 = $_resultSemesterObject->_ca_2;
    $_resultCa3 = $_resultSemesterObject->_ca_3;
    $_resultPracticalMarks = $_resultSemesterObject->_practical_marks;
    $_resultInternalMarks = $_resultSemesterObject->_internal_marks;
    $_resultTotalCredit = $_resultSemesterObject->_total_credit;
    $_resultResultYear = $_resultSemesterObject->_resultYear;
    $_resultResultSemType = $_resultSemesterObject->_resultSemType;
    $_resultResultRemarks = $_resultSemesterObject->_remarks;
    $_resultResultDeclareData = $_resultSemesterObject->_declare_date;
    $_resultResultCreatedAt = $_resultSemesterObject->_created_at;
    $_resultResultUpdatedAt = $_resultSemesterObject->_updated_at;
}

// Slot Table Variables Definations
{
    $_slotTable = $_slotTableObject->_tableName;
    $_slotId = $_slotTableObject->__id;
    $_slotName = $_slotTableObject->_slot_name;
    $_slotSlotType = $_slotTableObject->_slot_type;
    $_slotCreatedAt = $_slotTableObject->_created_at;
    $_slotUpdatedAt = $_slotTableObject->_updated_at;
}

// Student Details Table Table Variables Definations
{
    $_studentTable = $_studentsDetailsObject->_tableName;
    $_studentId = $_studentsDetailsObject->__id;
    $_studentCode = $_studentsDetailsObject->_enrollment_no;
    $_studentName = $_studentsDetailsObject->_student_name;
    $_studentProgram = $_studentsDetailsObject->_program_id;
    $_studentAdmitYear = $_studentsDetailsObject->_year_admitted;
    $_studentPassword = $_studentsDetailsObject->_student_password;
    $_studentCreatedAt = $_studentsDetailsObject->_created_at;
    $_studentUpdatedAt = $_studentsDetailsObject->_updated_at;
}
?>
