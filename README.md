# Talla Senior Developer Assessment

> A comprehensive Laravel + Filament application demonstrating multi-panel architecture, real-time chat, role-based permissions, and API integrations.


## Features

### Admin Application
- **User Management**: Create and manage employees with roles
- **Dynamic Roles & Permissions**: Fully configurable permission system
- **Real-time Chat**: Live messaging with employees


### Employee Application  
- **Testing Pages**: Three permission-controlled CRUD interfaces
- **Real-time Chat**: Direct communication with administrators
- **Country Management**: REST API integration with interactive UI
- **Role-based Access**: Granular permissions for different user types


##  Installation

### 1. Clone the Repository

```bash
[git clone https://github.com/your-username/talla-assessment.git](https://github.com/addabenkoceir13/talla-assessment)
cd talla-assessment
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Copy Environment File

```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

## ⚙️ Configuration

### 1. Database Configuration

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=talla_assessment
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Broadcasting Configuration

Add these lines to `.env` for real-time features:

```env
# Broadcasting Settings
BROADCAST_DRIVER=reverb
QUEUE_CONNECTION=database

# WebSocket Server (Reverb)
REVERB_APP_ID=204***
REVERB_APP_KEY=40961**************
REVERB_APP_SECRET=c2775************
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

# Frontend WebSocket Settings
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME=http

# Chat Escalation Settings
CHAT_ESCALATION_MINUTES=10
```

### 3. Additional Settings

```env
# Mail Configuration (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@talla-assessment.com"
MAIL_FROM_NAME="${APP_NAME}"

# External API
COUNTRIES_API_URL="https://restcountries.com/v3.1"
```

## Database Setup

### 1. Create Database

Create a new database named `talla_assessment` (or your preferred name).

### 2. Run Migrations

```bash
php artisan migrate --seed
```

### 3. Seed the Database

```bash
# Or run all seeders at once
php artisan db:seed
```

### 4. Create Storage Link

```bash
php artisan storage:link
```

## Running the Application

### Development Environment

You need to run **4 separate commands** in different terminal windows:

#### Terminal 1: Laravel Development Server
```bash
php artisan serve
```
**Access:** http://localhost:8000

#### Terminal 2: WebSocket Server (Real-time Features)
```bash
php artisan reverb:start --debug
```
**Purpose:** Enables real-time chat and notifications

#### Terminal 3: Queue Worker (Background Jobs)
```bash
php artisan queue:work --verbose
```
**Purpose:** Processes message escalation and notifications

#### Terminal 4: Frontend Assets (Development)
```bash
npm run dev
```
**Purpose:** Compiles JavaScript and CSS with hot reloading

### Production Commands

For production deployment:

```bash
# Build frontend assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run queue worker with supervisor
php artisan queue:work --daemon

# Schedule task runner (add to crontab)
php artisan schedule:work
```


##  User Accounts

The system comes pre-configured with test accounts:

### Administrator Access
```
Email: admin@gmail.com
Password: 123456789
Access: All features and permissions
```

### Employee Access

| User | Email | Password | Permissions |
|------|-------|----------|-------------|
| **Employee ** | employee@gmail.com | 123456789 |  |
| **Employee 01** | employee01@gmail.com | 123456789 |  |
| **Employee 02** | employee02@gmail.com | 123456789 |  |
| **Employee 03** | employee03@gmail.com | 123456789 |  |
| **Employee 04** | employee04@gmail.com | 123456789 |  |
| **Employee 05** | employee05@gmail.com | 123456789 |  |





### Permission System
- **Roles**: admin, employee
- **Permissions**: Granular CRUD permissions for each feature
- **Dynamic**: Fully configurable through admin interface
- **Middleware**: Route-level and resource-level protection

## 🛠️ Troubleshooting

### Common Issues

#### Real-time Features Not Working
```bash
# Check if WebSocket server is running
netstat -an | grep 8080

# Restart WebSocket server
php artisan reverb:start --debug

# Check browser console for connection errors
# Ensure firewall allows port 8080
```

#### Queue Jobs Not Processing
```bash
# Check queue status
php artisan queue:monitor

# Restart queue worker
php artisan queue:restart
php artisan queue:work --verbose

# Check failed jobs
php artisan queue:failed
```

#### Permission Issues
```bash
# Clear cache
php artisan config:clear
php artisan cache:clear


### Development Tools

```bash
# Clear all caches
php artisan optimize:clear


```


##  Additional Commands

### Useful Artisan Commands
```bash
# Create new admin user
php artisan make:filament-user --panel=admin

# Create new employee user  
php artisan make:filament-user --panel=employee

# Check scheduled tasks
php artisan schedule:list


