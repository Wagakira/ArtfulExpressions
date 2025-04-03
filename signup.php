<?php
session_start();
$conn = new mysqli("localhost", "root", "", "artgallery");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$signupSuccess = false;
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirmPassword"]);
    $accountType = $_POST["accountType"];

    // Validation
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword) || $accountType == "--Select Account--") {
        $errorMessage = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format.";
    } elseif ($password !== $confirmPassword) {
        $errorMessage = "Passwords do not match.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (name, email, password, account_type) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $hashedPassword, $accountType);

        if ($stmt->execute()) {
            $signupSuccess = true;
            echo "<script>
                    alert('Signup successful! Redirecting to login...');
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 3000);
                  </script>";
        } else {
            $errorMessage = "Error: " . $conn->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Artful Expressions</title>
    <link rel="stylesheet" href="signn.css">
</head>
<body>
    <div class="sweet">
        <h1>Artful Expressions</h1>
        <h2>Sign Up</h2>
        <p>Join us to stay updated on upcoming art events and support artists.</p>

        <?php if (!empty($errorMessage)): ?>
            <p style="color: red;"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <form action="signup.php" method="post">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br>

            <label for="confirmPassword">Confirm Password:</label><br>
            <input type="password" id="confirmPassword" name="confirmPassword" required><br>

            <label>Account Type:</label><br>
            <select name="accountType" required>
                <option>--Select Account--</option>
                <option value="Aesthete">Aesthete</option>
                <option value="Art patron">Art patron</option>
            </select><br><br>
            <p>Already have an account? <a href="login.php">Log in</a></p>

            <button type="submit">Sign Up</button>
        </form>

    </div>
</body>
</html>
