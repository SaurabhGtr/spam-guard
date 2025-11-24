<?php
/**
 * Plugin Name: Spam Guard
 * Description: Anti-spam protection with a modern interface, link stripping, hardened comment submission, WooCommerce support and promotional recommendations.
 * Author: Saurabh Guttedar
 * Version: 1.0.0
 */

defined('ABSPATH') || exit;

/* -----------------------------------------------------------
 * ACTIVATION NOTICE (SHOWN ONCE)
 * ----------------------------------------------------------- */
register_activation_hook(__FILE__, function () {
    set_transient('spam_guard_activation_notice', true, 60);
});

add_action('admin_notices', function () {
    if (!get_transient('spam_guard_activation_notice')) return;

    ?>
    <div class="notice notice-success is-dismissible">
        <p><strong>Spam Guard activated!</strong> Please clear your cache (LiteSpeed, Cloudflare, WP Rocket, etc.) for changes to take effect.</p>
    </div>
    <?php

    delete_transient('spam_guard_activation_notice');
});

/* -----------------------------------------------------------
 * ADMIN MENU (Suite UI)
 * ----------------------------------------------------------- */
add_action('admin_menu', function () {
    add_menu_page(
        'Spam Guard',
        'Spam Guard',
        'manage_options',
        'spam-guard',
        'spam_guard_ui_dashboard',
        'dashicons-shield-alt',
        58
    );

    add_submenu_page('spam-guard', 'Dashboard', 'Dashboard', 'manage_options', 'spam-guard', 'spam_guard_ui_dashboard');
    add_submenu_page('spam-guard', 'Settings', 'Settings', 'manage_options', 'spam-guard-settings', 'spam_guard_ui_settings');
    add_submenu_page('spam-guard', 'Recommended', 'Recommended Plugins', 'manage_options', 'spam-guard-recommended', 'spam_guard_ui_recommended');
    add_submenu_page('spam-guard', 'CloudHashed', 'CloudHashed', 'manage_options', 'spam-guard-cloudhashed', 'spam_guard_ui_cloudhashed');
    add_submenu_page('spam-guard', 'About', 'About', 'manage_options', 'spam-guard-about', 'spam_guard_ui_about');
});

/* -----------------------------------------------------------
 * SUITE UI CSS
 * ----------------------------------------------------------- */
