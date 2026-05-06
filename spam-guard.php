<?php
/**
 * Plugin Name: Spam Guard
 * Description: Anti-spam protection with a modern interface, link stripping, hardened comment submission, WooCommerce support and promotional recommendations.
 * Author: Saurabh Guttedar
 * Version: 2.0.0
 */

defined('ABSPATH') || exit;

/* -----------------------------------------------------------
 * ACTIVATION NOTICE (SHOWN ONCE)
 * ----------------------------------------------------------- */
register_activation_hook(__FILE__, function () {
    set_transient('spam_guard_activation_notice', true, 60);
});

add_action('admin_notices', function () {
    if (!get_transient('spam_guard_activation_notice'))
        return;

    ?>
    <div class="notice notice-success is-dismissible">
        <p><strong>Spam Guard activated!</strong> Please clear your cache (LiteSpeed, Cloudflare, WP Rocket, etc.) for
            changes to take effect.</p>
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
    if (!isset($_GET['page']) || strpos($_GET['page'], 'spam-guard') === false)
        return;
    ?>
    <style>
        .sg-wrapper {
            display: flex;
            gap: 20px;
        }

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

        .sg-sidebar a:hover,
        .sg-sidebar .active {
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

        .sg-btn:hover {
            background: #135e96;
        }

        .sg-promo-img {
            width: 100%;
            border-radius: 6px;
            margin-bottom: 12px;
            display: block;
        }
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
            <a href="https://warpperformance.com" target="_blank" rel="noopener">
                <img class="sg-promo-img" src="https://warpperformance.com/wp-content/uploads/warp-preview-2x.webp"
                    alt="Warp Performance">
            </a>
            <h2>Warp Performance</h2>
            <p><strong>WordPress at Warp Speed</strong></p>
            <p>Full cloud optimization solution — blazing-fast performance, CDN, edge caching, and more for WordPress sites.
            </p>
            <a class="sg-btn" target="_blank" rel="noopener" href="https://warpperformance.com">Visit Warp Performance →</a>
        </div>

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
    register_setting('spam_guard_settings', 'spam_guard_disable_self_links');
    register_setting('spam_guard_settings', 'spam_guard_honeypot');
    register_setting('spam_guard_settings', 'spam_guard_min_length', ['sanitize_callback' => 'absint']);
    register_setting('spam_guard_settings', 'spam_guard_block_url_names');

    add_settings_section('spam_guard_main', '', null, 'spam_guard');

    add_settings_field('disable_url', 'Disable Website Field', function () {
        echo '<input type="checkbox" name="spam_guard_disable_url_field" value="1" ' . checked(1, get_option('spam_guard_disable_url_field', 1), false) . '> Remove the "Website" field';
    }, 'spam_guard', 'spam_guard_main');

    add_settings_field('strip_links', 'Strip Links in Comments', function () {
        echo '<input type="checkbox" name="spam_guard_strip_links" value="1" ' . checked(1, get_option('spam_guard_strip_links', 1), false) . '> Remove all links';
    }, 'spam_guard', 'spam_guard_main');

    add_settings_field('disable_self_links', 'Disable Self-Linking in Comments', function () {
        echo '<input type="checkbox" name="spam_guard_disable_self_links" value="1" ' . checked(1, get_option('spam_guard_disable_self_links', 1), false) . '> Remove links in comment text that point to the commenter\'s own website';
    }, 'spam_guard', 'spam_guard_main');

    add_settings_field('honeypot', 'Honeypot Trap', function () {
        echo '<input type="checkbox" name="spam_guard_honeypot" value="1" ' . checked(1, get_option('spam_guard_honeypot', 1), false) . '> Add a hidden field to catch bots that fill all inputs automatically';
    }, 'spam_guard', 'spam_guard_main');

    add_settings_field('min_length', 'Minimum Comment Length', function () {
        $len = get_option('spam_guard_min_length', 10);
        echo '<input type="number" name="spam_guard_min_length" value="' . esc_attr($len) . '" min="0" style="width:65px"> characters (0 = disabled)';
    }, 'spam_guard', 'spam_guard_main');

    add_settings_field('block_url_names', 'Block URL-Stuffed Author Names', function () {
        echo '<input type="checkbox" name="spam_guard_block_url_names" value="1" ' . checked(1, get_option('spam_guard_block_url_names', 1), false) . '> Reject comments where the author name contains a URL';
    }, 'spam_guard', 'spam_guard_main');
});

/* -----------------------------------------------------------
 * COMMENT PROTECTION ENGINE
 * ----------------------------------------------------------- */
$key = defined('NONCE_SALT') && NONCE_SALT ? NONCE_SALT : ABSPATH;
define('SPAM_GUARD_KEY', md5($key));

function spam_guard_should_run()
{
    if (!is_singular())
        return false;
    if (!comments_open())
        return false;

    if (function_exists('is_product') && is_product())
        return comments_open(get_the_ID());

    return true;
}

add_filter('comment_form_default_fields', function ($fields) {
    if (get_option('spam_guard_disable_url_field', 1))
        unset($fields['url']);
    return $fields;
});

add_filter('preprocess_comment', function ($c) {
    if (get_option('spam_guard_strip_links', 1)) {
        $c['comment_content'] = preg_replace('#https?://\S+#', '', $c['comment_content']);
        $c['comment_content'] = preg_replace('#<a.*?>(.*?)</a>#i', '$1', $c['comment_content']);
    }

    if (get_option('spam_guard_disable_self_links', 1)) {
        $author_url = trim($c['comment_author_url'] ?? '');
        if ($author_url) {
            $host = parse_url($author_url, PHP_URL_HOST);
            if ($host) {
                $host_q = preg_quote($host, '#');
                $c['comment_content'] = preg_replace('#https?://' . $host_q . '\S*#i', '', $c['comment_content']);
                $c['comment_content'] = preg_replace('#<a[^>]+href=["\']https?://' . $host_q . '[^"\']*["\'][^>]*>(.*?)</a>#i', '$1', $c['comment_content']);
            }
        }
    }

    if (get_option('spam_guard_block_url_names', 1)) {
        $name = $c['comment_author'] ?? '';
        if (preg_match('#https?://#i', $name)) {
            wp_die('Your display name may not contain a URL.', 'Comment Blocked', ['back_link' => true, 'response' => 400]);
        }
    }

    $min = (int) get_option('spam_guard_min_length', 10);
    if ($min > 0 && mb_strlen(trim(wp_strip_all_tags($c['comment_content']))) < $min) {
        wp_die(
            sprintf('Your comment is too short. Please write at least %d characters.', $min),
            'Comment Too Short',
            ['back_link' => true, 'response' => 400]
        );
    }

    return $c;
});

add_action('comment_form_after_fields', function () {
    if (!get_option('spam_guard_honeypot', 1))
        return;
    echo '<p style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;" aria-hidden="true">'
        . '<label for="sg_trap">Leave this field empty</label>'
        . '<input type="text" id="sg_trap" name="sg_trap" value="" autocomplete="off" tabindex="-1">'
        . '</p>';
});

add_filter('comment_form_defaults', function ($d) {
    if (spam_guard_should_run())
        $d['action'] = '';
    return $d;
});

add_action('wp_footer', function () {
    if (!spam_guard_should_run())
        return; ?>
    <script>
        let c = document.querySelector("#commentform,#ast-commentform,#fl-comment-form,#ht-commentform");
        if (c) document.addEventListener("scroll", () => c.action = "<?php echo site_url(); ?>/wp-comments-post.php?<?php echo SPAM_GUARD_KEY; ?>");
    </script>
<?php });

add_action('init', function () {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        return;
    if (strpos($_SERVER['REQUEST_URI'], 'wp-comments-post.php') === false)
        return;

    if (get_option('spam_guard_honeypot', 1) && !empty($_POST['sg_trap'])) {
        header("HTTP/1.1 400 Bad Request");
        wp_die("<h1>Error 400</h1><p>Invalid comment request.</p>");
    }

    $q = $_SERVER['QUERY_STRING'] ?? '';
    $ref = $_SERVER['HTTP_REFERER'] ?? '';
    $valid = ($q === SPAM_GUARD_KEY) && strpos($ref, home_url()) !== false;

    if (!$valid) {
        header("HTTP/1.1 400 Bad Request");
        wp_die("<h1>Error 400</h1><p>Invalid comment request.</p>");
    }
});
