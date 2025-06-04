# 🎵 Music Streaming Web Application

This is a full-stack web application developed using **PHP**, **MySQL**, and **HTML/CSS** that allows users to listen to music, explore playlists, artists, and albums, and track their play history. The interface is designed according to the specifications provided in the project PDF.

---

## 📁 Folder Structure

```
birbabadir/
│
├── data/                  # Contains all .txt data files (users.txt, country.txt, artists.txt, etc.)
├── homepage.php           # Main dashboard after login
├── login.html             # User login interface
├── login.php              # Backend logic for login
├── install.php            # Creates database and all necessary tables
├── generate_data.php      # Populates database tables from txt files
├── artistpage.php         # Artist detail page
├── playlistpage.php       # Playlist detail page
├── currentmusic.php       # Now playing music info page
├── generalSQL.php         # Page for executing custom SQL queries
├── logout.php             # Logs user out
├── style.css              # Styling file (if exists)
├── assets/                # Folder for images and icons (if used)
└── README.md              # You are here!
```

---

## ✅ Features

- **User Authentication**
  - Login with username and password
  - Session-based access control

- **Homepage (After Login)**
  - View all playlists on the left
  - Last 10 played songs (top-right)
  - Top 5 artists from user’s country (bottom-right)
  - Global search bar to find playlists or songs
  - Search bar to find and follow new artists
  - "+" button to add a new playlist

- **Playlist Page**
  - Displays all songs in the playlist
  - Shows artist's country next to each song
  - Add new songs via search bar

- **Artist Page**
  - Shows artist info and image
  - Lists last 5 albums and top 5 most listened songs
  - Allows following artists

- **Now Playing Page**
  - Displays currently playing song info

- **SQL Analysis Page**
  - Run custom queries to see top genres, countries, etc.

---

## 📦 Installation

### 1. Prerequisites
- [AMPPS](https://ampps.com) (or XAMPP, WAMP)
- PHP 7.x or 8.x
- MySQL

### 2. Steps

#### 🛠 Step 1: Database Setup
- Place all files in `C:\Program Files\Ampps\www\birbabadir`
- Start Apache and MySQL via AMPPS control panel
- Open browser and go to:
  ```
  http://localhost/birbabadir/install.php
  ```
  This will create the database and tables.

#### 🧪 Step 2: Add Data
- Visit:
  ```
  http://localhost/birbabadir/generate_data.php
  ```
  This will import data from the `data/` folder into the database.

#### 🔐 Step 3: Login
- Go to:
  ```
  http://localhost/birbabadir/login.html
  ```
- Use a sample username and password from `users.txt`

---

## 👤 Sample Login
```
Username: furkanuy
Password: 1234
```

---

## 📝 Notes

- Only the tables and pages specified in the project PDF are used.
- No extra PHP files like `search.php` were created.
- All user interactions update the database in real-time (e.g., following an artist, playing a song).
- Image links in the txt files are fetched directly from their URLs.
- All SQL queries are executed safely using proper syntax and structure.

---

## 📄 Project Requirements Covered

✔️ PDF'e uygun tam sayfa yapısı  
✔️ Minimum veri seti ve ilişkiler  
✔️ Fonksiyonel arama ve ekleme işlemleri  
✔️ SQL veri analizi ve görsel sayfa yapısı  
✔️ Gerçek sanatçı ve albüm verileri  
✔️ Tam kullanıcı oturumu ve şifre kontrolü  

---

## 👨‍💻 Developer

**Furkan Uysal**  
Computer Engineering Student - Yeditepe University  
Project Completed: June 2025

---
