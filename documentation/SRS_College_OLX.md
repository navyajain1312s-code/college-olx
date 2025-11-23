Page 1 – Cover Page

# SOFTWARE REQUIREMENTS SPECIFICATION
## FOR
### COLLEGE OLX (SMART CAMPUS MARKETPLACE)

**Version 2.0 (Expanded)**

**Prepared by:**
Shanky Prajapati (Roll No: [Insert Roll No])
Hansika Gupta (Roll No: [Insert Roll No])
Navya Jain (Roll No: [Insert Roll No])
Shivanshu Chauhan (Roll No: [Insert Roll No])

**Under the Guidance of:**
[Guide Name]
[Designation]

**Department of Computer Science & Engineering**
**[Generic Institute of Technology]**
**[City, State, Zip Code]**

**Academic Year: 2024-2025**

---

Page 2 – Acknowledgement

# ACKNOWLEDGEMENT

The successful completion of any major project requires the guidance and support of many individuals. We would like to express our deepest appreciation to all those who provided us the possibility to complete this project.

First and foremost, we express our sincere gratitude to our project guide, **[Guide Name]**, for their patience, motivation, enthusiasm, and immense knowledge. Their guidance helped us in all the time of research and writing of this thesis. We could not have imagined having a better advisor and mentor for our study.

We are also thankful to the Head of Department, **[HOD Name]**, for providing us with the necessary facilities, computer labs, and internet connectivity required for the development of this system.

We also thank the non-teaching staff of the Department of Computer Science & Engineering for their cooperation.

Last but not least, we would like to thank our families and friends for their understanding and support during the late nights and weekends spent on this project.

**The Team:**
Shanky Prajapati
Hansika Gupta
Navya Jain
Shivanshu Chauhan

---

Page 3 – Certificate

# CERTIFICATE

**Department of Computer Science & Engineering**
**[Generic Institute of Technology]**

This is to certify that the project report entitled **"COLLEGE OLX (SMART CAMPUS MARKETPLACE)"** submitted by:

*   **Shanky Prajapati**
*   **Hansika Gupta**
*   **Navya Jain**
*   **Shivanshu Chauhan**

in partial fulfillment of the requirements for the award of the degree of **Bachelor of Technology in Computer Science & Engineering** is a bona fide record of the work carried out by them under my supervision and guidance during the academic year 2024-2025.

The content of this report, in full or in parts, has not been submitted to any other Institute or University for the award of any other degree or diploma.

<br>
<br>
<br>

_________________________
**[Guide Name]**
Project Guide
Dept. of CSE

_________________________
**[HOD Name]**
Head of Department
Dept. of CSE

**External Examiner:** _________________________

**Place:** [City]
**Date:** [Date]

---

Page 4 – Declaration

# DECLARATION

We hereby declare that the project work entitled **"COLLEGE OLX"** submitted to **[Generic Institute of Technology]**, is a record of an original work done by us under the guidance of **[Guide Name]**, Department of Computer Science & Engineering.

We further declare that the work reported in this project has not been submitted and will not be submitted, either in part or in full, for the award of any other degree or diploma in this institute or any other institute or university.

We have followed the ethical standards of software engineering and have not plagiarized any code or content from unauthorized sources. All external libraries and resources used have been duly acknowledged in the references section.

<br>
<br>

**Signatures of Students:**

1.  Shanky Prajapati __________________
2.  Hansika Gupta __________________
3.  Navya Jain __________________
4.  Shivanshu Chauhan __________________

---

Page 5 – Abstract

# ABSTRACT

In the modern academic ecosystem, students often face the challenge of acquiring expensive textbooks, electronics, and dorm essentials for short-term use. Simultaneously, graduating students struggle to dispose of these items efficiently. Existing platforms like OLX or eBay are geographically too broad, lack trust, and often require complex logistics for delivery.

The **College OLX (Smart Campus Marketplace)** is a specialized web-based application designed to bridge this gap by facilitating the buying and selling of used goods specifically within a college campus environment. The system creates a closed, trusted community where transactions are safer and more convenient.

**Key functionalities include:**
*   **Secure Authentication**: A robust login system ensuring only verified students and faculty can access the platform.
*   **Dynamic Marketplace**: A categorized feed of products with advanced search and filtering options.
*   **Real-time Communication**: An integrated chat system powered by **Google Firebase**, allowing instant negotiation between buyers and sellers without revealing personal phone numbers.
*   **Admin Governance**: A comprehensive dashboard for administrators to monitor user activity, moderate content, and ensure platform safety.

