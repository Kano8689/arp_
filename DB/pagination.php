<?php
function getPaginationParams()
{
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : 10;
    return [$page, $limit];
}

function paginationUI($conn, $table, $currentPage, $limit, $whereSQL = '', $filterQuery = '')
{

    $currentPage = (int) $currentPage;
    $limit = (int) $limit;

    // Count total records (with optional filter)
    $result = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM $table $whereSQL");
    $row = mysqli_fetch_assoc($result);
    $totalRecords = $row['cnt'];

    $totalPages = max(1, ceil($totalRecords / $limit)); // Always at least 1 page

    $filterQuery = '';
    if (isset($_GET['filterName']) && $_GET['filterName'] !== '')
        $filterQuery .= '&filterName=' . urlencode($_GET['filterName']);
    if (isset($_GET['filterSem']) && $_GET['filterSem'] !== '')
        $filterQuery .= '&filterSem=' . urlencode($_GET['filterSem']);
    if (isset($_GET['filterDep']) && $_GET['filterDep'] !== '')
        $filterQuery .= '&filterDep=' . urlencode($_GET['filterDep']);
    if (isset($_GET['filterGrad']) && $_GET['filterGrad'] !== '')
        $filterQuery .= '&filterGrad=' . urlencode($_GET['filterGrad']);
    if (isset($_GET['filterName_faculty']) && $_GET['filterName_faculty'] !== '')
        $filterQuery .= '&filterName_faculty=' . urlencode($_GET['filterName_faculty']);
    if (isset($_GET['filterCode_faculty']) && $_GET['filterCode_faculty'] !== '')
        $filterQuery .= '&filterCode_faculty=' . urlencode($_GET['filterCode_faculty']);
    if (isset($_GET['filterDept_faculty']) && $_GET['filterDept_faculty'] !== '')
        $filterQuery .= '&filterDept_faculty=' . urlencode($_GET['filterDept_faculty']);
    if (isset($_GET['filterEmail_faculty']) && $_GET['filterEmail_faculty'] !== '')
        $filterQuery .= '&filterEmail_faculty=' . urlencode($_GET['filterEmail_faculty']);
    if (isset($_GET['filterProgram']) && $_GET['filterProgram'] !== '')
        $filterQuery .= '&filterProgram=' . urlencode($_GET['filterProgram']);
    if (isset($_GET['filterEnNo']) && $_GET['filterEnNo'] !== '')
        $filterQuery .= '&filterEnNo=' . urlencode($_GET['filterEnNo']);
    if (isset($_GET['filterName_student']) && $_GET['filterName_student'] !== '')
        $filterQuery .= '&filterName_student=' . urlencode($_GET['filterName_student']);
    if (isset($_GET['filterAdYear']) && $_GET['filterAdYear'] !== '')
        $filterQuery .= '&filterAdYear=' . urlencode($_GET['filterAdYear']);
    if (isset($_GET['filter_academic_year']) && $_GET['filter_academic_year'] !== '')
        $filterQuery .= '&filter_academic_year=' . urlencode($_GET['filter_academic_year']);

    if (isset($_GET['filter_semester_type']) && $_GET['filter_semester_type'] !== '')
        $filterQuery .= '&filter_semester_type=' . urlencode($_GET['filter_semester_type']);

    if (isset($_GET['filter_program']) && $_GET['filter_program'] !== '')
        $filterQuery .= '&filter_program=' . urlencode($_GET['filter_program']);

    if (isset($_GET['filter_student']) && $_GET['filter_student'] !== '')
        $filterQuery .= '&filter_student=' . urlencode($_GET['filter_student']);

    if (isset($_GET['filter_course']) && $_GET['filter_course'] !== '')
        $filterQuery .= '&filter_course=' . urlencode($_GET['filter_course']);

    if (isset($_GET['filter_faculty']) && $_GET['filter_faculty'] !== '')
        $filterQuery .= '&filter_faculty=' . urlencode($_GET['filter_faculty']);

    if (isset($_GET['filter_slot']) && $_GET['filter_slot'] !== '')
        $filterQuery .= '&filter_slot=' . urlencode($_GET['filter_slot']);


    if (isset($_GET['filter_faculty_ceo']) && $_GET['filter_faculty_ceo'] !== '')
        $filterQuery .= '&filter_faculty_ceo=' . urlencode($_GET['filter_faculty_ceo']);

    if (isset($_GET['filter_coursecode_ceo']) && $_GET['filter_coursecode_ceo'] !== '')
        $filterQuery .= '&filter_coursecode_ceo=' . urlencode($_GET['filter_coursecode_ceo']);

    if (isset($_GET['filter_coursename_ceo']) && $_GET['filter_coursename_ceo'] !== '')
        $filterQuery .= '&filter_coursename_ceo=' . urlencode($_GET['filter_coursename_ceo']);




    echo '<div class="pagination-container">';

    // ----- Pagination Numbers -----
    echo '<div class="pagination-buttons">';

    // First / Prev
    if ($currentPage > 1) {
        echo '<a class="page-btn" href="?page=1&limit=' . $limit . $filterQuery . '">⏮ First</a>';
        echo '<a class="page-btn" href="?page=' . ($currentPage - 1) . '&limit=' . $limit . $filterQuery . '">◀ Prev</a>';
    } else {
        echo '<span class="page-btn disabled">⏮ First</span>';
        echo '<span class="page-btn disabled">◀ Prev</span>';
    }

    // Numbered pages (max 5)
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);

    for ($i = $start; $i <= $end; $i++) {
        if ($i == $currentPage) {
            echo '<span class="page-btn active">' . $i . '</span>';
        } else {
            echo '<a class="page-btn" href="?page=' . $i . '&limit=' . $limit . $filterQuery . '">' . $i . '</a>';
        }
    }

    // Next / Last
    if ($currentPage < $totalPages) {
        echo '<a class="page-btn" href="?page=' . ($currentPage + 1) . '&limit=' . $limit . $filterQuery . '">Next ▶</a>';
        echo '<a class="page-btn" href="?page=' . $totalPages . '&limit=' . $limit . $filterQuery . '">Last ⏭</a>';
    } else {
        echo '<span class="page-btn disabled">Next ▶</span>';
        echo '<span class="page-btn disabled">Last ⏭</span>';
    }

    echo '</div>';

    // ----- Entries Per Page -----
    echo '<div class="entries-select">';
    echo '<form method="get" class="entries_form">';
    echo '<label for="limit">Entries per page: </label>';
    echo '<select name="limit" id="limit" onchange="this.form.submit()">';
    $options = [5, 10, 20, 50, 100];
    foreach ($options as $opt) {
        $selected = $opt == $limit ? 'selected' : '';
        echo "<option value='$opt' $selected>$opt</option>";
    }
    echo '</select>';
    echo '<input type="hidden" name="page" value="1">';
    if (isset($_GET['filterName']) && $_GET['filterName'] !== '')
        echo '<input type="hidden" name="filterName" value="' . htmlspecialchars($_GET['filterName']) . '">';
    if (isset($_GET['filterSem']) && $_GET['filterSem'] !== '')
        echo '<input type="hidden" name="filterSem" value="' . htmlspecialchars($_GET['filterSem']) . '">';
    if (isset($_GET['filterDep']) && $_GET['filterDep'] !== '')
        echo '<input type="hidden" name="filterDep" value="' . htmlspecialchars($_GET['filterDep']) . '">';
    if (isset($_GET['filterGrad']) && $_GET['filterGrad'] !== '')
        echo '<input type="hidden" name="filterGrad" value="' . htmlspecialchars($_GET['filterGrad']) . '">';

    if (isset($_GET['filterName_faculty']) && $_GET['filterName_faculty'] !== '')
        echo '<input type="hidden" name="filterName_faculty" value="' . htmlspecialchars($_GET['filterName_faculty']) . '">';
    if (isset($_GET['filterCode_faculty']) && $_GET['filterCode_faculty'] !== '')
        echo '<input type="hidden" name="filterCode_faculty" value="' . htmlspecialchars($_GET['filterCode_faculty']) . '">';
    if (isset($_GET['filterDept_faculty']) && $_GET['filterDept_faculty'] !== '')
        echo '<input type="hidden" name="filterDept_faculty" value="' . htmlspecialchars($_GET['filterDept_faculty']) . '">';
    if (isset($_GET['filterEmail_faculty']) && $_GET['filterEmail_faculty'] !== '')
        echo '<input type="hidden" name="filterEmail_faculty" value="' . htmlspecialchars($_GET['filterEmail_faculty']) . '">';

    if (isset($_GET['filterProgram']) && $_GET['filterProgram'] !== '')
        echo '<input type="hidden" name="filterProgram" value="' . htmlspecialchars($_GET['filterProgram']) . '">';
    if (isset($_GET['filterEnNo']) && $_GET['filterEnNo'] !== '')
        echo '<input type="hidden" name="filterEnNo" value="' . htmlspecialchars($_GET['filterEnNo']) . '">';
    if (isset($_GET['filterName_student']) && $_GET['filterName_student'] !== '')
        echo '<input type="hidden" name="filterName_student" value="' . htmlspecialchars($_GET['filterName_student']) . '">';
    if (isset($_GET['filterAdYear']) && $_GET['filterAdYear'] !== '')
        echo '<input type="hidden" name="filterAdYear" value="' . htmlspecialchars($_GET['filterAdYear']) . '">';

    if (isset($_GET['filter_academic_year']) && $_GET['filter_academic_year'] !== '')
        echo '<input type="hidden" name="filter_academic_year" value="' . htmlspecialchars($_GET['filter_academic_year']) . '">';
    if (isset($_GET['filter_semester_type']) && $_GET['filter_semester_type'] !== '')
        echo '<input type="hidden" name="filter_semester_type" value="' . htmlspecialchars($_GET['filter_semester_type']) . '">';
    if (isset($_GET['filter_program']) && $_GET['filter_program'] !== '')
        echo '<input type="hidden" name="filter_program" value="' . htmlspecialchars($_GET['filter_program']) . '">';
    if (isset($_GET['filter_student']) && $_GET['filter_student'] !== '')
        echo '<input type="hidden" name="filter_student" value="' . htmlspecialchars($_GET['filter_student']) . '">';
    if (isset($_GET['filter_course']) && $_GET['filter_course'] !== '')
        echo '<input type="hidden" name="filter_course" value="' . htmlspecialchars($_GET['filter_course']) . '">';
    if (isset($_GET['filter_faculty']) && $_GET['filter_faculty'] !== '')
        echo '<input type="hidden" name="filter_faculty" value="' . htmlspecialchars($_GET['filter_faculty']) . '">';
    if (isset($_GET['filter_slot']) && $_GET['filter_slot'] !== '')
        echo '<input type="hidden" name="filter_slot" value="' . htmlspecialchars($_GET['filter_slot']) . '">';

    if (isset($_GET['filter_faculty_ceo']) && $_GET['filter_faculty_ceo'] !== '')
        echo '<input type="hidden" name="filter_faculty_ceo" value="' . htmlspecialchars($_GET['filter_faculty_ceo']) . '">';
    if (isset($_GET['filter_coursecode_ceo']) && $_GET['filter_coursecode_ceo'] !== '')
        echo '<input type="hidden" name="filter_coursecode_ceo" value="' . htmlspecialchars($_GET['filter_coursecode_ceo']) . '">';
    if (isset($_GET['filter_coursename_ceo']) && $_GET['filter_coursename_ceo'] !== '')
        echo '<input type="hidden" name="filter_coursename_ceo" value="' . htmlspecialchars($_GET['filter_coursename_ceo']) . '">';

    echo '</form>';
    echo '</div>';

    echo '</div>';

    return $totalRecords;
}

