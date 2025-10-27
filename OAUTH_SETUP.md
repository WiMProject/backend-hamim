# OAuth Setup Guide - Backend Hamim

Panduan lengkap untuk setup Google dan Facebook OAuth untuk fitur Social Login.

## 📋 Prerequisites

- Akun Google (untuk Google Cloud Console)
- Akun Facebook Developer
- Domain/URL aplikasi: `backend-hamim.test` atau domain production

---

## 🔵 Google OAuth Setup

### Step 1: Buka Google Cloud Console
1. **URL:** https://console.cloud.google.com/
2. **Login** dengan akun Google
3. **Create New Project** atau pilih existing project
   - Project Name: `Backend Hamim`
   - Organization: (optional)

### Step 2: Enable Google APIs
1. **Go to:** APIs & Services > Library
2. **Search:** "Google+ API" atau "Google Identity"
3. **Click:** Enable

### Step 3: Create OAuth 2.0 Credentials
1. **Go to:** APIs & Services > Credentials
2. **Click:** Create Credentials > OAuth 2.0 Client IDs
3. **Configure OAuth consent screen** (jika belum):
   - User Type: External
   - App Name: Backend Hamim
   - User support email: your-email@domain.com
   - Developer contact: your-email@domain.com
4. **Create OAuth Client ID:**
   - Application type: **Web application**
   - Name: `Backend Hamim API`
   - Authorized JavaScript origins:
     - `http://backend-hamim.test`
     - `https://your-production-domain.com`
   - Authorized redirect URIs:
     - `http://backend-hamim.test/auth/google/callback`
     - `https://your-production-domain.com/auth/google/callback`

### Step 4: Copy Google Credentials
Setelah create, akan muncul popup dengan:
- **Client ID:** `123456789-abcdefghijk.apps.googleusercontent.com`
- **Client Secret:** `GOCSPX-abcdefghijk123456789`

**Simpan credentials ini untuk dimasukkan ke .env file**

---

## 🔵 Facebook OAuth Setup

### Step 1: Buka Facebook Developers
1. **URL:** https://developers.facebook.com/
2. **Login** dengan akun Facebook
3. **Click:** My Apps > Create App

### Step 2: Create Facebook App
1. **Select App Type:** Consumer
2. **Click:** Continue
3. **App Details:**
   - App Name: `Backend Hamim`
   - App Contact Email: your-email@domain.com
4. **Click:** Create App

### Step 3: Add Facebook Login Product
1. **Dashboard** > Add Product
2. **Find:** Facebook Login
3. **Click:** Set Up

### Step 4: Configure Facebook Login
1. **Go to:** Facebook Login > Settings
2. **Valid OAuth Redirect URIs:**
   ```
   http://backend-hamim.test/auth/facebook/callback
   https://your-production-domain.com/auth/facebook/callback
   ```
3. **Save Changes**

### Step 5: Configure App Settings
1. **Go to:** Settings > Basic
2. **App Domains:**
   ```
   backend-hamim.test
   your-production-domain.com
   ```
3. **Privacy Policy URL:** (optional tapi recommended)
4. **Terms of Service URL:** (optional)

### Step 6: Copy Facebook Credentials
1. **Go to:** Settings > Basic
2. **Copy:**
   - **App ID:** `123456789012345`
   - **App Secret:** `abcdefghijk123456789` (click Show)

**Simpan credentials ini untuk dimasukkan ke .env file**

---

## ⚙️ Environment Configuration

### Update .env File
Masukkan credentials yang sudah didapat ke file `.env`:

```env
# Google OAuth
GOOGLE_CLIENT_ID=123456789-abcdefghijk.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-abcdefghijk123456789
GOOGLE_REDIRECT_URI=http://backend-hamim.test/auth/google/callback

# Facebook OAuth
FACEBOOK_CLIENT_ID=123456789012345
FACEBOOK_CLIENT_SECRET=abcdefghijk123456789
FACEBOOK_REDIRECT_URI=http://backend-hamim.test/auth/facebook/callback
```

### Production Environment
Untuk production, ganti URL dengan domain production:
```env
GOOGLE_REDIRECT_URI=https://api.yourdomain.com/auth/google/callback
FACEBOOK_REDIRECT_URI=https://api.yourdomain.com/auth/facebook/callback
```

---

## 🧪 Testing Social Login

### API Endpoints
- **Google Login:** `POST /api/auth/google`
- **Facebook Login:** `POST /api/auth/facebook`

### Request Format
```json
{
    "token": "access_token_from_frontend"
}
```

### Response Format
```json
{
    "message": "Google login successful",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "avatar": "https://lh3.googleusercontent.com/...",
        "google_id": "123456789"
    },
    "token": "sanctum_api_token_here"
}
```

### Testing dengan Postman
1. **Method:** POST
2. **URL:** `http://backend-hamim.test/api/auth/google`
3. **Body (JSON):**
   ```json
   {
       "token": "ya29.a0AfH6SMC..."
   }
   ```

---

## 🔒 Security Notes

### Google OAuth
- **Scopes:** Default scope sudah cukup (profile, email)
- **Token Validation:** Backend akan validate token dengan Google API
- **Refresh Token:** Tidak diperlukan untuk login sekali

### Facebook OAuth
- **Permissions:** Default permissions (public_profile, email)
- **App Review:** Untuk production, mungkin perlu app review
- **Token Validation:** Backend akan validate dengan Facebook Graph API

### General Security
- **HTTPS:** Wajib untuk production
- **CORS:** Configure CORS untuk frontend domain
- **Rate Limiting:** Implement rate limiting untuk login endpoints
- **Token Expiry:** Set appropriate token expiry time

---

## 🚨 Troubleshooting

### Common Issues

**1. "redirect_uri_mismatch" Error**
- Pastikan redirect URI di OAuth app sama dengan yang di config
- Check typo di URL (http vs https, trailing slash, dll)

**2. "invalid_client" Error**
- Client ID atau Client Secret salah
- Check .env file dan restart server

**3. "access_denied" Error**
- User cancel authorization
- App belum approved (Facebook)

**4. "invalid_token" Error**
- Token expired atau invalid
- Frontend perlu generate token baru

### Debug Steps
1. **Check .env file** - pastikan credentials benar
2. **Check OAuth app settings** - redirect URI, domains
3. **Check server logs** - error details
4. **Test dengan curl** - isolate frontend issues

---

## 📞 Support

Jika ada masalah dalam setup:
1. **Check dokumentasi resmi:**
   - Google: https://developers.google.com/identity/protocols/oauth2
   - Facebook: https://developers.facebook.com/docs/facebook-login
2. **Contact developer team**
3. **Check server logs** untuk error details

---

**Status:** ✅ Ready for OAuth Setup
**Last Updated:** 27 Oktober 2025