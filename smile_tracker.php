<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';
$smileScore = 0;
$rewardEarned = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['smile_image'])) {
    try {
        // Validate file exists and has no errors
        if (!isset($_FILES['smile_image']['error']) || $_FILES['smile_image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("File upload error");
        }

        // Create upload directory
        $uploadDir = 'uploads/';
        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                throw new Exception("Failed to create upload directory");
            }
        }

        // Generate filename
        $filename = uniqid('smile_') . '.jpg';
        $uploadPath = $uploadDir . $filename;

        // Move uploaded file with validation
        if (!move_uploaded_file($_FILES['smile_image']['tmp_name'], $uploadPath)) {
            throw new Exception("Failed to save uploaded file");
        }

        // Execute Python script with full path
        $pythonScript = 'public_html/routeoptimiser.software/smile_detection.py';
        $command = "python3 " . escapeshellarg($pythonScript) . " " . escapeshellarg($uploadPath) . " 2>&1";
        $output = shell_exec($command);

        // Validate Python output
        if (empty($output)) {
            throw new Exception("Python script returned no output");
        }

        $result = json_decode($output ?? '', true) ?? []; // Handle null safely

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON response from Python: " . json_last_error_msg() . ". Output: " . htmlspecialchars($output ?? ''));
        }

        if (!isset($result['smile_score'])) {
            throw new Exception("Python script failed. Output: " . htmlspecialchars($output ?? ''));
        }

        $smileScore = (int)$result['smile_score'];
        $rewardEarned = floor($smileScore / 10);

        // Update database
        if ($rewardEarned > 0) {
            $stmt = $conn->prepare("UPDATE users SET points = points + ? WHERE id = ?");
            $stmt->bind_param("ii", $rewardEarned, $_SESSION['user_id']);
            $stmt->execute();
            $stmt->close();
            
            $success = "Great smile! You earned $rewardEarned points!";
        } else {
            $error = "Smile more to earn points! Your score: $smileScore% (need 10%+)";
        }

    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smile Tracker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .error {
            color: #d32f2f;
            background-color: #ffebee;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .success {
            color: #388e3c;
            background-color: #e8f5e9;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .upload-area {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px 0;
            cursor: pointer;
            border-radius: 4px;
        }
        .result-area {
            margin-top: 20px;
            padding: 15px;
            background-color: #e3f2fd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Smile Tracker</h1>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error ?? ''); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success ?? ''); ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" class="upload-area">
            <h3>Upload Your Smile Photo</h3>
            <input type="file" name="smile_image" id="smile_image" accept="image/jpeg,image/png" required>
            <br>
            <button type="submit" class="btn">Analyze My Smile</button>
        </form>
        
        <?php if ($smileScore > 0): ?>
            <div class="result-area">
                <h2>Your Results</h2>
                <p><strong>Smile Score:</strong> <?php echo htmlspecialchars((string)$smileScore); ?>%</p>
                <?php if ($rewardEarned > 0): ?>
                    <p>🎉 <strong>Points Earned:</strong> <?php echo htmlspecialchars((string)$rewardEarned); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>