<?php
// Database connection details
$servername = "";
$username = "";
$password = "";
$dbname = "";

// Discord webhook URL
$webhookUrl = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current day of the week (Monday, Tuesday, etc.)
$currentDayOfWeek = date('l');

// Convert the day to lowercase for comparison with the database field
$currentDayOfWeekLowercase = strtolower($currentDayOfWeek);

// Query to fetch lessons for the current day
$sql = "SELECT LessonName, Room, Teacher, Manditory, TimeStart, TimeEnd, ModuleName 
        FROM TimetableGeneric 
        WHERE LOWER(DayOfWeek) = '$currentDayOfWeekLowercase'";
$result = $conn->query($sql);

// If there are lessons scheduled for today
if ($result->num_rows > 0) {
    $lessons = array();
    while($row = $result->fetch_assoc()) {
        // Prepare lesson data for each row
        $lessonData = array(
            "title" => $row["LessonName"],
            "description" => "Room: " . $row["Room"] . "\nTeacher: " . $row["Teacher"] . "\nManditory: " . $row["Manditory"] . "\nTime Lesson Start: " . $row["TimeStart"] . "\nTime Lesson Ends: " . $row["TimeEnd"],
            "color" => 5417907,
            "author" => array(
                "name" => $row["ModuleName"]
            )
        );
        array_push($lessons, $lessonData);
    }

    // Close the database connection
    $conn->close();

    // Prepare Discord message
    $discordMessage = array(
        "content" => "Timetable for Today:",
        "embeds" => $lessons,
        "username" => "Timetable",
        "avatar_url" => "",
        "attachments" => []
    );

    // Send message to Discord using cURL
    $data_string = json_encode($discordMessage);

    $ch = curl_init($webhookUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string))
    );

    $result = curl_exec($ch);
    curl_close($ch);
}

// Redirect to Google after executing the Discord message
header("Location: https://www.google.com");
exit(); // Ensure that subsequent code is not executed after redirection

?>