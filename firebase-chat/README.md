# UniMart - Campus Marketplace

A student-to-student marketplace with real-time chat functionality.

## Features
- User authentication (login/signup)
- Product listing and management
- Shopping cart
- Real-time buyer-seller chat
- Admin panel for sellers

## Local Development

### Requirements
- PHP 8.0+
- SQLite (included)

### Setup
```bash
# Start PHP server
php -S localhost:8000 -t public
```

Visit `http://localhost:8000`

## Deployment to Railway

### Quick Deploy
1. Fork this repository
2. Sign up at [railway.app](https://railway.app)
3. Click "New Project" → "Deploy from GitHub"
4. Select this repository
5. Add PostgreSQL database service
6. Deploy!

Your app will be live at: `https://yourapp.up.railway.app`

### Environment Variables
Railway will automatically set:
- `DATABASE_URL` - PostgreSQL connection string
- `PORT` - Server port

## Tech Stack
- **Backend**: PHP 8.0
- **Database**: SQLite (local) / PostgreSQL (production)
- **Frontend**: HTML, CSS, JavaScript
- **Chat**: Mock Firebase (file-based)

## Project Structure
```
firebase-chat/
├── public/           # Web root
│   ├── 1.php        # Homepage
│   ├── login.php    # Login page
│   ├── signup.php   # Signup page
│   ├── product.php  # Product details
│   ├── admins.php   # Seller admin panel
│   ├── chat.js      # Chat widget
│   └── admin.js     # Admin chat
├── railway.json     # Railway config
└── composer.json    # PHP dependencies
```

## License
MIT
