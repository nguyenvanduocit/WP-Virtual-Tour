<?php
/**
 * Project : classifiedengine
 * User: thuytien
 * Date: 12/1/2014
 * Time: 9:04 AM
 */
if (!class_exists('VG_Notice')):
    class VG_Notice
    {
        protected $theme_key;
        protected $new_theme_message;

        public function __construct()
        {
            $this->isNewVersionAvailble();
        }

        function isNewVersionAvailble()
        {
            $update_themes = get_site_transient('update_themes');
            if (isset($update_themes->response)) {
                if (array_key_exists($this->theme_key, $update_themes->response)) {
                    add_action( 'admin_notices', array($this,'theme_update_messate') );
                }
            }
        }

        public function theme_update_messate()
        {
            ?>
            <div class="updated">
                <p><?php echo $this->new_theme_message; ?></p>
            </div>
            <?php
        }
    }
endif;