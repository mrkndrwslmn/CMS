<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShowcaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert showcases
        $showcases = [
            [
                'id' => '0a85bb47-87c7-497c-a061-1c164cbeb312',
                'project_name' => 'Cabrew — Cashiering System for Brewvery',
                'category' => 'Desktop Application',
                'slug' => 'cabrew-cashiering-system',
                'short_description' => 'A cashiering system built for Brewvery, a milk tea and coffee shop, focused on order and sales management.',
                'full_description' => 'Cabrew is a Java-based desktop application built with Apache NetBeans, designed to help the staff of Brewvery manage their day-to-day operations efficiently. It supports user registration, secure login, order handling (cashiering), and detailed sales history tracking. The application is connected to a MySQL database and is built with future scalability in mind, with planned features like inventory and analytics dashboard.',
                'features' => json_encode([
                    'User Registration',
                    'Secure Login System',
                    'Order Management (Cashiering)',
                    'Sales History Viewing',
                    'SQL Database Integration',
                    'Future plans for inventory and analytics'
                ]),
                'tools_used' => 'Java (Apache NetBeans), MySQL',
                'thumbnail' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753442499/15_zn99em.png',
                'login_details' => 'Run via Apache NetBeans. No online demo. Local DB setup required.',
                'live_link' => 'Private',
                'github_link' => 'Private',
                'client_name' => 'Brewvery',
                'duration' => '3 weeks',
                'status' => 'completed',
                'attachments' => null,
                'created_at' => '2023-05-25 11:05:00',
                'updated_at' => '2023-05-25 11:05:00',
            ],
            [
                'id' => '208d776f-42b0-473e-a164-951608658fc9',
                'project_name' => 'PoliTest',
                'category' => 'Web Application',
                'slug' => 'politest',
                'short_description' => 'PoliTest is a personalized political recommender that aligns users with politicians who share their values and insights.',
                'full_description' => 'This system helps users understand the views and positions of various politicians based on their personal values and insights. After assessing the stance of the users, it recommends politicians who align with their political personality. It aims to help voters make more informed decisions by suggesting representatives who reflect their beliefs.',
                'features' => json_encode([
                    'User insight assessment',
                    'Generates personalized political recommendations',
                    'Compares user\'s stance with politicians positions',
                    'Visualizes compatibility for decision-making'
                ]),
                'tools_used' => 'Python, Javascript, React-vite, Flask, MongoDB',
                'thumbnail' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753455810/33_g79bhp.png',
                'login_details' => '["Email", "Password"]',
                'live_link' => null,
                'github_link' => 'https://github.com/jnieshaaa/PoliTest',
                'client_name' => 'LU',
                'duration' => '3 months',
                'status' => 'completed',
                'attachments' => json_encode([]),
                'created_at' => '2025-02-27 14:57:18',
                'updated_at' => '2025-02-28 14:57:18',
            ],
            [
                'id' => '3d912c47-f3d1-4b7e-9e12-80f540dd5b0b',
                'project_name' => 'StudyCards',
                'category' => 'Mobile Application',
                'slug' => 'studycards',
                'short_description' => 'An Android app for creating, managing, and studying digital flashcards with tests and progress tracking.',
                'full_description' => 'StudyCards is an Android application designed to help users create, manage, and study digital flashcards and study sets. The app supports learning through flashcards, tests, and progress tracking, making it ideal for students and self-learners.',
                'features' => json_encode([
                    'User Authentication',
                    'Create/Edit Study Sets',
                    'Flashcard Learning',
                    'Spaced Repitition',
                    'Test Mode',
                    'Progress Tracking',
                    'Public & Private Sets'
                ]),
                'tools_used' => 'Java, Android Studio',
                'thumbnail' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753777533/47_tfejbn.png',
                'login_details' => null,
                'live_link' => 'private',
                'github_link' => 'private',
                'client_name' => 'Personal Project',
                'duration' => '2 months',
                'status' => 'completed',
                'attachments' => null,
                'created_at' => '2024-11-22 00:00:00',
                'updated_at' => '2024-11-22 00:00:00',
            ],
            [
                'id' => '672916bb-04ad-4076-84d6-04974015ede3',
                'project_name' => 'T.V.A Express Logistics Inc.',
                'category' => 'Web Application',
                'slug' => 'tva-express-logistics',
                'short_description' => 'A comprehensive truck rental and logistics management system with multi-role portals for admin, drivers, and customers.',
                'full_description' => 'T.V.A Express Logistics Inc. is a full-featured PHP and MySQL web application that manages truck rental operations, logistics, and fleet management. It supports multi-role access, secure authentication, rental request handling, GPS tracking, emergency reporting, payment processing, and a robust dashboard system for administrators. Built using Tailwind CSS, Alpine.js, and modern PHP development practices, the system ensures scalable logistics management for growing businesses.',
                'features' => json_encode([
                    'Multi-role user authentication (Admin, Driver, Customer)',
                    'Fleet and delivery management',
                    'Real-time GPS tracking and status updates',
                    'Rental request and approval system',
                    'Emergency issue reporting',
                    'Admin dashboard with analytics',
                    'Secure payment system',
                    'Customer order tracking and history'
                ]),
                'tools_used' => 'PHP, MySQL, Tailwind CSS, Alpine.js, XAMPP',
                'thumbnail' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753444972/11_sjgg5t.png',
                'login_details' => 'Run via XAMPP/Apache. Configure db.php for local connection.',
                'live_link' => 'Private',
                'github_link' => 'Private',
                'client_name' => 'Private',
                'duration' => '3 weeks',
                'status' => 'completed',
                'attachments' => null,
                'created_at' => '2025-02-09 12:03:04',
                'updated_at' => '2025-02-09 12:03:04',
            ],
            [
                'id' => '6b1c822e-64c1-4be0-9a2b-91f8f9cd1d4b',
                'project_name' => 'PC LEAGUE Management System',
                'category' => 'Web Application',
                'slug' => 'pc-league-quotation-inventory-system',
                'short_description' => 'PC LEAGUE Management System is a web-based system for managing PC parts inventory, generating quotations and tracking expenses.',
                'full_description' => 'PC LEAGUE Management System is a full-featured inventory and quotation management platform for a PC parts business. It supports admins and employees with role-based access, real-time quotation generation with dynamic inventory selection, expense tracking, and PDF generation. The system is built using Tailwind CSS and Node.js serverless functions.',
                'features' => json_encode([
                    'User Authentication',
                    'Role-Based Access',
                    'Quotation Creation and Export',
                    'Inventory Tracking with Stock Alerts',
                    'Sales Management',
                    'Expense Management',
                    'PDF Export'
                ]),
                'tools_used' => 'HTML, Tailwind CSS, JavaScript, Node.js (Serverless), Supabase, Puppeteer, Google Cloud Run',
                'thumbnail' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753545430/TREIS_ADIUTOR_4_oul0md.png',
                'login_details' => 'Admin: /login.html → /admin/dashboard.html
Employee: /login.html → /general/dashboard.html',
                'live_link' => null,
                'github_link' => null,
                'client_name' => 'PC LEAGUE',
                'duration' => '1 month',
                'status' => 'completed',
                'attachments' => null,
                'created_at' => '2025-07-29 16:03:28',
                'updated_at' => '2025-07-29 16:03:28',
            ],
            [
                'id' => '8985287c-714b-445f-8729-11f0adfe918f',
                'project_name' => 'KKB - Split Bills with Friends',
                'category' => 'Mobile Application',
                'slug' => 'kkb-app',
                'short_description' => 'Split Group Expenses and Save Money Together',
                'full_description' => 'KKB is the ultimate app for tracking group finances and simplifying payment settlements. Track group expenses in real-time, get settlement overviews, save together with group goals, use custom exchange rates, and engage with friends through built-in comment threads.',
                'features' => json_encode([
                    'Track Group Expenses in Real-Time',
                    'Settlement Overview',
                    'Save Together with Group Goals',
                    'Custom Exchange Rates',
                    'Engage with your Friends'
                ]),
                'tools_used' => 'Flutter, Supabase',
                'thumbnail' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1757675426/Cream_Simple_Get_App_Instagram_Story_1024_x_768_px_py66qg.png',
                'login_details' => null,
                'live_link' => 'https://play.google.com/store/apps/details?id=com.kkb.splitapp&pcampaignid=web_share',
                'github_link' => null,
                'client_name' => null,
                'duration' => '3 Months',
                'status' => 'completed',
                'attachments' => null,
                'created_at' => '2025-08-20 11:11:21',
                'updated_at' => '2025-09-12 11:11:21',
            ],
            [
                'id' => '9950c26e-f819-462f-b66f-93f5a84fc30b',
                'project_name' => 'DigiCard — Digital Business Cards',
                'category' => 'Web Application',
                'slug' => 'digicard-digital-business-cards',
                'short_description' => 'A fully functional web app for creating, sharing, and managing digital business cards with QR codes and printable templates.',
                'full_description' => 'DigiCard is a modern PHP-based web application that allows users to create, customize, and share digital business cards. Features include secure authentication, card design using templates or custom layout, persistent user data, QR code generation, downloadable/printable cards, analytics for admin, and a team creators page. It uses Tailwind CSS for styling, QRious.js for QR codes, and jsPDF/html2canvas for PDF/image exports.',
                'features' => json_encode([
                    'User authentication with password reset',
                    'Business card creation with 10 templates',
                    'Custom card design like Canva',
                    'QR code generation for digital cards',
                    'Editable name, contact, and social links',
                    'Download as image or vCard',
                    'Printable card layout',
                    'Creators/About page',
                    'Admin analytics dashboard',
                    'Persistent saved user data'
                ]),
                'tools_used' => 'PHP (MVC), HTML, CSS, JavaScript, Tailwind CSS, AOS, MySQL, QRious.js, html2canvas, jsPDF',
                'thumbnail' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753443929/14_afopxv.png',
                'login_details' => 'Visit http://localhost/gcard/frontend/pages/index.php after setup. Admin credentials: admin@digicard.com / admin123',
                'live_link' => 'Private',
                'github_link' => 'Private',
                'client_name' => 'Private',
                'duration' => '3 weeks',
                'status' => 'completed',
                'attachments' => null,
                'created_at' => '2025-01-28 09:48:43',
                'updated_at' => '2025-01-28 09:48:43',
            ],
            [
                'id' => '9f154eab-4443-4608-8508-7f832e4a5032',
                'project_name' => 'GradeNet - Grading System for CCS',
                'category' => 'Web Application',
                'slug' => 'gradenet',
                'short_description' => 'A role-based grade management system for colleges, built with Flask and MySQL.',
                'full_description' => 'GradeNet is a web-based grade management system for the College of Computer Studies. It provides role-based dashboards and tools for Deans, Faculty, and Students to manage users, subjects, and grades efficiently.',
                'features' => json_encode([
                    'Dashboards and permissions for Dean, Faculty, and Student users.',
                    'Add, edit, archive, and list faculty and students. Bulk import students via CSV.',
                    'Add, edit, assign, and view subjects. Faculty loading for subject assignments.',
                    'Define grading categories, enter and update grades, and calculate grade equivalents.',
                    'Students can view their enrolled subjects and grades.',
                    'Secure login, session management, and CSRF protection.'
                ]),
                'tools_used' => 'Python 3, Flask, Flask-WTF, Flask-Login, MySQL, WTForms, Jinja2 Templates',
                'thumbnail' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753450190/23_i1isrg.png',
                'login_details' => 'Dean
Email: dean@gradenet.com
Password: dean123

Faculty
Email: faculty@gradenet.com
Password: faculty123

Student
Email: student@gradenet.com
Password: student123',
                'live_link' => 'Private',
                'github_link' => 'Private',
                'client_name' => 'College of Computer Studies',
                'duration' => '2 weeks',
                'status' => 'completed',
                'attachments' => null,
                'created_at' => '2025-02-15 13:34:01',
                'updated_at' => '2025-02-15 13:34:01',
            ],
            [
                'id' => 'a28eca3b-92c7-4cc1-8d00-325e6afe04fa',
                'project_name' => 'ScheDue',
                'category' => 'Mobile Application',
                'slug' => 'schedu',
                'short_description' => 'ScheDue is your ultimate scheduling planner and task reminder app, designed to make your life more organized and stress-free.',
                'full_description' => 'ScheDue is your ultimate scheduling planner and task reminder app, designed to make your life more organized and stress-free. 

Whether you\'re a student, a professional, or anyone looking to boost productivity, ScheDue is the perfect companion to help you stay focused and achieve your goals. Never miss a deadline again—plan smarter with ScheDue!',
                'features' => json_encode([
                    'Manage daily tasks and appointments',
                    'Set deadlines and reminders',
                    'Clean and user-friendly interface'
                ]),
                'tools_used' => 'Java, XML, Android Studio',
                'thumbnail' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753453868/31_zkgnlh.png',
                'login_details' => 'Email: user@example.com
Password: user123456',
                'live_link' => 'Private',
                'github_link' => 'https://github.com/jnieshaaa/SchedulingAppointmentPlanner',
                'client_name' => 'Laguna University',
                'duration' => '3 weeks',
                'status' => 'completed',
                'attachments' => null,
                'created_at' => '2024-10-15 16:00:00',
                'updated_at' => '2024-12-10 16:00:00',
            ],
        ];

        DB::table('showcases')->insert($showcases);

        // Insert showcase screenshots
        $screenshots = [
            ['id' => '0092a62e-b8d0-475d-be36-06856d53db76', 'showcase_id' => '9f154eab-4443-4608-8508-7f832e4a5032', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753450189/22_kqmqwh.png', 'caption' => null, 'sort_order' => 4, 'created_at' => '2025-07-25 13:34:57'],
            ['id' => '0917f055-df76-4af0-bb25-ce98901f39af', 'showcase_id' => 'a28eca3b-92c7-4cc1-8d00-325e6afe04fa', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753453868/30_f3qkbh.png', 'caption' => null, 'sort_order' => 0, 'created_at' => '2025-07-25 14:35:00'],
            ['id' => '128872d6-6008-4363-b22a-966866af9d23', 'showcase_id' => '8985287c-714b-445f-8729-11f0adfe918f', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1757675928/3_oikp0r.png', 'caption' => null, 'sort_order' => 2, 'created_at' => '2025-09-12 11:14:07'],
            ['id' => '15203c91-ff7e-4962-9b36-703d725ef4fe', 'showcase_id' => '9950c26e-f819-462f-b66f-93f5a84fc30b', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753443929/14_afopxv.png', 'caption' => 'Dashboard Overview', 'sort_order' => 2, 'created_at' => '2025-07-25 11:46:16'],
            ['id' => '1f09f92e-7d35-4236-a3ea-b8c70d0c714e', 'showcase_id' => '8985287c-714b-445f-8729-11f0adfe918f', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1757675930/4_bh9osn.png', 'caption' => null, 'sort_order' => 3, 'created_at' => '2025-09-12 11:14:23'],
            ['id' => '1f40ee21-4082-459a-befa-a4ee51d9133c', 'showcase_id' => '208d776f-42b0-473e-a164-951608658fc9', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753455810/39_grqbbf.png', 'caption' => null, 'sort_order' => 6, 'created_at' => '2025-07-25 15:08:51'],
            ['id' => '39f8e4a0-ef7f-47ea-a662-ea5950f9a8e6', 'showcase_id' => '672916bb-04ad-4076-84d6-04974015ede3', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753444972/11_sjgg5t.png', 'caption' => 'Landing Page', 'sort_order' => 1, 'created_at' => '2025-07-25 12:06:04'],
            ['id' => '431a31d3-d740-4153-b12d-5006a0aaaa08', 'showcase_id' => '672916bb-04ad-4076-84d6-04974015ede3', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753444975/18_rfk3f7.png', 'caption' => 'Calendar', 'sort_order' => 8, 'created_at' => '2025-07-25 12:06:04'],
            ['id' => '4c26071d-ea1e-41a3-9928-27c0b4cd6edf', 'showcase_id' => '0a85bb47-87c7-497c-a061-1c164cbeb312', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753442500/16_mzoawt.png', 'caption' => 'Sales History Page', 'sort_order' => 5, 'created_at' => '2025-07-25 11:30:46'],
            ['id' => '4d9a7a96-f23c-4ed4-963f-aafdb560aed8', 'showcase_id' => '672916bb-04ad-4076-84d6-04974015ede3', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753444972/13_seyjvq.png', 'caption' => 'Driver Dashboard', 'sort_order' => 6, 'created_at' => '2025-07-25 12:06:04'],
            ['id' => '50a55d61-d9bf-4fcf-a3fd-cf3db0c11d78', 'showcase_id' => '8985287c-714b-445f-8729-11f0adfe918f', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1757675926/2_om3pri.png', 'caption' => null, 'sort_order' => 1, 'created_at' => '2025-09-12 11:13:40'],
            ['id' => '55b04144-4213-4d38-8950-4f0c9d5e57e1', 'showcase_id' => '672916bb-04ad-4076-84d6-04974015ede3', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753444974/14_imqgke.png', 'caption' => 'Delivery Details', 'sort_order' => 5, 'created_at' => '2025-07-25 12:06:04'],
            ['id' => '55fa0b64-24ee-4a07-a85d-64cc04c7167f', 'showcase_id' => '672916bb-04ad-4076-84d6-04974015ede3', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753444973/16_zwfk1i.png', 'caption' => 'Payment Details', 'sort_order' => 3, 'created_at' => '2025-07-25 12:06:04'],
            ['id' => '58f84a3a-fa73-48f3-9477-e24a298d8f89', 'showcase_id' => '9f154eab-4443-4608-8508-7f832e4a5032', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753450187/21_fvv2fu.png', 'caption' => null, 'sort_order' => 2, 'created_at' => '2025-07-25 13:34:57'],
            ['id' => '68cfc465-4157-44ec-b5f3-ea0e3bbb5d5b', 'showcase_id' => '672916bb-04ad-4076-84d6-04974015ede3', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753444973/17_i0xp9c.png', 'caption' => 'Emergency Report', 'sort_order' => 4, 'created_at' => '2025-07-25 12:06:04'],
            ['id' => '81297fef-699e-48df-b530-b3e1090ea403', 'showcase_id' => '672916bb-04ad-4076-84d6-04974015ede3', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753444974/15_tvbxzv.png', 'caption' => 'Truck List', 'sort_order' => 7, 'created_at' => '2025-07-25 12:06:04'],
            ['id' => '8d9a205e-6478-4c66-a093-3edf31e8b8c4', 'showcase_id' => '9f154eab-4443-4608-8508-7f832e4a5032', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753450187/20_rz7x1f.png', 'caption' => null, 'sort_order' => 3, 'created_at' => '2025-07-25 13:34:57'],
            ['id' => '8eb07e3d-cd53-4ab7-8f27-26f5c7263a85', 'showcase_id' => '672916bb-04ad-4076-84d6-04974015ede3', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753444972/12_w0tbmn.png', 'caption' => 'Customer Dashboard', 'sort_order' => 2, 'created_at' => '2025-07-25 12:06:04'],
            ['id' => '902d025a-8367-42e8-bbb3-a8163740993c', 'showcase_id' => '9950c26e-f819-462f-b66f-93f5a84fc30b', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753443929/12_wqyttq.png', 'caption' => 'Login Page', 'sort_order' => 3, 'created_at' => '2025-07-25 11:46:16'],
            ['id' => '931d433b-b90d-4cfc-9e79-4267c52beb0f', 'showcase_id' => '208d776f-42b0-473e-a164-951608658fc9', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753455810/38_scwihd.png', 'caption' => null, 'sort_order' => 2, 'created_at' => '2025-07-25 15:08:51'],
            ['id' => '99083a63-dd4c-4c48-a964-f5051df13277', 'showcase_id' => '208d776f-42b0-473e-a164-951608658fc9', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753455810/33_g79bhp.png', 'caption' => null, 'sort_order' => 1, 'created_at' => '2025-07-25 15:08:51'],
            ['id' => '9c9d0eb3-93f7-4bbf-9b18-3ef277446b76', 'showcase_id' => '208d776f-42b0-473e-a164-951608658fc9', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753455810/36_dioqpn.png', 'caption' => null, 'sort_order' => 5, 'created_at' => '2025-07-25 15:08:51'],
            ['id' => 'a68bab87-451d-4e9b-8dba-76a2095c94f3', 'showcase_id' => '9950c26e-f819-462f-b66f-93f5a84fc30b', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753443929/13_hxzhly.png', 'caption' => 'Login Page', 'sort_order' => 4, 'created_at' => '2025-07-25 11:39:40'],
            ['id' => 'a8a28056-245e-4327-8e9a-2d0cfb49814b', 'showcase_id' => '9f154eab-4443-4608-8508-7f832e4a5032', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753450192/24_eivqwj.png', 'caption' => null, 'sort_order' => 5, 'created_at' => '2025-07-25 13:34:57'],
            ['id' => 'aa2f888b-3404-4f1d-a722-be13f71b5a25', 'showcase_id' => '0a85bb47-87c7-497c-a061-1c164cbeb312', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753442499/18_nfbgrz.png', 'caption' => 'Cashiering Interface', 'sort_order' => 4, 'created_at' => '2025-07-25 11:30:46'],
            ['id' => 'b1f54feb-230a-4359-a830-38c9f7f4bcce', 'showcase_id' => '208d776f-42b0-473e-a164-951608658fc9', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753455809/34_thozqg.png', 'caption' => null, 'sort_order' => 3, 'created_at' => '2025-07-25 15:08:51'],
            ['id' => 'c1febe6d-aa25-4c5a-8397-c63c67f6171f', 'showcase_id' => '9f154eab-4443-4608-8508-7f832e4a5032', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753450185/19_dc8uwg.png', 'caption' => null, 'sort_order' => 1, 'created_at' => '2025-07-25 13:34:57'],
            ['id' => 'c5318d53-7e3e-45a3-bf83-b1eba705b012', 'showcase_id' => '0a85bb47-87c7-497c-a061-1c164cbeb312', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753442500/17_jlxyiw.png', 'caption' => 'Cashiering Interface', 'sort_order' => 2, 'created_at' => '2025-07-25 11:30:46'],
            ['id' => 'c55056d6-9d3c-4ab1-abf6-0909e0bc028b', 'showcase_id' => '9f154eab-4443-4608-8508-7f832e4a5032', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753450190/23_i1isrg.png', 'caption' => null, 'sort_order' => 6, 'created_at' => '2025-07-25 13:34:57'],
            ['id' => 'd4d2c427-91cd-43ea-8ff0-5299c1f27a43', 'showcase_id' => '0a85bb47-87c7-497c-a061-1c164cbeb312', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753442499/15_zn99em.png', 'caption' => 'Cashiering Interface', 'sort_order' => 1, 'created_at' => '2025-07-25 11:30:46'],
            ['id' => 'e2ae9ce8-4337-4a96-a30e-801bb1ae9b99', 'showcase_id' => 'a28eca3b-92c7-4cc1-8d00-325e6afe04fa', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753453942/32_jzkuqe.png', 'caption' => null, 'sort_order' => 0, 'created_at' => '2025-07-25 14:35:00'],
            ['id' => 'f15f1e16-1e4b-46f0-bf95-b239e51780a5', 'showcase_id' => 'a28eca3b-92c7-4cc1-8d00-325e6afe04fa', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753453868/31_zkgnlh.png', 'caption' => null, 'sort_order' => 0, 'created_at' => '2025-07-25 14:35:00'],
            ['id' => 'f382f8c4-adb4-454b-b388-ed569c59e07d', 'showcase_id' => '9950c26e-f819-462f-b66f-93f5a84fc30b', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753443944/11_jm6p7k.png', 'caption' => 'Dashboard Overview', 'sort_order' => 1, 'created_at' => '2025-07-25 11:39:40'],
            ['id' => 'fbf78362-fca2-48f2-84fd-058629f99cfb', 'showcase_id' => '208d776f-42b0-473e-a164-951608658fc9', 'image_url' => 'https://res.cloudinary.com/do9pjlmm7/image/upload/v1753455809/35_zbd0lh.png', 'caption' => null, 'sort_order' => 4, 'created_at' => '2025-07-25 15:08:51'],
        ];

        DB::table('showcase_screenshots')->insert($screenshots);
    }
}
