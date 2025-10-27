# Backend Hamim - Lumen API

Backend API menggunakan Laravel Lumen untuk project Hamim.

## Progress Development - 27 Oktober 2025

### ✅ Yang Sudah Dikerjakan

#### 1. **Setup Project**
- Laravel Lumen framework
- Database MySQL connection
- Enable Facades & Eloquent
- Valet development server

#### 2. **User Authentication System**
- **Database:** Tabel `user` dengan fields:
  - id, name, email, password, phone_number, address, profile_picture
  - email_verified_at, remember_token, timestamps
- **Model:** User model dengan auto password hashing
- **API Endpoints:**
  - `POST /api/auth/register` - Register user baru
  - `POST /api/auth/login` - Login dengan email/password
  - `POST /api/auth/logout` - Logout user
- **Features:**
  - Email & phone unique validation
  - Password auto-hash saat register
  - Token-based authentication
  - Proper error handling

#### 3. **Asset Management System**
- **Database:** Tabel `assets` dengan fields:
  - id, name, type, category, file_path, file_url
  - file_size, mime_type, metadata (JSON), is_active, timestamps
- **Model:** Asset model dengan JSON casting
- **Storage:** 
  - Files disimpan di `storage/app/public/assets/`
  - Symbolic link `public/storage -> storage/app/public`
- **API Endpoints:**
  - `POST /api/assets/upload` - Upload file (image/document/video)
  - `GET /api/assets` - Get all assets
  - `GET /api/assets/{id}` - Get single asset
  - `GET /api/assets?type=image` - Filter by type
  - `GET /api/assets?category=banner` - Filter by category
- **File Serving:**
  - `GET /storage/assets/{filename}` - Direct file access
  - `GET /storage/assets/{path}` - Via FileController (with access control)

#### 4. **Database Setup**
- **Migrations:** User table & Assets table
- **Seeders:** Dummy assets dengan placeholder images
- **Configuration:** Filesystem config untuk file upload

#### 5. **Dependencies Installed**
- `league/flysystem` - File system abstraction
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
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
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
- Register/Login/Logout API ready
- Token-based auth system
- User data management

**Asset Management:**
- File upload dengan validation
- Asset listing dengan filter
- Direct file access via URL
- Metadata support (width, height, etc)

**File Storage:**
- Organized dalam folder `assets/`
- Public access via `/storage/assets/`
- Support multiple file types

### 📝 Next Steps (Future)
- [ ] User profile update API
- [ ] Asset delete/update endpoints
- [ ] Image resize/compression
- [ ] File access permissions
- [ ] API rate limiting
- [ ] Email verification
- [ ] Password reset

### 🔗 API Endpoints Summary

#### Auth
- `POST /api/auth/register`
- `POST /api/auth/login`
- `POST /api/auth/logout`

#### Assets
- `GET /api/assets`
- `GET /api/assets/{id}`
- `POST /api/assets/upload`
- `GET /storage/assets/{filename}`

### 💻 Development Environment
- **Framework:** Laravel Lumen
- **Database:** MySQL
- **Server:** Laravel Valet
- **Domain:** `backend-hamim.test`
- **Storage:** Local filesystem with public access

---
**Status:** ✅ Ready for Frontend Integration