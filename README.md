# Pirla - Hotel Property Management System

A professional, production-grade Hotel Property Management System (PMS) built with Laravel 11 and Filament 3. This is a multi-hotel SaaS-ready application designed with clean architecture principles and scalability in mind.

## Features

### Multi-Tenancy
- Row-level tenancy via `hotel_id`
- Single database architecture
- Users can belong to multiple hotels
- Global scope automatically filters data by current hotel
- Middleware-based hotel context management

### Core Modules

#### Hotel Management
- Hotel profiles with settings
- Currency, timezone, and tax configuration
- Check-in/check-out time management

#### Inventory Management
- Room types with capacity and pricing
- Individual room tracking
- Room status management (Available, Occupied, Maintenance, Out of Order)
- Amenity management

#### Guest Management
- Complete guest profiles
- Document tracking
- Guest tagging for CRM
- Soft delete support

#### Reservation System
- Full reservation lifecycle (Inquiry → Option → Confirmed → Checked In → Checked Out)
- Reservation source tracking (Direct, OTA, Phone, Email)
- ULID-based reservation codes
- Multi-guest support
- Room assignment
- Domain events for lifecycle changes

#### Rate & Availability
- Rate plans
- Rate rules with date ranges and restrictions
- Daily availability management
- Stop-sell functionality

#### Financial Management
- Invoice generation from reservations
- Invoice line items
- Multiple payment methods (Cash, Card, Bank Transfer, OTA Virtual Card)
- Payment tracking and status management

#### Operations
- Housekeeping task management
- Maintenance ticket system
- Priority-based workflow
- Staff assignment

### Architecture

#### Clean Architecture Structure
```
app/
├── Domain/              # Domain models and business logic
│   ├── Hotel/
│   ├── Inventory/
│   ├── Reservation/
│   ├── Rate/
│   ├── Finance/
│   ├── Guest/
│   ├── Operations/
│   └── Shared/         # Shared enums and traits
├── Application/        # Application services and DTOs
│   ├── Services/       # Business logic services
│   ├── DTOs/
│   └── Actions/
├── Infrastructure/     # Infrastructure concerns
│   ├── Tenancy/       # Multi-tenancy implementation
│   ├── Persistence/
│   └── Support/       # Policies and helpers
└── Filament/          # UI layer (Filament resources)
```

#### Key Services
- **AvailabilityService**: Check and manage room availability
- **PricingService**: Calculate pricing with rate rules
- **ReservationService**: Handle reservation lifecycle
- **InvoicingService**: Generate invoices and process payments

#### Security
- Spatie Laravel Permission for RBAC
- Policy-based authorization
- Hotel-level data isolation
- Built-in roles: Admin, Manager, Receptionist, Housekeeping

## Installation

### Requirements
- PHP 8.2 or higher
- Composer
- SQLite/MySQL/PostgreSQL

### Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/alexrinaldi/pirla.git
   cd pirla
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   
   For SQLite (default):
   ```bash
   touch database/database.sqlite
   ```
   
   Or update `.env` for MySQL/PostgreSQL:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pirla
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

7. **Access the admin panel**
   
   Navigate to: `http://localhost:8000/admin`
   
   **Demo Credentials:**
   - Admin: `admin@example.com` / `password`
   - Manager: `manager@example.com` / `password`
   - Receptionist: `receptionist@example.com` / `password`

## Demo Data

The seeders create a complete demo setup:
- **Grand Plaza Hotel** with full configuration
- **10 Rooms**: 7 Deluxe Rooms (101-107) and 3 Suites (201-203)
- **3 Room Amenities**: WiFi, TV, Mini Bar
- **1 Rate Plan** with availability for next 30 days
- **1 Guest**: John Doe
- **1 Confirmed Reservation** starting tomorrow
- **1 Invoice** with proper calculations
- **4 User Roles** with appropriate permissions

## Usage

### Navigation Structure

#### Setup
- Hotels
- Room Types
- Rooms

#### Front Desk
- Reservations
- Guests
- Reservation Planning (Custom grid view)

#### Rates & Availability
- Rate Plans

#### Finance
- Invoices
- Payments

#### Operations
- Housekeeping Tasks
- Maintenance Tickets

### Key Workflows

#### Creating a Reservation
1. Navigate to Reservations → Create
2. Select check-in/out dates, guests
3. Add reservation items (room types)
4. Link guests
5. System automatically generates invoice

#### Room Assignment
Use the Reservation Planning page to view room occupancy and assign specific rooms to reservations.

#### Check-in Process
1. Open reservation
2. Assign rooms to reservation items
3. Use ReservationService::checkIn()
4. Rooms automatically marked as occupied

#### Invoice Management
1. Invoices auto-generated from reservations
2. Add/edit line items as needed
3. Record payments
4. System tracks payment status

## Technical Details

### Technology Stack
- **Framework**: Laravel 11.x
- **Admin Panel**: Filament 3.x
- **PHP**: 8.2+
- **Permissions**: Spatie Laravel Permission
- **Database**: SQLite/MySQL/PostgreSQL

### Database Schema
- 21 core tables
- Proper foreign key constraints
- Soft deletes on guests
- Enum-based status fields
- Optimized indexes

### Code Quality
- Strict types everywhere (`declare(strict_types=1)`)
- PHP Enums for all status fields
- Service layer for business logic
- Event-driven architecture
- Policy-based authorization
- Form Request validation ready

## Extending the System

### Adding a New Domain
1. Create domain directory in `app/Domain/YourDomain/`
2. Add models in `Models/` subdirectory
3. Create services in `app/Application/Services/`
4. Add Filament resources in `app/Filament/Resources/`

### Adding OTA Integration (Future)
The architecture supports future OTA integration:
- Reservation source already tracks OTA bookings
- Payment method includes OTA Virtual Card
- Extensible service layer for external APIs

### Adding Public Booking Engine (Future)
The system is designed to support a public booking engine:
- Availability service ready for real-time checks
- Pricing service supports rate calculations
- Clean separation of business logic from UI

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

### Database Reset
```bash
php artisan migrate:fresh --seed
```

## License

MIT License

## Credits

Built with:
- [Laravel](https://laravel.com)
- [Filament](https://filamentphp.com)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)

---

**Note**: This is a foundation for a production-grade PMS. While core functionality is implemented, additional features like reporting, analytics, channel manager integration, and public booking engine should be added based on specific business requirements.

