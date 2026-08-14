# HANN PRINT - B2B & B2C E-Commerce Sablon Platform 🚀

A modern, scalable, and robust E-Commerce platform tailored for the custom printing (sablon) industry. This platform elegantly handles both B2B (wholesale/resellers) and B2C (retail) workflows within a unified, seamless system.

## ✨ Key Features & Architecture

### 1. Dual-Track E-Commerce (B2B & B2C)
- **Role-Based Pricing**: Dynamic pricing tiers based on user type (Wholesale vs Retail).
- **Minimum Order Quantity (MOQ)**: Enforced MOQ validation for wholesale items (e.g., Min 6 pcs for B2B).
- **Smart Checkout**: B2B users bypass certain retail steps and get special wholesale packaging rules.

### 2. Modern Checkout & Payment Simulation
- **Dynamic Shipping**: Integration-ready shipping module with intelligent weight calculation and dimensional mapping.
- **Payment Gateway Mockup**: Full simulation of Virtual Account (VA) and QRIS payment flows, demonstrating Webhook-ready architecture without exposing real API keys.
- **Instant Verification**: Simulated real-time payment confirmation and order status updates.

### 3. Inventory Management
- **Polymorphic Stock Tracking**: Complete audit trail for stock movements (`stock_histories`) - tracking IN (restocks) and OUT (purchases).
- **Soft Deletes**: Implemented `SoftDeletes` across core models (`Produk`, `Pesanan`) to maintain historical data integrity and prevent orphaned records.

### 4. Admin Dashboard
- **Data Visualization**: Dynamic chart integration showing sales trends and product performance.
- **Order Management**: Comprehensive tools to update order statuses, manage stock, and view transaction history.

## 🛠️ Technology Stack

- **Backend**: Laravel 9.x (PHP 8.1+)
- **Frontend**: Blade Templating Engine, Vanilla CSS, JS
- **Database**: MySQL (Eloquent ORM)
- **Design Pattern**: MVC (Model-View-Controller)

## 🚀 Getting Started (Showcase Mode)

To run this project locally with the pre-configured showcase data:

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/hann-print.git
   cd hann-print
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Configure your database credentials in the `.env` file.*

4. **Migrate & Seed (Crucial for Showcase)**
   ```bash
   php artisan migrate:fresh --seed
   ```
   *This command will populate the database with dummy B2B/B2C users, 20+ realistic orders, products, and reviews to immediately demonstrate the platform's capabilities.*

5. **Run the Application**
   ```bash
   php artisan serve
   ```

## 🔐 Demo Credentials

Use these credentials to explore the different user experiences:

| Role | Email | Password | Description |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@admin.com` | `admin123` | Full access to dashboard, stock, and orders. |
| **B2B (Reseller)** | `b2b@pembeli.com` | `reseller123` | Access to wholesale pricing and bulk ordering. |
| **B2C (Retail)** | `b2c@pembeli.com` | `pembeli123` | Standard retail customer experience. |

## 📐 Senior Engineering Practices Demonstrated

- **Database Normalization**: Merged redundant tables (`Produk` & `ProdukNon`) into a single, cohesive `Produk` table with a `tipe_produk` enum.
- **Fat Model, Skinny Controller**: Business logic delegated appropriately to keep controllers clean.
- **Security First**: Prepared for secure webhook integrations, protected against Mass Assignment vulnerabilities, and sanitized inputs.
- **Clean UI/UX**: Custom CSS without relying on generic Bootstrap templates ("No AI Slop"), ensuring a unique, brand-aligned aesthetic.

---
*Developed with ❤️ for the printing industry.*