The project is developed using a **LAMP/WAMP stack** (Linux/Windows, Apache, MySQL/SQLite, PHP) for the backend and **HTML5, CSS3, JavaScript** for the frontend. This SRS document provides a comprehensive blueprint of the system, detailing every functional and non-functional requirement, system model, and design constraint necessary for successful implementation.

---

Page 6 – Table of Contents

# TABLE OF CONTENTS

1.  **Introduction** (Page 8)
    1.1 Purpose
    1.2 Document Conventions
    1.3 Intended Audience
    1.4 Product Scope
    1.5 Feasibility Study
2.  **Overall Description** (Page 14)
    2.1 Product Perspective
    2.2 Product Functions
    2.3 User Classes and Characteristics
    2.4 Operating Environment
    2.5 Design and Implementation Constraints
    2.6 Assumptions and Dependencies
3.  **System Features** (Page 20)
    3.1 User Authentication Module
    3.2 Product Management Module
    3.3 Search and Discovery Module
    3.4 Real-time Chat Module
    3.5 Administration Module
4.  **External Interface Requirements** (Page 30)
    4.1 User Interfaces
    4.2 Hardware Interfaces
    4.3 Software Interfaces
    4.4 Communications Interfaces
5.  **Functional Requirements** (Page 35)
    5.1 Requirement Analysis
    5.2 Detailed Functional Requirements (FR-1 to FR-5)
6.  **Non-Functional Requirements** (Page 45)
    6.1 Performance
    6.2 Safety & Security
    6.3 Reliability & Availability
7.  **System Models & Use Case Narratives** (Page 50)
    7.1 Use Case Diagrams & Detailed Narratives
    7.2 Activity Diagrams
    7.3 Sequence Diagrams
    7.4 Class Diagrams
    7.5 Data Flow Diagrams (Level 0, 1, 2)
    7.6 Entity Relationship (ER) Diagram
8.  **Database Design** (Page 65)
    8.1 Schema Description
    8.2 Data Dictionary
9.  **Testing Strategy** (Page 70)
    9.1 Test Plan
    9.2 Test Cases
10. **Future Enhancements & Conclusion** (Page 75)
11. **Appendices & References** (Page 76)

---

Page 7 – List of Figures and Tables

# LIST OF FIGURES AND TABLES

**List of Figures:**
*   Figure 1.1: Waterfall Model (Process Model)
*   Figure 2.1: System Architecture
*   Figure 7.1: Use Case Diagram - Student
*   Figure 7.2: Use Case Diagram - Admin
*   Figure 7.3: Activity Diagram - Login
*   Figure 7.4: Activity Diagram - Post Ad
*   Figure 7.5: Sequence Diagram - Chat Flow
*   Figure 7.6: Class Diagram
*   Figure 7.7: DFD Level 0
*   Figure 7.8: DFD Level 1
*   Figure 7.9: ER Diagram

**List of Tables:**
*   Table 1: User Characteristics
*   Table 2: Hardware Requirements
*   Table 3: Software Requirements
*   Table 4: Functional Requirements Matrix
*   Table 5: Use Case Narrative - Login
*   Table 6: Use Case Narrative - Post Ad
*   Table 7: Data Dictionary - Users Table
*   Table 8: Data Dictionary - Products Table
*   Table 9: Test Cases - Authentication

---

Page 8 – Introduction

# 1. INTRODUCTION

## 1.1 Purpose
The purpose of this Software Requirements Specification (SRS) document is to provide a detailed, rigorous, and comprehensive description of the requirements for the **College OLX** system. This document serves as the primary reference for the development team, testing team, and project stakeholders. It defines the functional behaviors, performance standards, and design constraints that the final software product must satisfy.

The document is written in accordance with the **IEEE 830-1998** standard for Software Requirements Specifications. It is intended to bridge the gap between the user's needs and the developer's technical implementation.

## 1.2 Document Conventions
*   **Bold** text is used for headings, key terms, and emphasis.
*   *Italic* text is used for variables and file names.
*   **Monospace** font is used for code snippets and database column names.
*   Requirements are numbered as **FR-X.Y** (Functional) and **NFR-X.Y** (Non-Functional).
*   Priorities are classified as **High**, **Medium**, and **Low**.

## 1.3 Intended Audience
This document is intended for the following stakeholders:
1.  **Project Developers**: To understand the exact logic to be implemented in PHP and JavaScript.
2.  **Database Administrators**: To design the MySQL/SQLite schema based on the data dictionary.
3.  **Quality Assurance (QA) Team**: To design test cases and validate the system against the SRS.
4.  **Project Guide/Evaluators**: To assess the depth and quality of the project planning.
5.  **End Users (Students)**: To understand the features and limitations of the system.

