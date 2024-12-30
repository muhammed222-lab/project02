# Project Analysis Documentation

## Project Overview
This is a comprehensive analysis of the project structure and functionality.

## Directory Structure

### Root Level Components
- `.gitignore` - Git ignore file
- `.htaccess` - Apache configuration file
- Various PHP files for authentication and user management:
  - `login.php`
  - `signup.php`
  - `logout.php`
  - `reset_password.php`
  - `verify_otp.php`
  - `confirm_otp.php`
  - `new_password.php`
  - `update_password.php`
  - Database operations: `db.php`
  - Search functionality: `search.php`
  - Terms and conditions: `terms.php`

### Key Directories
1. **JavaScript/**
   - `cookies.js`
   - `main.js`
   - `user.js`

2. **SQL/**
   - `database.sql` - Database schema and structure

3. **admin/**
   - Admin dashboard and management
   - User management functionality
   - Profile management

4. **assets/**
   - `images/` - Project images and resources

5. **creator/**
   - Creator dashboard
   - Project management
   - Bank details verification
   - Profile management

6. **freelancer/**
   - Freelancer dashboard
   - Project application system
   - Profile management
   - Gig management

7. **includes/**
   - Common components
   - Database connections
   - Header and footer

8. **instructor/**
   - Instructor dashboard
   - Profile management
   - Job management

9. **php/**
   - Core PHP components
   - Authentication handlers

10. **student/**
    - Student dashboard
    - Project management
    - Chat functionality
    - Payment processing
    - Profile settings

## User Roles
The system appears to have multiple user roles:
- Admin
- Creator
- Freelancer
- Instructor
- Student

Each role has its own dedicated section with specific functionalities.

## Core Features
1. Authentication System
   - Login/Signup
   - Password Reset
   - OTP Verification
   - Session Management

2. Project Management
   - Project Creation
   - Project Applications
   - Project Status Updates
   - File Uploads

3. Communication
   - Chat System
   - Messaging
   - File Sharing

4. Financial Features
   - Payment Processing
   - Bank Details Verification
   - Transaction Verification

5. Profile Management
   - Profile Updates
   - Settings Management
   - Role-specific Customizations

## File Upload System
Multiple upload directories for different user roles:
- creator/uploaded/
- freelancer/uploads/
- student/uploads/

## Database Structure

### Core Tables

1. **Users Table**
```sql
- Primary user information storage
- Fields: id, name, email, password, role
- Roles: student, freelancer, instructor, creator
- Tracks: department, matric_number, profile picture
- Manages: cookies acceptance, join date
```

2. **Projects Table**
```sql
- Stores projects created by creators/freelancers
- Fields: id, creator_id, title, description, price
- Tracks: creation date, sale status, category
- Includes: project_file and writeup_file
```

3. **Custom Projects Table**
```sql
- Handles student-initiated project requests
- Fields: project_title, description, keywords, deadline
- Tracks: budget, proposals, student details
- Manages: application counts
```

### Financial System

1. **Transactions Table**
```sql
- Manages all financial transactions
- Records: buyer, creator, project details
- Handles: amount, commission (default 20%)
- Timestamps all transactions
```

2. **Bank Details Table**
```sql
- Stores creator/freelancer banking information
- Fields: account_number, account_bank, account_name
- Secure user-bank detail association
```

### Project Management

1. **Project Interests Table**
```sql
- Tracks student interest in projects
- Records: project views and purchases
- Links users to projects they're interested in
```

2. **Project Applications Table**
```sql
- Manages applications for custom projects
- Stores: creator details, qualifications
- Includes: application reasoning and timestamps
```

### Communication System

1. **Messages Table**
```sql
- Handles direct messaging between users
- Fields: sender_id, receiver_id, content
- Timestamps all messages
```

2. **Notifications Table**
```sql
- System-wide notification management
- Tracks: read/unread status
- Stores: user-specific messages
```

### User Experience

1. **Reviews Table**
```sql
- Project feedback system
- Implements: 1-5 rating scale
- Stores: detailed review text
```

2. **User Sessions Table**
```sql
- Manages active user sessions
- Tracks: login/logout times
- Handles: session tokens
```

3. **User Cookies Table**
```sql
- Manages cookie consent
- Tracks: consent status and date
```

## Key Database Relationships

1. **Project Ownership**
   - Creators/Freelancers → Projects (one-to-many)
   - Students → Custom Projects (one-to-many)

2. **Project Interactions**
   - Users → Project Interests (many-to-many)
   - Creators → Project Applications (one-to-many)

3. **Financial Flows**
   - Projects → Transactions (one-to-many)
   - Users → Bank Details (one-to-one)

4. **Communication Paths**
   - Users → Messages (many-to-many)
   - Users → Notifications (one-to-many)

## Security Features
- Password hashing (VARCHAR(255) for password field)
- Session management with tokens
- Cookie consent tracking
- Secure banking information storage

## Authentication Implementation

### Frontend Components
```html
- Modern UI using Tailwind CSS
- Responsive design with mobile-friendly layouts
- Clear user feedback and error messaging
- Password reset functionality
- New account creation option
```

### Security Measures
1. **Database Security**
   - PDO prepared statements for SQL injection prevention
   - Password hashing verification
   - Input sanitization using htmlspecialchars

2. **Session Management**
   ```php
   - Session initialization on login
   - User ID and role storage in session
   - Session token management
   - Secure session handling
   ```

3. **Role-Based Access Control**
   ```php
   - Dynamic routing based on user roles:
     * Student → student/dashboard.php
     * Freelancer → freelancer/dashboard.php
     * Instructor → instructor/dashboard.php
     * Creator → creator/dashboard.php
   - Role-specific permissions and access
   ```

4. **Error Handling**
   ```php
   - Structured error responses
   - User-friendly error messages
   - Secure error logging
   - PDOException handling
   ```

### Authentication Flow
1. **Login Request**
   - Form submission with email/password
   - POST request to login handler

2. **Validation Process**
   - Input sanitization
   - Credential verification
   - Password hash comparison

3. **Session Initialization**
   - User session creation
   - Role assignment
   - Redirect to appropriate dashboard

4. **Security Headers**
   - Content Security Policy
   - XSS Protection
   - CSRF Prevention

### Frontend Frameworks
1. **CSS Framework**
   - Tailwind CSS v2.2.19
   - Responsive design components
   - Custom utility classes

2. **Icon System**
   - Ionicons v5.5.2
   - Scalable vector icons
   - Consistent UI elements

3. **User Interface**
   - Clean, modern design
   - Intuitive form layouts
   - Clear navigation paths
   - Responsive components

---

## Project Management System

### Creator Projects

1. **Project Creation Interface**
```php
- Role-restricted access (creator only)
- Interactive assistance sidebar
- Comprehensive form validation
- File upload management
```

2. **File Management**
```php
- Project file uploads (.zip, .rar)
- Documentation uploads (.docx, .txt)
- Secure file storage in creator/uploaded/
- Unique file naming with uniqid()
```

3. **Project Metadata**
```php
- Title and description
- Pricing information
- Category classification
- Creator association
```

4. **UI Features**
```html
- Interactive help system
- Hover-based assistance
- Category suggestions
- Responsive layout
```

### Freelancer Gigs

1. **Gig Creation System**
```php
- Freelancer-specific interface
- Screenshot-based presentation
- Tag-based categorization
- Price setting
```

2. **Media Management**
```php
- Dual screenshot requirement
- Image file handling
- Secure storage in freelancer/uploads/
- Unique image naming
```

3. **Gig Structure**
```php
- Gig name and description
- Visual representation
- Tag and category system
- Pricing model
```

### Common Features

1. **Security Measures**
```php
- Role-based access control
- Session validation
- Input sanitization
- Secure file handling
```

2. **Database Integration**
```sql
- Projects table for creators
- Gigs table for freelancers
- User associations
- File path storage
```

3. **User Experience**
```html
- Tailwind CSS styling
- Responsive design
- Form validation
- Success/error feedback
```

### Project Workflows

1. **Creator Workflow**
   - Project creation
   - File uploads
   - Documentation
   - Project listing
   - Sales tracking

2. **Freelancer Workflow**
   - Gig creation
   - Portfolio building
   - Service offering
   - Client interaction

3. **Student Interaction**
   - Project browsing
   - Purchase system
   - Download access
   - Review capability

### Technical Implementation

1. **File Processing**
```php
- File type validation
- Secure upload handling
- Unique naming system
- Storage management
```

2. **Database Operations**
```php
- PDO prepared statements
- Transaction handling
- Error management
- Data validation
```

3. **Security Features**
```php
- Session management
- Input sanitization
- Access control
- Error handling
```

---

## Payment and Transaction System

### Payment Gateway Integration

1. **Flutterwave Integration**
```php
- API-based integration
- Test environment configuration
- Secure key management
- Transaction reference generation
```

2. **Payment Processing**
```php
- Amount validation
- Currency handling (NGN)
- Buyer verification
- Payment URL generation
```

### Transaction Flow

1. **Payment Initiation**
```php
- Session validation
- User data retrieval
- Payment payload creation
- Gateway redirection
```

2. **Transaction Verification**
```php
- Status verification
- Transaction ID validation
- Amount confirmation
- Currency validation
```

3. **Success Handling**
```php
- Payment data storage
- Transaction recording
- User notification
- Receipt generation
```

### Security Implementation

1. **Environment Variables**
```php
- Secret key management
- API credentials
- Test/Production switching
- Secure configuration
```

2. **Data Validation**
```php
- Amount verification
- Currency validation
- User authentication
- Transaction verification
```

3. **Error Handling**
```php
- Payment failures
- API errors
- Transaction issues
- User feedback
```

### Database Operations

1. **Payment Records**
```sql
- Transaction ID storage
- Amount and currency
- Buyer information
- Timestamp tracking
```

2. **Transaction Status**
```php
- Payment verification
- Status updates
- Error logging
- Success recording
```

### User Experience

1. **Payment Interface**
```html
- Clear payment forms
- Status feedback
- Error messages
- Success confirmation
```

2. **Transaction Feedback**
```php
- Payment confirmation
- Receipt display
- Error notifications
- Status updates
```

### Technical Details

1. **API Integration**
```php
- RESTful endpoints
- HTTP requests
- Response handling
- Error management
```

2. **Data Flow**
```php
- User → Payment Gateway
- Gateway → Verification
- Verification → Database
- Database → User Feedback
```

3. **Security Measures**
```php
- HTTPS enforcement
- Data encryption
- Session security
- Input validation
```

---

## Communication System

### Message Management

1. **Conversation System**
```php
- User-to-creator messaging
- Message history tracking
- Conversation listing
- Real-time updates
```

2. **Message Storage**
```sql
- Sender and receiver tracking
- Timestamp management
- Message content storage
- Conversation threading
```

### Chat Interface

1. **User Interface**
```html
- Clean chat layout
- Message timestamps
- Sender identification
- Responsive design
```

2. **Message Features**
```php
- Real-time display
- Message history
- Conversation context
- User feedback
```

### Technical Implementation

1. **Database Operations**
```php
- PDO prepared statements
- Message retrieval
- Conversation tracking
- Error handling
```

2. **Security Measures**
```php
- Session validation
- Input sanitization
- XSS prevention
- SQL injection protection
```

### User Experience

1. **Chat Features**
```html
- Message composition
- History viewing
- User identification
- Time tracking
```

2. **Navigation**
```php
- Conversation listing
- Chat access
- Message sending
- History browsing
```

### Message Flow

1. **Sending Process**
```php
- Message composition
- Recipient validation
- Database storage
- Delivery confirmation
```

2. **Receiving Process**
```php
- Message retrieval
- Sender verification
- Display formatting
- History updating
```

### System Architecture

1. **Database Structure**
```sql
- Messages table
- User relationships
- Timestamp tracking
- Content storage
```

2. **Application Logic**
```php
- Message routing
- User validation
- Content processing
- Error management
```

3. **Interface Components**
```html
- Message display
- Input forms
- Navigation elements
- Status indicators
```

---

## Profile Management System

### Role-Specific Features

1. **Student Profile**
```php
- Department information
- Matric number tracking
- Academic details
- Basic profile management
```

2. **Creator Profile**
```php
- Profile picture uploads
- Email management
- Project portfolio
- File handling system
```

3. **Freelancer Profile**
```php
- Portfolio management
- Skill presentation
- Service offerings
- Work history
```

### Common Features

1. **Profile Data**
```php
- Name management
- Email verification
- Account settings
- Profile updates
```

2. **Security Measures**
```php
- Role validation
- Session checks
- Input sanitization
- Access control
```

### File Management

1. **Profile Pictures**
```php
- Image upload handling
- File type validation
- Storage management
- Path tracking
```

2. **Document Storage**
```php
- Secure file handling
- Upload directories
- File naming
- Access control
```

### User Interface

1. **Profile Forms**
```html
- Clean form layouts
- Input validation
- Error feedback
- Success messages
```

2. **Visual Elements**
```html
- Profile pictures
- Form styling
- Responsive design
- User feedback
```

### Technical Implementation

1. **Database Operations**
```php
- Profile updates
- Data validation
- Error handling
- Transaction management
```

2. **Security Features**
```php
- Input sanitization
- Session validation
- Access control
- Error management
```

### Profile Workflows

1. **Update Process**
```php
- Data validation
- File processing
- Database updates
- User notification
```

2. **Access Control**
```php
- Role verification
- Permission checks
- Session management
- Secure routing
```

---

## Admin Management System

### Dashboard Features

1. **Analytics Dashboard**
```javascript
- User type distribution charts
- Interactive data visualization
- Real-time statistics
- Chart.js integration
```

2. **User Overview**
```php
- User count by role
- Status monitoring
- Activity tracking
- Quick actions
```

### User Management

1. **User Control**
```php
- User status management
- Account disabling
- Role management
- Profile viewing
```

2. **Search Functionality**
```javascript
- Real-time user search
- Filter by name/email
- Dynamic results
- Instant feedback
```

### Data Management

1. **User Data**
```sql
- Basic information retrieval
- Status tracking
- Role assignment
- Activity logging
```

2. **Database Operations**
```php
- PDO prepared statements
- Status updates
- User modifications
- Error handling
```

### Security Implementation

1. **Access Control**
```php
- Admin authentication
- Session validation
- Role verification
- Secure routing
```

2. **Data Protection**
```php
- Input sanitization
- SQL injection prevention
- XSS protection
- CSRF prevention
```

### Interface Components

1. **User Interface**
```html
- Clean dashboard layout
- Interactive tables
- Modal dialogs
- Status indicators
```

2. **Visual Elements**
```javascript
- Data charts
- Status badges
- Action buttons
- Search interface
```

### Admin Workflows

1. **User Management**
```php
- View user details
- Disable accounts
- Update status
- Monitor activity
```

2. **System Monitoring**
```javascript
- Track user distribution
- Monitor system status
- View analytics
- Generate reports
```

---

*This completes the comprehensive analysis of the project structure and functionality.*