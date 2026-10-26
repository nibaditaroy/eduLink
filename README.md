# eduLink &mdash; Interactive E-Learning & Hands-On Coding Lab Platform

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12" />
  <img src="https://img.shields.io/badge/PHP-8.5-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.5" />
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="TailwindCSS" />
  <img src="https://img.shields.io/badge/Currency-৳%20BDT%20(Taka)-22c55e?style=for-the-badge" alt="Currency BDT" />
  <img src="https://img.shields.io/badge/Status-Production%20Ready-indigo?style=for-the-badge" alt="Status" />
</p>

**eduLink** is a modern, high-performance web-based learning management system and interactive coding lab built specifically for Bangladeshi tech enthusiasts and students. It integrates rich video lectures, modular notes, quizzes, assignments, and an in-browser code execution sandbox with native Bangladeshi payment gateways.

---

## 🌟 Key Features

### 1. 🎓 Rich Curriculum & Lesson Player
- **Modular Content Engine:** Every lesson supports multi-video players, theoretical notes, warning/tip callouts, Atom One Dark syntax-highlighted code blocks, and practical coding challenges.
- **Free Preview Lessons:** Potential students can preview initial curriculum lessons without prior course enrollment (simply log in to start learning).
- **Quizzes & MCQs:** Interactive multiple-choice quizzes per chapter with instant evaluation, explanations, and score tracking.
- **Hands-On Lab Assignments:** Practical problem statements where students submit solution code and receive teacher feedback with marks.

### 2. ⚡ In-Browser Interactive Practice Sandbox
- **Instant Code Runner:** Students can open the side-by-side or fullscreen interactive code editor to test code snippets directly.
- **Multi-Language Sandbox:** Supports **Python 3**, **PHP 8.5**, **JavaScript (Node.js)**, and live **HTML/Tailwind CSS** rendering.
- **Sandbox Features:** Custom standard input (`stdin`), real-time stdout/stderr capture, execution benchmarks, syntax reset, and copy-to-clipboard.

### 3. 🇧🇩 Native Bangladeshi Taka (৳ BDT) Payments
- Transparent localized pricing in Bangladeshi Taka (`৳`, `BDT`, `Tk`).
- Full support for popular mobile financial services (**bKash**, **Nagad**, **Rocket**) and Credit/Debit Cards.
- Instant automated transaction logging, invoice generation, and enrollment activation.

### 4. 🔒 Role-Based Access Control & Strict Security
- **Single Unified Login Portal (`/login`):** Automatically detects user roles and routes administrators to the admin dashboard and students to their learning hub.
- **Strict Admin Authorization (`AdminMiddleware`):** All `/admin/*` management routes are protected by dedicated middleware. Students attempting direct URL navigation to administrative panels are blocked and redirected with clear alerts.

### 5. 🛠️ Administrative Control Center (`/admin/dashboard`)
- **Real-Time Analytics:** Track total revenue (৳ BDT), active enrollments, course popularity, and student retention.
- **Curriculum Builder:** Create courses, chapters, and rich multimedia lessons with embedded code snippets.
- **Quiz & Assignment Manager:** Review submissions, evaluate student code, and issue grades.
- **User Management:** Oversee student accounts, suspend or block disruptive users, and audit platform security.
- **Financial Audit Log:** Monitor payment gateway transactions, verify bKash/Nagad TrxIDs, and manage payment statuses.

---

## 🚀 Pre-Seeded Courses

| Course Title | Chapters | Lessons | Includes |
| :--- | :---: | :---: | :--- |
| **Python for Beginners: From Scratch to Mastery** | 6 Chapters | 12 Lessons | Variables, Data Structures, OOP, File Handling, Quizzes, Lab Challenges |
| **Modern PHP 8.5 & Web Development Mastery** | 6 Chapters | 12 Lessons | PHP 8.5 Syntax, Arrays, Functions, OOP, Form Handling, Real Projects |

---

## 🔑 Demo Credentials

A single login page is available at `http://localhost:8000/login`. Use one of the pre-configured demo accounts or click the **1-Click Quick Login** buttons on the page:

| Role | Email | Password | Landing Page |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@gmail.com` | `password` | `/admin/dashboard` |
| **Student** | `student@gmail.com` | `password` | `/dashboard` |

> [!NOTE]
> Only users with the `admin` role can access `/admin/*`. Authenticated students attempting to access `/admin/dashboard` will be automatically redirected to `/dashboard` with an access-denied notification.

---

## 💻 Tech Stack

- **Framework:** Laravel 12
- **Language:** PHP 8.5
- **Database:** SQLite / MySQL
- **Frontend:** Blade Templating, TailwindCSS, FontAwesome 6
- **Code Highlighting:** Highlight.js (Atom One Dark theme)
- **Execution Sandbox:** Symfony Process runner with resource limiting

---

## 📦 Installation & Setup

### Prerequisites
- PHP >= 8.2 (PHP 8.5 recommended)
- Composer >= 2.x
- Node.js & NPM (optional, for asset rebuilds)
- SQLite or MySQL

### 1. Clone the Repository
```bash
git clone https://github.com/nibaditaroy/eduLink.git
cd eduLink
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup & Seeding
Run migrations and populate the database with the pre-configured Python & PHP courses, lessons, quizzes, and demo accounts:
```bash
touch database/database.sqlite
php artisan migrate:fresh --seed
```

### 5. Build Assets & Start Development Server
```bash
npm run build
php artisan serve
```

Visit the application at: `http://localhost:8000`

---

## 🧭 Application Routes Overview

- **Public Marketplace:**
  - `GET /` &mdash; Homepage with course showcase and hero section
  - `GET /courses` &mdash; Course catalog with search and filters
  - `GET /courses/{slug}` &mdash; Course syllabus and details
- **Authentication:**
  - `GET /login` &mdash; Unified student and admin login
  - `POST /login` &mdash; Authenticate user and redirect to appropriate dashboard
  - `GET /register` &mdash; New student registration
  - `POST /logout` &mdash; Sign out
- **Student Learning Hub (Requires Auth):**
  - `GET /dashboard` &mdash; Student enrolled courses and progress
  - `GET /learn/{course}/{lesson?}` &mdash; Interactive curriculum player with practice sandbox
  - `POST /code/execute` &mdash; API endpoint for running Python, PHP, JS, or HTML
  - `GET /checkout/{slug}` &mdash; Course checkout with bKash/Nagad/Rocket (৳ BDT)
- **Admin Portal (Requires Admin Role):**
  - `GET /admin/dashboard` &mdash; Administrative analytics & platform metrics
  - `RESOURCE /admin/courses` &mdash; Course CRUD
  - `RESOURCE /admin/chapters` &mdash; Chapter management
  - `RESOURCE /admin/lessons` &mdash; Lesson management with rich content blocks
  - `RESOURCE /admin/quizzes` &mdash; Quiz & question manager
  - `RESOURCE /admin/assignments` &mdash; Assignment & submission grading
  - `RESOURCE /admin/users` &mdash; User status & role control
  - `GET /admin/enrollments` &mdash; Enrollment logs
  - `GET /admin/transactions` &mdash; Gateway transaction audit logs

---

## 📄 License
This project is open-sourced software licensed under the [MIT License](LICENSE).