---

Page 9 – Introduction (Cont.)

## 1.4 Product Scope
**College OLX** is a localized C2C (Consumer-to-Consumer) e-commerce platform.

**Problem Statement:**
Students often buy expensive books and equipment that are only useful for one semester. Selling these items to juniors is difficult due to a lack of a centralized communication channel. Notice boards are inefficient, and public platforms like OLX are too broad and prone to scams.

**Proposed Solution:**
A dedicated web portal for the college where:
*   **Identity is Verified**: Users must register, reducing scam risks.
*   **Logistics are Zero**: Buyers and sellers are on the same campus, so no shipping is needed.
*   **Communication is Instant**: Integrated chat allows for quick negotiation.

**Core Modules:**
1.  **Authentication Module**: Login, Signup, Password Hashing.
2.  **Marketplace Module**: Feed, Search, Filter, Pagination.
3.  **Seller Module**: Image Upload, Ad Management (CRUD).
4.  **Communication Module**: Real-time Chat via Firebase.
5.  **Admin Module**: User Ban, Post Deletion, Analytics.

---

Page 10 – Introduction (Cont.)

## 1.5 Feasibility Study

Before commencing development, a detailed feasibility study was conducted to ensure the project's viability.

### 1.5.1 Technical Feasibility
The project uses standard web technologies:
*   **Frontend**: HTML, CSS, JS (Widely supported, team is proficient).
*   **Backend**: PHP (Easy to deploy, runs on XAMPP).
*   **Database**: SQLite/MySQL (Robust, free).
*   **Real-time**: Firebase (Free tier is sufficient for college traffic).
*   **Conclusion**: The project is technically feasible with the available resources.

### 1.5.2 Operational Feasibility
The system is designed to be user-friendly. Students are digital natives and will have no trouble navigating a web interface similar to other e-commerce sites. No special training is required for users.
*   **Conclusion**: The system will be easily adopted by the target audience.

### 1.5.3 Economic Feasibility
*   **Development Cost**: $0 (Open source tools).
*   **Hosting Cost**: Minimal (Can be hosted on college servers or free tiers like Heroku/000webhost).
*   **Maintenance Cost**: Low.
*   **Conclusion**: The project is economically viable.

---

Page 11 – Overall Description

# 2. OVERALL DESCRIPTION

## 2.1 Product Perspective
College OLX is a standalone web-based system. However, it interacts with several external components:
1.  **Web Browser**: The client-side interface.
2.  **Web Server (Apache)**: Handles HTTP requests and serves PHP files.
3.  **Database Server (MySQL/SQLite)**: Stores structured data (users, products).
4.  **Firebase Realtime Database**: A NoSQL cloud database used exclusively for storing chat messages to enable instant updates without page reloads.

**System Architecture:**
[Client Browser] <---> [Apache Web Server + PHP] <---> [MySQL Database]
       ^
       |
       v
[Firebase Cloud] (For Chat)

## 2.2 Product Functions
The system provides the following major functions:
*   **Registration**: Users can create accounts.
*   **Login/Logout**: Secure session management.
*   **Post Ad**: Users can upload product details and images.
*   **View Ad**: Detailed view of product with seller info.
*   **Search**: Keyword-based search for products.
*   **Filter**: Category-based filtering (Books, Electronics, etc.).
*   **Chat**: One-on-one messaging between buyer and seller.
*   **My Ads**: Dashboard for sellers to manage their listings.
*   **Admin Panel**: Moderation tools for the administrator.

---

Page 12 – Overall Description (Cont.)

## 2.3 User Classes and Characteristics

The system has three primary user classes:

### 1. Guest User
*   **Characteristics**: Anonymous user who has not logged in.
*   **Privileges**:
    *   Can view the landing page.
    *   Can view the "About Us" and "Contact" pages.
    *   Can access Login and Signup pages.
    *   **Restriction**: Cannot view product details or chat.

### 2. Registered Student (Buyer/Seller)
*   **Characteristics**: A student or faculty member with a valid account.
*   **Privileges**:
    *   **As Buyer**: Can browse all products, search, view details, and initiate chat.
    *   **As Seller**: Can post new ads, upload images, edit price/description, and delete sold items.
    *   **General**: Can update profile, change password, and view chat history.

### 3. Administrator
*   **Characteristics**: A super-user responsible for system maintenance.
*   **Privileges**:
    *   Full access to all database records.
    *   Can delete any user account (e.g., for violating policies).
    *   Can delete any product listing (e.g., illegal items).
    *   View system statistics.

