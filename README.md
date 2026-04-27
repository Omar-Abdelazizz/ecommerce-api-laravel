# 🛒 E-Commerce API (Laravel)

A fully functional RESTful API for an e-commerce system built with Laravel.
This project demonstrates real-world backend concepts including authentication, cart management, discounts, orders, and role-based access control.

---

## 🚀 Features

### 🔐 Authentication

* User التسجيل و تسجيل الدخول باستخدام Laravel Sanctum
* Token-based authentication
* Logout functionality

### 👤 Roles & Permissions

* باستخدام Spatie Laravel Permission
* Roles: `admin`, `user`
* Dynamic permission assignment
* حماية الـ endpoints باستخدام middleware

---

### 🛍️ Products System

* Categories
* Items (Products)
* Variants (Size, Color, etc.)
* Item Variants (SKU, Stock, Price)

---

### 🛒 Cart System

* Add item to cart
* Update quantity
* Remove item
* View cart

---

### 💸 Discount System

* Discounts على:

  * Item
  * Category
* Types:

  * Percentage
  * Fixed
* Conditions:

  * Minimum quantity
  * Minimum price
* Smart discount calculation per item

---

### 📦 Orders System

* Checkout process
* Stock validation قبل الشراء
* تطبيق الخصومات
* إنشاء Order + Order Items
* تفريغ الكارت بعد الشراء

---

## 🧠 Technologies Used

* Laravel
* Laravel Sanctum
* Spatie Laravel Permission
* MySQL / SQLite

---

## ⚙️ Installation

```bash
git clone <repo-url>
cd project

composer install

cp .env.example .env
php artisan key:generate

php artisan migrate:fresh --seed

php artisan serve
```

---

## 🔑 Test Credentials

### Admin

```json
email: admin1@test.com
password: 12345678
```

### User

```json
email: ahmed@test.com
password: 12345678
```

---

## 📮 API Endpoints

### 🔐 Auth

| Method | Endpoint      |
| ------ | ------------- |
| POST   | /api/register |
| POST   | /api/login    |
| POST   | /api/logout   |

---

### 🛍️ Public

| Method | Endpoint        |
| ------ | --------------- |
| GET    | /api/categories |
| GET    | /api/items      |
| GET    | /api/variants   |
| GET    | /api/discounts  |

---

### 🛒 Cart (Auth Required)

| Method | Endpoint             |
| ------ | -------------------- |
| GET    | /api/cart            |
| POST   | /api/cart            |
| PATCH  | /api/cart/items/{id} |
| DELETE | /api/cart/items/{id} |
| POST   | /api/cart/checkout   |

---

### 🛠️ Admin (Protected)

| Method | Endpoint              |
| ------ | --------------------- |
| POST   | /api/admin/items      |
| POST   | /api/admin/categories |
| POST   | /api/admin/variants   |
| POST   | /api/admin/discounts  |

> Requires `admin` role + permissions

---

## 📦 Sample Request

### Add to Cart

```json
POST /api/cart

{
  "item_variant_id": 1,
  "quantity": 2
}
```

---

### Checkout

```json
POST /api/cart/checkout
```

---

## 🧪 Seeder Data

Includes:

* 2 Admins
* 3 Users
* Categories (Clothes, Shoes, Electronics)
* Items + Variants
* Discounts جاهزة للتست

---

## 🧱 Architecture Notes

* Controllers are kept lightweight
* Business logic handled in Services (e.g., DiscountService)
* Clean separation of concerns

---

## 🔮 Future Improvements

* Order status (pending, shipped, delivered)
* Pagination
* API Resources
* Swagger Documentation
* Payment integration

---

## 👨‍💻 Author

Built as a backend practice project for mastering Laravel APIs, system design, and real-world business logic.

---

## ⭐ Final Note

This is not just a CRUD project — it simulates real e-commerce logic
including pricing, stock, discounts, and permissions.

---
