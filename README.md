# Backend Hamim - Lumen API

Backend API menggunakan Laravel Lumen untuk project Hamim.

## Progress Development - 28 Oktober 2025 (Updated)

### ✅ Yang Sudah Dikerjakan

#### 1. **Setup Project**
- Laravel Lumen framework
- Database MySQL connection
- Enable Facades & Eloquent
- Valet development server

#### 2. **User Authentication System**
- **Database:** Tabel `user` dengan fields:
  - id, name, email, password, phone_number, address, profile_picture
  - firebase_uid, avatar, email_verified_at, remember_token, timestamps
- **Model:** User model dengan auto password hashing & Sanctum HasApiTokens
- **API Endpoints:**
  - `POST /api/auth/register` - Register user baru (Sanctum token)
  - `POST /api/auth/login` - Login dengan email/password (Sanctum token)
  - `POST /api/auth/firebase` - Social login via Firebase (Google/Facebook/Apple)
  - `POST /api/auth/logout` - Logout user
- **Features:**
  - Email & phone unique validation
  - Password auto-hash saat register
  - **Laravel Sanctum** - API token management
  - **Firebase Auth** - Social login (Google, Facebook, Apple, dll)
  - Proper error handling & token verification

#### 3. **Enhanced Asset Management System**
- **Database:** Tabel `assets` dengan fields:
  - id, name, type, category, file_path, file_url
  - file_size, mime_type, metadata (JSON), is_active, timestamps
- **Model:** Asset model dengan JSON casting
- **Storage:** 
  - Files disimpan di `storage/app/public/assets/`
  - Symbolic link `public/storage -> storage/app/public`
- **API Endpoints:**
  - `POST /api/assets/upload` - Upload file (image/audio/video/animation)
  - `POST /api/assets/translations` - Create translation from JSON text
  - `GET /api/assets` - Get all assets with filtering
  - `GET /api/assets/{id}` - Get single asset
  - `GET /api/assets?type=image` - Filter by type
  - `GET /api/assets?category=banner` - Filter by category
  - `GET /api/translations/{language}` - Get translation content (id/en/ar)
- **Auto Metadata Detection:**
  - **Images:** Width, height (PNG/JPG/SVG)
  - **Lottie:** Width, height, frame rate, total frames
  - **Audio:** Duration, bitrate, sample rate (MP3/WAV/M4A)
  - **Video:** Duration, resolution (MP4/WebM)
- **Multi-Language Support:**
  - Translation files as JSON assets
  - Dynamic language switching
  - Centralized translation management
- **File Serving:**
  - `GET /storage/assets/{filename}` - Direct file access
  - `GET /storage/assets/{path}` - Via FileController (with access control)

#### 4. **Database Setup**
- **Migrations:** User table & Assets table
- **Seeders:** Dummy assets dengan placeholder images
- **Configuration:** Filesystem config untuk file upload

#### 5. **Dependencies Installed**
- `laravel/sanctum` - API authentication
- `firebase/php-jwt` - Firebase token verification
- `guzzlehttp/guzzle` - HTTP client for Firebase API
- `league/flysystem` - File system abstraction
- `james-heinrich/getid3` - Audio/video metadata extraction
- Standard Lumen packages

### 🔧 Technical Details

#### **Database Schema:**
```sql
-- Users table
CREATE TABLE user (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(100),
    phone_number VARCHAR(25) UNIQUE,
    address VARCHAR(255) NULL,
    profile_picture VARCHAR(500) NULL,
    firebase_uid VARCHAR(255) UNIQUE NULL,
    avatar VARCHAR(500) NULL,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Sanctum API Tokens
CREATE TABLE personal_access_tokens (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    tokenable_type VARCHAR(255),
    tokenable_id BIGINT,
    name VARCHAR(255),
    token VARCHAR(64) UNIQUE,
    abilities TEXT NULL,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Assets table
CREATE TABLE assets (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    type VARCHAR(50),
    category VARCHAR(100) NULL,
    file_path VARCHAR(500),
    file_url VARCHAR(500),
    file_size INT NULL,
    mime_type VARCHAR(100) NULL,
    metadata JSON NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### **API Response Format:**
```json
{
    "message": "Success message",
    "data": { ... },
    "token": "auth_token" // untuk auth endpoints
}
```

### 🚀 Ready for Frontend

**Authentication:**
- **Regular Auth:** Register/Login dengan email/password
- **Social Auth:** Firebase Auth (Google, Facebook, Apple, dll)
- **API Tokens:** Laravel Sanctum untuk semua API calls
- **Unified System:** Semua auth method return Sanctum token
- User data management

**Enhanced Asset Management:**
- **Universal file support:** Images, audio, video, animations, translations
- **Auto metadata detection:** Dimensions, duration, quality info
- **Multi-language system:** JSON-based translations
- **Smart filtering:** By type, category, language
- **Dual upload methods:** File upload & direct JSON input
- **Complete metadata:** Auto-extracted from all file types

**File Storage:**
- Organized dalam folder `assets/`
- Public access via `/storage/assets/`
- Support multiple file types

### 🎯 Today's Achievements (28 Oktober 2025)
- ✅ **Enhanced metadata detection** - Auto-extract info from all file types
- ✅ **getID3 integration** - Accurate audio/video duration & quality
- ✅ **Multi-language support** - Translation management via assets
- ✅ **Dual input methods** - File upload & direct JSON creation
- ✅ **Complete asset system** - Images, audio, video, animations, translations
- ✅ **Smart filtering** - Advanced query capabilities
- ✅ **Translation GET endpoints** - Frontend-ready translation consumption
- ✅ **Production ready** - All asset types supported with metadata

### 📝 Next Steps (Future)
- [ ] User profile update API
- [ ] Asset delete/update endpoints
- [ ] Image resize/compression
- [ ] File access permissions
- [ ] API rate limiting
- [ ] Email verification
- [ ] Password reset
- [ ] Firebase project setup & configuration
- [ ] Frontend Firebase SDK integration
- [ ] Admin panel for asset management

### 🔗 API Endpoints Summary

#### Auth
- `POST /api/auth/register` - Email/password registration
- `POST /api/auth/login` - Email/password login
- `POST /api/auth/firebase` - Social login (Google/Facebook/Apple)
- `POST /api/auth/logout` - Logout user

#### Assets
- `GET /api/assets` - List all assets with filtering
- `GET /api/assets/{id}` - Get single asset
- `POST /api/assets/upload` - Upload any file type
- `POST /api/assets/translations` - Create translation from JSON
- `GET /api/assets/translations` - Get all translations
- `GET /api/assets/translations/{language}` - Get translation content by language
- `GET /storage/assets/{filename}` - Direct file access

#### Translations (Alternative endpoints)
- `GET /api/translations` - Get all translations
- `GET /api/translations/{language}` - Get translation content by language (id/en/ar)
- `POST /api/translations` - Create translation from JSON

### 💻 Development Environment
- **Framework:** Laravel Lumen
- **Database:** MySQL
- **Server:** Laravel Valet
- **Domain:** `backend-hamim.test`
- **Storage:** Local filesystem with public access

---
### 📚 Documentation
- `README.md` - Project overview & progress
- `FIREBASE_SETUP.md` - Complete Firebase Auth setup guide

---
**Status:** ✅ Ready for Frontend Integration with Firebase Auth