---

Page 13 – Overall Description (Cont.)

## 2.4 Operating Environment

The system is designed to operate in the following environment:

**Server Side:**
*   **OS**: Windows 10/11 or Linux (Ubuntu 20.04+).
*   **Web Server**: Apache 2.4+.
*   **Language**: PHP 7.4 or 8.0+.
*   **Database**: MySQL 5.7+ or SQLite 3.

**Client Side:**
*   **Hardware**: Any device with a screen and internet connection (Laptop, Smartphone, Tablet).
*   **Software**: Modern Web Browser (Chrome, Firefox, Safari, Edge).
*   **Network**: 3G/4G/Wi-Fi connection required for real-time features.

## 2.5 Design and Implementation Constraints

1.  **Regulatory Policy**: The system must not allow the sale of illegal items (drugs, weapons) or academic dishonesty materials (exam papers).
2.  **Hardware Limitations**: The college server has limited storage; therefore, image uploads are compressed and capped at 5MB.
3.  **Reliability**: The chat system relies on Firebase; if Google services are down, chat will not function.
4.  **Security**: User passwords must never be stored in plain text.

---

Page 14 – System Features

# 3. SYSTEM FEATURES

## 3.1 User Authentication Module

**Description:**
The gatekeeper of the system. It ensures that only authorized personnel can access the marketplace.

**Detailed Logic:**
1.  **Signup**:
    *   User enters Name, Email, Password, Phone.
    *   System checks if Email already exists in DB.
    *   If yes, show error "Email already taken".
    *   If no, hash password using `password_hash()`.
    *   Insert record into `users` table.
2.  **Login**:
    *   User enters Email, Password.
    *   System fetches user record by Email.
    *   Verifies password using `password_verify()`.
    *   If match, start PHP Session `$_SESSION['user_id']`.
    *   Redirect to Dashboard.

**Functional Requirements:**
*   **FR-1.1**: System shall accept valid email addresses only.
*   **FR-1.2**: Password must be at least 6 characters long.
*   **FR-1.3**: System shall prevent brute-force attacks (future scope: CAPTCHA).

---

Page 15 – System Features (Cont.)

## 3.2 Product Management Module

**Description:**
Allows users to convert their physical items into digital listings.

**Detailed Logic:**
1.  **Post Ad**:
    *   User fills form: Title, Category, Price, Description.
    *   User selects image file.
    *   System validates file type (jpg, png, jpeg).
    *   System moves file to `public/uploads/` directory with a unique name.
    *   System inserts product metadata into `products` table linked to `user_id`.
2.  **My Products**:
    *   Query `SELECT * FROM products WHERE user_id = current_user`.
    *   Display list with "Edit" and "Delete" buttons.
3.  **Delete Ad**:
    *   User clicks Delete.
    *   System removes record from DB.
    *   System unlinks (deletes) the image file from the server to save space.

**Functional Requirements:**
*   **FR-2.1**: All fields (Title, Price, Category) are mandatory.
*   **FR-2.2**: Price must be a positive numeric value.
*   **FR-2.3**: Image size must not exceed 5MB.

---

Page 16 – System Features (Cont.)

## 3.3 Search and Discovery Module

**Description:**
The core browsing experience for buyers.

**Detailed Logic:**
1.  **Feed**:
    *   Default view shows latest 20 products (`ORDER BY id DESC`).
2.  **Search**:
    *   User types "Calculus Book".
    *   System executes `SELECT * FROM products WHERE title LIKE '%Calculus Book%'`.
    *   Returns matching results.
3.  **Filter**:
    *   User selects Category "Electronics".
    *   System executes `SELECT * FROM products WHERE category = 'Electronics'`.

**Functional Requirements:**
*   **FR-3.1**: Search shall be case-insensitive.
*   **FR-3.2**: Results shall display image, title, price, and "Chat" button.
*   **FR-3.3**: Clicking a result shall open the full Product Details page.

---

Page 17 – System Features (Cont.)

## 3.4 Real-time Chat Module

**Description:**
Enables instant communication using Firebase Realtime Database.

**Detailed Logic:**
1.  **Initiate Chat**:
    *   Buyer clicks "Chat" on Seller's product.
    *   System generates a unique `chat_id` based on `min(uid1, uid2) + max(uid1, uid2)`.
    *   Opens chat window.
2.  **Send Message**:
    *   User types message and hits Send.
    *   JavaScript pushes object to Firebase:
        ```json
        {
          "sender": "user_123",
          "message": "Is this available?",
          "timestamp": 1678900000
        }
        ```
