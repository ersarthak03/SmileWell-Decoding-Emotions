<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Happy Fun Article Zone!</title>
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
        
        .articles-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            padding: 20px;
        }
        
        .article-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
            border: 5px solid transparent;
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
        
        .article-card:hover {
            transform: translateY(-10px) rotate(2deg);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }
        
        .article-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }
        
        .article-image::after {
            content: "";
            position: absolute;
            bottom: -50px;
            left: -50px;
            right: -50px;
            height: 100px;
            background: white;
            transform: rotate(-3deg);
        }
        
        .article-content {
            padding: 25px;
            position: relative;
        }
        
        .article-title {
            font-size: 1.8em;
            margin: 0 0 15px 0;
            color: #FF6B8B;
            text-shadow: 2px 2px 0px rgba(0,0,0,0.1);
        }
        
        .article-excerpt {
            color: #555;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .article-full {
            display: none;
            padding-top: 20px;
            border-top: 2px dashed #FF6B8B;
            margin-top: 20px;
            color: #333;
            line-height: 1.7;
        }
        
        .article-full p {
            margin-bottom: 15px;
        }
        
        .read-more {
            display: inline-block;
            background: #FF6B8B;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            border: 3px dashed transparent;
            cursor: pointer;
        }
        
        .read-more:hover {
            background: white;
            color: #FF6B8B;
            border-color: #FF6B8B;
            transform: scale(1.1);
        }
        
        .emoji-float {
            position: absolute;
            font-size: 24px;
            animation: floatUp 5s linear infinite;
            opacity: 0;
            z-index: 10;
        }
        
        @keyframes floatUp {
            0% { transform: translateY(0) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
        }
        
        .category-tag {
            position: absolute;
            top: 15px;
            right: 15px;
            background: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-weight: bold;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transform: rotate(10deg);
        }
        
        .reaction-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .reaction-button {
            background: rgba(0,0,0,0.05);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .reaction-button:hover {
            transform: scale(1.2) rotate(10deg);
            background: rgba(0,0,0,0.1);
        }
        
        .reaction-button.liked {
            background: #FFD6E7;
            color: #FF6B8B;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }
        
        .header h1 {
            font-size: 3.5em;
            color: white;
            text-shadow: 3px 3px 0 #FF6B8B, 6px 6px 0 #B5EAD7;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 1.5em;
            color: white;
            text-shadow: 2px 2px 0 rgba(0,0,0,0.1);
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
        
        @keyframes explode {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(3); opacity: 0; }
        }
        
        @keyframes contentDrop {
            from { 
                transform: translateY(-20px);
                opacity: 0;
            }
            to { 
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎪 Wacky Article Wonderland! 🎪</h1>
        <p>Where learning meets 🤪 crazy fun!</p>
    </div>
    
    <div class="articles-container">
        <!-- Article 1 -->
        <div class="article-card">
            <div class="article-image" style="background-image: url('https://source.unsplash.com/random/600x400?happy');">
                <span class="category-tag">🤣 Comedy</span>
            </div>
            <div class="article-content">
                <h2 class="article-title">10 Ways to Turn Your Cat Into a Stand-Up Comedian</h2>
                <p class="article-excerpt">Discover how Mr. Whiskers went from napping champion to the funniest feline in town with these purr-fectly hilarious techniques!</p>
                
                <div class="article-full">
                    <p>🐾 <strong>1. The Classic "I Fits I Sits" Routine:</strong> Train your cat to sit in increasingly ridiculous places - fruit bowls, shoes, your face at 3am. The smaller the space, the bigger the laughs!</p>
                    
                    <p>🎤 <strong>2. Microcat Stand-Up:</strong> Build a tiny stage with a miniature microphone. Record your cat's meows and edit them into a killer 5-minute set about the struggles of being fed only 14 times a day.</p>
                    
                    <p>👔 <strong>3. Business Cat:</strong> Dress your feline in a tiny tie and have them "present" boring topics with deadpan delivery. "The budget projections for tuna acquisition... unacceptable."</p>
                    
                    <p>🤷 <strong>4. The Ignoring You Bit:</strong> Cats are natural masters of this. Just point out how they'll cuddle with anyone EXCEPT the person who buys their food and cleans their litter.</p>
                    
                    <p>🛏️ <strong>5. Bedtime Comedy:</strong> Document their nightly routine of sprinting across your body at maximum velocity exactly when you're trying to sleep.</p>
                    
                    <p>Remember, the key to feline comedy is their complete lack of awareness that they're being funny. That's what makes it purr-fect!</p>
                </div>
                
                <div class="reaction-buttons">
                    <button class="reaction-button">😂</button>
                    <button class="reaction-button">😲</button>
                    <button class="reaction-button">🤩</button>
                </div>
                <button class="read-more" onclick="toggleArticle(this)">Read More 🎭</button>
            </div>
        </div>
        
        <!-- Article 2 -->
        <div class="article-card">
            <div class="article-image" style="background-image: url('https://source.unsplash.com/random/600x400?dance');">
                <span class="category-tag">💃 Dance</span>
            </div>
            <div class="article-content">
                <h2 class="article-title">The Secret Dance Moves of Office Chairs</h2>
                <p class="article-excerpt">Your swivel chair has more rhythm than you think! Learn the hidden choreography of ergonomic furniture that will make you the star of the next office party.</p>
                
                <div class="article-full">
                    <p>💺 <strong>The Basic Spin:</strong> Start with a simple 360-degree rotation. Add dramatic arm flourishes when your boss walks by for maximum effect.</p>
                    
                    <p>🔄 <strong>The Meeting Shuffle:</strong> Subtly rotate 5 degrees left, then 5 degrees right throughout long meetings. Bonus points if you sync with colleagues.</p>
                    
                    <p>🚀 <strong>The Productivity Boost:</strong> Push off your desk for a full-speed rotation when you need creative inspiration. Warning: May cause coworkers to question your sanity.</p>
                    
                    <p>🤸 <strong>The Chair Dip:</strong> Lean back precariously while maintaining intense eye contact with a coworker. The office equivalent of a trust fall.</p>
                    
                    <p>🎶 <strong>The Wheelie:</strong> For advanced users only. Tilt back on two wheels while typing to demonstrate your complete mastery of office kinetics.</p>
                    
                    <p>Pro Tip: Combine these moves with exaggerated facial expressions for a full performance art piece that will either get you promoted or fired.</p>
                </div>
                
                <div class="reaction-buttons">
                    <button class="reaction-button">🕺</button>
                    <button class="reaction-button">💃</button>
                    <button class="reaction-button">🎶</button>
                </div>
                <button class="read-more" onclick="toggleArticle(this)">Read More 🪑</button>
            </div>
        </div>
        
        <!-- Article 3 -->
        <div class="article-card">
            <div class="article-image" style="background-image: url('https://source.unsplash.com/random/600x400?food');">
                <span class="category-tag">🍕 Food</span>
            </div>
            <div class="article-content">
                <h2 class="article-title">Pizza Toppings That Will Make You Question Reality</h2>
                <p class="article-excerpt">From marshmallow-pepperoni hybrids to existential-crisis-inducing pineapple arrangements, these toppings will blow your mind and stretch your stomach!</p>
                
                <div class="article-full">
                    <p>🍍 <strong>The Existential Pineapple:</strong> Arrange pineapple chunks to spell out "WHY?" in the center of your pizza. Serve with a side of deep philosophical questions.</p>
                    
                    <p>🍬 <strong>Sweet & Savory Chaos:</strong> Combine pepperoni, marshmallows, and gummy bears. The flavor rollercoaster will take you from "this is wrong" to "this is brilliant" in every bite.</p>
                    
                    <p>🌶️ <strong>The Inferno Illusion:</strong> Create a pepperoni spiral that looks like a black hole. Watch as coworkers hesitate before taking the first bite into the spicy unknown.</p>
                    
                    <p>🍫 <strong>Dessert Pizza Paradox:</strong> Nutella base with bacon bits and potato chips. Is it breakfast? Dessert? A cry for help? Nobody knows!</p>
                    
                    <p>🦄 <strong>Unicorn Surprise:</strong> Food coloring turns the cheese into a rainbow. The surprise? It actually tastes exactly the same as normal pizza, making you question the nature of perception.</p>
                    
                    <p>Remember: The best pizza toppings are the ones that make your Italian grandmother sigh dramatically from 5000 miles away.</p>
                </div>
                
                <div class="reaction-buttons">
                    <button class="reaction-button">🍕</button>
                    <button class="reaction-button">🤯</button>
                    <button class="reaction-button">🤤</button>
                </div>
                <button class="read-more" onclick="toggleArticle(this)">Read More 🍽️</button>
            </div>
        </div>
    </div>
    
    <button class="crazy-toggle" id="crazyToggle">🤪</button>
    
    <script>
        // Create floating emojis
        function createFloatingEmojis() {
            const emojis = ['😂', '🤪', '🎉', '✨', '🤩', '🦄', '🍕', '🐶', '🌈', '🎈'];
            const container = document.body;
            
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
            }, 500);
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
        
        // Toggle article content
        function toggleArticle(button) {
            const articleContent = button.parentElement;
            const fullContent = articleContent.querySelector('.article-full');
            const isHidden = fullContent.style.display !== 'block';
            
            if (isHidden) {
                fullContent.style.display = 'block';
                fullContent.style.animation = 'contentDrop 0.5s ease-out';
                button.textContent = 'Show Less 🙈';
                
                // Add explosion effect
                const explosion = document.createElement('div');
                explosion.innerHTML = '🎉✨🤩';
                explosion.style.position = 'absolute';
                const rect = button.getBoundingClientRect();
                explosion.style.left = `${rect.left + rect.width/2}px`;
                explosion.style.top = `${rect.top}px`;
                explosion.style.fontSize = '24px';
                explosion.style.animation = 'explode 1s forwards';
                explosion.style.transform = 'translate(-50%, -50%)';
                document.body.appendChild(explosion);
                
                setTimeout(() => {
                    explosion.remove();
                }, 1000);
            } else {
                fullContent.style.display = 'none';
                button.textContent = 'Read More 🎭';
            }
        }
        
        // Reaction buttons
        function setupReactionButtons() {
            document.querySelectorAll('.reaction-button').forEach(button => {
                button.addEventListener('click', function() {
                    this.classList.toggle('liked');
                    
                    if (this.classList.contains('liked')) {
                        this.style.transform = 'scale(1.5) rotate(360deg)';
                        setTimeout(() => {
                            this.style.transform = 'scale(1.2) rotate(10deg)';
                        }, 300);
                    } else {
                        this.style.transform = '';
                    }
                });
            });
        }
        
        // Start animations
        window.addEventListener('load', () => {
            createFloatingEmojis();
            setupReactionButtons();
        });
    </script>
</body>
</html>