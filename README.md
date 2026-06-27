# Fevourd-K - NGO CSR Citizen Engagement Platform

## 📋 Overview

Fevourd-K is a comprehensive technology-enabled social impact ecosystem that connects NGOs, Corporates/CSR teams, Individual donors, Vendors, and Citizens & volunteers. The platform enables transparent funding, measurable impact, and scalable social initiatives while never acting as a financial intermediary.

## UiPath AgentHack Submission

**ImpactOps Maestro** is the UiPath agentic automation layer for Fevourd-K. It coordinates NGO and CSR back-office workflows while Fevourd-K remains the system of record for campaigns, field proof, finance claims, ledgers, and compliance reports.

### Agent Type

Hybrid agentic process: UiPath Maestro orchestrates workflow decisions and human approvals, UiPath automations execute the repeatable work, and Fevourd-K provides the Laravel/Vue operating portal and APIs.

### UiPath Solutions

1. **Compliance Review** - reviews NGO/CSR compliance inputs and produces findings, recommendations, and status updates.
2. **Campaign Draft** - prepares campaign/social-update drafts from Fevourd-K campaign and feed data.
3. **Field Proof** - checks field-task evidence, active sessions, GPS trail points, and completion status.
4. **Finance Claim** - reviews expense claims and routes approvals before approved claims post to the NGO ledger.
5. **CSR Report** - assembles CSR impact and compliance summaries for corporate CSR users.
6. **ImpactOps Maestro Agentic Process** - coordinates the end-to-end workflow and handoffs across the above solutions.

### UiPath Components To Include In Submission

- UiPath Maestro process for ImpactOps orchestration.
- UiPath Studio projects/packages for the six workflow solutions.
- Orchestrator queues, triggers, folders, and assets used by the demo tenant.
- Human-in-the-loop approval evidence, such as Action Center tasks or equivalent review screens.
- Sanitized sample payloads that show how UiPath reads from and writes back to Fevourd-K.

See [uipath/README.md](uipath/README.md) and [docs/agenthack-submission-checklist.md](docs/agenthack-submission-checklist.md) for the current submission artifact checklist.

## 🏗️ Architecture

### Core Principles
- ❌ No pooled funds
- ❌ No central wallet  
- ❌ Platform never holds money
- ✅ Individual payment gateway per NGO
- ✅ Full audit trail
- ✅ Compliance-first design
- ✅ Single codebase (web + PWA)

### Technology Stack

**Backend:**
- Laravel 11+
- PHP 8.2+
- REST APIs (JSON)
- Queue system (Redis)
- Job processing (Horizon)

**Frontend:**
- Laravel + Inertia.js
- Vue 3
- Tailwind CSS
- PWA enabled

**Database:**
- PostgreSQL
- Redis (cache, queues)

**Mobile Strategy:**
- Android: Trusted Web Activity (TWA)
- iOS: WebView initially
- PWA-first approach

## 🚀 Features Implemented

### ✅ Completed Features

1. **Database Architecture**
   - Complete PostgreSQL schema with all required tables
   - Location-based hierarchy (State → District → City)
   - NGO verification system
   - Multi-role user management
   - Campaign and donation tracking
   - Audit logging system

2. **Authentication & Authorization**
   - Role-based access control using Spatie Laravel Permission
   - 8 distinct user roles: Super Admin, State Admin, NGO Admin, NGO Staff, Corporate CSR Manager, Donor, Vendor, Volunteer
   - Permission-based feature access
   - Secure authentication system

3. **Frontend Setup**
   - Vue 3 + Inertia.js integration
   - Tailwind CSS with custom components
   - Responsive design
   - PWA configuration
   - Modern UI/UX components

4. **NGO Management**
   - NGO registration and verification
   - Document upload and verification
   - Bank account management
   - Payment gateway configuration

5. **Location System**
   - Karnataka-focused (ready for national expansion)
   - Hierarchical location structure
   - Location-tagged entities

6. **PWA Configuration**
   - Service worker setup
   - App manifest
   - Mobile-optimized interface
   - Offline capabilities

### 🔄 In Progress

1. **Donation Architecture**
   - Multiple payment gateway integration (Razorpay, PhonePe, Stripe)
   - Direct NGO payment processing
   - Donation cart functionality
   - Recurring donations

2. **Campaign System**
   - Campaign creation and management
   - Progress tracking
   - Social sharing
   - Donor wall

### 📋 Pending Features