3.  **Receive Message**:
    *   `firebase.database().ref('chats/' + chat_id).on('child_added')` listener triggers.
    *   New message is appended to the DOM dynamically.

**Functional Requirements:**
*   **FR-4.1**: Messages shall appear instantly (< 1 sec latency).
*   **FR-4.2**: Chat history shall be persistent across sessions.
*   **FR-4.3**: Users shall be notified of new messages (visual cue).

---

Page 18 – System Features (Cont.)

## 3.5 Administration Module

**Description:**
Tools for the super-admin to manage the ecosystem.

**Detailed Logic:**
1.  **Admin Login**:
    *   Hardcoded or database-flagged admin credentials.
2.  **User Management**:
    *   Table view of all registered users.
    *   Action: "Delete User" -> Removes user and ALL their products.
3.  **Product Moderation**:
    *   Table view of all products.
    *   Action: "Delete Product" -> Removes inappropriate listings.

**Functional Requirements:**
*   **FR-5.1**: Admin dashboard shall be accessible only to users with `role='admin'`.
*   **FR-5.2**: Admin actions shall be irreversible and require a confirmation dialog.

---

Page 19 – External Interface Requirements

# 4. EXTERNAL INTERFACE REQUIREMENTS

## 4.1 User Interfaces

The UI is designed with a "Mobile-First" approach using CSS Flexbox and Grid.

**Screens:**
1.  **Landing Page**: Hero section with "Join Now" CTA.
2.  **Login/Signup**: Clean forms with floating labels.
3.  **Home/Feed**: Grid layout of product cards.
    *   *Card*: Image (top), Title (bold), Price (green), Category (badge).
4.  **Product Details**: Large image on left, details on right (Desktop). Stacked on Mobile.
5.  **Chat Interface**:
    *   Left Sidebar: List of recent chats.
    *   Main Area: Message bubbles (Right for sent, Left for received).
    *   Bottom: Input field and Send icon.

## 4.2 Hardware Interfaces
*   **Server**:
    *   Processor: Dual Core 2.0 GHz+.
    *   RAM: 4 GB+.
    *   Storage: 50 GB SSD (for images and DB).
*   **Client**:
    *   Screen Resolution: Min 320px width (Mobile), 1920x1080 (Desktop recommended).

## 4.3 Software Interfaces
*   **Database Connector**: PDO (PHP Data Objects) for secure SQL interaction.
*   **Firebase SDK**: JavaScript library version 8.0+ for client-side chat logic.
*   **Bootstrap/Tailwind** (Optional): Used for rapid UI styling.

## 4.4 Communications Interfaces
*   **Protocol**: HTTP/1.1 or HTTP/2.
*   **SSL/TLS**: HTTPS is mandatory for secure data transmission (especially passwords).
*   **WebSocket**: Managed internally by Firebase for real-time updates.

---

Page 20 – Functional Requirements

# 5. FUNCTIONAL REQUIREMENTS

## 5.1 Requirement Analysis
The system is data-centric. Most operations involve CRUD (Create, Read, Update, Delete) on the database. The real-time aspect is decoupled using Firebase.

## 5.2 Detailed Functional Requirements

### FR-1: Authentication
| ID | Requirement | Priority |
| :--- | :--- | :--- |
| FR-1.1 | The system shall allow a guest to sign up as a student. | High |
| FR-1.2 | The system shall verify that the email is unique. | High |
| FR-1.3 | The system shall hash passwords before storage. | High |
| FR-1.4 | The system shall maintain user sessions via cookies. | High |
| FR-1.5 | The system shall allow users to logout and destroy the session. | Medium |

### FR-2: Product Management
| ID | Requirement | Priority |
| :--- | :--- | :--- |
| FR-2.1 | The system shall allow logged-in users to post an ad. | High |
| FR-2.2 | The system shall validate that the price is a number. | Medium |
| FR-2.3 | The system shall allow users to upload one image per product. | High |
| FR-2.4 | The system shall allow users to edit their own ads. | Low |
| FR-2.5 | The system shall allow users to delete their own ads. | Medium |

---

Page 21 – Functional Requirements (Cont.)

### FR-3: Marketplace & Search
| ID | Requirement | Priority |
| :--- | :--- | :--- |
| FR-3.1 | The system shall display all products in reverse chronological order. | High |
| FR-3.2 | The system shall allow searching by product title. | High |
| FR-3.3 | The system shall allow filtering by category. | Medium |
| FR-3.4 | The system shall display a "Sold" tag if the item is no longer available. | Low |

