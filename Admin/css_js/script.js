// Add Program ------------------------------------------------------------------------------------------------------------------
function openModal(id) {
    const overlay = document.getElementById(id);
    overlay.style.display = "flex";
    requestAnimationFrame(() => overlay.classList.add("active")); // trigger animation
    document.body.classList.add("modal-open");

    // Autofocus first input
    const firstInput = overlay.querySelector("input");
    if (firstInput) firstInput.focus();

    // Add click outside listener (only once per overlay)
    overlay.addEventListener("click", function handler(e) {
        if (e.target === overlay) {
            closeModal(id);
        }
    });
}

function closeModal(id) {
    const overlay = document.getElementById(id);
    overlay.classList.remove("active");
    document.body.classList.remove("modal-open");

    // wait for animation before hiding
    setTimeout(() => {
        overlay.style.display = "none";
        // resetForm();
    }, 300); // same as CSS transition duration
    location.reload();
}

function editProgram(id, name, sem, dept, graduation, page, limit, filterName, filterSem, filterDep, filterGrad) {
    openModal('editprogramModal');
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_programName').value = name;
    document.getElementById('edit_programSem').value = sem;
    document.getElementById('edit_programDept').value = dept;
    document.getElementById('edit_graduationType').value = graduation;

    // console.log("graduation");
    // console.log(graduation);
    


    document.getElementById('edit_page').value = page;
    document.getElementById('edit_limit').value = limit;

    // Set hidden filter inputs
    document.getElementById('edit_filterName').value = filterName;
    document.getElementById('edit_filterSem').value = filterSem;
    document.getElementById('edit_filterDep').value = filterDep;
    document.getElementById('edit_filterGrad').value = filterGrad;
}




// -------------------------------------------------------------------------------------------------------------------------
// Add Course
function open1Modal(id) {
    const overlay = document.getElementById(id);
    overlay.style.display = "flex";
    requestAnimationFrame(() => overlay.classList.add("active")); // trigger animation
    document.body.classList.add("modal-open");

    // Autofocus first input
    const firstInput = overlay.querySelector("input");
    if (firstInput) firstInput.focus();

    // Add click outside listener (only once per overlay)
    overlay.addEventListener("click", function handler(e) {
        if (e.target === overlay) {
            close1Modal(id);
        }
    });
}

function close1Modal(id) {
    const overlay = document.getElementById(id);
    overlay.classList.remove("active");
    document.body.classList.remove("modal-open");

    // wait for animation before hiding
    setTimeout(() => {
        overlay.style.display = "none";
        resetForm();
    }, 300); // same as CSS transition duration
}

function editCourse(id, name, code, owner, type, theoryMarks, practicalMarks, credit, page, limit, filterOwner, filterCode, filterName, filterType) {
    open1Modal('editcourseModal');

    document.getElementById('edit_id').value = id;
    document.getElementById('edit_cName').value = name;
    document.getElementById('edit_cCode').value = code;

    var ownerSelect = document.getElementById('edit_cOwner');
    ownerSelect.value = owner;
    ownerSelect.dispatchEvent(new Event('change'));

    var typeSelect = document.getElementById('edit_cType1');
    typeSelect.value = type == 3 ? "Theory & Practical" : type == 2 ? "Practical" : type == 1 ? "Theory" : "";
    typeSelect.dispatchEvent(new Event('change'));
    typeSelect.dispatchEvent(new Event('change'));
    document.getElementById('edit_cTheory').value = theoryMarks;
    document.getElementById('edit_cPractical').value = practicalMarks;
    document.getElementById('edit_cTEP').value = credit;


    document.getElementById('edit_page').value = page;
    document.getElementById('edit_limit').value = limit;

    // Set hidden filter inputs
    document.getElementById('edit_filterName').value = filterName;
    document.getElementById('edit_filterCode').value = filterCode;
    document.getElementById('edit_filterOwner').value = filterOwner;
    document.getElementById('edit_filterType').value = filterType;

    handleCourseTypeChange1();
}

