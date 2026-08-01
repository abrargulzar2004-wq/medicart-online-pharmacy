# 💊 MediCart – Online Pharmacy & Healthcare Store

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" />
  <img src="https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" />
  <img src="https://img.shields.io/badge/Status-Completed-success?style=for-the-badge" />
</p>

<p align="center">
A Secure, Responsive and Modern Full Stack E-Commerce Web Application for an Online Pharmacy built with Laravel.
</p>

---

# 🌐 Project Links

| Resource | Link |
|----------|------|
| 🚀 Live Demo |  https://medicart-online-pharmacy.onrender.com |
| 💻 GitHub Repository | https://github.com/your-username/medicart |

> Replace the placeholder URLs with your actual Live Demo and GitHub Repository before submission.

---

# 📖 Table of Contents

- Project Overview
- Why MediCart?
- Core Features
- Customer Website
- Admin Panel
- Authentication & Security
- Technology Stack
- System Architecture
- Database Overview
- Installation Guide
- Project Structure
- Business Workflow
- Screenshots
- Future Enhancements
- Author

---

# 📌 Project Overview

**MediCart** is a modern **Full Stack E-Commerce Web Application** developed for an online pharmacy and healthcare store. It enables customers to browse medicines, manage shopping carts, and place orders through a secure and responsive platform.

The application also provides a powerful **Admin Panel** for managing products, categories, brands, inventory, customers, contact messages, and customer orders.

The project follows Laravel's **MVC (Model-View-Controller)** architecture and is designed with scalability, maintainability, and clean code practices in mind.

---

# 💡 Why MediCart?

The goal of MediCart is to simplify the process of purchasing healthcare products online while maintaining security, usability, and efficient inventory management.

The application focuses on delivering:

- Secure customer authentication
- Easy medicine browsing
- Efficient order processing
- Organized inventory management
- Responsive user experience
- Centralized administration

---

# 🎯 Project Objectives

- Develop a complete Full Stack E-Commerce solution.
- Provide a secure online shopping experience.
- Implement clean MVC architecture.
- Build a professional Admin Dashboard.
- Manage medicines and inventory efficiently.
- Process customer orders effectively.
- Ensure responsive design across all devices.
- Follow Laravel development best practices.

---

# ✨ Core Features

## 👤 Customer Module

- User Registration
- Email OTP Verification
- Secure Login & Logout
- Forgot Password
- Product Search
- Category Filtering
- Brand Filtering
- Product Details
- Shopping Cart
- Checkout
- Order Placement
- Order History
- Profile Management
- About Page
- Contact Page

---

## 👨‍💼 Admin Module

- Secure Admin Authentication
- Dashboard Analytics
- Product Management
- Category Management
- Brand Management
- Inventory Management
- Customer Management
- Order Management
- Contact Message Management
- Product Image Upload

---

# 🛒 Customer Website

The customer-facing website provides a smooth and intuitive shopping experience.

## 🏠 Home

- Hero Banner
- Featured Medicines
- Latest Products
- Popular Categories
- Promotional Sections

---

## 💊 Products

Customers can:

- Browse Medicines
- Search Products
- Filter by Category
- Filter by Brand
- View Availability
- View Prices

---

## 📄 Product Details

Each product page includes:

- Product Image
- Product Name
- Description
- Category
- Brand
- Price
- Stock Status
- Add to Cart

---

## 🛍 Shopping Cart

Customers can:

- Add Products
- Update Quantity
- Remove Products
- View Cart Total
- Continue Shopping
- Proceed to Checkout

---

## 💳 Checkout

The checkout process includes:

- Shipping Information
- Customer Details
- Order Summary
- Order Confirmation

---

## 📦 My Orders

Customers can:

- View Order History
- Check Order Status
- View Order Details

---

## 👤 Profile

Customers can:

- Update Personal Information
- Change Password
- Manage Account Details

---

## ℹ️ About Page

The About page introduces MediCart, explains the project's mission, healthcare commitment, and provides information about the pharmacy and its services.

---

## 📞 Contact Page

The Contact page enables customers to submit inquiries through a contact form while also displaying the pharmacy's email address, phone number, and business location.

---

# 👨‍💼 Admin Dashboard

The Admin Panel provides centralized management of the complete application.

## Dashboard

The dashboard provides quick insights into:

- Total Products
- Total Categories
- Total Customers
- Total Orders
- Pending Orders
- Completed Orders

---

## Product Management

Administrators can:

- Add Products
- Edit Products
- Delete Products
- Upload Product Images
- Update Product Stock

---

## Category Management

- Create Categories
- Edit Categories
- Delete Categories

---

## Brand Management

- Add Brands
- Update Brands
- Delete Brands

---

## Inventory Management

- Monitor Stock Levels
- Update Inventory
- Identify Low Stock Products

---

## Order Management

Manage customer orders throughout the order lifecycle.

Supported order statuses:

- Pending
- Processing
- Shipped
- Delivered
- Cancelled

---

## Customer Management

- View Registered Customers
- Search Customers
- Manage Customer Records

---

## Contact Messages

- View Messages
- Read Messages
- Delete Messages

---

# 🔐 Authentication & Security

MediCart uses Laravel's authentication system along with additional security practices.

## Customer Authentication Flow

```

Register
│
▼
Email OTP Verification
│
▼
Account Activated
│
▼
Login with Email & Password
│
▼
Customer Dashboard

```

## Security Features

