<?php
/**
 * WooCommerce Breadcrumb Permalinks Settings Class
 *
 * @package   WooCommerce Breadcrumb Permalinks
 * @author    Captain Theme <info@captaintheme.com>
 * @license   GPL-2.0+
 * @link      http://captaintheme.com
 * @copyright 2014 Captain Theme
 * @since     1.1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * WCBP Settings Class
 *
 * @package  WooCommerce Breadcrumb Permalinks
 * @author   Captain Theme <info@captaintheme.com>
 * @since    1.1.1
 */

class WCBP_Settings {

	protected static $instance = null;
    
    /**
     * Constructor.
     */
    private function __construct() {

		add_action( 'admin_init', array( $this, 'permalinks_settings' ) );
		add_action( 'admin_init', array( $this, 'permalinks_save' ) );

	}

	/**
	 * Start the Class when called
	 *
	 * @package WooCommerce Breadcrumb Permalinks
	 * @author  Captain Theme <info@captaintheme.com>
	 * @since   1.1.1
	 */

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	/**
	 *  Register and define the settings
	 *
	 * @package WooCommerce Breadcrumb Permalinks
	 * @author  Captain Theme <info@captaintheme.com>
	 * @since   1.1.1
	 */
	public function permalinks_settings() {

		register_setting(
			'permalink',
			'wcbp_permalinks_base',
			'esc_attr'
		);
		
		add_settings_field(
			'wcbp_the_base',
			'Shop Permalinks Base',
			array( $this, 'setting_input' ),
			'permalink',
			'woocommerce-permalink'
		);

        //Pagination Custom field
        register_setting(
            'permalink_pagination',
            'wcbp_permalink_pagination',
            'esc_attr'
        );

        add_settings_field(
            'permalink_pagination',
            'Pagination Permalinks Base',
            array( $this, 'setting_input_pagination' ),
            'permalink',
            'woocommerce-permalink'
        );

	}
        
        /**
	 *  Display and fill the form field
	 *
	 * @package WooCommerce Breadcrumb Permalinks
	 * @author  Captain Theme <info@captaintheme.com>
	 * @since   1.1.1
	 */
	public function setting_input() {

		$options = get_option( 'wcbp_permalinks_base' );

		if ( ISSET( $options ) ) {
			$value = $options;
		} else {
			$value = '';
		}

		
		?>

		<input name="the_permalink_base" id="wcbp_permalink_base" type="text" value="<?php echo esc_attr( $value ); ?>" class="regular-text code" /> 
		<?php
		echo '<span style="font-size: 13px; font-style: italic;">';
		_e( 'Enter the custom base to use, defined above in the Products Permalink Base settings, eg. products, items. If using \'shop\', you may leave blank.' );
		echo '</span>';

		echo '<p style="padding-top: 10px;">' . __( 'Please Note: The <strong>Product Permalink Base</strong> above must be ' ) . '<code>/your-shop-permalinks-base/%product_cat%/</code>,' . __( 'where <strong>your-shop-permalinks-base</strong> is the same as this <strong>Shop Permalinks Base</strong> option.' ) . '</p>';

	}

    /** Pagination Permalinks Callback
     *  Display and fill the form field
     *
     * @package WooCommerce Breadcrumb Permalinks
     * @author  Captain Theme <info@captaintheme.com>
     * @since   1.1.1
     */
    public function setting_input_pagination() {

        $options_pagination = get_option( 'wcbp_permalink_pagination' );

        if ( ISSET( $options_pagination ) ) {
            $value = $options_pagination;
        } else {
            $value = '';
        }

        //Pagination Permalinks Base
        ?>
        <input name="wcbp_permalink_pagination" id="wcbp_permalink_pagination" type="text" value="<?php echo esc_attr( $value ); ?>" class="regular-text code" />
        <?php
        echo '<span style="font-size: 13px; font-style: italic;">';
        _e( 'Enter the custom base to use, if you are using "page" you may leave blank.' );
        echo '</span>';

    }


    /**
	 * Save the permalinks.
	 * We need to save the options ourselves; settings api does not trigger save for the permalinks page
	 * @since 1.1.1
	 */
	public function permalinks_save() {
		if ( ! is_admin() )
			return;

		// We need to save the options ourselves; settings api does not trigger save for the permalinks page
		if ( isset( $_POST['the_permalink_base'] ) ) {

			$wcbp_base = wc_clean( $_POST['the_permalink_base'] );

			$permalinks = get_option( 'wcbp_permalinks_base' );
			if ( ! $permalinks ) {
				$permalinks = array();
			}

			$permalinks = untrailingslashit( $wcbp_base );
			$permalinks = preg_replace( '/\s+/', '', $permalinks );

			update_option( 'wcbp_permalinks_base', $permalinks );
		}

        // We need to save the options ourselves; settings api does not trigger save for the pagination permalinks page
        if ( isset( $_POST['wcbp_permalink_pagination'] ) ) {

            $wcbp_base_pagination = wc_clean( $_POST['wcbp_permalink_pagination'] );

            $permalinks_pagination = get_option( 'wcbp_permalink_pagination' );
            if ( ! $permalinks_pagination ) {
                $permalinks_pagination = array();
            }

            $permalinks_pagination = untrailingslashit( $wcbp_base_pagination );
            $permalinks_pagination = preg_replace( '/\s+/', '', $permalinks_pagination );

            update_option( 'wcbp_permalink_pagination', $permalinks_pagination );
        }

	}
}
