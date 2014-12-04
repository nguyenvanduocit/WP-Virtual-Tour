<?php

class CE_Welcome extends VG_Welcome
{
    function __construct()
    {
        parent::__construct();
        $this->title = 'Welcome to CE 1.8.3';
        $this->description = 'The new product from superman, batman, kick buttowski,  Phineas, Ferb and Heinz Doofenshmirtz. Now, check for what is new.';
        $this->pages = array(
            'ce-whatisnew' => array(
                'page_title' => 'What is new',
                'content_callback' => 'about_page_content',
                'is_visible' => false,
            ),
            'ce-about' => array(
                'page_title' => 'About CE',
                'content_callback' => 'about_page_content',
                'is_visible' => false,
            ),
            'ce-changelog' => array(
                'page_title' => 'Change log',
                'content_callback' => 'changelog_page_content',
                'is_visible' => false,
            )
        );
    }

    public function getPages()
    {
        return array_keys($this->pages);
    }

    public function changelog_page_content()
    {
        ?>
        <div class="changelog about-integrations">
            <h3><?php _e('WooCommerce REST API version 2', 'woocommerce'); ?></h3>

            <div class="wc-feature feature-section col three-col">
                <div>
                    <h4><?php _e('Introducing PUT/POST/DELETE methods', 'woocommerce'); ?></h4>

                    <p><?php _e('Update, delete and create orders, customers, products and coupons via the API.', 'woocommerce'); ?></p>
                </div>
                <div>
                    <h4><?php _e('Other enhancements', 'woocommerce'); ?></h4>

                    <p><?php _e('Resources can now be ordered by any field you define for greater control over returned results. v2 also introduces an endpoint for getting product categories from your store.', 'woocommerce'); ?></p>
                </div>
                <div class="last-feature">
                    <h4><?php _e('Webhooks', 'woocommerce'); ?></h4>

                    <p><?php _e('Trigger webhooks during events such as when an order is created. Opens up all kinds of external integration opportunities.', 'woocommerce'); ?></p>
                </div>
            </div>
        </div>
    <?php
    }

    public function about_page_content()
    {
        echo "this is About page";
    }
}


