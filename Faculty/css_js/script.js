// =========================
// Faculty demo data
// =========================
const facultyCourses = [
    { code: "CSE101", name: "Programming in C", type: "btech" },
    { code: "CSE201", name: "Data Structures", type: "btech" },
    { code: "CSE301", name: "Operating Systems", type: "btech" },
    { code: "CSE401", name: "Machine Learning", type: "btech" },
    { code: "BSC101", name: "Mathematics-I", type: "bsc" },
    { code: "BSC201", name: "Physics", type: "bsc" },
    { code: "BSC301", name: "Computer Networks", type: "bsc" },
    { code: "MSC101", name: "Advanced Data Science", type: "msc" },
    { code: "MSC201", name: "Deep Learning", type: "msc" },
    { code: "MSC301", name: "Cloud Computing", type: "msc" }
];

const students = [
    { roll: "stu001", name: "Alice" },
    { roll: "stu002", name: "Bob" },
    { roll: "stu003", name: "Charlie" },
    { roll: "stu004", name: "Diana" },
    { roll: "stu005", name: "Ethan" }
];

// =========================
// Functions
// =========================
function loadCourses() {
    // const year = document.getElementById("yearSelect").value;
    // const sem = document.getElementById("semSelect").value;
    // if (!year || !sem) { alert("Select year & semester"); return; }

    // const courseList = document.getElementById("courseList");
    // courseList.innerHTML = "";

    // facultyCourses.forEach(c => {
    //     const div = document.createElement("div");
    //     div.className = "course-card";
    //     div.innerHTML = `
    //     <div>
    //       <strong>${c.code}</strong><br>
    //       <span class="small-muted">${c.name}</span>
    //     </div>
    //     <button class="btn">Open</button>
    //   `;
    //     // attach click event
    //     div.querySelector("button").addEventListener("click", function () {
    //         openCourse(c.code, c.name, c.type);
    //     });
    //     courseList.appendChild(div);
    // });

    // document.getElementById("courseWrap").style.display = "block";
    // document.getElementById("marksWrap").style.display = "none";
}

function openCourse(code, name, type) {
    document.getElementById("courseWrap").style.display = "none";
    document.getElementById("marksWrap").style.display = "block";
    document.getElementById("courseTitle").innerText = `${code} - ${name}`;

    const header = document.getElementById("marksHeader");
    const body = document.getElementById("marksBody");

    if (type === "msc") {
        header.innerHTML = `<tr>
      <th>Roll No</th><th>Name</th>
      <th>CA1 (40)</th><th>CA2 (40)</th><th>Internal (20)</th>
      <th>Remarks</th><th>Action</th>
    </tr>`;
        body.innerHTML = "";
        students.forEach(s => {
            body.innerHTML += `<tr>
        <td>${s.roll}</td><td>${s.name}</td>
        <td><input type="number" max="40"></td>
        <td><input type="number" max="40"></td>
        <td><input type="number" max="20"></td>
        <td><input type="text" placeholder="Remarks"></td>
        <td><button class="btn" onclick="saveMarks('${s.name}')">Save</button></td>
      </tr>`;
        });
    } else {
        header.innerHTML = `<tr>
      <th>Roll No</th><th>Name</th>
      <th>CA1 (30)</th><th>CA2 (30)</th><th>CA3 (30)</th><th>Internal (10)</th>
      <th>Remarks</th><th>Action</th>
    </tr>`;
        body.innerHTML = "";
        students.forEach(s => {
            body.innerHTML += `<tr>
        <td>${s.roll}</td><td>${s.name}</td>
        <td><input type="number" max="30"></td>
        <td><input type="number" max="30"></td>
        <td><input type="number" max="30"></td>
        <td><input type="number" max="10"></td>
        <td><input type="text" placeholder="Remarks"></td>
        <td><button class="btn" onclick="saveMarks('${s.name}')">Save</button></td>
      </tr>`;
        });
    }

    // add save all + back buttons
    body.innerHTML += `<tr>
    <td colspan="7" style="text-align:right;">
      <button class="btn" onclick="saveAllMarks('${code}')">Save All</button>
      <button class="btn" onclick="goBackToCourses()">Back to Courses</button>
    </td>
  </tr>`;
}

function saveMarks(studentName) {
    alert("Marks saved for " + studentName);
}

function saveAllMarks(courseCode) {
    alert("All marks saved for course " + courseCode);
}

function goBackToCourses() {
    document.getElementById("marksWrap").style.display = "none";
    document.getElementById("courseWrap").style.display = "block";
}
