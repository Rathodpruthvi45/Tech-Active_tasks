<?php

function bulkInsertUsers($csvFilePath, $dbConnection)
{
    if (($handle = fopen($csvFilePath, "r")) !== FALSE) {
        // Prepare SQL statement for bulk insertion
        $insertQuery = "INSERT INTO users (user_name, email) VALUES ";

        // Prepare SQL statement for counting duplicate emails
        $countQuery = "INSERT INTO duplicate_emails (email, count) VALUES ";

        // Initialize an array to store unique email addresses
        $uniqueEmails = array();

        // Read each row from the CSV file
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Extract name and email from CSV row
            $user_name = $data[0];
            $user_email = $data[1];

            // Check if the email is already in the unique emails array
            if (in_array($user_email, $uniqueEmails)) {
                // If email is duplicate, increment the count in duplicate_emails table
                $countQuery .= "('$user_email', 1) ON DUPLICATE KEY UPDATE count = count + 1;";
            } else {
                // Add the email to the unique emails array
                $uniqueEmails[] = $user_email;

                // Append values for bulk insertion query
                $insertQuery .= "('$user_name', '$user_email'),";
            }
        }

        // Remove trailing comma from the insert query
        $insertQuery = rtrim($insertQuery, ",");

     
        if (!empty($insertQuery)) {
            mysqli_query($dbConnection, $insertQuery);
        }

      
        if (!empty($countQuery)) {
            mysqli_query($dbConnection, $countQuery);
        }

      
        fclose($handle);
    }
}

$csvFilePath = "user_details.csv";
$dbConnection = mysqli_connect("localhost", "root", "", "codeactive");

bulkInsertUsers($csvFilePath, $dbConnection);

mysqli_close($dbConnection);

?>