<?php
session_start();

// Check if the user is logged in
//if (!isset($_SESSION['id'])) {
  //  header("Location: login.php");
    //exit();
//}

// Database connection using MySQLi
$host = 'localhost';
$dbname = 'artgallery';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch current user details
//$id = $_SESSION['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

//if (!$user) {
  //  unset($_SESSION['id']);
    //header("Location: login.php");
    //exit();
//}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];
    $account_type = $_POST['account_type'];

    // Handle profile picture upload
    $profile_picture = $user['profile_picture'];
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_name = uniqid() . '_' . basename($_FILES['profile_picture']['name']);
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $file_path)) {
            // Delete old profile picture if it exists
            if ($profile_picture && file_exists($profile_picture)) {
                unlink($profile_picture);
            }
            $profile_picture = $file_path;
        } else {
            $error = "Failed to upload profile picture.";
        }
    }

    // Update user details in the database
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ?, account_type = ?, profile_picture = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $name, $email, $password, $account_type, $profile_picture, $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $success = "Profile updated successfully!";
            // Refresh user data
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        } else {
            $notice = "No changes were made.";
        }
    } else {
        $error = "Error updating profile: " . $conn->error;
    }
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .profile-pic {
            max-width: 150px;
            max-height: 150px;
            margin-bottom: 10px;
            border-radius: 50%;
            object-fit: cover;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .error {
            background-color: #f2dede;
            color: #a94442;
        }
        .notice {
            background-color: #fcf8e3;
            color: #8a6d3b;
        }
    </style>
</head>
<body>
    <h2>Update Profile</h2>
    
    <?php if (isset($success)): ?>
        <div class="message success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if (isset($notice)): ?>
        <div class="message notice"><?php echo htmlspecialchars($notice); ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="form-group">
            <label for="password">New Password (leave blank to keep current):</label>
            <input type="password" id="password" name="password" placeholder="Enter new password">
            <small>Password must be at least 8 characters long</small>
        </div>

        <div class="form-group">
            <label for="account_type">Account Type:</label>
            <select id="account_type" name="account_type" required>
                <option value="CUSTOMER" <?php echo $user['account_type'] === 'CUSTOMER' ? 'selected' : ''; ?>>Customer</option>
                <option value="ARTIST" <?php echo $user['account_type'] === 'ARTIST' ? 'selected' : ''; ?>>Artist</option>
                <option value="PATRON" <?php echo $user['account_type'] === 'PATRON' ? 'selected' : ''; ?>>Patron</option>
            </select>
        </div>

        <div class="form-group">
            <label for="profile_picture">Profile Picture:</label>
            <?php if ($user['profile_picture'] && file_exists($user['profile_picture'])): ?>
                <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="profile-pic">
            <?php else: ?>
                <div>No profile picture uploaded</div>
            <?php endif; ?>
            <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
            <small>Max file size: 2MB (JPEG, PNG)</small>
        </div>

        <button type="submit">Update Profile</button>
        <a href="dashboard.php" style="margin-left: 10px;">Back to Dashboard</a>
    </form>
</body>
</html>