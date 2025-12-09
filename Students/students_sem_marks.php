<?php

use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;

include_once("../DB/db.php");
if (!isset($_SESSION[$_session_login_type]) || $_SESSION[$_session_login_type] != 4) {
    header("Location: ../");
    exit;
}
include_once("../header.php");
?>


<!-- Main -->
<div class="container">
    <!-- Title + Breadcrumb -->
    <div class="page-header">
        <h3>Students Semester Marks</h3>
        <div class="breadcrumb-box">
            <a href="../" class="crumb-link"><span class="home-emoji">🏠</span><span>Students</span></a>
            <span class="sep">›</span>
            <span class="crumb-link crumb-disabled">Semester Marks</span>
        </div>
    </div>

    <div class="card">
        <h3 style="margin:0; color:var(--amity-blue)">Semester Marks</h3>
        <div class="small-muted">Choose year and semester</div>

        <select id="yearSelect">
            <option value="">Academic Year</option>
            <option value="2024-25">2024 - 2025</option>
        </select>

        <select id="semType">
            <option value="">Semester</option>
            <option value="Fall">Fall</option>
            <option value="Summer">Summer</option>
        </select>

        <button class="btn" onclick="loadMarks()">Load</button>

        <!-- marks table -->
        <div id="marksWrap" style="margin-top:18px; display:none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Credits</th>
                        <th>CA1 (40)</th>
                        <th>CA2 (40)</th>
                        <th>Internals (20)</th>
                        <th>Total (100)</th>
                    </tr>
                </thead>
                <tbody id="marksBody"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const demoCourses = [
        { code: 'CSE5006', name: 'Relational Database', credits: 3, ca1: 32, ca2: 29, internal: 17 },
        { code: 'CSE5010', name: 'Advanced Python', credits: 2, ca1: 35, ca2: 33, internal: 16 },
        { code: 'MAT5007', name: 'Mathematics for Data Science', credits: 4, ca1: 33, ca2: 34, internal: 18 }
    ];
    function loadMarks() {
        const year = document.getElementById('yearSelect').value;
        const sem = document.getElementById('semType').value;
        if (!year || !sem) { alert('Please choose year & semester'); return; }
        const tbody = document.getElementById('marksBody');
        tbody.innerHTML = '';
        demoCourses.forEach(c => {
            const total = c.ca1 + c.ca2 + c.internal;
            tbody.innerHTML += `<tr>
        <td>${c.code}</td><td>${c.name}</td><td>${c.credits}</td>
        <td>${c.ca1}</td><td>${c.ca2}</td><td>${c.internal}</td><td><strong>${total}</strong></td>
      </tr>`;
        });
        document.getElementById('marksWrap').style.display = 'block';
    }
</script>



<?php include_once("../footer.php"); ?>