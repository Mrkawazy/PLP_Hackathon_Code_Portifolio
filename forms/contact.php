<?php
//Mysql Connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "contact_form";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Create table if it doesn't exist 
if ($conn->query("SHOW TABLES LIKE 'contacts'")->num_rows == 0) {
    $sql = "CREATE TABLE contacts (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(30) NOT NULL,
        email VARCHAR(50) NOT NULL,
        message TEXT NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    if ($conn->query($sql) === TRUE) {
        echo "Table contacts created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Validate input
    if (empty($name) || empty($email) || empty($message)) {
        echo "All fields are required.";
    } else {
        $query="INSERT INTO contacts (name, email, message) VALUES ('$name', '$email', '$message')";
        if ($conn->query($query) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
        $to="obviouscc@outlook.com";
        $headers="From: $email\r\n";
        $subject="Contact Form Submission from $name";
        mail($to, $subject, $message, $headers);
    // Here you would typically send the email or save the data to a database
    echo "Thank you, $name. Your message has been sent.";
    }
}
?>