# Backend Hamim - Lumen API

Backend API menggunakan Laravel Lumen untuk project Hamim.

## Project Overview

Backend API untuk aplikasi mobile Duolingo Islam yang menyediakan sistem authentication, asset management, dan multi-language support. Dibangun menggunakan Laravel Lumen dengan fokus pada performa dan skalabilitas.

## Development Timeline

### Phase 1: Initial Setup (27 Oktober 2025)
- Project initialization dengan Laravel Lumen
- Database setup dan konfigurasi MySQL
- Basic authentication system dengan Sanctum
- User registration dan login endpoints

### Phase 2: Asset Management (28 Oktober 2025)
- Enhanced asset management system
- Multi-file type support (images, audio, video, animations)
- Automatic metadata detection dengan getID3
- Translation system dengan JSON assets
- File serving dan storage optimization

### Phase 3: Profile Management (10 November 2025)
- Complete user profile CRUD operations
- Password management (change, forgot, reset)
- API structure optimization
- Comprehensive testing dan debugging

## Progress Development - Updated 10 November 2025

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

### Technical Details

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

### Ready for Frontend

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

### Today's Achievements (28 Oktober 2025)
- **Enhanced metadata detection** - Auto-extract info from all file types
- **getID3 integration** - Accurate audio/video duration & quality
- **Multi-language support** - Translation management via assets
- **Dual input methods** - File upload & direct JSON creation
- **Complete asset system** - Images, audio, video, animations, translations
- **Smart filtering** - Advanced query capabilities
- **Translation GET endpoints** - Frontend-ready translation consumption
- **Profile management** - Update profile, change password, forgot password
- **API security** - Protected endpoints with Sanctum middleware
- **Production ready** - Complete user & asset management system

### Latest Updates (10 November 2025)
- **Profile Management System** - Complete user profile CRUD operations
- **Password Management** - Change password, forgot password, reset password
- **ProfileController** - Dedicated controller for profile operations
- **Authentication Enhancement** - Token-based profile access with Sanctum
- **Password Reset Flow** - Email-based password reset with secure tokens
- **API Structure Optimization** - Clean separation between auth and profile endpoints
- **Database Model Fix** - Added remember_token to fillable fields for password reset
- **Flexible Password Confirmation** - Optional password confirmation for register endpoint
- **Clean Code Implementation** - Added proper documentation, removed code duplication
- **Complete Testing** - All profile and password management endpoints tested and working

### Technical Issues Resolved (10 November 2025)
- **Sanctum Middleware Compatibility** - Fixed Lumen compatibility issues with Sanctum ServiceProvider
- **Token Authentication** - Resolved token validation using PersonalAccessToken::findToken() method
- **Mass Assignment Protection** - Fixed remember_token not being fillable in User model
- **Password Reset Token Storage** - Resolved token not saving to database issue
- **API Route Structure** - Optimized route organization for better maintainability
- **Code Quality** - Added PHPDoc comments, removed duplication, improved readability
- **Security Enhancement** - Hidden sensitive fields from JSON responses
- **Debug & Testing** - Comprehensive debugging and testing of all endpoints

### Next Steps (Future)
- [ ] Asset delete/update endpoints
- [ ] Image resize/compression
- [ ] File access permissions
- [ ] API rate limiting
- [ ] Email verification
- [ ] Email service for password reset (currently returns token in response for testing)
- [ ] Firebase project setup & configuration
- [ ] Frontend Firebase SDK integration
- [ ] Admin panel for asset management
- [ ] User logout with token deletion
- [ ] Profile picture upload endpoint

### API Endpoints Summary

#### Auth
- `POST /api/auth/register` - Email/password registration (optional password confirmation)
- `POST /api/auth/login` - Email/password login
- `POST /api/auth/firebase` - Social login (Google/Facebook/Apple)
- `POST /api/auth/logout` - Logout user
- `POST /api/auth/forgot-password` - Send password reset token
- `POST /api/auth/reset-password` - Reset password with token (requires confirmation)
- `POST /api/auth/change-password` - Change password (protected, requires confirmation)