### FR-4: Chat System
| ID | Requirement | Priority |
| :--- | :--- | :--- |
| FR-4.1 | The system shall allow a buyer to start a chat with a seller. | High |
| FR-4.2 | The system shall display messages in real-time. | High |
| FR-4.3 | The system shall show the timestamp of each message. | Low |
| FR-4.4 | The system shall group messages by conversation. | Medium |

### FR-5: Admin Functions
| ID | Requirement | Priority |
| :--- | :--- | :--- |
| FR-5.1 | The system shall provide a secure admin login page. | High |
| FR-5.2 | The system shall allow admins to view all users. | Medium |
| FR-5.3 | The system shall allow admins to delete users. | High |
| FR-5.4 | The system shall allow admins to delete products. | High |

---

Page 22 – Non-Functional Requirements

# 6. NON-FUNCTIONAL REQUIREMENTS

## 6.1 Performance
*   **NFR-1.1**: The application shall load the homepage within 2 seconds under normal load (10 concurrent users).
*   **NFR-1.2**: Database queries for search shall execute within 0.5 seconds.
*   **NFR-1.3**: Image uploads shall be processed (moved/resized) within 3 seconds.

## 6.2 Safety & Security
*   **NFR-2.1**: **Data Integrity**: The system shall prevent SQL Injection attacks by using Prepared Statements for all database interactions.
*   **NFR-2.2**: **XSS Protection**: All user-generated content (product descriptions, chat messages) shall be sanitized (escaped) before rendering to prevent Cross-Site Scripting.
*   **NFR-2.3**: **Access Control**: Pages like `add_product.php` and `chat.php` shall check for a valid session token before loading. Unauthorized access attempts should redirect to `login.html`.

## 6.3 Reliability & Availability
*   **NFR-3.1**: The system shall be available 99.5% of the time during the semester.
*   **NFR-3.2**: The system shall recover from a database connection failure by displaying a user-friendly "Maintenance" message instead of a stack trace.

## 6.4 Usability
*   **NFR-4.1**: The interface shall be responsive, adapting to screen sizes from 320px (iPhone SE) to 1920px (Desktop).
*   **NFR-4.2**: Error messages (e.g., "Wrong Password") shall be clear and descriptive.

---

Page 23 – System Models & Use Case Narratives

# 7. SYSTEM MODELS & USE CASE NARRATIVES

## 7.1 Use Case Narratives

This section provides a detailed textual description of the major use cases.

### Use Case 1: User Registration
| Field | Description |
| :--- | :--- |
| **Use Case ID** | UC-01 |
| **Use Case Name** | User Registration |
| **Actors** | Guest User |
| **Pre-conditions** | User is on the Signup Page. |
| **Post-conditions** | New user account is created in the database. |
| **Main Flow** | 1. User enters Name, Email, Password, Phone.<br>2. User clicks "Signup".<br>3. System validates input format.<br>4. System checks for duplicate email.<br>5. System hashes password.<br>6. System creates record.<br>7. System redirects to Login Page. |
| **Alternative Flow** | 4a. Email exists: System shows error "Email already registered". |
| **Exceptions** | Database connection fails: Show "Server Error". |

### Use Case 2: Post Ad
| Field | Description |
| :--- | :--- |
| **Use Case ID** | UC-02 |
| **Use Case Name** | Post Advertisement |
| **Actors** | Registered Student (Seller) |
| **Pre-conditions** | User is logged in. |
| **Post-conditions** | Product is listed on the feed. |
| **Main Flow** | 1. User clicks "Sell".<br>2. User fills product details.<br>3. User selects image.<br>4. User clicks "Submit".<br>5. System validates inputs.<br>6. System uploads image.<br>7. System saves product data.<br>8. System shows success message. |
| **Alternative Flow** | 5a. Invalid file type: Show "Only JPG/PNG allowed". |

---

Page 24 – System Models (Cont.)

### Use Case 3: Search Product
| Field | Description |
| :--- | :--- |
| **Use Case ID** | UC-03 |
| **Use Case Name** | Search Product |
| **Actors** | Registered Student (Buyer) |
| **Pre-conditions** | User is on Homepage. |
| **Post-conditions** | Filtered list of products is displayed. |
| **Main Flow** | 1. User types keyword in Search Bar.<br>2. User presses Enter.<br>3. System queries database for matches.<br>4. System renders product cards for matches. |
| **Alternative Flow** | 3a. No matches found: System displays "No products found". |

