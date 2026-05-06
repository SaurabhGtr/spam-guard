# Spam Guard – Anti-Spam Protection for WordPress

Spam Guard keeps comment spam off your site without CAPTCHA, third-party services, or any impact on page speed. It works by combining several lightweight techniques that stop bots cold while staying completely invisible to real visitors.

---

## How It Works

### Scroll-gate + hash validation
The comment form's action URL is intentionally blank on page load. When the visitor scrolls, JavaScript fills it with a unique per-site hash derived from your `NONCE_SALT`. Bots that POST directly to `wp-comments-post.php` never have the right hash and get a 400 error. Bots that don't execute JavaScript never get the URL at all.

### Honeypot field
A text field is injected into the comment form and hidden off-screen with CSS. It's invisible to humans, but bots that blindly fill every field will populate it — and get blocked on submission.

### Comment content filtering
Links, self-promotional URLs, and spammy content patterns are stripped or rejected before a comment is ever saved.

---

## Features

**Bot blocking**
- Scroll-based action URL injection — no scroll, no valid submission
- Per-site MD5 hash validation on every comment POST
- Referrer check — rejects requests not originating from your own site
- Honeypot trap — catches bots that fill all form fields automatically
- Blocks direct curl/bot POST attempts to `wp-comments-post.php`

**Comment filtering**
- Strip all hyperlinks from comment content
- Remove the Website URL field from the comment form entirely
- Block self-linking — strips links in comment text that point to the commenter's own website
- Reject comments where the author name contains a URL (keyword-stuffed names like "Buy Watches – https://…")
- Minimum comment length check — rejects comments under a configurable character count

**Compatibility**
- WooCommerce product reviews fully supported
- Works with Astra, Fluent Forms, Highlander, and standard WordPress comment forms
- Compatible with all caching plugins (LiteSpeed, WP Rocket, FlyingPress, Cloudflare, etc.)
- No external scripts, no tracking, no database tables

---

## Settings

All toggles are on by default. Configure them under **Spam Guard → Settings**.

| Setting | Default | Description |
|---------|---------|-------------|
| Disable Website Field | On | Removes the URL field from the comment form |
| Strip Links in Comments | On | Strips all URLs and `<a>` tags from comment text |
| Disable Self-Linking | On | Strips links pointing to the commenter's own website domain |
| Honeypot Trap | On | Injects a hidden field to catch bots |
| Minimum Comment Length | 10 | Rejects comments shorter than N characters (0 to disable) |
| Block URL-Stuffed Author Names | On | Rejects comments where the author name contains a URL |

---

## Testing It

**Should fail (Error 400):**
- Submit a comment without scrolling first
- POST directly to `wp-comments-post.php` without the hash query string:
  ```bash
  curl -X POST https://example.com/wp-comments-post.php
  ```
- Submit with the honeypot field populated

**Should pass:**
- Normal comment submission after scrolling
- WooCommerce product review submission

---

## Installation

1. Download the plugin ZIP
2. Upload to `/wp-content/plugins/spam-guard/` or install via **Plugins → Add New → Upload**
3. Activate from **Plugins → Installed Plugins**
4. Visit **Spam Guard → Settings** to review defaults
5. Clear your cache (LiteSpeed, Cloudflare, WP Rocket, etc.) after activation

---

## Recommended

### Warp Performance
**WordPress at Warp Speed** — full cloud optimization for WordPress: edge caching, CDN, server-level performance tuning, and more.  
➡ https://warpperformance.com

### Sticky Ad Lightweight
A zero-CWV-impact sticky ad plugin — boost ad revenue without hurting Core Web Vitals scores.  
➡ https://wordpress.org/plugins/sticky-ad-lightweight/

### CloudHashed
Managed WordPress hosting and support: performance, security, uptime monitoring, CDN, backups.  
➡ https://cloudhashed.com/

---

## File Structure

```
spam-guard/
├── spam-guard.php
└── README.md
```

---

## Author

**Saurabh Guttedar**  
High-performance WordPress, infrastructure, and optimization.

---

## License

GPLv2 or later — free to use, modify, and redistribute.