#### Profile (Protected)
- `GET /api/profile` - Get user profile
- `PUT /api/profile` - Update profile (name, phone, address, picture)

#### Assets (Protected)
- `GET /api/assets` - List all assets with filtering
- `GET /api/assets/{id}` - Get single asset
- `POST /api/assets/upload` - Upload any file type
- `POST /api/assets/translations` - Create translation from JSON
- `GET /api/assets/translations` - Get all translations
- `GET /api/assets/translations/{language}` - Get translation content by language
- `GET /storage/assets/{filename}` - Direct file access

#### Translations (Protected)
- `GET /api/translations` - Get all translations
- `GET /api/translations/{language}` - Get translation content by language (id/en/ar)
- `POST /api/translations` - Create translation from JSON

### Development Environment
- **Framework:** Laravel Lumen 10.x
- **Database:** MySQL 8.0
- **Server:** Laravel Valet (local development)
- **Domain:** `backend-hamim.test`
- **Storage:** Local filesystem dengan public access
- **Authentication:** Laravel Sanctum API tokens
- **Version Control:** Git dengan GitHub repository

### Installation & Setup

1. **Clone Repository**
   ```bash
   git clone https://github.com/WiMProject/backend-hamim.git
   cd backend-hamim
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   # Configure database dan Firebase settings
   ```

4. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Storage Link**
   ```bash
   php artisan storage:link
   ```

6. **Start Development Server**
   ```bash
   # Using Valet
   valet link backend-hamim
   # Or using built-in server
   php -S localhost:8000 -t public
   ```

### API Testing

Gunakan Postman atau tools serupa untuk testing API endpoints. Contoh authentication flow:

1. **Register/Login** untuk mendapatkan token
2. **Set Authorization Header**: `Bearer {your_token}`
3. **Test protected endpoints** seperti profile dan assets

#### Password Confirmation Rules
- **Register**: Optional - validates only if `password_confirmation` field is provided
- **Reset Password**: Required - must include `password_confirmation`
- **Change Password**: Required - must include `new_password_confirmation`

#### Example Register Requests
```json
// Without confirmation (matches UI mockup)
{
    "name": "Hana Sari",
    "email": "hana@example.com",
    "password": "hana123456",
    "phone_number": "081234567890"
}

// With confirmation (extra validation)
{
    "name": "Hana Sari",
    "email": "hana@example.com",
    "password": "hana123456",
    "password_confirmation": "hana123456",
    "phone_number": "081234567890"
}
```

### File Structure

```
backend-hamim/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php
│   │   ├── ProfileController.php
│   │   └── AssetController.php
│   ├── Models/
│   │   ├── User.php
│   │   └── Asset.php
│   └── Services/
│       └── FirebaseService.php
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── web.php
├── storage/
│   └── app/public/assets/
└── public/
    └── storage/ (symlink)
```*Server:** Laravel Valet
- **Domain:** `backend-hamim.test`
- **Storage:** Local filesystem with public access

---

### Documentation Files
- `README.md` - Project overview, progress, dan API documentation
- `FIREBASE_SETUP.md` - Complete Firebase Auth setup guide
- API Collection tersedia untuk Postman testing

### Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/new-feature`)
3. Commit changes (`git commit -am 'Add new feature'`)
4. Push to branch (`git push origin feature/new-feature`)
5. Create Pull Request

### Security

- Semua password di-hash menggunakan bcrypt
- API endpoints protected dengan Sanctum tokens
- Input validation pada semua endpoints
- CORS configuration untuk frontend integration
- Rate limiting akan ditambahkan di future updates

---

**Current Status:** Ready for Frontend Integration
**Last Updated:** 10 November 2025
**Version:** 1.0.0