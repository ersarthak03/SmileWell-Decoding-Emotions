<?php
session_start();
require_once 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';
$points_earned = 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['smile_score']) && isset($_POST['image_data'])) {
    $smile_score = intval($_POST['smile_score']);
    $image_data = $_POST['image_data'];
    
    // Calculate points based on smile score
    if ($smile_score >= 80) {
        $points_earned = 50;
    } elseif ($smile_score >= 60) {
        $points_earned = 30;
    } elseif ($smile_score >= 40) {
        $points_earned = 15;
    } else {
        $points_earned = 5;
    }
    
    // Save image to server
    $image_name = 'smile_capture_' . $user_id . '_' . time() . '.jpg';
    $image_path = 'uploads/' . $image_name;
    file_put_contents($image_path, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image_data)));
    
    // Update database
    try {
        $stmt = $pdo->prepare("UPDATE users SET points = points + ?, last_smile_image = ? WHERE id = ?");
        $stmt->execute([$points_earned, $image_path, $user_id]);
        $message = "You earned $points_earned points for your smile!";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmileWell Live Detector</title>
    <style>
        :root {
            --primary: #4a6fa5;
            --secondary: #ff7e5f;
            --accent: #f8c537;
            --light: #f8f9fa;
            --dark: #2c3e50;
            --success: #4CAF50;
            --danger: #e74c3c;
            --border-radius: 12px;
            --box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0e5ec 0%, #c9d6ff 100%);
            color: var(--dark);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .app-container {
            width: 100%;
            max-width: 1000px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .camera-frame {
            width: 100%;
            background: #1a1a1a;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
            border: 15px solid #333;
        }

        .camera-lens {
            position: absolute;
            width: 40px;
            height: 40px;
            background: radial-gradient(circle, #4a6fa5 0%, #1a1a1a 70%);
            border-radius: 50%;
            top: 20px;
            right: 20px;
            box-shadow: 0 0 10px rgba(74, 111, 165, 0.8);
        }

        .camera-screen {
            width: 100%;
            height: 500px;
            background: #000;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #videoElement {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scaleX(-1);
        }

        .camera-controls {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 15px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 50px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #3a5a8f;
            transform: translateY(-2px);
        }

        .btn-capture {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary);
            border: 4px solid white;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .btn-capture:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(74, 111, 165, 0.6);
        }

        .result-container {
            width: 100%;
            background: white;
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: var(--box-shadow);
            display: none;
            animation: fadeIn 0.5s ease-out;
        }

        .result-header {
            color: var(--primary);
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .smile-score-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px 0;
            gap: 15px;
        }

        .smile-score {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--dark);
        }

        .smile-emoji {
            font-size: 4rem;
            margin-bottom: 10px;
        }

        .points-earned {
            background: var(--accent);
            color: var(--dark);
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 4px 10px rgba(248, 197, 55, 0.3);
        }

        .detection-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .detection-box {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }

        .detection-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .detection-label {
            background: var(--primary);
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .detection-image {
            width: 100%;
            height: auto;
            display: block;
        }

        .flash {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: white;
            opacity: 0;
            z-index: 10;
        }

        .loading {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            z-index: 5;
            display: none;
        }

        .spinner {
            border: 5px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top: 5px solid var(--accent);
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin-bottom: 15px;
        }

        .user-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-bottom: 20px;
        }

        .points-display {
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .points-icon {
            color: var(--accent);
        }

        .message {
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .success-message {
            background: rgba(76, 175, 80, 0.2);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .error-message {
            background: rgba(231, 76, 60, 0.2);
            color: var(--danger);
            border: 1px solid var(--danger);
        }

        @keyframes flash {
            0% { opacity: 0; }
            50% { opacity: 0.8; }
            100% { opacity: 0; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes pointsPop {
            0% { opacity: 0; transform: translateY(20px) scale(0.8); }
            50% { opacity: 1; transform: translateY(-10px) scale(1.1); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        @media (max-width: 768px) {
            .camera-frame {
                padding: 10px;
                border-width: 10px;
            }
            
            .camera-screen {
                height: 400px;
            }
            
            .detection-grid {
                grid-template-columns: 1fr;
            }
            
            .result-header {
                font-size: 1.5rem;
            }
            
            .smile-emoji {
                font-size: 3rem;
            }
            
            .smile-score {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="user-info">
            <h1>SmileWell Live Detector</h1>
            <div class="points-display">
                <span class="points-icon">★</span>
                <span id="pointsValue">
                    <?php 
                        $stmt = $pdo->prepare("SELECT points FROM users WHERE id = ?");
                        $stmt->execute([$user_id]);
                        $user = $stmt->fetch();
                        echo $user['points'] ?? 0;
                    ?>
                </span>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error-message' : 'success-message'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="camera-frame">
            <div class="camera-lens"></div>
            <div class="camera-screen">
                <video id="videoElement" autoplay playsinline></video>
                <div class="flash" id="flash"></div>
                <div class="loading" id="loading">
                    <div class="spinner"></div>
                    <p>Analyzing your smile...</p>
                </div>
            </div>
            <div class="camera-controls">
                <button class="btn-capture" id="captureBtn">📷</button>
            </div>
        </div>

        <div class="result-container" id="resultContainer">
            <div class="result-header">Smile Detection Results</div>
            <div id="resultsContent"></div>
        </div>
    </div>

    <canvas id="canvas" style="display:none;"></canvas>

    <script>
        // Camera elements
        const video = document.getElementById('videoElement');
        const captureBtn = document.getElementById('captureBtn');
        const canvas = document.getElementById('canvas');
        const flash = document.getElementById('flash');
        const loading = document.getElementById('loading');
        const resultContainer = document.getElementById('resultContainer');
        const resultsContent = document.getElementById('resultsContent');
        const pointsValue = document.getElementById('pointsValue');

        // Start camera
        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        facingMode: 'user', 
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    }, 
                    audio: false 
                });
                video.srcObject = stream;
            } catch (err) {
                console.error("Error accessing camera:", err);
                alert("Could not access the camera. Please ensure you've granted camera permissions.");
            }
        }

        // Capture photo
        captureBtn.addEventListener('click', async () => {
            // Flash effect
            flash.style.animation = 'flash 500ms';
            setTimeout(() => {
                flash.style.animation = '';
            }, 500);

            // Show loading
            loading.style.display = 'flex';
            resultContainer.style.display = 'none';

            // Set canvas dimensions to match video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            // Draw current video frame to canvas
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Convert canvas to data URL
            const imageData = canvas.toDataURL('image/jpeg', 0.9);
            
            // Simulate smile detection (replace with actual API call)
            setTimeout(() => {
                // For demo purposes, generate a random smile score
                const smileScore = Math.floor(Math.random() * 100);
                
                // Display results
                showResults(imageData, smileScore);
                
                // Hide loading
                loading.style.display = 'none';
                
                // Send data to server to save and update points
                sendToServer(imageData, smileScore);
            }, 2000);
        });

        // Display results
        function showResults(imageSrc, smileScore) {
            let resultsHTML = '';
            
            resultsHTML = `
                <div class="smile-score-container">
                    <div class="smile-emoji">${getSmileEmoji(smileScore)}</div>
                    <div class="smile-score">${smileScore}% Smile</div>
                    <div class="points-earned" id="pointsEarned"></div>
                </div>
                <div class="detection-grid">
                    <div class="detection-box">
                        <div class="detection-label">Your Smile Capture</div>
                        <img src="${imageSrc}" class="detection-image">
                    </div>
                </div>
            `;
            
            resultsContent.innerHTML = resultsHTML;
            resultContainer.style.display = 'block';
            resultContainer.scrollIntoView({ behavior: 'smooth' });
        }

        // Send data to server
        function sendToServer(imageData, smileScore) {
            const formData = new FormData();
            formData.append('image_data', imageData);
            formData.append('smile_score', smileScore);
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(() => {
                // Update points display based on smile score
                updatePointsDisplay(smileScore);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Update points display
        function updatePointsDisplay(smileScore) {
            let points = 0;
            
            if (smileScore >= 80) {
                points = 50;
            } else if (smileScore >= 60) {
                points = 30;
            } else if (smileScore >= 40) {
                points = 15;
            } else {
                points = 5;
            }
            
            // Show points earned animation
            const pointsEarned = document.getElementById('pointsEarned');
            pointsEarned.textContent = `+${points} Points Earned!`;
            pointsEarned.style.animation = 'pointsPop 1s ease-out';
            
            // Update total points display
            const currentPoints = parseInt(pointsValue.textContent);
            pointsValue.textContent = currentPoints + points;
        }

        // Get appropriate emoji for score
        function getSmileEmoji(score) {
            if (score > 80) return '😁';
            if (score > 60) return '😊';
            if (score > 40) return '🙂';
            return '😐';
        }

        // Initialize camera on load
        window.addEventListener('load', startCamera);
    </script>
</body>
</html>