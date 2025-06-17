# 🎂 Poofy - Cake Business Website

A complete, responsive website for a cake business built with HTML, CSS, JavaScript, PHP, and MySQL.

## 🌟 Features

### Frontend Features
- **6 Complete Pages**: Home, Menu, Gallery, Order, About, Contact
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile
- **Interactive Elements**: Category filtering, image lightbox, smooth animations
- **Form Validation**: Real-time validation for order and contact forms
- **Modern UI**: Attractive pastel color scheme with cute, professional design

### Backend Features
- **PHP Backend**: Secure form processing and data management
- **MySQL Database**: Complete database schema for cakes, orders, and customers
- **Admin Panel**: Full-featured admin interface for managing the business
- **API Endpoints**: RESTful API for data operations

### Business Features
- **Cake Categories**: Birthday, Anniversary, Chocolate, Cupcakes, Mini Cakes
- **Order Management**: Complete order tracking system
- **Customer Inquiries**: Contact form with multiple inquiry types
- **Gallery Management**: Showcase your best cake creations
- **Social Media Integration**: WhatsApp, Instagram, Facebook links

## 📁 Project Structure

```
poofy-website/
├── index.html              # Home page
├── menu.html               # Cakes menu page
├── gallery.html            # Image gallery page
├── order.html              # Order form page
├── about.html              # About business page
├── contact.html            # Contact information page
├── css/
│   └── style.css           # Main stylesheet
├── js/
│   └── main.js             # JavaScript functionality
├── images/                 # Cake images and assets
├── php/
│   ├── config.php          # Database configuration
│   ├── process_order.php   # Order form handler
│   ├── process_contact.php # Contact form handler
│   └── admin_api.php       # Admin panel API
├── sql/
│   └── database_setup.sql  # Database schema
├── admin/
│   └── index.html          # Admin panel interface
└── README.md               # This file
```

## 🚀 Installation & Setup

### Prerequisites
- Web server with PHP 7.4+ support (Apache/Nginx)
- MySQL 5.7+ or MariaDB
- Modern web browser

### Step 1: Database Setup
1. Create a new MySQL database named `poofy_cakes`
2. Import the database schema:
   ```sql
   mysql -u your_username -p poofy_cakes < sql/database_setup.sql
   ```

### Step 2: Configuration
1. Edit `php/config.php` and update database credentials:
   ```php
   private $host = 'localhost';
   private $db_name = 'poofy_cakes';
   private $username = 'your_db_username';
   private $password = 'your_db_password';
   ```

### Step 3: File Upload
1. Upload all files to your web server's document root
2. Ensure proper file permissions (755 for directories, 644 for files)
3. Make sure the `images/` directory is writable

### Step 4: Testing
1. Visit your website URL
2. Test all forms and functionality
3. Access admin panel at `/admin/`

## 🎨 Customization

### Colors & Branding
Edit the CSS variables in `css/style.css`:
```css
:root {
    --primary-color: #FFC0CB;    /* Light Pink */
    --secondary-color: #F0E68C;  /* Soft Yellow */
    --accent-color: #DDA0DD;     /* Light Purple */
    --text-dark: #4B0082;        /* Indigo */
}
```

### Business Information
Update contact details in:
- All HTML files (footer sections)
- `contact.html` (contact information)
- `about.html` (business story)

### Cake Menu
Add/edit cakes through:
- Admin panel interface
- Direct database editing
- `sql/database_setup.sql` (for initial data)

## 📱 Mobile Responsiveness

The website is fully responsive and includes:
- Mobile-first design approach
- Touch-friendly navigation
- Optimized images for different screen sizes
- Collapsible mobile menu
- Responsive forms and buttons

## 🔧 Admin Panel

Access the admin panel at `/admin/` to:
- View dashboard statistics
- Manage orders and inquiries
- Add/edit/delete cakes
- Update business settings
- Track customer communications

### Admin Features
- **Dashboard**: Overview of orders, sales, and statistics
- **Order Management**: View, update, and track all orders
- **Cake Management**: Add new cakes, update pricing, manage availability
- **Customer Communications**: Respond to inquiries and messages

## 🛡️ Security Features

- Input validation and sanitization
- SQL injection prevention with PDO prepared statements
- XSS protection
- CSRF protection ready (can be enhanced)
- Secure file upload handling

## 📧 Email Configuration

To enable email notifications:
1. Uncomment email lines in `php/process_order.php` and `php/process_contact.php`
2. Configure your server's mail settings
3. Consider using SMTP for better deliverability

## 🌐 SEO Features

- Semantic HTML structure
- Meta tags for social sharing
- Clean, descriptive URLs
- Fast loading times
- Mobile-friendly design
- Structured data ready

## 🎯 Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- Mobile browsers (iOS Safari, Chrome Mobile)

## 📈 Performance

- Optimized images
- Minified CSS and JavaScript ready
- Efficient database queries
- Lazy loading for images
- Smooth animations with CSS transforms

## 🔄 Updates & Maintenance

### Regular Tasks
- Backup database regularly
- Update cake menu and prices
- Respond to customer inquiries
- Monitor order status
- Update gallery with new photos

### Technical Maintenance
- Keep PHP and MySQL updated
- Monitor server logs
- Regular security updates
- Performance optimization

## 🆘 Troubleshooting

### Common Issues

**Forms not submitting:**
- Check PHP configuration
- Verify database connection
- Check file permissions

**Images not loading:**
- Verify image file paths
- Check file permissions
- Ensure images directory exists

**Admin panel not working:**
- Check database connection
- Verify admin API endpoints
- Check browser console for errors

## 📞 Support

For technical support or customization requests:
- Check the documentation
- Review the code comments
- Test in a development environment first

## 🎉 Deployment Checklist

- [ ] Database created and imported
- [ ] Configuration files updated
- [ ] All files uploaded to server
- [ ] File permissions set correctly
- [ ] Forms tested and working
- [ ] Admin panel accessible
- [ ] Email notifications configured
- [ ] SSL certificate installed
- [ ] Backup system in place
- [ ] Contact information updated
- [ ] Social media links updated

## 📄 License

This project is created for the Poofy cake business. All rights reserved.

---

**Built with 💕 for Poofy Cakes**

*Handmade with love – Freshly baked websites from the development kitchen!*

