import cv2
import dlib
import numpy as np
import sys
import json
import os

# Initialize dlib's face detector and landmark predictor
detector = dlib.get_frontal_face_detector()
predictor_path = os.path.join(os.path.dirname(__file__), "shape_predictor_68_face_landmarks.dat")
predictor = dlib.shape_predictor(predictor_path)

def calculate_smile_score(landmarks, face_height):
    """Calculate smile score (0-100) based on facial landmarks"""
    try:
        left_corner = np.array([landmarks.part(48).x, landmarks.part(48).y])
        right_corner = np.array([landmarks.part(54).x, landmarks.part(54).y])
        upper_lip = np.array([landmarks.part(51).x, landmarks.part(51).y])
        lower_lip = np.array([landmarks.part(57).x, landmarks.part(57).y])

        mouth_width = np.linalg.norm(left_corner - right_corner)
        mouth_height = np.linalg.norm(upper_lip - lower_lip)
        
        # Normalize and calculate score (0-100)
        smile_score = min(100, max(0, (mouth_height / face_height) * 150))
        return round(smile_score, 2)
    except Exception as e:
        print(json.dumps({"error": f"Landmark calculation failed: {str(e)}"}))
        sys.exit(1)

if __name__ == "__main__":
    try:
        if len(sys.argv) < 2:
            print(json.dumps({"error": "No image path provided"}))
            sys.exit(1)
            
        image_path = sys.argv[1]
        
        # Verify image exists
        if not os.path.exists(image_path):
            print(json.dumps({"error": f"Image file not found: {image_path}"}))
            sys.exit(1)

        # Process image
        image = cv2.imread(image_path)
        if image is None:
            print(json.dumps({"error": "Could not read image file"}))
            sys.exit(1)
            
        gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
        faces = detector(gray)
        
        if not faces:
            print(json.dumps({"error": "No faces detected"}))
            sys.exit(0)
            
        # Get smile score for first face
        landmarks = predictor(gray, faces[0])
        face_height = faces[0].bottom() - faces[0].top()
        smile_score = calculate_smile_score(landmarks, face_height)
        
        # Return JSON result
        result = {
            "status": "success",
            "smile_score": smile_score,
            "faces_detected": len(faces)
        }
        
        print(json.dumps(result))
        
    except Exception as e:
        print(json.dumps({"error": f"Processing failed: {str(e)}"}))