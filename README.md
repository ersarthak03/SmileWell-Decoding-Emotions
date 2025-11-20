# 😄 SmileWell – Real-Time Emotion Detection Platform

SmileWell is an interactive, web-based platform that detects user emotions in real time using an AI-powered facial expression model.  
The platform provides a smile score, personalized feedback, and community-driven features such as a leaderboard, likes, and comments.

---

## 🚀 Features

### 🎯 Core Functionalities
- **Real-time smile detection** using a trained AI model.
- **Live camera capture** through the web interface.
- **Backend API** for image upload and inference.
- **AI-based confidence scoring** for user smiles.

### 🌐 Platform Capabilities
- **User dashboard** with detailed smile analytics.
- **Leaderboard system** based on user engagement.
- **Community interaction:** likes, comments, reactions.
- **Admin monitoring panel** (optional, if included).

### ⚙️ Architecture Highlights
- Frontend: **HTML, CSS, JavaScript**
- Backend: **Node.js / PHP**
- Database: **MySQL**
- AI Engine: Python model exposed via API
- Hosting: **Cpanel**
- Model deployment: **Docker container** for scalability

---

## 📐 System Architecture

                       +---------------------+
                       |     User Browser    |
                       |  (Camera Capture)   |
                       +----------+----------+
                                  |
                                  v
                     +---------------------------+
                     |     Frontend (HTML/JS)    |
                     |  Smile Capture & Display  |
                     +-------------+-------------+
                                   |
                                   v
                     +---------------------------+
                     |      Backend API          |
                     |   (Node.js / PHP Layer)   |
                     +------+------+-------------+
                            |      |
                            |      +------------------------------+
                            |                                     |
                            v                                     v
              +----------------------+             +---------------------------+
              |  Image Ingest Service |             |   Community Service      |
              | (validates & forwards)|             | Likes / Comments / Share |
              +-----------+----------+             +-----------+---------------+
                          |                                    |
                          v                                    |
                +-------------------+                         |
                |  Message Queue    |                         |
                | RabbitMQ / SQS    |                         |
                +---------+---------+                         |
                          |                                   |
                          v                                   |
                +-----------------------+                     |
                |     Worker Service    |                     |
                | (Async Processing)    |                     |
                +----+---------+--------+                     |
                     |         |                              |
                     v         v                              v
   +----------------------+   +--------------------+   +-----------------------+
   |  AI Model Server     |   |  Leaderboard Svc   |   |   Games Service       |
   | (Docker + ML Model)  |   | (Redis + DB Views) |   | Challenges / Scores   |
   +----------+-----------+   +----------+---------+   +-----------+-----------+
              |                          |                         |
              v                          v                         v
   +----------------------+   +--------------------+    +-----------------------+
   |      Database        |   |     Redis Cache     |   |  Notification Service |
   | MySQL / PostgreSQL   |   | Leaderboard Counters|   | WebSocket / SSE Push  |
   +----------+-----------+   +----------+----------+   +-----------+-----------+
              |                        |                           |
              +------------------------+---------------------------+
                                   |
                                   v
                  +-------------------------------------+
                  |   Frontend UI Updated in Real Time  |
                  |  (Scores, Leaderboard, Community)   |
                  +-------------------------------------+


## 🧠 AI Model

- Trained using a **facial expression dataset** (happy vs. non-happy).
- Preprocessing includes:
  - Face detection  
  - Grayscale normalization  
  - Image resizing  
- Model outputs:
  - `smile`: boolean  
  - `confidence`: float (0–100%)

---

## 🗄 Tech Stack

**Frontend:**  
- HTML, CSS  
- JavaScript  

**Backend:**  
- Node.js  
- PHP  
- Express.js (if used)

**Database:**  
- MySQL

**AI & ML:**  
- Python  
- OpenCV  
- TensorFlow / PyTorch  

**DevOps & Deployment:**  
- Docker container for ML model  
- Cpanel hosting  
- API URL exposed for inference

---

## 📷 User Flow

1. User opens SmileWell and grants camera access  
2. A snapshot is captured  
3. Image is uploaded to the backend API  
4. AI model predicts smile score  
5. Result displayed as:
   - 😊 *You are smiling! Score: 89%*  
   - 😐 *Neutral expression detected*  
6. Data saved to database  
7. Leaderboard updates in real time  

---

## 🔧 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/upload` | Uploads user image for prediction |
| `GET`  | `/leaderboard` | Fetches top users |
| `POST` | `/feedback` | Saves likes/comments |
| `GET`  | `/score/:id` | Returns smile score for a user |

---

## 🔐 Security

- Input validation on all endpoints  
- Secure file uploads  
- CORS restrictions  
- API rate limiting (optional)  

---

## 💾 Database Schema (Simplified)

### **Users Table**
id | name | email | created_at

markdown
Copy code

### **Scores Table**
id | user_id | smile_score | timestamp

markdown
Copy code

### **Leaderboard Table**
id | user_id | total_smiles | rank

yaml
Copy code

---

## 🏁 How to Run Locally

### 1. Clone the Repository
```bash
git clone https://github.com/ersarthak03/smilewell
cd smilewell
2. Install Dependencies
bash
Copy code
npm install
3. Set Up Backend
Configure .env for DB and API keys

Import MySQL schema

4. Run Server
bash
Copy code
npm start
5. Run AI Model (Docker)
bash
Copy code
docker build -t smilewell-model .
docker run -p 5000:5000 smilewell-model
📡 Deployment
Frontend + Backend deployed on Cpanel

AI model deployed as a Docker container

API exposed over HTTPS

CDN caching for media files (optional)

🙌 Acknowledgements
OpenCV & TensorFlow communities

Contributors and testers

Dataset authors

Inspiration from smile-recognition research papers

🧑‍💻 Author
Sarthak Arora

LinkedIn: https://www.linkedin.com/in/ersarthak03

GitHub: https://github.com/ersarthak03
