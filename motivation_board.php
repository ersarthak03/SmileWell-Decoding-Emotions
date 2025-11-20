<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌈 Crazy Motivation Station! ✨</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        :root {
            --rainbow-1: #FF9AA2;
            --rainbow-2: #FFB7B2;
            --rainbow-3: #FFDAC1;
            --rainbow-4: #E2F0CB;
            --rainbow-5: #B5EAD7;
            --rainbow-6: #C7CEEA;
            --font-crazy: 'Comic Neue', 'Comic Sans MS', cursive, sans-serif;
        }
        
        body {
            font-family: var(--font-crazy);
            background: linear-gradient(45deg, var(--rainbow-1), var(--rainbow-3), var(--rainbow-5));
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        .motivation-board {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 30px;
            padding: 30px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            position: relative;
            border: 8px solid transparent;
            background-clip: padding-box;
            animation: rainbowBorder 8s infinite linear;
        }
        
        @keyframes rainbowBorder {
            0% { border-color: var(--rainbow-1); }
            16% { border-color: var(--rainbow-2); }
            32% { border-color: var(--rainbow-3); }
            48% { border-color: var(--rainbow-4); }
            64% { border-color: var(--rainbow-5); }
            80% { border-color: var(--rainbow-6); }
            100% { border-color: var(--rainbow-1); }
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .header h1 {
            font-size: 3.5em;
            color: #FF6B8B;
            text-shadow: 3px 3px 0 white, 6px 6px 0 #B5EAD7;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 1.5em;
            color: #FF6B8B;
            text-shadow: 2px 2px 0 rgba(255,255,255,0.5);
        }
        
        .motivation-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }
        
        .motivation-card {
            background: rgba(255,255,255,0.8);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: 3px dashed transparent;
            position: relative;
            overflow: hidden;
        }
        
        .motivation-card:hover {
            transform: translateY(-5px) rotate(1deg);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: #FF6B8B;
        }
        
        .motivation-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--rainbow-1), var(--rainbow-2), var(--rainbow-3), var(--rainbow-4), var(--rainbow-5), var(--rainbow-6));
        }
        
        .motivation-title {
            font-size: 1.5em;
            color: #FF6B8B;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .motivation-content {
            color: #555;
            line-height: 1.7;
            margin-bottom: 20px;
        }
        
        .motivation-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .motivation-button {
            background: #FF6B8B;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .motivation-button:hover {
            background: white;
            color: #FF6B8B;
            transform: scale(1.1);
            box-shadow: 0 0 0 2px #FF6B8B;
        }
        
        .emoji-float {
            position: fixed;
            font-size: 24px;
            animation: floatUp 5s linear infinite;
            opacity: 0;
            z-index: 10;
            pointer-events: none;
        }
        
        @keyframes floatUp {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }
        
        .crazy-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #FF6B8B;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 100;
            transition: all 0.3s ease;
        }
        
        .crazy-toggle:hover {
            transform: scale(1.1) rotate(30deg);
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .highlight {
            animation: highlightPulse 1.5s infinite;
        }
        
        @keyframes highlightPulse {
            0% { background: rgba(255,255,255,0.8); }
            50% { background: rgba(255,214,231,0.8); }
            100% { background: rgba(255,255,255,0.8); }
        }
    </style>
</head>
<body>
    <!-- Emoji Rain Container -->
    <div id="emojiRain"></div>
    
    <div class="motivation-board">
        <div class="header">
            <h1>🌟 Crazy Motivation Station! 🌈</h1>
            <p>Get pumped with these wild motivational boosts!</p>
        </div>
        
        <div class="motivation-grid">
            <!-- Card 1 -->
            <div class="motivation-card highlight">
                <h3 class="motivation-title">🚀 Productivity Power</h3>
                <div class="motivation-content">
                    You're not procrastinating, you're just doing <strong>extensive research</strong>! Now go crush those goals like a squirrel on espresso!
                </div>
                <div class="motivation-actions">
                    <button class="motivation-button" onclick="pumpMeUp(this)">
                        <i class="ph-lightning"></i> Pump Me Up!
                    </button>
                </div>
            </div>
            
            <!-- Card 2 -->
            <div class="motivation-card">
                <h3 class="motivation-title">💪 Strength Boost</h3>
                <div class="motivation-content">
                    Remember: You're made of stardust and 70% water. That makes you basically a <strong>cosmic smoothie</strong> with unlimited potential!
                </div>
                <div class="motivation-actions">
                    <button class="motivation-button" onclick="pumpMeUp(this)">
                        <i class="ph-barbell"></i> I'm Strong!
                    </button>
                </div>
            </div>
            
            <!-- Card 3 -->
            <div class="motivation-card">
                <h3 class="motivation-title">🎯 Goal Getter</h3>
                <div class="motivation-content">
                    Your dreams are like tacos - they might seem messy at first, but with the right ingredients they become <strong>absolutely delicious</strong> achievements!
                </div>
                <div class="motivation-actions">
                    <button class="motivation-button" onclick="pumpMeUp(this)">
                        <i class="ph-target"></i> Let's Taco 'Bout It!
                    </button>
                </div>
            </div>
            
            <!-- Card 4 -->
            <div class="motivation-card">
                <h3 class="motivation-title">🌈 Positivity Punch</h3>
                <div class="motivation-content">
                    Bad day? You're not failing - you're just collecting data for your <strong>epic comeback story</strong>! Cue the dramatic music!
                </div>
                <div class="motivation-actions">
                    <button class="motivation-button" onclick="pumpMeUp(this)">
                        <i class="ph-smiley-wink"></i> Stay Positive!
                    </button>
                </div>
            </div>
            
            <!-- Card 5 -->
            <div class="motivation-card">
                <h3 class="motivation-title">🧠 Brain Boost</h3>
                <div class="motivation-content">
                    Your brain has about 86 billion neurons. That's like having <strong>86 billion tiny employees</strong> working for you! Time to put them to work!
                </div>
                <div class="motivation-actions">
                    <button class="motivation-button" onclick="pumpMeUp(this)">
                        <i class="ph-brain"></i> Brain Power!
                    </button>
                </div>
            </div>
            
            <!-- Card 6 -->
            <div class="motivation-card">
                <h3 class="motivation-title">🔥 Motivation Blast</h3>
                <div class="motivation-content">
                    You're not just a person - you're a <strong>human-shaped explosion of potential</strong> waiting to happen! Ka-pow! 💥
                </div>
                <div class="motivation-actions">
                    <button class="motivation-button" onclick="pumpMeUp(this)">
                        <i class="ph-fire"></i> Light My Fire!
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <button class="crazy-toggle" id="crazyToggle">🤪</button>
    
    <script>
        // Create floating emojis
        function createFloatingEmojis() {
            const emojis = ['🚀', '🌟', '🌈', '✨', '💪', '🎯', '🔥', '🧠', '💡', '🎉', '👑', '⚡'];
            const container = document.getElementById('emojiRain');
            
            setInterval(() => {
                const emoji = document.createElement('div');
                emoji.className = 'emoji-float';
                emoji.textContent = emojis[Math.floor(Math.random() * emojis.length)];
                
                // Random position
                const startPos = Math.random() * window.innerWidth;
                emoji.style.left = `${startPos}px`;
                emoji.style.bottom = '0';
                
                // Random animation duration
                const duration = 3 + Math.random() * 7;
                emoji.style.animationDuration = `${duration}s`;
                
                container.appendChild(emoji);
                
                // Remove emoji after animation
                setTimeout(() => {
                    emoji.remove();
                }, duration * 1000);
            }, 300);
        }
        
        // Toggle crazy mode
        document.getElementById('crazyToggle').addEventListener('click', function() {
            document.body.classList.toggle('crazy-mode');
            this.textContent = document.body.classList.contains('crazy-mode') ? '😵' : '🤪';
            
            if (document.body.classList.contains('crazy-mode')) {
                document.documentElement.style.setProperty('--rainbow-1', '#FF00FF');
                document.documentElement.style.setProperty('--rainbow-2', '#00FFFF');
                document.documentElement.style.setProperty('--rainbow-3', '#FFFF00');
                document.documentElement.style.setProperty('--rainbow-4', '#FF00FF');
                document.documentElement.style.setProperty('--rainbow-5', '#00FFFF');
                document.documentElement.style.setProperty('--rainbow-6', '#FFFF00');
            } else {
                document.documentElement.style.setProperty('--rainbow-1', '#FF9AA2');
                document.documentElement.style.setProperty('--rainbow-2', '#FFB7B2');
                document.documentElement.style.setProperty('--rainbow-3', '#FFDAC1');
                document.documentElement.style.setProperty('--rainbow-4', '#E2F0CB');
                document.documentElement.style.setProperty('--rainbow-5', '#B5EAD7');
                document.documentElement.style.setProperty('--rainbow-6', '#C7CEEA');
            }
        });
        
        // Pump me up function
        function pumpMeUp(button) {
            const card = button.closest('.motivation-card');
            
            // Add explosion effect
            const explosion = document.createElement('div');
            explosion.innerHTML = '💥🔥⚡';
            explosion.style.position = 'absolute';
            const rect = button.getBoundingClientRect();
            explosion.style.left = `${rect.left + rect.width/2}px`;
            explosion.style.top = `${rect.top}px`;
            explosion.style.fontSize = '24px';
            explosion.style.animation = 'explode 1s forwards';
            explosion.style.transform = 'translate(-50%, -50%)';
            explosion.style.zIndex = '100';
            document.body.appendChild(explosion);
            
            // Card animation
            card.style.animation = 'bounce 0.5s 3';
            card.classList.add('highlight');
            
            // Create motivational emoji burst
            for (let i = 0; i < 10; i++) {
                setTimeout(() => {
                    const emoji = document.createElement('div');
                    emoji.className = 'emoji-float';
                    emoji.textContent = ['💪', '🔥', '⚡', '🚀', '🌟'][Math.floor(Math.random() * 5)];
                    emoji.style.left = `${rect.left + Math.random() * rect.width}px`;
                    emoji.style.top = `${rect.top}px`;
                    emoji.style.animationDuration = `${1 + Math.random() * 2}s`;
                    document.body.appendChild(emoji);
                    
                    setTimeout(() => {
                        emoji.remove();
                    }, 3000);
                }, i * 100);
            }
            
            setTimeout(() => {
                explosion.remove();
                card.classList.remove('highlight');
            }, 1000);
        }
        
        // Start animations
        window.addEventListener('load', () => {
            createFloatingEmojis();
            
            // Random card highlighting
            setInterval(() => {
                const cards = document.querySelectorAll('.motivation-card');
                cards.forEach(card => card.classList.remove('highlight'));
                
                if (cards.length > 0) {
                    const randomCard = cards[Math.floor(Math.random() * cards.length)];
                    randomCard.classList.add('highlight');
                    
                    setTimeout(() => {
                        randomCard.classList.remove('highlight');
                    }, 3000);
                }
            }, 8000);
        });
    </script>
</body>
</html>