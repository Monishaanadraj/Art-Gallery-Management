# 🎨 Art Gallery Management System

## 📌 Overview
The **Art Gallery Management System** is an online platform that allows users to browse, purchase, and customize artworks while providing admins with management tools to oversee art products and orders. The system supports secure transactions, user authentication, and an intuitive UI for seamless navigation.

## 🛠 Features
### ✅ For Users (Buyers)
- Browse and search for artworks
- Add artworks to the cart and checkout
- Make purchases via card, UPI, or cash on delivery (dummy payment system)
- View order history and cancel orders
- Customize artworks before purchase
- User authentication (login/signup)

### ✅ For Admins
- Manage artworks (add, update, delete)
- Handle custom artwork requests
- Manage orders (view and update order statuses)
- View sales reports

## 🏗 Tech Stack
- **Frontend**: HTML, CSS, JavaScript, Bootstrap
- **Backend**: PHP
- **Database**: MySQL

## 📦 Database Structure
### **Tables:**
1. `users` - Stores user information (buyers and admins)
2. `artworks` - Stores details of available artworks
3. `orders` - Tracks purchase details
4. `cart` - Manages items added by users
5. `custom_art_requests` - Stores user-customized artwork requests

## 🚀 Installation Guide
1. **Clone the Repository**
   ```bash
   git clone https://github.com/your-username/art-gallery-management.git
   cd art-gallery-management
   
2. **Setup the Database**
   - Open PHPMyAdmin (http://localhost/phpmyadmin)
   - Create a database with the name agmsdb
   - Import agmsdb.sql file(given inside the SQL file folder)


3. **Run the Project**
   - Start a local server (XAMPP or WAMP)
   - Place the project folder in htdocs (for XAMPP) or www (for WAMP)
   - Run the script http://localhost/agms in a browser

4. **Credential for Admin panel :**
   - Username: admin
   - Password: Test@123

