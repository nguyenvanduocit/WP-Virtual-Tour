<?php

/**
 * Project : classifiedengine
 * User: thuytien
 * Date: 12/1/2014
 * Time: 9:02 AM
 */
if(!class_exists('VG_Welcome')):
class VG_Welcome
{

    protected $pages = array();
    protected $currentpage =array();

    protected $title;
    protected $description;

    public function __construct()
    {
        $this->title = 'Virtual Guide';
        $this->description = 'Virtual guide is a lib for theme to show welcome page, featured pointer.';
        $this->pages = array(
            'vg_about' => array(
                'page_title' => 'Virtual Guide',
                'menu_title' => 'Virtual Guide',
                'is_visible' => false,
                'page_content' => 'default_page',
                'style' => array(),
                'script' => array()
            )
        );
        $this->register();
    }

    public function register()
    {
        add_action('admin_menu', array($this, 'admin_menus'));
        add_action('admin_head', array($this, 'admin_head'));
        add_action('admin_init', array($this, 'welcome'));

        add_action('after_switch_theme', array($this, 'theme_active'), 10, 2);
    }

    function theme_active($newTheme, $oldTheme)
    {
        set_transient('_ce_activation_redirect','true',60*60);
    }

    public function admin_menus()
    {
        if (empty($_GET['page'])) {
            return;
        }

        $page = $_GET['page'];
        if (array_key_exists($page, $this->pages)) {
            $this->currentpage = $this->pages[$page];
            $this->currentpage['slug'] = $page;
        }
        foreach ($this->pages as $slug => $page) {
            $page = add_dashboard_page($page['page_title'], ( array_key_exists('menu_title',$page) ? $page['menu_title'] : $page['page_title']), 'manage_options', $slug, array($this, 'output_page'));
            add_action('admin_print_styles-' . $page, array($this, 'admin_style'));
            add_action('admin_print_scripts-' . $page, array($this, 'admin_script'));
        }
    }

    /**
     * remove link form admin menu to advoid user access this page muaualy
     */
    public function admin_head()
    {
        foreach ($this->pages as $slug => $page) {
            if (!$page['is_visible']) {
                remove_submenu_page('index.php', $slug);
            }
        }
    }

    /**
     * Add css to admin page
     */
    public function admin_style()
    {
        if (array_key_exists('style', $this->currentpage)) {
            $styles = $this->currentpage['style'];
            foreach ($styles as $index => $style) {
                wp_enqueue_style('vg_welcome_style_' . $index, $style);
            }
        }
    }

    /**
     * Add script to admin page
     */
    public function admin_script()
    {
        if (array_key_exists('script', $this->currentpage)) {
            $styles = $this->currentpage['script'];
            foreach ($styles as $index => $script) {
                wp_enqueue_script('vg_welcome_script_' . $index, $script);
            }
        }
    }

    public function output_page()
    {
        if (!empty($_GET['ce-updated']) || !empty($_GET['ce-installed'])) {
            flush_rewrite_rules();
        }
        ?>
        <div class="wrap about-wrap">
            <?php $this->page_head(); ?>
            <?php $this->page_content(); ?>
            <?php $this->page_footer(); ?>
        </div>
    <?php
    }

    /**
     * header content
     */
    protected function page_head()
    {
        ?>
        <h1><?php _e($this->title) ?></h1>
        <div class="about-text vg-about-text">
            <?php
            _e($this->description);
            ?>
        </div>
        <h2 class="nav-tab-wrapper">
            <?php $current_Slug = $this->currentpage['slug']; ?>
            <?php foreach ($this->pages as $slug => $page): ?>
                <a class="nav-tab <?php if ($slug == $current_Slug) echo 'nav-tab-active'; ?>"
                   href="<?php echo esc_url(admin_url(add_query_arg(array('page' => $slug), 'index.php'))); ?>">
                    <?php _e($page['page_title']); ?>
                </a>
            <?php endforeach; ?>
        </h2>
    <?php
    }

    /**
     * Footer content
     */
    protected function page_footer()
    {
    }

    /**
     * Body content
     */
    protected function page_content()
    {
        $handler = $this->currentpage['content_callback'];
        if (method_exists($this, $handler)) {
            call_user_func(array($this, $handler));
        }
    }

    public function default_page()
    {
        echo "Default page";
    }

    /**
     * Sends user to the welcome page on first activation
     */
    public function welcome()
    {
        // Bail if no activation redirect transient is set
        if (!get_transient('_ce_activation_redirect')) {
            return;
        }

        // Delete the redirect transient
        delete_transient('_ce_activation_redirect');

        // Bail if activating from network, or bulk, or within an iFrame
        if (is_network_admin() || isset($_GET['activate-multi']) || defined('IFRAME_REQUEST')) {
            return;
        }
        $page_keys = array_keys($this->pages);
        wp_redirect(admin_url('index.php?page='.$page_keys[0]));
        exit;
    }
}
endif;