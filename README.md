# 🏨 Event Management System - Royal Ambarrukmo Yogyakarta

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4.svg?style=flat&logo=php)](https://php.net/)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-13%2B-316192.svg?style=flat&logo=postgresql)](https://postgresql.org/)
[![Vercel](https://img.shields.io/badge/Deployed%20on-Vercel-000000.svg?style=flat&logo=vercel)](https://vercel.com/)
[![Supabase](https://img.shields.io/badge/Database-Supabase-3ECF8E.svg?style=flat&logo=supabase)](https://supabase.com/)

> **A comprehensive event management platform designed for hotels and hospitality businesses to streamline event booking, resource allocation, and customer management.**

## 📋 Table of Contents

- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Quick Start](#-quick-start)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Deployment](#-deployment)
- [API Documentation](#-api-documentation)
- [Database Schema](#-database-schema)
- [Contributing](#-contributing)
- [Security](#-security)
- [Performance](#-performance)
- [Monitoring](#-monitoring)
- [Troubleshooting](#-troubleshooting)
- [Support](#-support)
- [License](#-license)
- [Changelog](#-changelog)

## ✨ Features

### 🎯 Core Functionality
- **Event Booking Management** - Complete CRUD operations for event reservations
- **Business Block Management** - Organize events by business units and time blocks
- **User Management** - Role-based access control (Admin, Sales, Staff)
- **Real-time Calendar** - Interactive calendar view with FullCalendar integration
- **Event Notes System** - Department-specific notes and communications
- **Resource Allocation** - Function space and equipment management

### 🏢 Business Features
- **Multi-tenant Architecture** - Support for different business units
- **Customer Relationship Management** - Complete customer data management
- **Reporting & Analytics** - Event statistics and performance metrics
- **Mobile Responsive Design** - Optimized for all devices
- **Email Notifications** - Automated booking confirmations and reminders
- **Export Functionality** - Data export in multiple formats

### 🔧 Technical Features
- **RESTful API Architecture** - Clean and scalable API design
- **Database Optimization** - Efficient PostgreSQL queries with indexing
- **Caching Layer** - Improved performance with smart caching
- **Security First** - SQL injection protection, XSS prevention, CSRF tokens
- **Audit Logging** - Complete activity tracking and logging
- **Backup & Recovery** - Automated database backups

## 🚀 Tech Stack

### Backend
- **Language**: PHP 8.0+
- **Database**: PostgreSQL 13+ (Supabase)
- **Authentication**: Session-based with password hashing
- **API**: RESTful architecture

### Frontend
- **Framework**: Vanilla PHP with AdminLTE
- **CSS**: Bootstrap 3.3.5 + AdminLTE theme
- **JavaScript**: jQuery 2.1.4 + FullCalendar 2.2.5
- **Icons**: Font Awesome 4.4.0 + Ionicons 2.0.1

### Infrastructure
- **Hosting**: Vercel Serverless Functions
- **Database**: Supabase (PostgreSQL as a Service)
- **CDN**: Vercel Edge Network
- **SSL**: Automatic HTTPS with Vercel
- **Monitoring**: Built-in error tracking and logging

### Development Tools
- **Version Control**: Git + GitHub
- **Dependency Management**: Composer
- **Environment**: Docker support for local development
- **CI/CD**: GitHub Actions + Vercel automatic deployments

## 🏃‍♂️ Quick Start

### Prerequisites
- PHP 8.0 or higher
- Composer
- PostgreSQL 13+ (or Supabase account)
- Git

### 1. Clone Repository
```bash
git clone https://github.com/pangaribowo/ray-event-management.git
cd ray-event-management
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Setup
```bash
cp .env.example .env
# Edit .env with your database credentials
```

### 4. Database Migration
```bash
# Import the SQL schema to your PostgreSQL database
psql -h your-host -U postgres -d your-database -f supabase-migration.sql
```

### 5. Run Locally (Docker)
```bash
docker-compose up -d
```

### 6. Access Application
- **URL**: http://localhost:8080
- **Default Login**: 
  - Username: `admin`
  - Password: `admin123`

## 💻 Installation

### Local Development Setup

#### Using Docker (Recommended)
```bash
# Clone repository
git clone https://github.com/pangaribowo/ray-event-management.git
cd ray-event-management

# Start services
docker-compose up -d

# Access application
open http://localhost:8080
```

#### Manual Setup
```bash
# Install PHP dependencies
composer install

# Setup database (PostgreSQL)
createdb event_management
psql event_management < supabase-migration.sql

# Configure environment
cp .env.example .env
# Edit .env file with your settings

# Start PHP development server
php -S localhost:8000
```

### Production Setup

#### Vercel Deployment
```bash
# Install Vercel CLI
npm i -g vercel

# Deploy to Vercel
vercel --prod

# Set environment variables in Vercel dashboard
# See VERCEL-ENV-SETUP.md for details
```

## ⚙️ Configuration

### Environment Variables

| Variable | Description | Default | Required |
|----------|-------------|---------|----------|
| `APP_ENV` | Application environment | `development` | ✅ |
| `APP_DEBUG` | Debug mode | `true` | ❌ |
| `DB_HOST` | Database host | `localhost` | ✅ |
| `DB_PORT` | Database port | `5432` | ✅ |
| `DB_NAME` | Database name | `postgres` | ✅ |
| `DB_USER` | Database user | `postgres` | ✅ |
| `DB_PASSWORD` | Database password | - | ✅ |
| `SUPABASE_URL` | Supabase project URL | - | ❌ |
| `SUPABASE_ANON_KEY` | Supabase anonymous key | - | ❌ |
| `SUPABASE_SERVICE_ROLE_KEY` | Supabase service role key | - | ❌ |

### Database Configuration

#### Supabase Setup
1. Create a new project at [supabase.com](https://supabase.com)
2. Run the migration SQL: `supabase-migration.sql`
3. Get your connection credentials from Settings → Database
4. Update your `.env` file or Vercel environment variables

#### Local PostgreSQL Setup
```bash
# Install PostgreSQL
sudo apt-get install postgresql postgresql-contrib

# Create database
sudo -u postgres createdb event_management

# Create user
sudo -u postgres createuser --interactive

# Run migrations
psql event_management < supabase-migration.sql
```

## 🚀 Deployment

### Vercel Deployment (Recommended)

#### Automatic Deployment
1. Fork this repository
2. Connect your GitHub account to Vercel
3. Import the project in Vercel dashboard
4. Set environment variables (see `VERCEL-ENV-SETUP.md`)
5. Deploy automatically on every push to main branch

#### Manual Deployment
```bash
# Install Vercel CLI
npm i -g vercel

# Login to Vercel
vercel login

# Deploy
vercel --prod

# Set environment variables
vercel env add APP_ENV production
vercel env add DB_HOST your-supabase-host.supabase.co
# ... add all required variables

# Redeploy with new environment variables
vercel --prod
```

### Other Hosting Platforms

#### Heroku
```bash
# Install Heroku CLI
heroku create your-app-name

# Add PostgreSQL addon
heroku addons:create heroku-postgresql:hobby-dev

# Set environment variables
heroku config:set APP_ENV=production

# Deploy
git push heroku main
```

#### Traditional Hosting (cPanel/Shared Hosting)
1. Upload files via FTP to public_html
2. Create PostgreSQL database in hosting panel
3. Import `supabase-migration.sql`
4. Update `library/config.php` with database credentials
5. Set proper file permissions (755 for directories, 644 for files)

## 📚 API Documentation

### Authentication Endpoints

#### Login
```http
POST /login.php
Content-Type: application/x-www-form-urlencoded

name=admin&pwd=password
```

#### Logout
```http
GET /index.php?logout=1
```

### Event Management Endpoints

#### Create Event
```http
POST /index.php?cmd=create_event
Content-Type: application/x-www-form-urlencoded

event_name=Corporate Meeting&business_block_id=1&start_datetime=2025-01-01 09:00:00&end_datetime=2025-01-01 17:00:00
```

#### List Events
```http
GET /index.php?v=LIST
```

#### Create Business Block
```http
POST /index.php?cmd=create_block
Content-Type: application/x-www-form-urlencoded

block_name=Conference Hall A&account_name=ABC Corp&start_date=2025-01-01&end_date=2025-01-31
```

### User Management Endpoints

#### Create User
```http
POST /index.php?cmd=create
Content-Type: application/x-www-form-urlencoded

name=John Doe&email=john@example.com&phone=+1234567890&role=sales&position=Sales Manager
```

#### List Users
```http
GET /index.php?v=USERS
```

## 🗄️ Database Schema

### Core Tables

#### `users`
```sql
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    role VARCHAR(50) NOT NULL DEFAULT 'staff',
    pwd VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);
```

#### `event_bookings`
```sql
CREATE TABLE event_bookings (
    id SERIAL PRIMARY KEY,
    business_block_id INTEGER REFERENCES tbl_business_blocks(id),
    event_name VARCHAR(255) NOT NULL,
    function_space VARCHAR(100),
    start_datetime TIMESTAMP NOT NULL,
    end_datetime TIMESTAMP NOT NULL,
    pax INTEGER DEFAULT 0,
    rental VARCHAR(50) DEFAULT 'Exclude',
    status VARCHAR(20) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT NOW()
);
```

#### `tbl_business_blocks`
```sql
CREATE TABLE tbl_business_blocks (
    id SERIAL PRIMARY KEY,
    block_name VARCHAR(255) NOT NULL,
    account_type VARCHAR(50) DEFAULT 'Company',
    account_name VARCHAR(255),
    address TEXT,
    phone VARCHAR(20),
    owner_event VARCHAR(255),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    owner_id INTEGER REFERENCES users(id),
    created_at TIMESTAMP DEFAULT NOW()
);
```

### Complete Schema
For the complete database schema, see `supabase-migration.sql`

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

### Development Workflow
1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Make your changes
4. Run tests: `composer test`
5. Commit your changes: `git commit -m 'Add amazing feature'`
6. Push to the branch: `git push origin feature/amazing-feature`
7. Open a Pull Request

### Code Standards
- Follow PSR-12 coding standards
- Write comprehensive tests for new features
- Update documentation for any changes
- Use meaningful commit messages

### Setting Up Development Environment
```bash
# Clone your fork
git clone https://github.com/yourusername/ray-event-management.git
cd ray-event-management

# Install dependencies
composer install

# Set up pre-commit hooks
composer run setup-hooks

# Run development server
docker-compose up -d
```

## 🔒 Security

### Security Features
- **SQL Injection Protection**: All queries use prepared statements
- **XSS Prevention**: Input sanitization and output encoding
- **CSRF Protection**: Token-based CSRF protection
- **Password Security**: Bcrypt hashing for passwords
- **Session Security**: Secure session configuration
- **Input Validation**: Comprehensive input validation

### Security Best Practices
- Regular security audits
- Dependency vulnerability scanning
- Environment variable security
- HTTPS enforcement
- Regular backups

### Reporting Security Issues
Please report security vulnerabilities to [security@yourdomain.com](mailto:security@yourdomain.com)

## ⚡ Performance

### Performance Optimizations
- **Database Indexing**: Optimized database indexes
- **Query Optimization**: Efficient SQL queries
- **Caching**: Smart caching strategies
- **Asset Optimization**: Minified CSS/JS assets
- **CDN Integration**: Vercel Edge Network

### Performance Monitoring
- Database query performance tracking
- Application response time monitoring
- Error rate monitoring
- Resource usage tracking

### Benchmarks
- **Page Load Time**: < 2 seconds
- **API Response Time**: < 500ms
- **Database Query Time**: < 100ms
- **Concurrent Users**: 1000+

## 📊 Monitoring

### Application Monitoring
- **Error Tracking**: Comprehensive error logging
- **Performance Metrics**: Response time and throughput
- **User Analytics**: User behavior and usage patterns
- **Database Performance**: Query performance and optimization

### Monitoring Tools
- Vercel Analytics for deployment metrics
- Supabase Dashboard for database monitoring
- Custom logging for application-specific metrics

### Alerts and Notifications
- Error rate alerts
- Performance degradation alerts
- Database connection alerts
- Security incident alerts

## 🔧 Troubleshooting

### Common Issues

#### Database Connection Issues
```bash
# Check database connectivity
php -r "
try {
    \$pdo = new PDO('pgsql:host=your-host;port=5432;dbname=postgres', 'postgres', 'password');
    echo 'Connection successful!';
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage();
}
"
```

#### Vercel Deployment Issues
- Check environment variables are set correctly
- Verify `vercel.json` configuration
- Check function logs in Vercel dashboard
- Ensure PHP version compatibility

#### Permission Issues
```bash
# Fix file permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
```

### Debug Mode
Enable debug mode by setting `APP_DEBUG=true` in your environment variables.

### Logging
Logs are available in:
- Vercel: Function logs in dashboard
- Local: PHP error logs
- Database: Query logs in Supabase dashboard

## 🆘 Support

### Documentation
- [Deployment Guide](DEPLOYMENT-GUIDE.md)
- [Environment Setup](VERCEL-ENV-SETUP.md)
- [API Documentation](#-api-documentation)
- [Database Schema](#-database-schema)

### Community Support
- [GitHub Issues](https://github.com/pangaribowo/ray-event-management/issues)
- [Discussions](https://github.com/pangaribowo/ray-event-management/discussions)
- [Wiki](https://github.com/pangaribowo/ray-event-management/wiki)

### Commercial Support
For enterprise support, custom development, or consulting services:
- Email: [support@yourdomain.com](mailto:support@yourdomain.com)
- Phone: +1-XXX-XXX-XXXX

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

```
MIT License

Copyright (c) 2025 Event Management System

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

## 📈 Changelog

### [2.0.0] - 2025-01-23
#### Added
- Supabase integration for PostgreSQL database
- Vercel serverless deployment support
- Enhanced security with prepared statements
- Mobile-responsive design improvements
- Real-time event calendar updates
- Comprehensive API documentation

#### Changed
- Migrated from MySQL to PostgreSQL
- Updated PHP to version 8.0+
- Improved error handling and logging
- Enhanced user interface with AdminLTE

#### Security
- Implemented password hashing with bcrypt
- Added CSRF protection tokens
- Enhanced input validation and sanitization
- Secure session management

### [1.0.0] - 2024-12-01
#### Added
- Initial release
- Basic event management functionality
- User authentication system
- Calendar integration
- Business block management

---

## 🚀 Getting Started Now

Ready to deploy? Choose your path:

1. **🐳 Quick Local Setup**: `docker-compose up -d`
2. **☁️ Cloud Deployment**: Deploy to Vercel in one click
3. **🔧 Custom Setup**: Follow the detailed installation guide above

### Need Help?
- 📖 Read the [Documentation](DEPLOYMENT-GUIDE.md)
- 💬 Join our [Community](https://github.com/pangaribowo/ray-event-management/discussions)
- 🐛 Report [Issues](https://github.com/pangaribowo/ray-event-management/issues)
- ⭐ Star this repository if you find it useful!

---

<div align="center">
  <p><strong>Built with ❤️ for the hospitality industry</strong></p>
  <p>
    <a href="https://github.com/pangaribowo/ray-event-management">🏠 Home</a> •
    <a href="https://github.com/pangaribowo/ray-event-management/issues">🐛 Issues</a> •
    <a href="https://github.com/pangaribowo/ray-event-management/wiki">📖 Wiki</a> •
    <a href="#-support">💬 Support</a>
  </p>
</div>