function paginationUI3($conn, $table, $currentPage, $limit, $whereSQL = '', $filterQuery = '')
{
    // $n = count($uniqueResults);
    // echo "$currentPage";
    // exit;

    $currentPage = (int) $currentPage;
    $limit = (int) $limit;

    $whereSQL = str_replace("m._id", "_id", $whereSQL);

    // Count total records (with optional filter)
    $query = "SELECT COUNT(*) as cnt FROM $table $whereSQL";
    $result = mysqli_query($conn, $query);
    
    // echo "$query<br>";
    // echo "$whereSQL<br>";
    // echo "$table<br>";
    // exit;
    
    if (!$result) {
        die('Query failed: ' . mysqli_error($conn));
    }
    $row = mysqli_fetch_assoc($result);
    $totalRecords = $row['cnt'];

    $totalPages = max(1, ceil($totalRecords / $limit)); // Always at least 1 page

    $filterQuery = '';


    if (isset($_GET['filter_faculty_ceo']) && $_GET['filter_faculty_ceo'] !== '')
        $filterQuery .= '&filter_faculty_ceo=' . urlencode($_GET['filter_faculty_ceo']);

    if (isset($_GET['filter_coursecode_ceo']) && $_GET['filter_coursecode_ceo'] !== '')
        $filterQuery .= '&filter_coursecode_ceo=' . urlencode($_GET['filter_coursecode_ceo']);

    if (isset($_GET['filter_coursename_ceo']) && $_GET['filter_coursename_ceo'] !== '')
        $filterQuery .= '&filter_coursename_ceo=' . urlencode($_GET['filter_coursename_ceo']);




    echo '<div class="pagination-container">';

    // ----- Pagination Numbers -----
    echo '<div class="pagination-buttons">';

    // First / Prev
    if ($currentPage > 1) {
        echo '<a class="page-btn" href="?page=1&limit=' . $limit . $filterQuery . '">⏮ First</a>';
        echo '<a class="page-btn" href="?page=' . ($currentPage - 1) . '&limit=' . $limit . $filterQuery . '">◀ Prev</a>';
    } else {
        echo '<span class="page-btn disabled">⏮ First</span>';
        echo '<span class="page-btn disabled">◀ Prev</span>';
    }

    // Numbered pages (max 5)
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);

    for ($i = $start; $i <= $end; $i++) {
        if ($i == $currentPage) {
            echo '<span class="page-btn active">' . $i . '</span>';
        } else {
            echo '<a class="page-btn" href="?page=' . $i . '&limit=' . $limit . $filterQuery . '">' . $i . '</a>';
        }
    }

    // Next / Last
    if ($currentPage < $totalPages) {
        echo '<a class="page-btn" href="?page=' . ($currentPage + 1) . '&limit=' . $limit . $filterQuery . '">Next ▶</a>';
        echo '<a class="page-btn" href="?page=' . $totalPages . '&limit=' . $limit . $filterQuery . '">Last ⏭</a>';
    } else {
        echo '<span class="page-btn disabled">Next ▶</span>';
        echo '<span class="page-btn disabled">Last ⏭</span>';
    }

    echo '</div>';

    // ----- Entries Per Page -----
    echo '<div class="entries-select">';
    echo '<form method="get" class="entries_form">';
    echo '<label for="limit">Entries per page: </label>';
    echo '<select name="limit" id="limit" onchange="this.form.submit()">';
    $options = [5, 10, 20, 50, 100];
    foreach ($options as $opt) {
        $selected = $opt == $limit ? 'selected' : '';
        echo "<option value='$opt' $selected>$opt</option>";
    }
    echo '</select>';
    echo '<input type="hidden" name="page" value="1">';

    if (isset($_GET['filter_faculty_ceo']) && $_GET['filter_faculty_ceo'] !== '')
        echo '<input type="hidden" name="filter_faculty_ceo" value="' . htmlspecialchars($_GET['filter_faculty_ceo']) . '">';
    if (isset($_GET['filter_coursecode_ceo']) && $_GET['filter_coursecode_ceo'] !== '')
        echo '<input type="hidden" name="filter_coursecode_ceo" value="' . htmlspecialchars($_GET['filter_coursecode_ceo']) . '">';
    if (isset($_GET['filter_coursename_ceo']) && $_GET['filter_coursename_ceo'] !== '')
        echo '<input type="hidden" name="filter_coursename_ceo" value="' . htmlspecialchars($_GET['filter_coursename_ceo']) . '">';

    echo '</form>';
    echo '</div>';

    echo '</div>';

    return $totalRecords;
}

