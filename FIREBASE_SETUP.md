# Firebase Auth Setup Guide - Backend Hamim

Panduan setup Firebase Authentication untuk Social Login (Google, Facebook, Apple, dll).

## 📋 Prerequisites

- Akun Google (untuk Firebase Console)
- Domain aplikasi: `backend-hamim.test` atau domain production

---

## 🔥 Firebase Project Setup

### Step 1: Buat Firebase Project
1. **URL:** https://console.firebase.google.com/
2. **Click:** Create a project
3. **Project name:** `Backend Hamim`
4. **Enable Google Analytics:** (optional)
5. **Click:** Create project

### Step 2: Enable Authentication
1. **Go to:** Authentication > Get started
2. **Sign-in method tab**
3. **Enable providers:**
   - **Google:** Enable → Save
   - **Facebook:** Enable → Add App ID & App Secret → Save
   - **Apple:** Enable (untuk iOS) → Save

### Step 3: Add Web App
1. **Go to:** Project settings (gear icon)
2. **Your apps section:** Add app → Web
3. **App nickname:** `Backend Hamim Web`
4. **Register app**

### Step 4: Get Firebase Config
Copy Firebase config object:
```javascript
const firebaseConfig = {
  apiKey: "AIzaSyC...",
  authDomain: "backend-hamim.firebaseapp.com",
  projectId: "backend-hamim",
  storageBucket: "backend-hamim.appspot.com",
  messagingSenderId: "123456789",
  appId: "1:123456789:web:abcdef123456"
};
```

### Step 5: Configure Authorized Domains
1. **Go to:** Authentication > Settings > Authorized domains
2. **Add domains:**
   - `backend-hamim.test` (development)
   - `yourdomain.com` (production)

---

## ⚙️ Backend Configuration

### Update .env File
```env
FIREBASE_PROJECT_ID=backend-hamim
```

---

## 🔵 Google Sign-In Setup (Optional - Advanced)

Jika ingin custom Google Sign-In:

### Step 1: Google Cloud Console
1. **URL:** https://console.cloud.google.com/
2. **Select project:** backend-hamim
3. **APIs & Services:** Credentials

### Step 2: OAuth 2.0 Client
1. **Create credentials:** OAuth 2.0 Client ID
2. **Application type:** Web application
3. **Authorized JavaScript origins:**
   - `http://backend-hamim.test`
   - `https://yourdomain.com`

---

## 🔵 Facebook Login Setup (Optional - Advanced)

### Step 1: Facebook Developers
1. **URL:** https://developers.facebook.com/
2. **Create App:** Consumer type
3. **App name:** Backend Hamim

### Step 2: Facebook Login Product
1. **Add product:** Facebook Login
2. **Settings:** Valid OAuth Redirect URIs
   - `http://backend-hamim.test`
   - `https://yourdomain.com`

### Step 3: Get App Credentials
- **App ID:** Copy from Settings > Basic
- **App Secret:** Copy from Settings > Basic

### Step 4: Add to Firebase
1. **Firebase Console:** Authentication > Sign-in method
2. **Facebook:** Add App ID & App Secret

---

## 🧪 Frontend Implementation

### Install Firebase SDK
```bash
npm install firebase
```

### Initialize Firebase
```javascript
import { initializeApp } from 'firebase/app';
import { getAuth } from 'firebase/auth';

const firebaseConfig = {
  // Your config here
};

const app = initializeApp(firebaseConfig);
export const auth = getAuth(app);
```

### Google Sign-In
```javascript
import { signInWithPopup, GoogleAuthProvider } from 'firebase/auth';

const googleLogin = async () => {
  const provider = new GoogleAuthProvider();
  const result = await signInWithPopup(auth, provider);
  const idToken = await result.user.getIdToken();
  
  // Send to backend
  const response = await fetch('/api/auth/firebase', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ idToken })
  });
  
  const data = await response.json();
  // Use data.token for API calls
};
```

### Facebook Sign-In
```javascript
import { signInWithPopup, FacebookAuthProvider } from 'firebase/auth';

const facebookLogin = async () => {
  const provider = new FacebookAuthProvider();
  const result = await signInWithPopup(auth, provider);
  const idToken = await result.user.getIdToken();
  
  // Send to backend (same as Google)
};
```

---

## 🧪 Testing

### API Endpoint
```
POST /api/auth/firebase
Content-Type: application/json

{
  "idToken": "firebase_id_token_here"
}
```

### Expected Response
```json
{
  "message": "Firebase login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@gmail.com",
    "firebase_uid": "firebase_user_id",
    "avatar": "https://lh3.googleusercontent.com/..."
  },
  "token": "sanctum_api_token"
}
```

### Test dengan Postman
1. **Get ID Token:** Login via Firebase web app
2. **Copy token:** From browser console
3. **POST request:** To `/api/auth/firebase`

---

## 🔒 Security Best Practices

### Firebase Rules
- **Authentication required:** Only authenticated users
- **Token validation:** Backend validates Firebase tokens
- **HTTPS only:** Production must use HTTPS

### Backend Security
- **Token expiry:** Firebase tokens expire automatically
- **Sanctum tokens:** Set appropriate expiry
- **Rate limiting:** Implement for auth endpoints

---

## 🚨 Troubleshooting

### Common Issues

**1. "Firebase project not found"**
- Check `FIREBASE_PROJECT_ID` in .env
- Verify project exists in Firebase Console

**2. "Token verification failed"**
- Token expired (1 hour default)
- Invalid project ID
- Network connectivity issues

**3. "User creation failed"**
- Email already exists with different provider
- Database connection issues

### Debug Steps
1. **Check Firebase Console:** Authentication logs
2. **Check backend logs:** Error details
3. **Verify token:** Use Firebase Admin SDK tools
4. **Test connectivity:** Firebase endpoints

---

## 📞 Support

**Documentation:**
- Firebase Auth: https://firebase.google.com/docs/auth
- Firebase Web SDK: https://firebase.google.com/docs/web/setup

**Status:** ✅ Ready for Firebase Auth Setup
**Last Updated:** 28 Oktober 2025