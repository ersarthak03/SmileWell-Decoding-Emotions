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
flowchart LR
  %% Clients
  subgraph Clients
    U[Browser / Mobile App (WebRTC / Camera)]
  end

  %% Edge / CDN
  U -->|HTTPS, static assets| CDN[CDN (CloudFront / Cloudflare)]

  %% Frontend
  CDN --> FE[Frontend Web App (React / Vanilla JS)]
  U -->|WebSocket/SSE| FE

  %% API Gateway & Auth
  FE -->|REST / GraphQL| APIGW[API Gateway / Load Balancer]
  APIGW --> Auth[Auth Service (Cognito / JWT)]
  APIGW --> FEAuth[Auth-protected routes]

  %% Backend microservices
  subgraph Backend
    API[API Service (Node.js / Express / PHP)] 
    Ingest[Image Ingest Service]
    ModelSrv[Inference Service (Dockerized ML API)]
    Queue[Message Queue (RabbitMQ / SQS / Kafka)]
    Worker[Worker Pool (async processing)]
    DB[(Primary DB) - MySQL/Postgres]
    Cache[Redis Cache]
    Leaderboard[Leaderboard Service]
    Community[Community Service (Likes/Comments/Shares)]
    Games[Games Service]
    Notifications[Realtime Push (WebSocket / FCM / APNS)]
    Analytics[Analytics & ETL]
    CDNMedia[Media Storage (S3) + CDN]
    Auth <-- API
    API --> Ingest
    Ingest --> Queue
    Queue --> Worker
    Worker --> ModelSrv
    ModelSrv --> DB
    Worker --> CDNMedia
    API --> DB
    API --> Cache
    API --> Leaderboard
    API --> Community
    API --> Games
    API --> Notifications
    API --> Analytics
  end

  %% AI/ML & Monitoring
  subgraph ML & Infra
    ModelSrv
    Training[Model Training (GPU, Batch Jobs)]
    Artifacts[Model Registry]
    Monitoring[Prometheus + Grafana + Logs]
  end

  Worker --> Artifacts
  Training --> Artifacts
  ModelSrv --> Monitoring
  API --> Monitoring

  %% DevOps & CI/CD
  DevOps[CI/CD (GitHub Actions / GitHub Actions -> Docker Registry)]
  DevOps -->|build/push| ModelSrv
  DevOps -->|deploy| API

  %% External integrations
  API -->|Email| SES[SNS/SES]
  API -->|SMS| Twilio
  API -->|Payments| PaymentGateway

  %% Arrows for end-to-end
  CDNMedia --> FE
  Notifications --> FE
  Leaderboard --> FE
  Community --> FE

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
