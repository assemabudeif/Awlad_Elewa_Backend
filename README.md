# 🛒 Awlad Elewa E-Commerce Platform
### Laravel Backend & Admin Panel with Mobile API

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com/)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![API](https://img.shields.io/badge/API-REST-orange.svg)](docs)

> **Professional E-Commerce Backend System** built with Laravel featuring a comprehensive admin panel, mobile-first API, real-time notifications, and advanced business logic for commercial retail operations.

---

## 🚀 **Core Features & Capabilities**

### 🔐 **Authentication & Authorization**
- **Laravel Sanctum** token-based authentication for stateless mobile API.
- **Spatie Permissions** for robust role-based access control (RBAC).
- Secure password reset and user profile management.
- API rate limiting and security middleware to prevent abuse.

### 📱 **Mobile-First API (RESTful)**
- Comprehensive endpoints for all platform features.
- Optimized responses with `ProductResource` for consistent data structure.
- Search functionality across products, descriptions, and categories.
- Full support for cart, wishlist, and order management.

### 🛍️ **E-Commerce Engine**
- **Product Catalog:** Advanced product and category management.
- **Shopping Cart:** Persistent cart functionality for authenticated users.
- **Order Management:** Complete order lifecycle tracking from placement to completion.
- **Repair Services:** Unique module for booking and managing repair orders.
- **Wishlist:** Standard user wishlist functionality.

### 🔔 **Advanced Notification System**
- **Firebase Cloud Messaging (FCM)** integration via `laravel-notification-channels/fcm`.
- Real-time notifications for order status updates, promotions, and more.
- User-configurable notification settings.

### 🎛️ **Admin Panel (Filament)**
- Modern, powerful, and extensible admin interface built with **Filament v3**.
- **Dashboard:** At-a-glance analytics and key metrics.
- **Resource Management:** Full CRUD interfaces for Products, Orders, Users, Categories, Banners, and Repair Orders.
- **Settings Configuration:** Manage application settings and business logic dynamically.

---

## 🏗️ **Technical Architecture**

### **Backend Framework**
```
Laravel 12.x
├── PHP 8.2+
├── SQLite (Default), MySQL compatible
├── Redis (Recommended for Caching & Queues)
└── File Storage (Local/Public)
```

### **Key Technologies & Packages**

| Technology | Purpose | Version |
|------------|---------|----------------|
| **Laravel Sanctum** | API Authentication | `^4.0` |
| **Spatie Media Library** | File & Media Management | `^11.13` |
| **Spatie Permissions** | Authorization (RBAC) | `^6.20` |
| **FCM Notifications** | Push Notifications | `^5.1` |
| **Filament** | Admin Panel | `^3.0` |
| **Scribe** | API Documentation | `*` |

### **Database Schema Overview**
- **`users`**: Stores user data, authentication, and profile information.
- **`products`**: Core product catalog, including pricing and descriptions.
- **`categories`**: Hierarchical categories for products.
- **`orders` & `order_items`**: Manages customer orders and line items.
- **`repair_orders`**: Tracks requests for repair services.
- **`banners`**: Manages promotional banners for the mobile app.
- **`wishlists`**: Stores user's saved products.
- **`notifications`**: Logs notifications sent to users.
- **`settings`**: Key-value store for application settings.
- **Roles & Permissions**: Handled by `spatie/laravel-permission` tables.

---

## ⚙️ **Getting Started**

### **Prerequisites**
- PHP 8.2+
- Composer
- Node.js & NPM
- A database server (SQLite, MySQL, etc.)

### **1. Installation**
Clone the repository and install dependencies.
```bash
git clone <repository-url>
cd Awlad_Elewa
composer install
npm install
```

### **2. Environment Configuration**
Copy the example environment file and generate the application key.
```bash
cp .env.example .env
php artisan key:generate
```
Update your `.env` file with database credentials and other environment-specific settings (e.g., mail server, FCM credentials).

### **3. Database Migration & Seeding**
Run the database migrations and seeders to set up the schema and initial data.
```bash
php artisan migrate --seed
```

### **4. Start the Servers**
Use the custom `dev` script to run the PHP server, queue listener, and Vite development server concurrently.
```bash
composer run dev
```
- Your application will be available at `http://127.0.0.1:8000`.
- The Filament admin panel is typically at `http://127.0.0.1:8000/admin`.

---

## 🧪 **Running Tests**

The project includes a suite of tests. To run them, use the provided composer script:
```bash
composer test
```

---

## 📚 **API Documentation**

API documentation is generated using **Scribe**. To generate or update the documentation:
```bash
php artisan scribe:generate
```
This will create a `public/docs` directory with a beautiful and interactive API documentation portal. You can access it at `http://your-app-url/docs`.

---

## 📊 **Performance & Monitoring**

### **Optimization Strategies**
- **Database Indexing** on frequently queried columns
- **Eager Loading** to prevent N+1 queries
- **Redis Caching** for session and application data
- **Queue Workers** for background processing
- **API Rate Limiting** to prevent abuse

### **Monitoring & Logging**
```php
// Comprehensive logging
Log::info("Order #{$order->id} created by user {$user->name}");
Log::error("FCM notification failed: " . $exception->getMessage());

// Performance tracking
DB::enableQueryLog(); // Development monitoring
```

---

## 🔒 **Security Implementation**

### **API Security Measures**
- **Token-based authentication** with expiration
- **CORS configuration** for cross-origin requests
- **Input validation** with Laravel Form Requests
- **SQL injection prevention** through Eloquent ORM
- **XSS protection** with automatic escaping

### **Data Protection**
```php
// Sensitive data handling
protected $hidden = ['password', 'remember_token', 'fcm_token'];
protected $fillable = [...]; // Mass assignment protection

// Encryption for sensitive operations
Crypt::encrypt($sensitiveData);
Hash::make($password);
```

---

## 📱 **Mobile Integration**

### **Response Format Standards**
```json
{
    "data": {
        "id": 1,
        "name": "Product Name",
        "price": "100.00",
        "image": "https://domain.com/storage/products/image.jpg"
    },
    "message": "Operation successful",
    "status": "success",
    "pagination": {
        "current_page": 1,
        "total": 50,
        "per_page": 15
    }
}
```

### **Error Handling**
```json
{
    "message": "Validation failed", 
    "errors": {
        "email": ["The email field is required."]
    },
    "status": "error"
}
```

---

## 🎨 **Admin Panel Interface**

### **Modern UI Features**
- **Responsive Design** with Arabic RTL support
- **Interactive Dashboard** with real-time metrics
- **Advanced Forms** with validation feedback
- **Image Management** with drag-and-drop uploads
- **Notification Center** for system alerts

### **Business Operations**
- **Order Management** with status workflows
- **Product Catalog** with category management
- **User Administration** with role assignments  
- **Analytics Dashboard** with sales insights
- **Settings Panel** for business configuration

---

## 📈 **Scalability & Future Enhancements**

### **Current Architecture Benefits**
- **Microservice-ready** structure with service layers
- **API-first approach** for multi-platform support
- **Queue-based processing** for performance
- **Modular design** for feature extensions

### **Potential Enhancements**
- **Elasticsearch** integration for advanced search
- **Redis Pub/Sub** for real-time features
- **Docker containerization** for deployment
- **API versioning** for backward compatibility
- **GraphQL endpoints** for flexible data fetching

---

## 🏆 **Technical Achievements**

### **Backend Excellence**
✅ **RESTful API Design** with consistent response patterns  
✅ **Real-time Notifications** with Firebase integration  
✅ **Automated Business Logic** with event-driven architecture  
✅ **Advanced Security** implementation with multiple layers  
✅ **Scalable Architecture** with service-oriented design  
✅ **Queue Management** for background processing  
✅ **Comprehensive Testing** capabilities built-in  

### **Business Impact**
✅ **Complete E-commerce Solution** from catalog to fulfillment  
✅ **Multi-platform Support** for web and mobile applications  
✅ **Real-time Communication** between customers and business  
✅ **Automated Operations** reducing manual intervention  
✅ **Analytics-ready** structure for business intelligence  

---

## 📞 **Technical Contact & Documentation**

### **API Documentation**
- **Swagger/OpenAPI** specification available
- **Postman Collection** for testing
- **Response examples** for all endpoints
- **Error code references** for debugging

### **Code Quality**
- **PSR-12** coding standards compliance
- **SOLID principles** implementation
- **Design patterns** usage (Repository, Service, Observer)
- **Comprehensive comments** and documentation

---

## 🎯 **Professional Summary**

This project demonstrates advanced **Laravel development expertise** including:

- **Enterprise-level architecture** design and implementation
- **RESTful API development** with modern best practices  
- **Real-time systems** integration with Firebase
- **Database design** and optimization strategies
- **Security implementation** and threat mitigation
- **Performance optimization** and scalability planning
- **Modern PHP practices** and framework mastery

**Perfect for portfolios demonstrating full-stack backend development capabilities, API design expertise, and business logic implementation skills.**

---

### 📄 **License**
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

**Built with ❤️ using Laravel | Ready for production deployment**