### Use Case 4: Chat with Seller
| Field | Description |
| :--- | :--- |
| **Use Case ID** | UC-04 |
| **Use Case Name** | Initiate Chat |
| **Actors** | Buyer, Seller |
| **Pre-conditions** | User is logged in and viewing a product. |
| **Post-conditions** | Message is sent to seller. |
| **Main Flow** | 1. Buyer clicks "Chat" button.<br>2. System opens chat window.<br>3. Buyer types message and sends.<br>4. Message is stored in Firebase.<br>5. Seller receives notification/message. |

---

Page 25 – System Models (Cont.)

## 7.2 Activity Diagrams

### Activity Diagram: Buying Process
*(Textual Description)*
1.  **Start Node**
2.  **Decision**: Is User Logged In?
    *   No -> Go to Login Page -> Authenticate -> Return to Home.
    *   Yes -> Proceed.
3.  **Action**: Browse Feed / Search Product.
4.  **Action**: Select Product.
5.  **Action**: View Details.
6.  **Decision**: Interested?
    *   No -> Go back to Feed.
    *   Yes -> Click "Chat".
7.  **Action**: Negotiate Price.
8.  **Action**: Agree on Meeting.
9.  **Action**: Meet and Exchange Item.
10. **End Node**

## 7.3 Sequence Diagrams

### Sequence Diagram: Login Process
1.  **User** -> (Enter Credentials) -> **LoginView**
2.  **LoginView** -> (POST Request) -> **LoginHandler.php**
3.  **LoginHandler.php** -> (Query Email) -> **Database**
4.  **Database** -> (Return User Row) -> **LoginHandler.php**
5.  **LoginHandler.php** -> (Verify Hash) -> **Self**
6.  **LoginHandler.php** -> (Set Session) -> **SessionManager**
7.  **LoginHandler.php** -> (Redirect) -> **Dashboard**

---

Page 26 – System Models (Cont.)

## 7.4 Class Diagrams

**Class: User**
*   `+ int id`
*   `+ string name`
*   `+ string email`
*   `+ string password_hash`
*   `+ string phone`
*   `+ register()`
*   `+ login()`
*   `+ logout()`

**Class: Product**
*   `+ int id`
*   `+ int user_id`
*   `+ string title`
*   `+ float price`
*   `+ string description`
*   `+ string image_path`
*   `+ create()`
*   `+ read()`
*   `+ update()`
*   `+ delete()`

**Class: Database**
*   `+ connection`
*   `+ connect()`
*   `+ query()`
*   `+ fetch()`
*   `+ close()`

## 7.5 Entity Relationship (ER) Diagram

**Entities:**
1.  **USER**: Attributes (ID, Name, Email, Pass, Phone).
2.  **PRODUCT**: Attributes (ID, Title, Desc, Price, Image, Category).

**Relationships:**
*   **One-to-Many**: A **USER** can post multiple **PRODUCTS**.
    *   `User (1) ---- (N) Product`
*   **One-to-Many**: A **USER** can send multiple **MESSAGES**.

---

Page 27 – Database Design

# 8. DATABASE DESIGN

## 8.1 Schema Description

The system uses a relational database (MySQL/SQLite). The database name is `college_olx`.

## 8.2 Data Dictionary

### Table 1: `users`
This table stores the registration details of all students and admins.

| Column Name | Data Type | Length | Constraints | Description |
| :--- | :--- | :--- | :--- | :--- |
| `id` | INT | 11 | PK, Auto Increment | Unique identifier for the user. |
| `name` | VARCHAR | 100 | Not Null | Full name of the student. |
| `email` | VARCHAR | 100 | Unique, Not Null | College email address. |
| `password` | VARCHAR | 255 | Not Null | Bcrypt hashed password string. |
| `phone` | VARCHAR | 15 | Nullable | Contact number. |
| `role` | ENUM | - | Default 'student' | 'student' or 'admin'. |
| `created_at` | TIMESTAMP | - | Default CURRENT_TIMESTAMP | Account creation time. |

### Table 2: `products`
This table stores the items listed for sale.

| Column Name | Data Type | Length | Constraints | Description |
| :--- | :--- | :--- | :--- | :--- |
| `id` | INT | 11 | PK, Auto Increment | Unique identifier for the product. |
| `user_id` | INT | 11 | FK (users.id) | ID of the seller. |
| `title` | VARCHAR | 255 | Not Null | Title of the ad. |
| `description` | TEXT | - | Nullable | Detailed description. |
| `price` | DECIMAL | 10,2 | Not Null | Price in local currency. |
| `category` | VARCHAR | 50 | Not Null | e.g., Books, Electronics. |
| `image` | VARCHAR | 255 | Not Null | File path of the uploaded image. |
| `status` | ENUM | - | Default 'active' | 'active' or 'sold'. |
| `created_at` | TIMESTAMP | - | Default CURRENT_TIMESTAMP | Posting time. |

