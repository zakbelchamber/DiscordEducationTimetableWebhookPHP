
# Timetable Management System via Discord Webhook

This repository contains PHP files for managing and interacting with a timetable database. Below are details about each file and their functionalities:

## edittimetable.php

### Edit Timetable

This PHP file facilitates editing the timetable by providing a form to add or update lessons in the timetable database. It integrates with Bootstrap for UI components.

#### Instructions:

-   **Requirements:** Ensure the server has PHP installed and is configured to run PHP files.
-   **Setup Database:** Update the database connection details (`$servername`, `$username`, `$password`, `$dbname`) at the beginning of the file.

#### Usage:

-   Access this file through a web browser.
-   The form allows selection of existing lessons and editing their details (room, teacher, etc.).
-   Submitting the form updates or adds the lesson to the timetable.

#### Notes:

-   It's advisable to separate the database connection details into a separate configuration file for security.
-   This file combines HTML, PHP, and Bootstrap to create an interactive form for editing the timetable. Adjustments can be made to match specific styling or functionality requirements.

----------

## gettimetable.php

### Get Timetable

This PHP file fetches the timetable for the current day from the database and sends it to a Discord channel using a webhook. It automatically redirects to Google after executing the Discord message.

#### Instructions:

-   **Requirements:** Ensure the server has PHP installed and is configured to run PHP files.
-   **Setup Database:** Update the database connection details (`$servername`, `$username`, `$password`, `$dbname`) at the beginning of the file.
-   **Setup Discord Integration:** Add the Discord webhook URL (`$webhookUrl`) to the script to send the timetable data to the desired channel.

#### Usage:

-   Access this file through a web browser or trigger it programmatically.
-   It fetches lessons scheduled for the current day from the database.
-   Constructs a message with the timetable details and sends it to Discord via a webhook.
-   Redirects to Google after executing the Discord message.

#### Notes:

-   Adjust the redirection URL (`header("Location: https://www.google.com");`) to the desired destination after executing the Discord message.
-   Ensure the database table (`TimetableGeneric`) and its structure match the query used to fetch the timetable data (`SELECT LessonName, Room, ...`). Adjust queries if necessary.
-   This script uses cURL to send a JSON payload to the Discord webhook. Adjustments can be made for error handling or additional formatting before sending the data.