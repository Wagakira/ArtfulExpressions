<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Artful Expressions</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">🎀ArtfulExpressions</div>
        <nav>
            <ul>
                <li><a href="home.html">Home</a></li>
                <li><a href="aboutus.html">About Us</a></li>
                <li><a href="contact.php">Collaborate with Us</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <div class="contact-container">
        <h1>Collaborate with us</h1>
        
        <?php
        // Database configuration
        $host = "localhost";
        $username = "root";
        $password = ""; // Replace with your MySQL password
        $database = "artgallery";

        // Create connection
        $conn = new mysqli($host, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $subject = $_POST['subject'];
            $message = $_POST['message'];

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO contactus (name, phone, email, subject, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $phone, $email, $subject, $message);

            // Execute the statement
            if ($stmt->execute()) {
                echo "<script>document.getElementById('successMessage').style.display = 'block';</script>";
            } else {
                echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
            }

            $stmt->close();
        }

        $conn->close();
        ?>

        <form id="contactForm" method="POST" action="contact.php">
            <div class="form-group">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <div class="input-wrapper">
                    <input type="tel" id="phone" name="phone" maxlength="10">
                </div>
                <div class="error-message" id="phoneError"></div>
            </div>

            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="subject">Subject:</label>
                <select id="subject" name="subject" required>
                    <option value="">Select a subject</option>
                    <option value="collaboration">Collaboration</option>
                    <option value="feedback">Feedback</option>
                    <option value="commission">Art Patron</option>
                    <option value="donations">Donations</option>
                </select>
            </div>

            <div class="form-group">
                <label for="message">Your Message:</label>
                <textarea id="message" name="message" required></textarea>
            </div>

            <button type="submit">Send Message</button>
        </form>
        <div class="success-message" id="successMessage" style="display:none;">
            Message sent successfully! We'll get back to you soon.
        </div>
    </div>

    <!--<script src="script.js"></script>-->
     
</body>
</html>