---

Page 28 – Testing Strategy

# 9. TESTING STRATEGY

## 9.1 Test Plan
The testing phase ensures the system is bug-free and meets all requirements.

**Types of Testing:**
1.  **Unit Testing**: Testing individual PHP scripts (e.g., `db_connect.php`) to ensure they function in isolation.
2.  **Integration Testing**: Testing the flow between modules (e.g., Post Ad -> Database -> Feed).
3.  **System Testing**: Validating the entire application flow from start to finish.
4.  **Security Testing**: Checking for SQL Injection and XSS vulnerabilities.

## 9.2 Test Cases

### TC-01: User Login
| Step | Action | Input Data | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | Navigate to Login Page | URL: `/login.html` | Login form loads | Form loaded | Pass |
| 2 | Enter Valid Credentials | `test@college.edu` / `password123` | Redirect to Dashboard | Redirected | Pass |
| 3 | Enter Invalid Password | `test@college.edu` / `wrongpass` | Show "Invalid Password" | Shown | Pass |
| 4 | Enter Unregistered Email | `fake@college.edu` / `password` | Show "User not found" | Shown | Pass |

### TC-02: Post Ad
| Step | Action | Input Data | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | Click "Sell" Button | - | Load Sell Form | Form loaded | Pass |
| 2 | Submit Empty Form | - | Show "All fields required" | Shown | Pass |
| 3 | Upload Non-Image File | `document.pdf` | Show "Invalid file type" | Shown | Pass |
| 4 | Submit Valid Data | Title: "Book", Price: 500, Image: `book.jpg` | Success Message & Redirect | Success | Pass |

---

Page 29 – Future Enhancements & Conclusion

# 10. FUTURE ENHANCEMENTS & CONCLUSION

## 10.1 Future Enhancements
The current version of College OLX serves as a Minimum Viable Product (MVP). Several features are planned for the next phase:

1.  **In-App Payments**: Integration with Stripe or Razorpay to allow users to pay online, holding funds in escrow until the item is exchanged.
2.  **AI Image Recognition**: Automatically tagging products based on the uploaded image (e.g., detecting a "Laptop" and auto-selecting the category).
3.  **Mobile App**: Building a Flutter-based mobile app for Push Notifications.
4.  **Rating System**: Allowing buyers to rate sellers (1-5 stars) to build trust scores.
5.  **Lost & Found Section**: A special category for reporting lost items on campus.

## 10.2 Conclusion
The **College OLX** project successfully addresses the need for a localized, secure, and efficient marketplace for the college community. By leveraging modern web technologies and a robust database design, the system provides a seamless experience for students to buy and sell goods.

The inclusion of real-time chat solves the communication barrier, while the admin dashboard ensures the platform remains safe and moderated. The project not only solves a real-world problem but also demonstrates the practical application of Software Engineering principles, from Requirement Analysis (SRS) to Design, Implementation, and Testing.

---

Page 30 – Appendices & References

# 11. APPENDICES

## Appendix A: Installation Guide
1.  **Install XAMPP**: Download and install XAMPP for Windows.
2.  **Start Servers**: Open Control Panel and start Apache and MySQL.
3.  **Setup Database**:
    *   Open `localhost/phpmyadmin`.
    *   Create database `college_olx`.
    *   Import `database.sql`.
4.  **Deploy Code**:
    *   Copy project folder to `C:\xampp\htdocs\college-olx`.
5.  **Run**: Open browser and go to `localhost/college-olx`.

## Appendix B: Sample Code
**`login_handler.php`**
```php
<?php
session_start();
include 'db_connect.php';

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        header("Location: index.php");
    } else {
        echo "Invalid Password";
    }
} else {
    echo "User not found";
}
?>
```

# 12. REFERENCES
1.  **IEEE Std 830-1998**: IEEE Recommended Practice for Software Requirements Specifications.
2.  **PHP Documentation**: https://www.php.net/docs.php
3.  **Firebase Documentation**: https://firebase.google.com/docs/database/web/start
4.  **Pressman, R. S.** (2014). *Software Engineering: A Practitioner's Approach*. McGraw-Hill Education.
5.  **Sommerville, I.** (2015). *Software Engineering*. Pearson.

---
**END OF DOCUMENT**
