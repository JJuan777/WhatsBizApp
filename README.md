# 🚀 WhatsBizApp - Barber Shop Appointment System

![PHP](https://img.shields.io/badge/PHP-8.4-blue.svg)
![MySQL](https://img.shields.io/badge/Database-MySQL-orange.svg)
![Twilio](https://img.shields.io/badge/Twilio-WhatsApp-green.svg)
![Apache](https://img.shields.io/badge/Server-Apache-lightgrey.svg)

## 📌 Description
**WhatsBizApp** is a **PHP 8.4** web application designed for a barber shop. Users can register appointments, and notifications are sent via **WhatsApp** using **Twilio API**. The system includes an **Admin Panel** where administrators can cancel appointments and view availability.

## 🎯 Features
✅ **User Appointment Booking**.<br>
✅ **WhatsApp Notification System** (via Twilio).<br>
✅ **Admin Panel for Managing Appointments**.<br>
✅ **MySQL Database Integration**.<br>
✅ **Configuration via `.env` File**.<br>
✅ **PHP Composer Dependency Management**.<br>
✅ **Apache Server Deployment**.<br>

## 🏗️ Project Structure
```
WhatsBizApp/
│── app/              # Core Application Files
│   ├── Config/       # Database Connection and Twilio Config
│   ├── Models/       # Data Models
│   ├── Views/        # HTML Views
│── public/           # Publicly Accessible Files
│── sql/              # Database Setup (barberbd.sql)
│── vendor/           # Composer Dependencies
│── composer.json     # PHP Package Manager Configuration
```

## 🔑 Admin Panel Access
To access the **Admin Panel**, go to:
```
http://localhost:81/indexadmin.php
```
**Admin Credentials:**  
✉️ Email: `enriqueadmin@gmail.com`  
🔑 Password: `Carnada01`

## ⚙️ Configuration
1. **Database Configuration**
   - Edit the file `app/Config/config.php` and update MySQL credentials.
   - Import `sql/barberbd.sql` into MySQL to set up the database.

2. **Twilio WhatsApp Configuration**
   - Edit `app/Config/twilio_config.php` and update the following:
     ```php
     $sid = getenv('TWILIO_SID');
     $token = getenv('TWILIO_TOKEN');
     "from" => "whatsapp:+14155238886", // Update admin WhatsApp number
     ```
   - Make sure your `.env` file contains the correct Twilio credentials.

## 🛠️ How to Clone and Run
1. **Clone the repository**
   ```sh
   git clone https://github.com/JJuan777/WhatsBizApp.git
   cd WhatsBizApp
   ```
2. **Ensure Apache and MySQL are Installed**
   - If using **XAMPP**, ensure Apache and MySQL are running.
3. **Set Up Dependencies**
   ```sh
   composer install
   ```
4. **Import Database**
   - Open MySQL and import `sql/barberbd.sql`.
5. **Run the Application**
   - Open a browser and go to:
     ```
     http://localhost:81/index.php
     ```

## 📧 Contact
For any questions or suggestions, visit my profile on **[GitHub](https://github.com/JJuan777)**.

---
**© 2025 - WhatsBizApp** 🚀
