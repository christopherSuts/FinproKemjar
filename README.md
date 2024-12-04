
# Setup Database di XAMPP

## Prasyarat

Install **XAMPP**.

## Langkah 1: Menjalankan XAMPP

1. Buka **XAMPP Control Panel**.
2. Klik tombol **Start** pada **Apache** dan **MySQL** untuk menjalankan server web dan database.
3. Pastikan status kedua layanan tersebut berubah menjadi **Running**.

## Langkah 2: Mengakses phpMyAdmin

Buka browser dan akses **phpMyAdmin** dengan menuju ke [http://localhost/phpmyadmin](http://localhost/phpmyadmin).

## Langkah 3: Membuat Database Baru

1. Setelah masuk ke phpMyAdmin, klik tab **Databases**.
2. Pada form **Create database**, masukkan nama database yang diinginkan dan klik tombol **Create**.

## Langkah 4: Menyesuaikan Nama Database di `db_connection.php`

1. Buka file `db_connection.php`.
2. Sesuaikan bagian berikut

   ```php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "your_database_name";  // Sesuaikan nama database di sini
   ```

## Langkah 5: Membuat Tabel Users pada Database

Copy dan paste query berikut untuk membuat tabel `users`:

   ```sql
   CREATE TABLE users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       email VARCHAR(100) NOT NULL UNIQUE,
       username VARCHAR(50) NOT NULL UNIQUE,
       password VARCHAR(255) NOT NULL,
       role VARCHAR(50) NOT NULL DEFAULT 'user',
       jwt_token TEXT
   );
   ```
