<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (!$data || !isset($data['answers'])) {
            throw new Exception('Invalid data format');
        }

        $user_id = $_SESSION['user_id'];
        $answers = $data['answers'];
        $score = 0;
        
        foreach ($answers as $q_id => $user_answer) {
            $stmt = $conn->prepare("SELECT correct_answer FROM quiz_questions WHERE id = ?");
            $stmt->bind_param("i", $q_id);
            $stmt->execute();
            $correct = $stmt->get_result()->fetch_assoc()['correct_answer'];
            
            if ($user_answer === $correct) $score++;
        }
        
        $points = $score * 10;
        if ($score >= 8) $points += 30;
        
        $conn->query("UPDATE users SET points = points + $points WHERE id = $user_id");
        $conn->query("INSERT INTO quiz_results (user_id, score, points_earned) VALUES ($user_id, $score, $points)");
        
        echo json_encode([
            'success' => true,
            'score' => $score,
            'points' => $points,
            'unlocks_game' => ($score >= 5) // Unlock game if score 5+
        ]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

$questions = $conn->query("SELECT * FROM quiz_questions ORDER BY RAND() LIMIT 10")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>SmileWell Quiz</title>
    <style>
        body {
            font-family: 'Rubik', sans-serif;
            background: linear-gradient(135deg, #6e45e2, #ff6b6b);
            color: white;
            padding: 20px;
            margin: 0;
            overflow-x: hidden;
        }
        
        #quiz-container {
            max-width: 800px;
            margin: 0 auto;
            transition: all 0.5s ease;
        }
        
        #game-container {
            display: none;
            text-align: center;
            margin-top: 30px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        #game-canvas {
            background: rgba(0,0,0,0.7);
            border-radius: 10px;
            border: 3px solid #00f7ff;
            margin: 0 auto;
            display: block;
        }
        
        .game-controls {
            margin: 15px 0;
        }
        
        .game-button {
            background: #00b4d8;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 0 10px;
            transition: all 0.2s;
        }
        
        .game-button:hover {
            transform: scale(1.05);
        }
        
        #game-score {
            font-size: 1.5em;
            margin: 10px 0;
            color: #00f7ff;
        }
        
        /* Previous quiz styles */
        .question-card {
            background: rgba(0,0,0,0.5);
            padding: 15px;
            margin: 10px 0;
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        button {
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.2s;
            margin: 5px;
        }
        
        #webcam-container {
            margin: 20px auto;
            text-align: center;
            display: none;
            background: rgba(0,0,0,0.7);
            padding: 20px;
            border-radius: 10px;
            max-width: 400px;
        }
        
        #webcam, #canvas {
            border-radius: 10px;
            border: 3px solid #00f7ff;
            margin: 10px auto;
            display: block;
            background: black;
        }
        
        #mimic-feedback {
            font-size: 1.2em;
            margin-top: 10px;
            color: #00f7ff;
            min-height: 24px;
        }
        
        .mimic-button {
            background: #00b4d8;
        }
        
        .mimic-button:hover {
            background: #0096c7;
        }
        
        .submit-mimic {
            background: #4CAF50;
        }
        
        .submit-mimic:hover {
            background: #45a049;
        }
        
        .cancel-mimic {
            background: #ff5555;
        }
        
        .cancel-mimic:hover {
            background: #ff3333;
        }
        
        .hidden {
            display: none;
        }
        
        .submit-btn {
            background: #ff00aa;
            font-size: 1.1em;
            padding: 12px 30px;
            margin-top: 20px;
        }
        
        .submit-btn:hover {
            background: #e00090;
        }
        
        .mimic-completed {
            color: #00ff00;
            font-weight: bold;
            display: none;
            margin-top: 5px;
        }
        
        .question-card.unanswered {
            border: 2px solid #ff5555;
            box-shadow: 0 0 10px rgba(255,85,85,0.5);
        }
        
        .question-card.answered {
            border: 2px solid #00ff00;
            box-shadow: 0 0 10px rgba(0,255,0,0.3);
        }
        
        label {
            display: block;
            padding: 8px;
            margin: 5px 0;
            border-radius: 5px;
            cursor: pointer;
        }
        
        label:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .webcam-controls {
            margin-top: 15px;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div id="quiz-container">
        <h1 style="text-align: center; margin-bottom: 30px;">😊 SmileWell Quiz</h1>
        <div id="webcam-container">
            <h3 id="mimic-target" style="color: #00f7ff;"></h3>
            <video id="webcam" width="320" height="240" autoplay muted></video>
            <canvas id="canvas" width="320" height="240"></canvas>
            <div class="webcam-controls">
                <button class="submit-mimic" onclick="submitMimicChallenge()">Submit Expression</button>
                <button class="cancel-mimic" onclick="stopWebcam()">Cancel Challenge</button>
            </div>
            <p id="mimic-feedback"></p>
        </div>
        <div id="questions">
            <?php foreach ($questions as $q): ?>
                <div class="question-card" data-qid="<?= $q['id'] ?>" 
                     <?= $q['question_type'] === 'mimic_face' ? 'data-is-mimic="true" data-correct-answer="'.htmlspecialchars($q['correct_answer']).'"' : '' ?>>
                    <h3><?= htmlspecialchars($q['content']) ?></h3>
                    <?php if ($q['question_type'] === 'mimic_face'): ?>
                        <button class="mimic-button" onclick="startMimicChallenge('<?= $q['id'] ?>', '<?= $q['correct_answer'] ?>')">
                            Start Mimic Challenge
                        </button>
                        <p>Make the expression and click submit when ready</p>
                        <input type="hidden" name="q_<?= $q['id'] ?>" value="">
                        <div class="mimic-completed">✓ Challenge Completed!</div>
                    <?php else: ?>
                        <?php foreach (json_decode($q['options']) as $opt): ?>
                            <label>
                                <input type="radio" name="q_<?= $q['id'] ?>" value="<?= htmlspecialchars($opt) ?>">
                                <?= htmlspecialchars($opt) ?>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="submit-btn" onclick="submitQuiz()">Submit Quiz</button>
    </div>
    
    <div id="game-container">
        <h2>🎮 SmileWell Bonus Game 🎮</h2>
        <p>Catch smileys to earn bonus points!</p>
        <div id="game-score">Score: 0</div>
        <canvas id="game-canvas" width="600" height="400"></canvas>
        <div class="game-controls">
            <button class="game-button" onclick="startGame()">Start Game</button>
            <button class="game-button" onclick="endGame()">Finish & View Leaderboard</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1"></script>
    <script>
        // Quiz functionality
        let currentStream = null;
        let currentMimicQid = null;
        let currentCorrectEmotion = null;

        async function startMimicChallenge(qid, correctEmotion) {
            try {
                currentMimicQid = qid;
                currentCorrectEmotion = correctEmotion;
                
                document.querySelectorAll('.question-card').forEach(card => {
                    card.classList.add('hidden');
                });
                
                const webcamContainer = document.getElementById('webcam-container');
                webcamContainer.style.display = 'block';
                document.getElementById('mimic-target').textContent = `Show: ${correctEmotion}`;
                document.getElementById('mimic-feedback').textContent = 'Position your face and click submit';
                
                if (currentStream) {
                    currentStream.getTracks().forEach(track => track.stop());
                }
                
                const stream = await navigator.mediaDevices.getUserMedia({ 
                    video: {
                        facingMode: 'user',
                        width: { ideal: 640 },
                        height: { ideal: 480 }
                    } 
                });
                
                const video = document.getElementById('webcam');
                video.srcObject = stream;
                currentStream = stream;
                
            } catch (error) {
                console.error("Webcam error:", error);
                document.getElementById('mimic-feedback').textContent = `Error: ${error.message}`;
                stopWebcam();
            }
        }

        function submitMimicChallenge() {
            if (!currentStream) return;
            
            const questionCard = document.querySelector(`.question-card[data-qid="${currentMimicQid}"]`);
            const answerInput = questionCard.querySelector('input[type="hidden"]');
            answerInput.value = currentCorrectEmotion;
            
            questionCard.classList.remove('unanswered');
            questionCard.classList.add('answered');
            questionCard.querySelector('.mimic-completed').style.display = 'block';
            document.getElementById('mimic-feedback').innerHTML = '<span style="color:#00ff00">✓ Expression submitted!</span>';
            
            setTimeout(() => {
                stopWebcam();
                checkAllAnswered();
            }, 1000);
        }

        function stopWebcam() {
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
                currentStream = null;
            }
            document.getElementById('webcam-container').style.display = 'none';
            document.querySelectorAll('.question-card').forEach(card => {
                card.classList.remove('hidden');
            });
        }

        function checkAllAnswered() {
            let allAnswered = true;
            
            document.querySelectorAll('.question-card').forEach(q => {
                const qid = q.dataset.qid;
                let isAnswered = false;
                
                if (q.dataset.isMimic === "true") {
                    isAnswered = q.querySelector('input[type="hidden"]').value !== '';
                } else {
                    isAnswered = q.querySelector('input[type="radio"]:checked') !== null;
                }
                
                if (!isAnswered) {
                    allAnswered = false;
                    q.classList.add('unanswered');
                    q.classList.remove('answered');
                } else {
                    q.classList.remove('unanswered');
                    q.classList.add('answered');
                }
            });
            
            return allAnswered;
        }

        async function submitQuiz() {
            if (currentStream) {
                stopWebcam();
            }
            
            if (!checkAllAnswered()) {
                alert("Please answer all questions before submitting!");
                return;
            }
            
            const answers = {};
            document.querySelectorAll('.question-card').forEach(q => {
                const qid = q.dataset.qid;
                
                if (q.dataset.isMimic === "true") {
                    answers[qid] = q.querySelector('input[type="hidden"]').value;
                } else {
                    const selected = q.querySelector('input[type="radio"]:checked');
                    if (selected) {
                        answers[qid] = selected.value;
                    }
                }
            });
            
            const submitBtn = document.querySelector('.submit-btn');
            submitBtn.disabled = true;
            submitBtn.textContent = "Submitting...";
            
            try {
                const response = await fetch('quiz.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ answers })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    confetti({
                        particleCount: 150,
                        spread: 70,
                        origin: { y: 0.6 }
                    });
                    
                    if (result.unlocks_game) {
                        // Hide quiz and show game
                        document.getElementById('quiz-container').style.display = 'none';
                        document.getElementById('game-container').style.display = 'block';
                        
                        // Initialize game
                        initGame();
                    } else {
                        // Score too low - go straight to leaderboard
                        setTimeout(() => {
                            window.location.href = 'leaderboard.php';
                        }, 2000);
                    }
                } else {
                    alert("Error: " + (result.error || 'Unknown error'));
                    submitBtn.disabled = false;
                    submitBtn.textContent = "Submit Quiz";
                }
            } catch (error) {
                alert("Network error: " + error.message);
                submitBtn.disabled = false;
                submitBtn.textContent = "Submit Quiz";
            }
        }

        // Game functionality
        let gameActive = false;
        let gameScore = 0;
        let gameCanvas, gameCtx;
        let smileys = [];
        let player = {
            x: 300,
            y: 350,
            width: 60,
            height: 60,
            speed: 5
        };
        let keys = {};
        let animationFrameId;

        function initGame() {
            gameCanvas = document.getElementById('game-canvas');
            gameCtx = gameCanvas.getContext('2d');
            
            // Set up keyboard controls
            window.addEventListener('keydown', (e) => {
                keys[e.key] = true;
            });
            
            window.addEventListener('keyup', (e) => {
                keys[e.key] = false;
            });
        }

        function startGame() {
            if (gameActive) return;
            
            gameActive = true;
            gameScore = 0;
            smileys = [];
            updateGameScore();
            
            // Create initial smileys
            for (let i = 0; i < 5; i++) {
                createSmiley();
            }
            
            // Start game loop
            gameLoop();
            
            // Start smiley spawner
            setInterval(() => {
                if (gameActive && Math.random() > 0.7) {
                    createSmiley();
                }
            }, 2000);
        }

        function endGame() {
            gameActive = false;
            if (animationFrameId) {
                cancelAnimationFrame(animationFrameId);
            }
            
            // Add bonus points based on game score
            addBonusPoints(gameScore);
            
            // Redirect to leaderboard
            window.location.href = 'leaderboard.php';
        }

        function addBonusPoints(bonus) {
            // Make AJAX call to update points
            fetch('update_points.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_id: <?= $_SESSION['user_id'] ?>,
                    bonus_points: bonus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Bonus points added:", bonus);
                } else {
                    console.error("Failed to add bonus points");
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        }

        function createSmiley() {
            smileys.push({
                x: Math.random() * (gameCanvas.width - 30),
                y: -30,
                size: 20 + Math.random() * 20,
                speed: 1 + Math.random() * 3,
                color: `hsl(${Math.random() * 360}, 70%, 60%)`
            });
        }

        function updateGameScore() {
            document.getElementById('game-score').textContent = `Score: ${gameScore}`;
        }

        function gameLoop() {
            if (!gameActive) return;
            
            // Clear canvas
            gameCtx.clearRect(0, 0, gameCanvas.width, gameCanvas.height);
            
            // Update player position
            if (keys['ArrowLeft'] || keys['a']) {
                player.x -= player.speed;
            }
            if (keys['ArrowRight'] || keys['d']) {
                player.x += player.speed;
            }
            
            // Keep player in bounds
            player.x = Math.max(0, Math.min(gameCanvas.width - player.width, player.x));
            
            // Draw player (smiley face)
            gameCtx.fillStyle = '#00f7ff';
            gameCtx.beginPath();
            gameCtx.arc(player.x + player.width/2, player.y + player.height/2, player.width/2, 0, Math.PI * 2);
            gameCtx.fill();
            
            // Draw face features
            gameCtx.fillStyle = 'white';
            gameCtx.beginPath();
            gameCtx.arc(player.x + player.width/3, player.y + player.height/3, 8, 0, Math.PI * 2);
            gameCtx.arc(player.x + player.width*2/3, player.y + player.height/3, 8, 0, Math.PI * 2);
            gameCtx.fill();
            
            gameCtx.beginPath();
            gameCtx.arc(player.x + player.width/2, player.y + player.height*2/3, 10, 0, Math.PI);
            gameCtx.strokeStyle = 'white';
            gameCtx.lineWidth = 3;
            gameCtx.stroke();
            
            // Update and draw smileys
            for (let i = smileys.length - 1; i >= 0; i--) {
                const smiley = smileys[i];
                smiley.y += smiley.speed;
                
                // Draw smiley
                gameCtx.fillStyle = smiley.color;
                gameCtx.beginPath();
                gameCtx.arc(smiley.x, smiley.y, smiley.size/2, 0, Math.PI * 2);
                gameCtx.fill();
                gameCtx.strokeStyle = 'white';
                gameCtx.lineWidth = 2;
                gameCtx.stroke();
                
                // Draw smiley face
                gameCtx.fillStyle = 'white';
                gameCtx.beginPath();
                gameCtx.arc(smiley.x - smiley.size/4, smiley.y - smiley.size/6, smiley.size/8, 0, Math.PI * 2);
                gameCtx.arc(smiley.x + smiley.size/4, smiley.y - smiley.size/6, smiley.size/8, 0, Math.PI * 2);
                gameCtx.fill();
                
                gameCtx.beginPath();
                gameCtx.arc(smiley.x, smiley.y + smiley.size/6, smiley.size/4, 0, Math.PI);
                gameCtx.strokeStyle = 'white';
                gameCtx.lineWidth = 2;
                gameCtx.stroke();
                
                // Check collision with player
                const dx = smiley.x - (player.x + player.width/2);
                const dy = smiley.y - (player.y + player.height/2);
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                if (distance < smiley.size/2 + player.width/2) {
                    gameScore += Math.floor(smiley.size);
                    updateGameScore();
                    smileys.splice(i, 1);
                    createSmiley();
                    
                    confetti({
                        particleCount: 20,
                        spread: 40,
                        origin: { 
                            x: smiley.x / gameCanvas.width,
                            y: smiley.y / gameCanvas.height
                        }
                    });
                }
                
                // Remove if off screen
                if (smiley.y > gameCanvas.height + smiley.size) {
                    smileys.splice(i, 1);
                    createSmiley();
                }
            }
            
            animationFrameId = requestAnimationFrame(gameLoop);
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            checkAllAnswered();
            
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const questionCard = this.closest('.question-card');
                    questionCard.classList.remove('unanswered');
                    questionCard.classList.add('answered');
                    checkAllAnswered();
                });
            });
        });
    </script>
</body>
</html>