<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Timetable</title>
  <!-- Bootstrap CSS link -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2>Edit Timetable</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <?php
      // Database connection details
      $servername = "";
      $username = "";
      $password = "";
      $dbname = "";

      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);

      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      // Check if a form to edit timetable was submitted
      if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['lessonName'])) {
          // Sanitize input data
          $lessonName = mysqli_real_escape_string($conn, $_POST['lessonName']);
          $room = mysqli_real_escape_string($conn, $_POST['room']);
          $teacher = mysqli_real_escape_string($conn, $_POST['teacher']);
          $manditory = mysqli_real_escape_string($conn, $_POST['manditory']);
          $timeStart = mysqli_real_escape_string($conn, $_POST['timeStart']);
          $timeEnd = mysqli_real_escape_string($conn, $_POST['timeEnd']);
          $moduleName = mysqli_real_escape_string($conn, $_POST['moduleName']);
          $dayOfWeek = mysqli_real_escape_string($conn, $_POST['dayOfWeek']);

          // Check if the lesson already exists for the specified day
          $checkQuery = "SELECT * FROM TimetableGeneric WHERE LOWER(DayOfWeek) = LOWER('$dayOfWeek') AND LessonName = '$lessonName'";
          $checkResult = $conn->query($checkQuery);

          if ($checkResult->num_rows > 0) {
              // Update the existing lesson
              $updateQuery = "UPDATE TimetableGeneric 
                              SET Room = '$room', Teacher = '$teacher', Manditory = '$manditory', 
                                  TimeStart = '$timeStart', TimeEnd = '$timeEnd', ModuleName = '$moduleName' 
                              WHERE LOWER(DayOfWeek) = LOWER('$dayOfWeek') AND LessonName = '$lessonName'";
              $conn->query($updateQuery);
          } else {
              // Insert a new lesson
              $insertQuery = "INSERT INTO TimetableGeneric (LessonName, Room, Teacher, Manditory, TimeStart, TimeEnd, ModuleName, DayOfWeek) 
                              VALUES ('$lessonName', '$room', '$teacher', '$manditory', '$timeStart', '$timeEnd', '$moduleName', '$dayOfWeek')";
              $conn->query($insertQuery);
          }

          // Redirect to the same page after processing the form
          header("Location: " . $_SERVER['PHP_SELF']);
          exit();
      }

      // Get unique lesson names for the dropdown
      $lessonNamesQuery = "SELECT DISTINCT LessonName FROM TimetableGeneric";
      $lessonNamesResult = $conn->query($lessonNamesQuery);
      $lessonNames = [];
      if ($lessonNamesResult->num_rows > 0) {
          while ($row = $lessonNamesResult->fetch_assoc()) {
              $lessonNames[] = $row['LessonName'];
          }
      }
      ?>
      
      <div class="form-group">
        <label for="lesson">Select Lesson:</label>
        <select class="form-control" id="lesson" name="lessonName">
          <?php foreach ($lessonNames as $lesson) { ?>
            <option value="<?php echo $lesson; ?>"><?php echo $lesson; ?></option>
          <?php } ?>
        </select>
      </div>
      <!-- Add other form fields for editing lesson details (room, teacher, etc.) -->
      <!-- Example: -->
      <div class="form-group">
        <label for="room">Room:</label>
        <input type="text" class="form-control" id="room" name="room">
      </div>
      <!-- Add more fields as needed -->

      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>

  <!-- Bootstrap JS scripts -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