var last_type1 = 0;
function handleCourseTypeChange1() {
    var type = document.getElementById("edit_cType1").value;

    var theoryFields = document.getElementById("theoryFields1");
    var practicalFields = document.getElementById("practicalFields1");
    var totalCreditField = document.getElementById("totalCreditField1");

    theoryFields.style.display = (type == "Theory" || type === "Theory & Practical") ? "block" : "none";
    practicalFields.style.display = (type == "Practical" || type === "Theory & Practical") ? "block" : "none";
    totalCreditField.style.display = (type == "Theory" || type == "Practical" || type === "Theory & Practical") ? "block" : "none";

    if (last_type1 != type) {
        var theoryInputs = theoryFields.querySelectorAll("input, select, textarea");
        theoryInputs.forEach(function (input) {
            input.value = "";
        });

        var practicalInputs = practicalFields.querySelectorAll("input, select, textarea");
        practicalInputs.forEach(function (input) {
            input.value = "";
        });
    }

    last_type1 = type;
}

var last_type = 0;
function handleCourseTypeChange() {
    var type = document.getElementById("cType").value;

    var theoryFields = document.getElementById("theoryFields");
    var practicalFields = document.getElementById("practicalFields");
    var totalCreditField = document.getElementById("totalCreditField");

    theoryFields.style.display = (type == "Theory" || type === "Theory & Practical") ? "block" : "none";
    practicalFields.style.display = (type == "Practical" || type === "Theory & Practical") ? "block" : "none";
    totalCreditField.style.display = (type == "Theory" || type == "Practical" || type === "Theory & Practical") ? "block" : "none";


    if (last_type != type) {
        var theoryInputs = theoryFields.querySelectorAll("input, select, textarea");
        theoryInputs.forEach(function (input) {
            input.value = "";
        });

        var practicalInputs = practicalFields.querySelectorAll("input, select, textarea");
        practicalInputs.forEach(function (input) {
            input.value = "";
        });
    }

    last_type = type;
}



// -------------------------------------------------------------------------------------------------------------------------
// Add Student
function open2Modal(id) {
    const overlay = document.getElementById(id);
    overlay.style.display = "flex";
    requestAnimationFrame(() => overlay.classList.add("active")); // trigger animation
    document.body.classList.add("modal-open");

    // Autofocus first input
    const firstInput = overlay.querySelector("input");
    if (firstInput) firstInput.focus();

    // Add click outside listener (only once per overlay)
    overlay.addEventListener("click", function handler(e) {
        if (e.target === overlay) {
            close2Modal(id);
        }
    });
}

function close2Modal(id) {
    const overlay = document.getElementById(id);
    overlay.classList.remove("active");
    document.body.classList.remove("modal-open");

    // wait for animation before hiding
    setTimeout(() => {
        overlay.style.display = "none";
        resetForm();
    }, 300); // same as CSS transition duration
}

function editStudent(id, enNo, name, pName, aYear, page, limit, filterProgram, filterEnNo, filterName_student, filterAdYear) {
    open2Modal('editStudentModal');

    document.getElementById('edit_id').value = id;
    document.getElementById('editEnNo').value = enNo;
    document.getElementById('editName').value = name;
    document.getElementById('editPrgName').value = pName;
    document.getElementById('editAdYr').value = aYear;

    document.getElementById('edit_page').value = page;
    document.getElementById('edit_limit').value = limit;

    // Set hidden filter inputs
    document.getElementById('edit_filterProgram').value = filterProgram;
    document.getElementById('edit_filterEnNo').value = filterEnNo;
    document.getElementById('edit_filterName_student').value = filterName_student;
    document.getElementById('edit_filterAdYear').value = filterAdYear;

}




// -------------------------------------------------------------------------------------------------------------------------
// Add Faculty
function open3Modal(id) {
    const overlay = document.getElementById(id);
    overlay.style.display = "flex";
    requestAnimationFrame(() => overlay.classList.add("active")); // trigger animation
    document.body.classList.add("modal-open");

    // Autofocus first input
    const firstInput = overlay.querySelector("input");
    if (firstInput) firstInput.focus();

    // Add click outside listener (only once per overlay)
    overlay.addEventListener("click", function handler(e) {
        if (e.target === overlay) {
            close3Modal(id);
        }
    });
}

