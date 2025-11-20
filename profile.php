<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user data with error handling
try {
    $stmt = $conn->prepare("SELECT username, email, points, created_at FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$user) {
        throw new Exception("User data not found");
    }
    
    // Get user rank
    $rankStmt = $conn->prepare("SELECT COUNT(*) + 1 FROM users WHERE points > ?");
    $rankStmt->bind_param("i", $user['points']);
    $rankStmt->execute();
    $user['rank'] = $rankStmt->get_result()->fetch_row()[0];
    $rankStmt->close();
    
} catch (Exception $e) {
    $error = "Error loading profile: " . $e->getMessage();
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmileWell - My Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4CAF50;
            --primary-dark: #2E7D32;
            --secondary: #FFC107;
            --dark: #1A1A2E;
            --light: #F5F5F5;
            --accent: #FF5722;
            --glass: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1A1A2E, #16213E);
            color: white;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Floating bubbles background */
        .bubbles {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            top: 0;
            left: 0;
        }
        
        .bubble {
            position: absolute;
            bottom: -100px;
            background: var(--glass);
            border-radius: 50%;
            backdrop-filter: blur(5px);
            animation: rise 15s infinite ease-in;
        }
        
        .bubble:nth-child(1) {
            width: 40px;
            height: 40px;
            left: 10%;
            animation-duration: 8s;
        }
        
        .bubble:nth-child(2) {
            width: 20px;
            height: 20px;
            left: 20%;
            animation-duration: 5s;
            animation-delay: 1s;
        }
        
        .bubble:nth-child(3) {
            width: 50px;
            height: 50px;
            left: 35%;
            animation-duration: 7s;
            animation-delay: 2s;
        }
        
        .bubble:nth-child(4) {
            width: 80px;
            height: 80px;
            left: 50%;
            animation-duration: 11s;
            animation-delay: 0s;
        }
        
        .bubble:nth-child(5) {
            width: 35px;
            height: 35px;
            left: 55%;
            animation-duration: 6s;
            animation-delay: 1s;
        }
        
        .bubble:nth-child(6) {
            width: 45px;
            height: 45px;
            left: 65%;
            animation-duration: 8s;
            animation-delay: 3s;
        }
        
        .bubble:nth-child(7) {
            width: 25px;
            height: 25px;
            left: 75%;
            animation-duration: 7s;
            animation-delay: 2s;
        }
        
        .bubble:nth-child(8) {
            width: 80px;
            height: 80px;
            left: 80%;
            animation-duration: 6s;
            animation-delay: 1s;
        }
        
        @keyframes rise {
            0% {
                bottom: -100px;
                transform: translateX(0);
            }
            50% {
                transform: translateX(100px);
            }
            100% {
                bottom: 1080px;
                transform: translateX(-200px);
            }
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Profile header with glass morphism */
        .profile-header {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .profile-header:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
        }
        
        .profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(76, 175, 80, 0.1) 0%, transparent 70%);
            animation: rotate 15s linear infinite;
            z-index: -1;
        }
        
        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        
        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: bold;
            color: white;
            margin-right: 30px;
            border: 3px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
        
        .avatar:hover {
            transform: scale(1.05) rotate(10deg);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }
        
        .avatar canvas {
            width: 100%;
            height: 100%;
            display: block;
        }
        
        .profile-info h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 10px rgba(76, 175, 80, 0.3);
        }
        
        .profile-info p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 5px;
        }
        
        .stats {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }
        
        .stat-card {
            background: var(--glass);
            backdrop-filter: blur(5px);
            border: 1px solid var(--glass-border);
            border-radius: 15px;
            padding: 20px;
            min-width: 150px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            background: rgba(76, 175, 80, 0.2);
        }
        
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: translateX(-100%);
            transition: 0.6s;
        }
        
        .stat-card:hover::after {
            transform: translateX(100%);
        }
        
        .stat-card h3 {
            font-size: 2rem;
            color: var(--secondary);
            margin-bottom: 5px;
        }
        
        .stat-card p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }
        
        /* Progress section */
        .progress-section {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .section-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            color: var(--secondary);
        }
        
        .section-title i {
            margin-right: 10px;
        }
        
        .progress-container {
            margin-top: 20px;
        }
        
        .progress-item {
            margin-bottom: 15px;
        }
        
        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .progress-bar {
            height: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            overflow: hidden;
            position: relative;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            border-radius: 5px;
            width: 0;
            transition: width 1.5s ease-out;
            position: relative;
        }
        
        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shine 2s infinite;
        }
        
        @keyframes shine {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(100%);
            }
        }
        
        /* Badges section */
        .badges-section {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .badges-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .badge {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            width: 100px;
            height: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .badge:hover {
            transform: scale(1.1) rotate(10deg);
            background: rgba(76, 175, 80, 0.3);
        }
        
        .badge i {
            font-size: 2rem;
            margin-bottom: 5px;
            color: var(--secondary);
        }
        
        .badge p {
            font-size: 0.8rem;
            text-align: center;
        }
        
        .badge.locked {
            filter: grayscale(100%);
            opacity: 0.5;
        }
        
        .badge.locked::before {
            content: '\f023';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2rem;
            color: rgba(255, 255, 255, 0.8);
            z-index: 1;
        }
        
        /* Recent activity */
        .activity-section {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            background: rgba(76, 175, 80, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary);
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-content p {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .activity-time {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.8rem;
        }
        
        /* Floating action button */
        .fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 100;
        }
        
        .fab:hover {
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            
            .avatar {
                margin-right: 0;
                margin-bottom: 20px;
            }
            
            .stats {
                flex-direction: column;
                align-items: center;
            }
            
            .stat-card {
                width: 100%;
            }
            
            .badges-grid {
                grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            }
            
            .badge {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>
    <!-- Floating bubbles background -->
    <div class="bubbles">
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
    </div>
    
    <div class="container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="avatar" id="userAvatar">
                <!-- Avatar will be generated by JavaScript -->
            </div>
            <div class="profile-info">
                
                <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($_SESSION['emailsaved']) ?>!</p>
                <p><i class="fas fa-calendar-alt"></i> Member since May 2025</p>
                
                <div class="stats">
                    <div class="stat-card" onclick="window.location.href='leaderboard.php'">
                        <h3><?php echo $_SESSION['rank']; ?></h3>
                        <p>Rank</p>
                    </div>
                    <div class="stat-card" onclick="window.location.href='leaderboard.php'">
                        <h3><?php echo number_format($_SESSION['points']); ?></h3>
                        <p>Points</p>
                    </div>
                    <div class="stat-card" onclick="window.location.href='#'">
                        <h3>12</h3>
                        <p>Badges</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Progress Section -->
        <div class="progress-section">
            <h2 class="section-title"><i class="fas fa-chart-line"></i> Your Progress</h2>
            <div class="progress-container">
                <div class="progress-item">
                    <div class="progress-label">
                        <span>Smile Streak</span>
                        <span>0/7 days</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 0%"></div>
                    </div>
                </div>
                <div class="progress-item">
                    <div class="progress-label">
                        <span>Challenge Completion</span>
                        <span>2/15</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 20%"></div>
                    </div>
                </div>
                <div class="progress-item">
                    <div class="progress-label">
                        <span>Community Engagement</span>
                        <span>30%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 45%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Badges Section -->
        <div class="badges-section">
            <h2 class="section-title"><i class="fas fa-trophy"></i> Your Badges</h2>
            <div class="badges-grid">
                <div class="badge">
                    <i class="fas fa-smile"></i>
                    <p>Happy Starter</p>
                </div>
                <div class="badge">
                    <i class="fas fa-fire"></i>
                    <p>3 Day Streak</p>
                </div>
                <div class="badge">
                    <i class="fas fa-star"></i>
                    <p>Top 10%</p>
                </div>
                <div class="badge">
                    <i class="fas fa-comments"></i>
                    <p>Socializer</p>
                </div>
                <div class="badge locked">
                    <i class="fas fa-medal"></i>
                    <p>7 Day Streak</p>
                </div>
                <div class="badge locked">
                    <i class="fas fa-crown"></i>
                    <p>Champion</p>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="activity-section">
            <h2 class="section-title"><i class="fas fa-history"></i> Recent Activity</h2>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-smile"></i>
                    </div>
                    <div class="activity-content">
                        <p>Completed "Daily Smile Challenge"</p>
                        <div class="activity-time">2 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="activity-content">
                        <p>Earned "Happy Starter" badge</p>
                        <div class="activity-time">1 day ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="activity-content">
                        <p>Joined "Positive Vibes" community</p>
                        <div class="activity-time">3 days ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Floating Action Button -->
    <div class="fab" onclick="window.location.href='#'">
        <i class="fas fa-plus"></i>
    </div>
    
    <script>
        // Random Avatar Generator
        function generateRandomAvatar() {
            const avatar = document.getElementById('userAvatar');
            const username = "<?php echo $user['username']; ?>";
            const colors = [
                '#FF5733', '#33FF57', '#3357FF', '#F333FF', 
                '#33FFF5', '#FF33A8', '#B833FF', '#33FFBD',
                '#4CAF50', '#FFC107', '#FF5722', '#9C27B0',
                '#2196F3', '#00BCD4', '#8BC34A', '#CDDC39'
            ];
            const shapes = ['circle', 'square', 'triangle'];
            
            // Create canvas
            const canvas = document.createElement('canvas');
            const size = 120; // Match your avatar size
            canvas.width = size;
            canvas.height = size;
            const ctx = canvas.getContext('2d');
            
            // Draw background
            ctx.fillStyle = colors[Math.floor(Math.random() * colors.length)];
            ctx.fillRect(0, 0, size, size);
            
            // Draw random shape
            ctx.fillStyle = 'rgba(255, 255, 255, 0.7)';
            const shape = shapes[Math.floor(Math.random() * shapes.length)];
            
            switch(shape) {
                case 'circle':
                    ctx.beginPath();
                    ctx.arc(size/2, size/2, size/3, 0, Math.PI * 2);
                    ctx.fill();
                    break;
                case 'square':
                    ctx.fillRect(size/3, size/3, size/3, size/3);
                    break;
                case 'triangle':
                    ctx.beginPath();
                    ctx.moveTo(size/2, size/3);
                    ctx.lineTo(size/3, size*2/3);
                    ctx.lineTo(size*2/3, size*2/3);
                    ctx.closePath();
                    ctx.fill();
                    break;
            }
            
            // Add initial letter
            ctx.font = 'bold 48px Poppins';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillStyle = 'white';
            ctx.fillText(username.charAt(0).toUpperCase(), size/2, size/2);
            
            // Add canvas to avatar
            avatar.innerHTML = '';
            avatar.appendChild(canvas);
            
            // Add border color
            avatar.style.borderColor = colors[Math.floor(Math.random() * colors.length)];
        }

        // Generate avatar when page loads
        generateRandomAvatar();

        // Add click to regenerate
        document.getElementById('userAvatar').addEventListener('click', generateRandomAvatar);

        // Animate progress bars on page load
        document.addEventListener('DOMContentLoaded', function() {
            const progressFills = document.querySelectorAll('.progress-fill');
            progressFills.forEach(fill => {
                const width = fill.style.width;
                fill.style.width = '0';
                setTimeout(() => {
                    fill.style.width = width;
                }, 100);
            });
            
            // Add ripple effect to stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach(card => {
                card.addEventListener('click', function(e) {
                    const x = e.clientX - e.target.getBoundingClientRect().left;
                    const y = e.clientY - e.target.getBoundingClientRect().top;
                    
                    const ripple = document.createElement('span');
                    ripple.classList.add('ripple');
                    ripple.style.left = `${x}px`;
                    ripple.style.top = `${y}px`;
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 1000);
                });
            });
        });
    </script>
</body>
</html>