function paginationUI2($conn, $baseQuery, $currentPage, $limit, $filterQuery = '')
{
    // Wrap the full query in a subquery for counting
    $countQuery = "SELECT COUNT(*) AS cnt FROM ($baseQuery) AS sub";
    $result = mysqli_query($conn, $countQuery);
    $row = mysqli_fetch_assoc($result);
    $totalRecords = $row['cnt'];

    $totalPages = max(1, ceil($totalRecords / $limit)); // Always at least 1 page

    // // Preserve filters in links
    // $filterQuery = '';
    // if (isset($_GET['filterName']) && $_GET['filterName'] !== '')
    //     $filterQuery .= '&filterName=' . urlencode($_GET['filterName']);
    // if (isset($_GET['filterSem']) && $_GET['filterSem'] !== '')
    //     $filterQuery .= '&filterSem=' . urlencode($_GET['filterSem']);

    echo '<div class="pagination-container">';
    echo '<div class="pagination-buttons">';

    if ($currentPage > 1) {
        echo '<a class="page-btn" href="?page=1&limit=' . $limit . $filterQuery . '">⏮ First</a>';
        echo '<a class="page-btn" href="?page=' . ($currentPage - 1) . '&limit=' . $limit . $filterQuery . '">◀ Prev</a>';
    } else {
        echo '<span class="page-btn disabled">⏮ First</span>';
        echo '<span class="page-btn disabled">◀ Prev</span>';
    }

    $totalPages = max(1, $totalPages);
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);

    for ($i = $start; $i <= $end; $i++) {
        if ($i == $currentPage) {
            echo '<span class="page-btn active">' . $i . '</span>';
        } else {
            echo '<a class="page-btn" href="?page=' . $i . '&limit=' . $limit . $filterQuery . '">' . $i . '</a>';
        }
    }

    if ($currentPage < $totalPages) {
        echo '<a class="page-btn" href="?page=' . ($currentPage + 1) . '&limit=' . $limit . $filterQuery . '">Next ▶</a>';
        echo '<a class="page-btn" href="?page=' . $totalPages . '&limit=' . $limit . $filterQuery . '">Last ⏭</a>';
    } else {
        echo '<span class="page-btn disabled">Next ▶</span>';
        echo '<span class="page-btn disabled">Last ⏭</span>';
    }

    echo '</div>';

    // Entries per page dropdown
    echo '<div class="entries-select">';
    echo '<form method="get"  class="entries_form">';
    echo '<label for="limit">Entries per page: </label>';
    echo '<select name="limit" id="limit" onchange="this.form.submit()">';
    $options = [5, 10, 20, 50, 100];
    foreach ($options as $opt) {
        $selected = $opt == $limit ? 'selected' : '';
        echo "<option value='$opt' $selected>$opt</option>";
    }
    echo '</select>';
    echo '<input type="hidden" name="page" value="1">';

    if (isset($_GET['filterOwner']) && $_GET['filterOwner'] !== '') {
        echo '<input type="hidden" name="filterOwner" value="' . htmlspecialchars($_GET['filterOwner']) . '">';
    }
    if (isset($_GET['filterCode']) && $_GET['filterCode'] !== '') {
        echo '<input type="hidden" name="filterCode" value="' . htmlspecialchars($_GET['filterCode']) . '">';
    }
    if (isset($_GET['filterName']) && $_GET['filterName'] !== '') {
        echo '<input type="hidden" name="filterName" value="' . htmlspecialchars($_GET['filterName']) . '">';
    }
    if (isset($_GET['filterType']) && $_GET['filterType'] !== '') {
        echo '<input type="hidden" name="filterType" value="' . htmlspecialchars($_GET['filterType']) . '">';
    }

    echo '</form>';
    echo '</div>';
    echo '</div>';

    return $totalRecords;
}