class CE_Pointer extends VG_Pointer
{
function __construct($excudePage = array())
{
    $this->afteractived = array(
        'selector' => '#toplevel_page_et-overview',
        'content' => '<h3>' . __('Dashboard', 'wordpress-seo') . '</h3><p>' . __('This is the WordPress SEO Dashboard, here you can restart this tour or revert the WP SEO settings to default.', 'wordpress-seo') . '</p>'
            . '<p><strong>' . __('More WordPress SEO', 'wordpress-seo') . '</strong><br/>' . sprintf(__('There&#8217;s more to learn about WordPress &amp; SEO than just using this plugin. A great start is our article %1$sthe definitive guide to WordPress SEO%2$s.', 'wordpress-seo'), '<a target="_blank" href="' . esc_url('https://yoast.com/articles/wordpress-seo/#utm_source=wpseo_dashboard&utm_medium=wpseo_tour&utm_campaign=tour') . '">', '</a>') . '</p>',
        'action_buttons' => array(
            array(
                "text" => "Begin tour",
                "function" => 'window.location="' . admin_url('admin.php?page=et-overview') . '";',
            )
        ),
        'position' => array('edge' => 'top', 'align' => 'center'),
    );

    $this->new_pointer = array(
        'et-overview' => array(
            array(
                'selector' => '#page_title',
                'content' => '<h3>' . __('Overview adfasf', 'wordpress-seo') . '</h3><p>' . __('In this page, you can take a overview to all your system.', 'wordpress-seo') . '</p>',
                'action_buttons' => array(
                    array(
                        "text" => "Next",
                        "function" => 'nextPointer();',
                    )
                ),
                'position' => array('edge' => 'top', 'align' => 'center'),
            )
        )
    );

    $this->pointers = array(
        'et-overview' => array(
            array(
                'selector' => '#page_title',
                'content' => '<h3>' . __('Overview', 'wordpress-seo') . '</h3><p>' . __('In this page, you can take a overview to all your system.', 'wordpress-seo') . '</p>',
                'action_buttons' => array(
                    array(
                        "text" => "Next",
                        "function" => 'navToPage("' . admin_url('admin.php?page=et-settings') . '");',
                    )
                ),
                'position' => array('edge' => 'top', 'align' => 'center'),
            )
        ),
        'et-settings' => array(
            array(
                'selector' => '#page_title',
                'content' => '<h3>' . __('Setting page', 'wordpress-seo') . '</h3><p>' . __('You can see all you settings.', 'wordpress-seo') . '</p>',
                'action_buttons' => array(
                    array(
                        "text" => "next",
                        "function" => 'nextPointer();',
                    )
                ),
                'position' => array('edge' => 'top', 'align' => 'left'),
            ),
            array(
                'selector' => '#general_session',
                'content' => '<h3>' . __('General settings', 'wordpress-seo') . '</h3><p>' . __('You can see all you settings.', 'wordpress-seo') . '</p>',
                'action_buttons' => array(
                    array(
                        "text" => "Previous",
                        "function" => 'previousPointer();',
                    ),
                    array(
                        "text" => "next",
                        "function" => 'nextPointer();',
                    )
                ),
                'position' => array('edge' => 'left', 'align' => 'center'),
            ), array(
                'selector' => '#branding_section',
                'content' => '<h3>' . __('General settings', 'wordpress-seo') . '</h3><p>' . __('You can nav to url by using navToPage(URL).', 'wordpress-seo') . '</p>',
                'action_buttons' => array(
                    array(
                        "text" => "WordPressKite.com",
                        "function" => 'navToPage("http://laptrinh.senviet.org");',
                    ),
                    array(
                        "text" => "next",
                        "function" => 'nextPointer();',
                    )
                ),
                'position' => array('edge' => 'left', 'align' => 'center'),
            ), array(
                'selector' => '#social_section',
                'content' => '<h3>' . __('Social settings', 'wordpress-seo') . '</h3><p>' . __('You can use javascript to change page title.', 'wordpress-seo') . '</p>',
                'action_buttons' => array(
                    array(
                        "text" => "Change title",
                        "function" => '$("#page_title").text("Changed")',
                    ), array(
                        "text" => "next",
                        "function" => 'nextPointer();',
                    )
                ),
                'position' => array('edge' => 'left', 'align' => 'center'),
            ), array(
                'selector' => 'input[name*="site_desc"',
                'content' => '<h3>' . __('Branding settings', 'wordpress-seo') . '</h3><p>' . __('Write down some Sample value', 'wordpress-seo') . '</p>',
                'action_buttons' => array(),
                'position' => array('edge' => 'right', 'align' => 'left'),
                'action_buttons' => array(
                    array(
                        "text" => "Sample value",
                        "function" => '$("input[name*=\'site_desc\']").val("This is sample data")',
                    ),
                    array(
                        "text" => "next",
                        "function" => 'navToPage("' . admin_url('admin.php?page=et-payments') . '");',
                    )
                )
            ),
        ),
        'et-payments' => array(
            array(
                'selector' => '#et-head-statistics',
                'content' => '<h3>' . __('Your site payment static', 'wordpress-seo') . '</h3><p>' . __('You can see all you settings.', 'wordpress-seo') . '</p>',
                'position' => array('edge' => 'right', 'align' => 'center'),
            ),
        )
    );
    $this->option_key = 'et_options';
    $this->excludePages = $excudePage;
    parent::__construct();
}
}

class CE_Notice extends VG_Notice
{
    public function __construct()
    {
        //$this->theme_key = 'classifiedengine';
        $this->theme_key = 'classifiedengine';
        $this->new_theme_message = 'New theme update';
        parent::__construct();
    }
}

    if (is_admin()) {
        $welcome = new CE_Welcome();
    }