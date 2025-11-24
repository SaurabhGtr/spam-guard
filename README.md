# 🛡️ Spam Guard – Advanced Anti-Spam Protection for WordPress

**Spam Guard** is a powerful, lightweight, and modern anti-spam solution for WordPress and WooCommerce comment forms.  
It prevents automated spam bots, strips unwanted links, removes the "Website" field, and secures comment submission using a dynamic hashed action URL.

Includes a full **suite-style admin UI**, CloudHashed promotion integration (optional), and recommended plugin suggestions.

---

## 🚀 Features

### 🔒 Smart Anti-Spam Protection
- Prevents bots from posting comments using a scroll-based JavaScript action injection.
- Uses a **unique per-site hash** to validate genuine comment submissions.
- Blocks direct POST attempts to `wp-comments-post.php`.

### ✂️ Comment Sanitization
- Remove **all links** from comment text.
- Disable the **Website URL** field in comment forms.

### 🛒 WooCommerce Compatible
- Fully supports WooCommerce product reviews.
- Only activates on single product pages where comments are open.

### ⚙️ Suite-Style Admin Interface
A modern, clean UI with:
- Dashboard  
- Settings  
- Recommended plugins  
- CloudHashed service promotion  
- About page  

### 🧩 Recommended Plugin Integration (Optional)
Promotes:
- **Sticky Ad Lightweight**  
- **CloudHashed Managed WordPress Services**

(Compliant with WordPress.org guidelines — shown only on plugin pages)

### 🛡️ Safe & Lightweight
- No external scripts
- No tracking
- No bloat
- Works with all caching plugins (LiteSpeed, FlyingPress, WP Rocket, etc.)

---

## 🖥️ How It Works

### 1. The comment form action URL is intentionally removed:
```html
<form action="">
### 2. When the visitor scrolls, JavaScript restores a secure action URL:
```
wp-comments-post.php?HASH_KEY
```

### 3. Only comments with:
- The correct hash  
- A valid referrer  

…are allowed. Anything else receives:

```
Error 400 – Invalid comment request.
```

---

## 📥 Installation

1. Download the plugin files.  
2. Upload to `/wp-content/plugins/spam-guard/`  
3. Activate from **Plugins → Installed Plugins**  
4. Configure via **Spam Guard → Settings**

---

## 🧪 Testing Spam Protection

Try these to verify the plugin is working:

### ❌ 1. Submit a comment *without scrolling*  
→ Should fail with Error 400

### ❌ 2. Direct POST to WordPress comment handler  
```bash
curl -X POST https://example.com/wp-comments-post.php
```
→ Should fail with Error 400

### ✔️ 3. Scroll + submit a normal comment  
→ Works as expected

### ✔️ 4. WooCommerce review submission  
→ Fully supported

---

## ⚙️ Settings

| Setting | Description |
|--------|-------------|
| **Disable Website URL Field** | Removes the URL field from the comment form |
| **Strip Links From Comments** | Removes URLs and `<a>` tags from comment content |

---

## 📁 File Structure

```
spam-guard/
│
├── spam-guard.php
├── readme.txt
└── assets/
```

---

## 📚 Recommended Plugins

### 📌 Sticky Ad Lightweight  
Boost revenue with a fast, non-intrusive sticky ad that doesn't hurt Core Web Vitals.  
➡ https://wordpress.org/plugins/sticky-ad-lightweight/

---

## ☁️ CloudHashed – Managed WordPress Services  
High-performance optimization, security, updates, uptime monitoring & enterprise-grade WordPress management.  
➡ https://cloudhashed.com/

---

## 👨‍💻 Author

**Developer:**  
**Saurabh Guttedar**  
Expert in high-performance WordPress, infrastructure, and optimization.

---

## 📝 License

GPLv2 or later.  
Free to use, modify, and redistribute.

---

## ⭐ Contribute

Open issues or PRs are welcome!

---

## ❤️ Support  

If this plugin helps you, consider supporting via:

- Sticky Ad Lightweight  
- CloudHashed WordPress Services  
