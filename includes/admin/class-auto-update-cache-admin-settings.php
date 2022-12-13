<?php

class A_u_cache_Admin_Settings
{

    /**
     * A_u_cache_Admin_Settings Constructor.
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
        add_action( 'wp_ajax_pbc_update_clear_cache_time', array( $this, 'update_clear_cache_time' ) );
    }

    /**
     * Add options page.
     */
    public function add_plugin_page()
    {
        add_options_page(
            __( 'Auto Update Cache', 'auto-update-cache' ),
            __( 'Auto Update Cache', 'auto-update-cache' ),
            'manage_options',
            'auto-update-cache',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback.
     */
    public function create_admin_page()
    {
        // Set class property

        ?>
        <div class="wrap">
            <h1 class="text-center">Auto update cache</h1>
            <div id="pbc_notices">
                <div class="updated settings-error notice pbc-notice pbc-notice-update-caching-time" style="display: none">
                    <p><strong><?php _e( 'Completed, Your site is now updated.', 'auto-update-cache' ); ?></strong></p>
                    <button type="button" class="notice-dismiss" onclick="pbc_close_notice(this)"><span class="screen-reader-text"><?php _e( 'Dismiss this notice.' ); ?></span></button>
                </div>
            </div>
            <button class="accordion">Settings</button>
            <div class="panel" style="display:block">
              <form method="post" action="<?php echo admin_url( 'options.php '); ?>">
                   <?php
                   do_settings_sections( 'auto-update-cache' );
                   settings_fields( 'A_u_cache_options_group' );
                   ?>
              </form>
            </div>

            <button class="accordion">Update center</button>
            <div class="panel">
                <h2>More functions is on the way...</h2>
                <p>If this plugin helped you, Please consider giving it ratings! thank you :)</p>
            </div>

            <!--
            <button class="accordion"></button>
            <div class="panel">

            </div> -->

            <script>
            var acc = document.getElementsByClassName("accordion");
            var i;

            for (i = 0; i < acc.length; i++) {
                acc[i].addEventListener("click", function() {
                    this.classList.toggle("active");
                    var panel = this.nextElementSibling;
                    if (panel.style.display === "block") {
                        panel.style.display = "none";
                    } else {
                        panel.style.display = "block";
                    }
                });
            }
            </script>

        </div>
        <?php
    }

    /**
     * Register and add settings.
     */
    public function page_init()
    {
        register_setting(
            'A_u_cache_options_group', // Option group
            'A_u_cache_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'A_u_cache_settings', // ID
            null, // Title
            null, // Callback
            'auto-update-cache' // Page
        );

        add_settings_field(
            'always_clear_cache',
            __( 'Automatic', 'auto-update-cache' ),
            array( $this, 'clear_cache_automatically_callback' ),
            'auto-update-cache',
            'A_u_cache_settings'
        );
        add_settings_field(
            'update_css_js_files',
            __( 'Manual', 'auto-update-cache' ),
            array( $this, 'clear_cache_manually_callback' ),
            'auto-update-cache',
            'A_u_cache_settings'
        );
    }

    /**
     * Sanitize each setting field as needed.
     *
     * @param $input
     * @return mixed
     */
    public function sanitize( $input )
    {
        return A_u_cache::instance()->filter_options( $input );
    }

    /**
     * Displays options to clear cache automatically.
     */
    public function clear_cache_automatically_callback()
    {
        $options = A_u_cache::instance()->get_options();
        $clear_cache_automatically = $options['clear_cache_automatically'];
        $clear_cache_automatically_minutes = $options['clear_cache_automatically_minutes'];
        ?>


        <label>
            <input type="radio" name="A_u_cache_options[clear_cache_automatically]" value="every_period"<?php echo $clear_cache_automatically == 'every_period' ? ' checked' : ''; ?> />
            <?php _e( 'After every', 'auto-update-cache' ); ?> <input type="number" name="A_u_cache_options[clear_cache_automatically_minutes]" value="<?php echo $clear_cache_automatically_minutes ?>" step="1" min="1" max="99999" style="width: 65px"> <?php _e( 'minutes', 'auto-update-cache' ); ?>
        </label><br>
        <label>
            <input type="radio" name="A_u_cache_options[clear_cache_automatically]" value="never"<?php echo $clear_cache_automatically == 'never' ? ' checked' : ''; ?> />
            <?php _e( 'Do not update automatically', 'auto-update-cache' ); ?>
        </label><br>


        <?php
         submit_button();

    }

    /**
     * Displays options to clear cache manually.
     */
    public function clear_cache_manually_callback()
    {
        $options = A_u_cache::instance()->get_options();
        $show_on_toolbar = $options['show_on_toolbar'];
        ?>
         <button class="button" onclick="pbc_update_clear_cache_time(this)"><?php _e( 'Update CSS and JS files now', 'auto-update-cache' ); ?></button>

         <script>
             function pbc_close_notice(element) {
                 jQuery(element).parents('.pbc-notice').fadeOut('fast');
             }

             function pbc_update_clear_cache_time( element ) {
                 var update_button = jQuery( element );

                 var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';

                 var data = {
                     action: 'pbc_update_clear_cache_time',
                     nonce: '<?php echo wp_create_nonce( 'pbc_update_clear_cache_time' ) ?>'
                 };

                 update_button.attr('disabled', true);
                 jQuery.post(ajax_url, data, function() {
                     update_button.attr('disabled', false );
                     jQuery('.pbc-notice-update-caching-time').hide().addClass('is-dismissible').fadeIn('fast');
                 });
             }
         </script>

         <?php
    }

    /**
     * Ajax actions to clear cache manually.
     */
    public function update_clear_cache_time()
    {
        check_ajax_referer( 'pbc_update_clear_cache_time', 'nonce' );

        update_option( 'A_u_cache_clear_cache_time', time() );

        exit;
    }

    /**
     * Actions to clear cache when file is edited.
     */
    // public function update_file_edited()
    // {
    //
    //   add_filter( 'style_loader_src', 'autover_version_filter' );
    //   add_filter( 'script_loader_src', 'autover_version_filter' );
    //   function autover_version_filter( $src ) {
    //   	$url_parts = wp_parse_url( $src );
    //
    //   	$extension = pathinfo( $url_parts['path'], PATHINFO_EXTENSION );
    //   	if ( ! $extension || ! in_array( $extension, [ 'css', 'js' ] ) ) {
    //   		return $src;
    //   	}
    //
    //   	if ( defined( 'AUTOVER_DISABLE_' . strtoupper( $extension ) ) ) {
    //   		return $src;
    //   	}
    //
    //   	$file_path = rtrim( ABSPATH, '/' ) . urldecode( $url_parts['path'] );
    //   	if ( ! is_file( $file_path ) ) {
    //   		return $src;
    //   	}
    //
    //   	$timestamp_version = filemtime( $file_path ) ?: filemtime( utf8_decode( $file_path ) );
    //   	if ( ! $timestamp_version ) {
    //   		return $src;
    //   	}
    //
    //   	if ( ! isset( $url_parts['query'] ) ) {
    //   		$url_parts['query'] = '';
    //   	}
    //
    //   	$query = [];
    //   	parse_str( $url_parts['query'], $query );
    //   	unset( $query['v'] );
    //   	unset( $query['ver'] );
    //   	$query['ver']       = "$timestamp_version";
    //   	$url_parts['query'] = build_query( $query );
    //
    //   	return autover_build_url( $url_parts );
    //   }
    // }
    //
    // function autover_build_url( array $parts ) {
    // 	return ( isset( $parts['scheme'] ) ? "{$parts['scheme']}:" : '' ) .
    // 		   ( ( isset( $parts['user'] ) || isset( $parts['host'] ) ) ? '//' : '' ) .
    // 		   ( isset( $parts['user'] ) ? "{$parts['user']}" : '' ) .
    // 		   ( isset( $parts['pass'] ) ? ":{$parts['pass']}" : '' ) .
    // 		   ( isset( $parts['user'] ) ? '@' : '' ) .
    // 		   ( isset( $parts['host'] ) ? "{$parts['host']}" : '' ) .
    // 		   ( isset( $parts['port'] ) ? ":{$parts['port']}" : '' ) .
    // 		   ( isset( $parts['path'] ) ? "{$parts['path']}" : '' ) .
    // 		   ( isset( $parts['query'] ) ? "?{$parts['query']}" : '' ) .
    // 		   ( isset( $parts['fragment'] ) ? "#{$parts['fragment']}" : '' );
    // }

}

new A_u_cache_Admin_Settings();
