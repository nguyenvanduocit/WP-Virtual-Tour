<?php
if (!class_exists('VG_Pointer')):
    /**
     * This class handles the pointers used in the introduction tour.
     *
     * @todo Add an introductory pointer on the edit post page too.
     */
    class VG_Pointer
    {
        public static $instance;
        /**
         * Class constructor.
         */
        protected $option_key;
        protected $pointers;
        protected $old_pointer = array();
        protected $new_pointer = array();
        protected $afteractived;
        protected $excludePages;

        protected static $INSTALLED_OPTION_KEY = 'vg_isUpDate';
        public function __construct()
        {
            if (current_user_can('manage_options')) {
                $options = get_option($this->option_key);

                add_action('after_switch_theme', array($this, 'theme_active'), 10, 2);

                //restart tour

                if (isset($_GET['vg_restart_tour'])) {
                    $options['ignore_tour'] = false;
                    $options[self::$INSTALLED_OPTION_KEY] = false;
                    update_option($this->option_key, $options);
                }

                $page = isset($_GET['page']) ? $_GET['page'] : '';

                //Check if begin tour
                if (!in_array($page, $this->excludePages)) {
                    if (!isset($options['ignore_tour']) || $options['ignore_tour'] === false) {
                        add_action('admin_footer', array($this, 'vg_script_style'));
                        add_action('wp_ajax_vg_set_ignore', array($this, 'vg_set_ignore'));
                        add_action('admin_print_footer_scripts', array($this, 'intro_tour'));
                    }
                }
            }
        }
        public function vg_script_style(){
            wp_enqueue_style('wp-pointer');
            wp_enqueue_script('jquery-ui');
            wp_enqueue_script('wp-pointer');
            wp_enqueue_script('utils');
        }
        function theme_active($newTheme, $oldTheme)
        {
            $options = array();
            $options['ignore_tour'] = false;
            update_option($this->option_key, $options);
        }
        function vg_set_ignore()
        {
            if (!current_user_can('manage_options')) {
                die('-1');
            }
            check_ajax_referer('vg-ignore');
            $options = get_option($this->option_key);
            $ignore_key = sanitize_text_field($_POST['option']);
            $options['ignore_' . $ignore_key] = true;
            $options[self::$INSTALLED_OPTION_KEY] = true;
            update_option($this->option_key, $options);
            die('1');
        }

        public static function get_instance()
        {
            if (!(self::$instance instanceof self)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Load the introduction tour
         */
        function intro_tour()
        {
            global $pagenow;

            $page = '';

            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            }

            $options = get_option($this->option_key);
            if(!isset($options[self::$INSTALLED_OPTION_KEY]) || $options[self::$INSTALLED_OPTION_KEY] === false )
            {
                //new install
                $this->pointers = array_merge_recursive($this->new_pointer, $this->pointers);
            }

            $pointers = array();

            if ('admin.php' != $pagenow || !array_key_exists($page, $this->pointers)) {
                //After actived
                $pointers = array($this->afteractived);
            } else {
                if ('' != $page && in_array($page, array_keys($this->pointers))) {
                    $pointers = $this->pointers[$page];
                }
            }
            $this->print_scripts($pointers);
        }

        /**
         * Prints the pointer script
         *
         * @param string $selector The CSS selector the pointer is attached to.
         * @param array $options The options for the pointer.
         * @param array $button_array The options for the buttons.
         */
        function print_scripts($pointers, $defer_loading = false)
        {
            ?>
            <script type="text/javascript">
                //<![CDATA[
                var vg_pointer = (function ($) {
                    // Don't show the tour on screens with an effective width smaller than 1024px or an effective height smaller than 768px.
                    if (jQuery(window).width() < 1024 || jQuery(window).availWidth < 1024) {
                        return;
                    }

                    var pointers = <?php echo json_encode( $pointers ); ?>;
                    var defer_loading = <?php echo ($defer_loading)?'true':'false' ?>;
                    var current_index = 0;
                    var currentPointer;
                    var function_list = {
                        <?php
                        foreach($pointers as $pointer_index => $pointer):
                            if(isset($pointer['action_buttons'])):
                                foreach($pointer['action_buttons'] as $index => $buton):
                                   ?>
                        action_function_<?php echo $pointer_index . '_' . $index; ?>: function () {
                            <?php echo $buton['function']; ?>
                        },
                        <?php
                    endforeach;
                endif;
            endforeach;
            ?>
                    };
                    var openPointer = function () {

                        var pointeroption = pointers[current_index];
                        pointeroption = $.extend(pointeroption, {
                            buttons: function (event, t) {
                                var button = jQuery('<a id="pointer-close" style="margin:0 5px;" class="button-secondary">close</a>');
                                return button;
                            }
                        });
                        $(pointeroption.selector).pointer(pointeroption).pointer('open');
                        currentPointer = pointeroption.selector;
                        if (pointeroption.action_buttons) {
                            addButtons(pointeroption.action_buttons);
                        }
                    };
                    var addButtons = function (buttons) {
                        for (var index = 0; index < buttons.length; index++) {
                            jQuery('#pointer-close').after('<a style="margin:0 5px;" data-index="' + current_index + '_' + index + '" class="button-primary pointer-action-button">' + buttons[index].text + '</a>');

                        }
                    };
                    var nextPointer = function () {
                        if (current_index < pointers.length - 1) {
                            $(currentPointer).pointer('destroy');
                            current_index++;
                            openPointer();
                        }
                    };

                    var previousPointer = function () {
                        if (current_index > 0) {
                            $(currentPointer).pointer('destroy');
                            current_index--;
                            openPointer();
                        }
                    };
                    var navToPage = function (url) {
                        window.location = url;
                    };
                    var setup = function () {
                        openPointer();
                        $(document).on('click', '#pointer-close', function () {
                            $.post(ajaxurl, {
                                    action: 'vg_set_ignore',
                                    option: 'tour',
                                    _wpnonce: '<?php echo esc_js( wp_create_nonce( 'vg-ignore' ) ); ?>'
                                }
                                , function (data) {
                                    if (data == 1) {
                                        $(currentPointer).pointer('destroy');
                                    }
                                    else {
                                        console.log('Error');
                                    }
                                }
                            );
                        });

                        $(document).on('click', 'a.pointer-action-button', function () {
                            var point_index = $(this).data("index");
                            var fnstring = 'action_function_' + point_index;
                            function_list[fnstring]();

                        });
                    }

                    if (defer_loading) {
                        $(window).bind('load.wp-pointers', setup);
                    }
                    else {
                        $(document).ready(setup);
                    }
                })(jQuery);
                //]]>
            </script>
        <?php
        }

    } /* End of class */
endif;