1. **Auto NGO Website Generation**
   - Subdomain creation (ngo-name.favoredk.org)
   - Template-based NGO websites
   - Custom domain support

2. **CSR Module**
   - Corporate dashboards
   - SDG mapping
   - CSR compliance reports
   - Analytics and insights

3. **Live Broadcasting**
   - NGO live events
   - Campaign launches
   - Live donation during streams

4. **Vendor Marketplace**
   - Vendor verification
   - Service listings
   - Review system

5. **Analytics & Dashboards**
   - Comprehensive analytics
   - Impact tracking
   - Financial reporting

## 🛠️ Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- PostgreSQL
- Redis

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd Fevourd-k
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database configuration**
   - Configure PostgreSQL in `.env`
   - Run migrations: `php artisan migrate`
   - Seed data: `php artisan db:seed`

5. **Start development servers**
   ```bash
   # Laravel server
   php artisan serve
   
   # Vite dev server (in another terminal)
   npm run dev
   ```

## 🏢 User Roles & Permissions

### Role Hierarchy

1. **Super Admin**
   - Full system access
   - NGO verification
   - User management
   - System configuration

2. **State Admin**
   - State-level oversight
   - NGO verification within state
   - Analytics access

3. **NGO Admin**
   - NGO profile management
   - Campaign creation
   - Team management
   - Donation tracking

4. **NGO Staff**
   - Limited NGO access
   - Campaign management
   - Donor communication

5. **Corporate CSR Manager**
   - CSR campaign management
   - Impact tracking
   - Compliance reporting

6. **Donor**
   - Browse campaigns
   - Make donations
   - Track contributions

7. **Vendor**
   - Service listings
   - Client management
   - Portfolio showcase

8. **Volunteer**
   - Event participation
   - Skill-based volunteering

## 📊 Database Schema

### Core Tables

- **Users & Authentication**: users, ngo_users, corporate_users, donors
- **Location**: states, districts, cities
- **NGOs**: ngos, ngo_documents, ngo_bank_accounts, ngo_payment_gateways
- **Campaigns**: campaigns, donations, recurring_donations, donation_cart
- **Events**: events, event_registrations, live_streams
- **Content**: impact_reports, analytics
- **System**: audit_logs, notifications, subscriptions

## 🔧 Configuration

### Payment Gateways
```env
RAZORPAY_KEY=your_razorpay_key
RAZORPAY_SECRET=your_razorpay_secret
PHONEPE_MERCHANT_ID=your_phonepe_merchant_id
PHONEPE_SALT_KEY=your_phonepe_salt_key
```

### Email Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
```

### Redis Configuration
```env
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## 🚀 Deployment

### Production Deployment

1. **Server Requirements**
   - Ubuntu 20.04+ or equivalent
   - Nginx/Apache
   - PHP 8.2+ with required extensions
   - PostgreSQL 13+
   - Redis 6+

2. **Deployment Steps**
   ```bash
   # Pull latest code
   git pull origin main
   
   # Install dependencies
   composer install --no-dev --optimize-autoloader
   npm ci && npm run build
   
   # Run migrations
   php artisan migrate --force
   
   # Clear caches
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Queue Worker Setup**
   ```bash
   # Install Supervisor
   sudo apt-get install supervisor
   
   # Configure queue worker
   php artisan queue:work --daemon
   ```

## 🧪 Testing

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter NGOTest

# Generate coverage report
php artisan test --coverage
```

### Test Coverage
- Unit tests for models and services
- Feature tests for API endpoints
- Browser tests for user flows
- Integration tests for payment gateways

## 📈 Performance Optimization

### Caching Strategy
- Redis for session storage
- Application-level caching
- Database query optimization
- CDN for static assets

### Security Measures
- Input validation and sanitization
- SQL injection prevention
- XSS protection
- CSRF protection
- Rate limiting
- Data encryption

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 📞 Support

For support and queries:
- Email: support@favoredk.org
- Documentation: /docs
- Issues: GitHub Issues

## 🗺️ Roadmap

### Phase 1 (Current)
- ✅ Core platform setup
- ✅ Authentication system
- ✅ NGO management
- 🔄 Payment integration
- 🔄 Campaign system

### Phase 2 (Next 3 months)
- Auto NGO websites
- CSR module
- Advanced analytics
- Mobile apps

### Phase 3 (6+ months)
- AI-powered matching
- Blockchain transparency
- International expansion
- Advanced vendor marketplace

---

**Fevourd-K** - Digital infrastructure for social impact, built for trust, compliance, and scale.
# fevourdk