add_action('admin_head', function () {
    if (!isset($_GET['page']) || strpos($_GET['page'], 'spam-guard') === false) return;
    ?>
    <style>
        .sg-wrapper { display: flex; gap: 20px; }
        .sg-sidebar {
            width: 200px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            height: fit-content;
        }
        .sg-sidebar a {
            display: block;
            padding: 10px;
            text-decoration: none;
            margin-bottom: 8px;
            border-radius: 6px;
            color: #1d2327;
        }
        .sg-sidebar a:hover, .sg-sidebar .active {
            background: #2271b1;
            color: #fff !important;
        }
        .sg-content {
            flex: 1;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .sg-card {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .sg-btn {
            display: inline-block;
            padding: 8px 12px;
            background: #2271b1;
            color: #fff !important;
            border-radius: 4px;
            text-decoration: none;
        }
        .sg-btn:hover { background: #135e96; }
    </style>
    <?php
});

/* -----------------------------------------------------------
 * HELPER: Sidebar
 * ----------------------------------------------------------- */
function spam_guard_sidebar($active)
{
    $pages = [
        'spam-guard' => 'Dashboard',
        'spam-guard-settings' => 'Settings',
        'spam-guard-recommended' => 'Recommended Plugins',
        'spam-guard-cloudhashed' => 'CloudHashed',
        'spam-guard-about' => 'About'
    ];

    echo '<div class="sg-sidebar">';
    foreach ($pages as $slug => $label) {
        $class = $active === $slug ? 'active' : '';
        echo "<a class='$class' href='?page=$slug'>$label</a>";
    }
    echo '</div>';
}

/* -----------------------------------------------------------
 * DASHBOARD
 * ----------------------------------------------------------- */
function spam_guard_ui_dashboard()
{
    echo "<div class='sg-wrapper'>";
    spam_guard_sidebar('spam-guard');
    ?>
    <div class="sg-content">
        <h1>Spam Guard Suite</h1>
        <p>Your advanced comment security & anti-spam solution.</p>

        <div class="sg-card">
            <h2>Status</h2>
            <p>Spam Guard is active and protecting your comment forms.</p>
        </div>

        <div class="sg-card">
            <h2>Quick Actions</h2>
            <a class="sg-btn" href="?page=spam-guard-settings">Configure Settings</a>
            <a class="sg-btn" href="?page=spam-guard-recommended">Recommended Plugins</a>
            <a class="sg-btn" href="?page=spam-guard-cloudhashed">CloudHashed Services</a>
        </div>
    </div>
    </div>
    <?php
}

/* -----------------------------------------------------------
 * SETTINGS PAGE
 * ----------------------------------------------------------- */
function spam_guard_ui_settings()
{
    echo "<div class='sg-wrapper'>";
    spam_guard_sidebar('spam-guard-settings');
    ?>
    <div class="sg-content">
        <h1>Settings</h1>

        <form method="post" action="options.php">
            <?php
            settings_fields('spam_guard_settings');
            do_settings_sections('spam_guard');
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    </div>
    <?php
}

/* -----------------------------------------------------------
 * RECOMMENDED PLUGINS
 * ----------------------------------------------------------- */
function spam_guard_ui_recommended()
{
    echo "<div class='sg-wrapper'>";
    spam_guard_sidebar('spam-guard-recommended');
    ?>
    <div class="sg-content">
        <h1>Recommended Plugins</h1>

        <div class="sg-card">
            <h2>📌 Sticky Ad Lightweight</h2>
            <p>Boost ad revenue with a zero-CWV-impact sticky ad.</p>
            <a class="sg-btn" target="_blank" href="https://wordpress.org/plugins/sticky-ad-lightweight/">View Plugin →</a>
        </div>
    </div>
    </div>
    <?php
}

/* -----------------------------------------------------------
 * CLOUDHASHED PAGE
 * ----------------------------------------------------------- */
function spam_guard_ui_cloudhashed()
{
    echo "<div class='sg-wrapper'>";
    spam_guard_sidebar('spam-guard-cloudhashed');
    ?>
    <div class="sg-content">
        <h1>CloudHashed — Managed WordPress Support</h1>

        <div class="sg-card">
            <p>Professional WordPress management: performance, security, uptime, CDN, backups, optimization.</p>
            <a class="sg-btn" href="https://cloudhashed.com/" target="_blank">Visit CloudHashed →</a>
        </div>
    </div>
    </div>
    <?php
}

/* -----------------------------------------------------------
 * ABOUT PAGE
 * ----------------------------------------------------------- */
function spam_guard_ui_about()
{
    echo "<div class='sg-wrapper'>";
    spam_guard_sidebar('spam-guard-about');
    ?>
    <div class="sg-content">
        <h1>About Spam Guard</h1>

        <div class="sg-card">
            <p>Created to combat modern spam bots, prevent automated submissions and keep your site clean.</p>
            <p>Developer: <strong>Saurabh Guttedar</strong></p>
        </div>
    </div>
    </div>
    <?php
}

/* -----------------------------------------------------------
 * SETTINGS FIELDS
 * ----------------------------------------------------------- */
add_action('admin_init', function () {
    register_setting('spam_guard_settings', 'spam_guard_disable_url_field');
    register_setting('spam_guard_settings', 'spam_guard_strip_links');

    add_settings_section('spam_guard_main', '', null, 'spam_guard');

    add_settings_field('disable_url', 'Disable Website Field', function () {
        echo '<input type="checkbox" name="spam_guard_disable_url_field" value="1" ' . checked(1, get_option('spam_guard_disable_url_field', 1), false) . '> Remove the "Website" field';
    }, 'spam_guard', 'spam_guard_main');

    add_settings_field('strip_links', 'Strip Links in Comments', function () {
        echo '<input type="checkbox" name="spam_guard_strip_links" value="1" ' . checked(1, get_option('spam_guard_strip_links', 1), false) . '> Remove all links';
    }, 'spam_guard', 'spam_guard_main');
});

/* -----------------------------------------------------------
 * COMMENT PROTECTION ENGINE
 * ----------------------------------------------------------- */
$key = defined('NONCE_SALT') && NONCE_SALT ? NONCE_SALT : ABSPATH;
define('SPAM_GUARD_KEY', md5($key));

function spam_guard_should_run()
{
    if (!is_singular()) return false;
    if (!comments_open()) return false;

    if (function_exists('is_product') && is_product())
        return comments_open(get_the_ID());

    return true;
}

add_filter('comment_form_default_fields', function ($fields) {
    if (get_option('spam_guard_disable_url_field', 1)) unset($fields['url']);
    return $fields;
});

add_filter('preprocess_comment', function ($c) {
    if (get_option('spam_guard_strip_links', 1)) {
        $c['comment_content'] = preg_replace('#https?://\S+#', '', $c['comment_content']);
        $c['comment_content'] = preg_replace('#<a.*?>(.*?)</a>#i', '$1', $c['comment_content']);
    }
    return $c;
});

add_filter('comment_form_defaults', function ($d) {
    if (spam_guard_should_run()) $d['action'] = '';
    return $d;
});

add_action('wp_footer', function () {
    if (!spam_guard_should_run()) return; ?>
    <script>
        let c = document.querySelector("#commentform,#ast-commentform,#fl-comment-form,#ht-commentform");
        if (c) document.addEventListener("scroll", ()=>c.action="<?php echo site_url(); ?>/wp-comments-post.php?<?php echo SPAM_GUARD_KEY; ?>");
    </script>
<?php });

add_action('init', function () {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
    if (strpos($_SERVER['REQUEST_URI'], 'wp-comments-post.php') === false) return;

    $q = $_SERVER['QUERY_STRING'] ?? '';
    $ref = $_SERVER['HTTP_REFERER'] ?? '';
    $valid = ($q === SPAM_GUARD_KEY) && strpos($ref, home_url()) !== false;

    if (!$valid) {
        header("HTTP/1.1 400 Bad Request");
        wp_die("<h1>Error 400</h1><p>Invalid comment request.</p>");
    }
});
