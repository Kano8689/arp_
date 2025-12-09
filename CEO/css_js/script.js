
$(document).ready(function () {
  $('#openAddProgramModal').click(function () {
    $('#addProgramModalContent').load('add_program.php #modalFormContent', function () {
      var modal = new bootstrap.Modal(document.getElementById('addProgramModal'));
      modal.show();

      // Initialize Bootstrap validation
      const forms = document.querySelectorAll('.needs-validation');
      Array.from(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    });
  });
});


$(document).ready(function () {

    // Open Edit Modal
    $('.editProgramBtn').click(function () {
        var programId = $(this).data('id');

        $('#editProgramModalContent').load('edit_program.php #editModalContent', function () {
            var modal = new bootstrap.Modal(document.getElementById('editProgramModal'));
            modal.show();

            // Initialize Bootstrap validation
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        });
    });

});



// add_course.php
(() => {
  const form = document.getElementById("addCourseForm");

  form.addEventListener("submit", (event) => {
    event.preventDefault();
    event.stopPropagation();

    if (!form.checkValidity()) {
      form.classList.add("was-validated");
    } else {
      // Form is valid, submit data to PHP backend here or via AJAX
      alert("Course added successfully!");
      form.reset();
      form.classList.remove("was-validated");
    }
  });
})();