- Password Hashing
- Email OTP Verification
- CSRF Protection
- Authentication Middleware
- Authorization
- Session Security
- Form Validation
- Input Sanitization

---

# 🛠 Technology Stack

| Layer | Technology |
|--------|------------|
| Framework | Laravel 12 |
| Backend | PHP 8 |
| Frontend | Blade, HTML5, CSS3, JavaScript |
| UI Framework | Bootstrap 5 |
| Database | MySQL |
| Version Control | Git & GitHub |
| Development Environment | XAMPP |
| IDE | Visual Studio Code |

---

# 🏗 System Architecture

```

Customer
│
▼
Laravel Blade Views
│
▼
Controllers
│
▼
Business Logic
│
▼
Eloquent ORM
│
▼
MySQL Database
│
▼
Admin Dashboard

```

---# 🗄️ Database Overview

MediCart uses **MySQL** as its relational database. The database is designed using Laravel migrations with proper relationships to ensure data consistency and scalability.

| Table | Purpose |
|-------|---------|
| users | Stores customer and administrator accounts |
| categories | Stores product categories |
| brands | Stores medicine brands |
| products | Stores product information |
| carts | Stores customer shopping carts |
| cart_items | Stores cart items |
| orders | Stores customer orders |
| order_items | Stores ordered products |
| contacts | Stores customer inquiries |
| password_reset_tokens | Stores password reset requests |
| sessions | Stores active user sessions |

---

# 📁 Project Structure

```
MediCart/
│
├── app/
│   ├── Http/
│   ├── Models/
│   ├── Providers/
│
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   ├── seeders/
│
├── public/
├── resources/
│   ├── views/
│   ├── css/
│   ├── js/
│
├── routes/
├── storage/
├── tests/
├── artisan
├── composer.json
├── .env.example
└── README.md
```

---

# ⚙️ Installation Guide

## 1️⃣ Clone the Repository

```bash
git clone https://github.com/your-username/medicart.git
```

---

## 2️⃣ Navigate to the Project

```bash
cd medicart
```

---

## 3️⃣ Install PHP Dependencies

```bash
composer install
```

---

## 4️⃣ Create Environment File

```bash
cp .env.example .env
```

---

## 5️⃣ Generate Application Key

```bash
php artisan key:generate
```

---

## 6️⃣ Configure Database

Update the following values inside the `.env` file.

```env
APP_NAME=MediCart
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medicart
DB_USERNAME=root
DB_PASSWORD=
```

---

## 7️⃣ Run Database Migrations

```bash
php artisan migrate
```

If seeders are available:

```bash
php artisan db:seed
```

or

```bash
php artisan migrate --seed
```

---

## 8️⃣ Create Storage Link

```bash
php artisan storage:link
```

---

## 9️⃣ Start the Development Server

```bash
php artisan serve
```

Visit:

```
http://127.0.0.1:8000
```

---

# 🔄 Business Workflow

```
Customer Registration
        │
        ▼
Email OTP Verification
        │
        ▼
Secure Login
        │
        ▼
Browse Products
        │
        ▼
Add Products to Cart
        │
        ▼
Checkout
        │
        ▼
Place Order
        │
        ▼
Admin Reviews Order
        │
        ▼
Order Processing
        │
        ▼
Order Delivered
```

---

# 📱 Responsive Design

MediCart is fully responsive and optimized for:

- 💻 Desktop
- 💼 Laptop
- 📱 Mobile
- 📲 Tablet

The interface is designed to provide a consistent user experience across different screen sizes.

---

# 📸 Screenshots

> Replace the placeholders below with your actual project screenshots.

## Customer Website

- 🏠 Home Page
- 💊 Products Page
- 📄 Product Details
- 🛒 Shopping Cart
- 💳 Checkout
- 🔐 Login
- 📝 Registration
- 👤 Profile
- 📦 My Orders
- ℹ️ About Page
- 📞 Contact Page

---

## Admin Panel

- 📊 Dashboard
- 💊 Product Management
- 📂 Category Management
- 🏷️ Brand Management
- 📦 Inventory Management
- 📋 Order Management
- 👥 Customer Management
- 📩 Contact Messages

---

# 🚀 Future Enhancements

The following features can be added in future releases:

- Online Payment Gateway Integration
- Wishlist
- Product Reviews & Ratings
- Discount Coupons
- Email Notifications
- PDF Invoice Generation
- Sales Reports & Analytics
- Advanced Product Filtering
- REST API
- Mobile Application

---

# 👨‍💻 Author

**Muhammad Ibrar**

**Project:** MediCart – Online Pharmacy & Healthcare Store

**Degree Program:** BS Software Engineering

**Framework:** Laravel

**GitHub:** https://github.com/your-username

---

# 🙏 Acknowledgements

This project was developed as part of a **Full Stack E-Commerce Web Application** academic assignment.

Special thanks to:

- Laravel Documentation
- PHP Documentation
- MySQL Documentation
- Bootstrap Documentation

---

# ⭐ Conclusion

MediCart is a modern Full Stack E-Commerce application that demonstrates practical implementation of Laravel MVC architecture, secure authentication, responsive web design, database management, inventory control, and complete order processing.

The project follows real-world software engineering practices while providing customers with a smooth online shopping experience and administrators with an efficient management system.

---

<p align="center">
  <strong>⭐ If you found this project helpful, consider giving it a star on GitHub.</strong>
</p>

<p align="center">
  <strong>💊 Built with Laravel • PHP • MySQL • Bootstrap • JavaScript</strong>
</p>