function close3Modal(id) {
    const overlay = document.getElementById(id);
    overlay.classList.remove("active");
    document.body.classList.remove("modal-open");

    // wait for animation before hiding
    setTimeout(() => {
        overlay.style.display = "none";
        resetForm();
    }, 300); // same as CSS transition duration
}

function editFaculty(id, code, name, dept, email, date, page, limit, filterName_faculty, filterCode_faculty, filterDept_faculty, filterEmail_faculty) {
    open3Modal('editfacultyModal');
    document.getElementById('edit_id').value = id;
    document.getElementById('fId1').value = code;
    document.getElementById('fName1').value = name;
    document.getElementById('programDept1').value = dept;
    document.getElementById('fEmail1').value = email;
    document.getElementById('fJoinDate1').value = date;

    document.getElementById('edit_page').value = page;
    document.getElementById('edit_limit').value = limit;

    // Set hidden filter inputs
    document.getElementById('edit_filterDept_faculty').value = filterDept_faculty;
    document.getElementById('edit_filterCode_faculty').value = filterCode_faculty;
    document.getElementById('edit_filterName_faculty').value = filterName_faculty;
    document.getElementById('edit_filterEmail_faculty').value = filterEmail_faculty;
    // console.log(email);
}
// -------------------------------------------------------------------------------------------------------------------------
function toggleDropdown() {
    document.getElementById("sortDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function (event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            if (dropdowns[i].classList.contains('show')) {
                dropdowns[i].classList.remove('show');
            }
        }
    }
}

// -------------------------------------------------------------------------------------------------------------------------
// Mapping
function openmapping(id) {
    const overlay = document.getElementById(id);
    overlay.style.display = "flex";
    requestAnimationFrame(() => overlay.classList.add("active")); // trigger animation
    document.body.classList.add("modal-open");

    // Autofocus first input
    const firstInput = overlay.querySelector("input");
    if (firstInput) firstInput.focus();

    // Add click outside listener (only once per overlay)
    overlay.addEventListener("click", function handler(e) {
        if (e.target === overlay) {
            closeMapping(id);
        }
    });
}


function closeMapping(id) {
    const overlay = document.getElementById(id);
    overlay.classList.remove("active");
    document.body.classList.remove("modal-open");

    // wait for animation before hiding
    setTimeout(() => {
        overlay.style.display = "none";
        resetForm();
    }, 300); // same as CSS transition duration
}


function editFacMapping(mapid, /*student_name,*/ faculty_name, course_name, slot, sem_type, slotyear, page, limit, filterAcademicYear, filterSemesterType, filterProgram, filterStudent, filterCourse, filterFaculty, filterSlot) {
    openModal('editFacMappingModal');

console.log("cmslkcvnjkasjcksjvk");
console.log(slotyear);
    
    
    if (sem_type == 1) {
        sem_val = "Fall"
    } else {
        sem_val = "Summer"
    }

    document.getElementById('edit_map_id').value = mapid;
    document.getElementById('edit_semesteryear').value = slotyear;
    document.getElementById('edit_semesterName').value = sem_val;
    document.getElementById('edit_slot').value = slot;
    document.getElementById('edit_course').value = course_name;
    // document.getElementById('edit_student').value = student_name;
    document.getElementById('edit_faculty').value = faculty_name;


    document.getElementById('edit_page').value = page;
    document.getElementById('edit_limit').value = limit;

    // Set hidden filter inputs
    document.getElementById('edit_filter_academic_year').value = filterAcademicYear;
    document.getElementById('edit_filter_semester_type').value = filterSemesterType;
    document.getElementById('edit_filter_program').value = filterProgram;
    document.getElementById('edit_filter_student').value = filterStudent;
    document.getElementById('edit_filter_course').value = filterCourse;
    document.getElementById('edit_filter_faculty').value = filterFaculty;
    document.getElementById('edit_filter_slot').value = filterSlot;

}

