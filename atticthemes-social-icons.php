<?php
/*
Plugin Name: AtticThemes: Social Icons
Description: AtticThemes Social Icons provides you with a unique and user friendly UI to build sets of social icons that can be used in any shortcode enabled area like post or page contents. There is no restriction on the number of sets you can have, nor the number of icons you may add to a single set. Each icon can have its separate link, making it possible to use the same icon for unlimited number of users.
Version: 2.1.1
Author: atticthemes
Author URI: http://themeforest.net/user/atticthemes?ref=atticthemes
License: GPLv2 or later
*/
?>
<?php
if( !class_exists('AttichThemes_Social') ) {
	class AttichThemes_Social {
		public $version = '2.1.1';

		public static $icons = array();
		public static $icon_sizes = array();

		public $shortcode_tag = 'atsi';
		public $help_tabs = array();

		private $dev = false;
		private $min_suffix = '.min';

		public $options_page;

		public static function addIcon( $icon, $title = null ) {
			if( is_array($icon) ) {
				self::$icons = array_merge( self::$icons, $icon );
			} elseif( is_string($icon) && isset($title) ) {
				self::$icons[ $icon ] = $title;
			}
		}

		public static function addSize( $size, $name = null ) {
			if( is_array($size) ) {
				self::$icons = array_merge( self::$icon_sizes, $size );
			} elseif( is_string($size) && isset($name) ) {
				self::$icon_sizes[ $size ] = $name;
			}
		}

		public static function Init() {
			if( function_exists('do_action') ) {
				do_action( 'atsi_before_init' );
			}

			new AttichThemes_Social();

			if( function_exists('do_action') ) {
				do_action( 'atsi_after_init' );
			}
		}

		public function __construct() {
			if( $this->dev ) {
				$this->min_suffix = '';
			}

			/* setup icons data */
			self::$icons = array_merge( self::$icons, array(
					'fivehundredpx' => '500px',
					'aboutme' => 'about.me',
					'addme' => 'Add Me',
					'amazon' => 'Amazon',
					'aol' => 'AOL',
					'appstorealt' => 'App Store',
					'appstore' => 'App Store',
					'apple' => 'Apple',
					'bebo' => 'Bebo',
					'behance' => 'Behance',
					'bing' => 'Bing',
					'blip' => 'Blip',
					'blogger' => 'Blogger',
					'coroflot' => 'Coroflot',
					'delicious' => 'Delicious',
					'designbump' => 'Design Bump',
					'designfloat' => 'DesignFloat',
					'deviantart' => 'DeviantART',
					'diggalt' => 'Digg',
					'digg' => 'Digg',
					'dribble' => 'Dribbble',
					'drupal' => 'Drupal',
					'ebay' => 'eBay',
					'email' => 'Email',
					'emberapp' => 'Ember',
					'etsy' => 'Etsy',
					'facebook' => 'Facebook',
					'feedburner' => 'FeedBurner',
					'flickr' => 'Flickr',
					'foodspotting' => 'Foodspotting',
					'foursquare' => 'Foursquare',
					'friendsfeed' => 'FriendFeed',
					'github' => 'GitHub',
					'githubalt' => 'GitHub',
					'googleplus' => 'Google Plus',
					'grooveshark' => 'Grooveshark',
					'heart' => 'Heart',
					'icq' => 'ICQ',
					'imessage' => 'iMessage',
					'itunes' => 'iTunes',
					'lastfm' => 'Last.fm',
					'linkedin' => 'LinkedIn',
					'metacafe' => 'Metacafe',
					'msn' => 'MSN',
					'myspace' => 'Myspace',
					'newsvine' => 'Newsvine',
					'paypal' => 'PayPal',
					'photobucket' => 'Photobucket',
					'picasa' => 'Picasa',
					'pinterest' => 'Pinterest',
					'quora' => 'Quora',
					'reddit' => 'Reddit',
					'rss' => 'RSS',
					'scribd' => 'Scribd',
					'sharethis' => 'ShareThis',
					'skype' => 'Skype',
					'slashdot' => 'Slashdot',
					'slideshare' => 'Slideshare',
					'smugmug' => 'SmugMug',
					'soundcloud' => 'SoundCloud',
					'spotify' => 'Spotify',
					'squidoo' => 'Squidoo',
					'stackoverflow' => 'Stack Overflow',
					'stumbleupon' => 'StumbleUpon',
					'technorati' => 'Technorati',
					'tumblr' => 'Tumblr',
					'twitterbird' => 'Twitter',
					'twitter' => 'Twitter',
					'viddler' => 'Viddler',
					'vimeo' => 'Vimeo',
					'virb' => 'Virb',
					'wikipedia' => 'Wikipedia',
					'windows' => 'Windows',
					'wordpress' => 'WordPress',
					'xing' => 'Xing',
					'yahoo' => 'Yahoo',
					'yelp' => 'Yelp',
					'youtube' => 'YouTube',
					'instagram' => 'Instagram',
				)
			);

			self::$icon_sizes = array_merge( self::$icon_sizes, array(
					'small' => __('Small', 'atticthemes_social'),
					'medium' => __('Medium', 'atticthemes_social'),
					'large' => __('Large', 'atticthemes_social'),
				)
			);

			/* setup help tabs */
			$this->help_tabs = array(
				'icon_set_tab' => array(
					'title' => __('Building Icon Sets', 'atticthemes_social'),
					'content' => __('It is very straightforward, just click the + icon in a "Icon Set" box (the gray box); this will automatically create an "Icon Set" shortcode that can be used in any shortcode enabled area, like pages and posts for example. Each time a new set is created an empty one will appear, so more sets can be built if needed. <br></br> To remove icons just drag & drop icons from within the set into the trash box. If a set has its icons removed, it will be deleted. <br></br>Every newly created set has its unique ID but it may be changed to any other ID that does not contain special characters or spaces. In order to change a set ID, just double click on the generated shortcode next to the "size selector". <br></br> Setting a custom ID is recommended, this will prevent issues with removed sets and unused shortcodes. If a set is removed, but the shortcode is still in use, the set will be rendered empty. By having the custom set ID in the shortcode you may restore it anythime without going through all the places the shortcode was used, in order to change it to the new one.', 'atticthemes_social')
				),
				'icon_setup_tab' => array(
					'title' => __('Setting Up The Icons', 'atticthemes_social'),
					'content' => __('None of the icons have their links set when they are used to create new sets. These icons are marked with an * (asterisk) to indicate the lack of a link. To set a link, just double click on the icon and type in or past a link in the input box of the popup window. Click "Done" or hit ENTER on your keyboard, to save the changes.', 'atticthemes_social')
				),
				/*'icon_attribution_tab' => array(
					'title' => __('Credits', 'atticthemes_social'),
					'content' => sprintf(__('Icons are Orman Clark\'s and are available at <a href="%s" target="_blank">premiumpixels.com</a>', 'atticthemes_social'), 'http://www.premiumpixels.com/freebies/41-social-media-icons-png/')
				),*/
			);
			/* add localization */
			//load_plugin_textdomain('atticthemes_social', false, basename( dirname( __FILE__ ) ) . '/languages');
			/* add shortcode */
			add_shortcode( $this->shortcode_tag , array( $this, 'handle_shortcode') );
			/* add actions */
			add_action( 'wp_ajax_atticthemes_social_icon_save_set', array( $this, 'saveSet') );
			add_action( 'wp_ajax_atticthemes_social_icon_increment_ids', array( $this, 'incrementIds') );
			/* admin init */
			add_action( 'admin_init', array( $this, 'init_admin_page' ) );
			add_action( 'admin_menu', array( $this, 'add_admin_page' ) );

			/* admin scripts and styles */
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts_and_style' ) );

			/* scrips and styles */
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_and_style' ) );


			/*$update_op = array();
			for( $i = 0; $i < 500; $i++ ) {
				$update_op['icon-set-' . $i] = array (
					'icons' => array (
						array (
							'id' => 'github',
							'link' => 'https://www.facebook.com/atticthemes',
						),
						array (
							'id' => 'githubalt',
							'link' => 'https://www.facebook.com/atticthemes',
						),
						array (
							'id' => 'googleplus',
							'link' => 'https://www.facebook.com/atticthemes',
						),
					),
					'size' => 'small',
				);
			}*/
			//update_option( 'atticthemes_social_icon_sets', $update_op);
			//delete_option( 'atticthemes_social_icon_sets' );
			//delete_option( 'atticthemes_social_icons_ids' );
			//error_log(var_export(get_option( 'atticthemes_social_icon_sets', false ), true));

			//update_option( 'atticthemes_social_icon_sets', get_option('_backup_atticthemes_social_icon_sets') );

			//update_option( 'atticthemes_social_icons_ids', 500);
		} //END public function __construct





		public function handle_shortcode( $atts ) {
			extract( shortcode_atts( array(
				'set' => false,
			), $atts ) );

			$sets = $this->decode( get_option( 'atticthemes_social_icon_sets', '' ) );

			ob_start();
			if( $sets && $set && isset($sets[$set]) && isset($sets[$set]['icons']) ) {
				//error_log(print_r($sets[$set],true));
				$size = isset($sets[$set]['size']) ? $sets[$set]['size'] : 'medium';
				foreach ($sets[$set]['icons'] as $icon) {
					?><a title="<?php echo self::$icons[ $icon['id'] ]; ?>" data-icon="<?php echo $icon['id']; ?>" href="<?php echo $icon['link']; ?>" target="_blank" class="atsi atsi-<?php echo $size; ?> atsi-<?php echo $icon['id']; ?>"></a><?php
				}
			}
			return ob_get_clean();
		}






		public function enqueue_scripts_and_style() {
			wp_register_style(
				'atsi-style',
				plugins_url( 'css/atticthemes-social-icons-style'.$this->min_suffix.'.css' , __FILE__ ),
				array(),
				$this->version,
				'all'
			);
			wp_enqueue_style( 'atsi-style' );
		}

		/* register and enqueue admin scripts */
		public function enqueue_admin_scripts_and_style() {
			wp_register_style(
				'atsi-style',
				plugins_url( 'css/atticthemes-social-icons-style'.$this->min_suffix.'.css' , __FILE__ ),
				array(),
				$this->version,
				'all'
			);
			wp_enqueue_style( 'atsi-style' );


			wp_register_style(
				'atticthemes-social-icons-style-admin',
				plugins_url( 'css/atticthemes-social-icons-style-admin'.$this->min_suffix.'.css' , __FILE__ ),
				array(),
				$this->version,
				'all'
			);
			wp_enqueue_style( 'atticthemes-social-icons-style-admin' );

			//javascript
			wp_register_script(
				'atticthemes-social-icons-script',
				plugins_url( 'javascript/atticthemes-social-icons-script'.$this->min_suffix.'.js' , __FILE__ ),
				array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable' ),
				$this->version,
				true
			);
			wp_enqueue_script( 'atticthemes-social-icons-script' );

			$icon_sizes_arr = array();

			foreach (self::$icon_sizes as $size => $name) {
				$icon_sizes_arr[] = array(
					'size' => $size,
					'name' => $name
				);
			}

			wp_localize_script( 'atticthemes-social-icons-script', 'atticthemes_social_icons', array(
					'shortcode_tag' => $this->shortcode_tag,
					'plugin_url' => plugin_dir_url( __FILE__ ),
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'ajax_nonce' => wp_create_nonce( '_atticthemes-social-icons-nonce_' ),
					'no_icons_in_set' => __('Can not edit the ID, the icon set is empty.', 'atticthemes_social'),
					'icon_sizes' => $icon_sizes_arr
				)
			);
		}

		/* on admin init */
		public function init_admin_page() {
			
		}

		/* add admin page */
		public function add_admin_page() {
			$this->options_page = add_menu_page(
				__('Generate Social Icons Shortcode', 'atticthemes_social'),
				__('Social Icons', 'atticthemes_social'),
				'manage_options',
				'generate-social-icons-shortcode',
				array( $this, 'create_admin_page' ),
				'dashicons-share'/*,
				plugins_url( 'images/menu-icon.png' , __FILE__ )*/
			);

			add_action('load-' . $this->options_page, array( $this, 'add_help_tabs' ) );
		}

		public function add_help_tabs() {
			$screen = get_current_screen();

			if( !empty($this->help_tabs) ) {
				foreach ($this->help_tabs as $id => $data) {
					if ( $screen->id != $this->options_page ) {
						return;
					}
					$screen->add_help_tab( array(
							'id' => $id,
							'title'	=> $data['title'],
							'content'	=> '<p>' . $data['content'] . '</p>',
						)
					);
				}

				$screen->set_help_sidebar(
					__('<h4>Additional Information</h4><p></p>')
				);
			}

		}

		/* create the admin page */
		public function create_admin_page() {

			?>
			<div class="wrap">
				<div class="atticthemes-social-icons-wrapper">
					
					<div class="atticthemes-social-icons-title-wrapper">
						<span class="atticthemes-social-icons-title"><?php 
						_e('Create Icon Sets', 'atticthemes_social'); 
						?></span>
						<em><?php _e('Click on the + icon in an icon set to add icons to that set. Drag selected icons inside sets to rearrange them. Drop icons into the trash can to get rid of them.', 'atticthemes_social'); ?></em>
					</div>

					<div class="atticthemes-social-icon-sets-wrapper">
						<span class="spinner atticthemes-social-icons-set-preloader"></span>
						<?php $sets = $this->decode( get_option( 'atticthemes_social_icon_sets', '' ) ); //print_r($sets); ?>
						<?php if( isset( $sets ) && !empty( $sets ) ) { ?>
							<?php foreach( $sets as $set_id => $set_data ) { ?>
								<?php $icons = isset($set_data['icons']) ? $set_data['icons'] : array(); ?>
								<?php if( $icons ) { ?>
									<div data-set-id="<?php echo $set_id; ?>" class="atticthemes-social-icon-set-container <?php echo 'atticthemes-social-icon-size-' . $set_data['size']; ?>">
										<ul class="atticthemes-social-icon-set-trash"></ul>
										<ul class="atticthemes-social-icon-set">
											<?php foreach( $icons as $icon ) {
												$id = isset($icon['id']) ? $icon['id'] : '';
												$link = isset($icon['link']) ? $icon['link'] : '';
												$title = $id && isset(self::$icons[$icon['id']]) ? self::$icons[$icon['id']] : '';
												?>
												<li title="<?php echo esc_attr( $title ); ?>" data-icon="<?php echo $id; ?>" data-link="<?php echo $link; ?>" class="atsi atsi-<?php echo $id; ?> <?php echo 'atsi-size-' . $set_data['size']; ?> <?php echo empty($link) ? 'no-link' : ''; ?>"></li>
											<?php } ?>
											<li class="atticthemes-social-icon-add-icon dashicons dashicons-plus"></li>
										</ul>
										<div class="atticthemes-social-icon-set-shortcode">
											<input type="text" readonly="true" value="<?php echo esc_attr('['.$this->shortcode_tag.' set="'.$set_id.'"]'); ?>" class="atticthemes-social-icon-set-shortcode-text" />
											
											<select class="atticthemes-social-icon-set-size">
												<?php foreach (self::$icon_sizes as $key => $value) { ?>
													<option <?php echo $set_data['size'] == $key ? 'selected="selected"' : ''; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								<?php } //END if !empty($icons) ?>
							<?php } ?>
						<?php } ?>
					</div>

					<div class="atticthemes-social-icons-list-wrapp">
						<div class="atticthemes-social-icons-list-cont">
							<span class="atticthemes-social-icons-list-close dashicons dashicons-no"></span>
							<div class="atticthemes-social-icons-list-title">
								<div class="atticthemes-social-icons-list-title-wrap"><?php 
									_e('Available Icons', 'atticthemes_social');
									?><span class="spinner atticthemes-social-icons-list-preloader"></span>
								</div>
							</div>
							<h4><?php _e('Click on the icon to add the set.', 'atticthemes_social'); ?></h4>
							<ul class="atticthemes-social-icons-list"><?php
								foreach ( self::$icons as $id => $title ) {
									?><li title="<?php echo esc_attr( $title ); ?>" data-icon="<?php echo $id; ?>" class="atsi atsi-<?php echo $id; ?> no-link"></li><?php
								}
							?></ul>
							<span class="atticthemes-social-icons-list-status-bar"></span>
						</div>
					</div>

					<div class="atticthemes-social-icon-editor-wrapp">
						<div class="atticthemes-social-icon-editor">
							<span class="atticthemes-social-icon-editor-title"></span>
							<h4><?php _e('Type or paste a link for this icon.', 'atticthemes_social'); ?></h4>

							<input type="text" class="atticthemes-social-icon-link-input"/>

							<span class="atticthemes-social-icon-editor-status-bar"></span>

							<button class="atticthemes-social-icon-editor-done-button button"><?php _e('Done', 'atticthemes_social'); ?></button>
							<button class="atticthemes-social-icon-editor-cancel-button button"><?php _e('Cancel', 'atticthemes_social'); ?></button>
							
							
							<span class="spinner atticthemes-social-icon-editor-preloader"></span>
						</div>
					</div>

					<div class="atticthemes-social-icon-set-id-editor-wrapp">
						<div class="atticthemes-social-icon-set-id-editor">
							<span class="atticthemes-social-icon-set-id-editor-title"><?php _e('Set a custom ID', 'atticthemes_social'); ?></span>
							<h4><?php _e('No spaces or special characters allowed,  _  and  -  are allowed.', 'atticthemes_social'); ?></h4>
							<input type="text" class="atticthemes-social-icon-set-id-link-input"/>

							<span class="atticthemes-social-icon-set-id-editor-status-bar"></span>

							<button class="atticthemes-social-icon-set-id-editor-done-button button"><?php _e('Done', 'atticthemes_social'); ?></button>
							<button class="atticthemes-social-icon-set-id-editor-cancel-button button"><?php _e('Cancel', 'atticthemes_social'); ?></button>
							
							
							<span class="spinner atticthemes-social-icon-set-id-editor-preloader"></span>
						</div>
					</div>
				</div>
				<?php //END atticthemes-social-icons-wrapper ?>
			</div>
		<?php }


		public function incrementIds() {
			if ( !isset($_REQUEST['nonce']) || (isset($_REQUEST['nonce']) && !wp_verify_nonce( $_REQUEST['nonce'], '_atticthemes-social-icons-nonce_')) ) {
				exit('Do not even try!');
			}
			if( isset($_REQUEST['increment']) && $_REQUEST['increment'] === 'true' ) {
				$update = update_option( 'atticthemes_social_icons_ids', get_option( 'atticthemes_social_icons_ids', 0 ) + 1 );
			}
			if( isset($update) && $update ) {
				echo json_encode( array(
						'status' => 'success',
						'ID' => get_option( 'atticthemes_social_icons_ids' )
					)
				);
			} else {
				echo json_encode( array(
						'status' => 'no-change',
						'ID' => get_option( 'atticthemes_social_icons_ids' )
					)
				);
			}
			die;
		}


		public function saveSet() {
			if ( !isset($_REQUEST['nonce']) || (isset($_REQUEST['nonce']) && !wp_verify_nonce( $_REQUEST['nonce'], '_atticthemes-social-icons-nonce_')) ) {
				exit('Do not even try!');
			}

			if( isset($_REQUEST['data']) && !empty($_REQUEST['data']) ) {
				$data = $_REQUEST['data'];

				//---------
				if( $this->areValidArrayKeys( $data ) ) {
					$update = update_option( 'atticthemes_social_icon_sets', $data );
					if( $update ) {
						echo json_encode( array(
								'status' => 'success',
								'data' => $data
							)
						);
					} else {
						echo json_encode( array(
								'status' => 'no-change'
							)
						);
					}
				} else {
					echo json_encode( array(
							'status' => 'error',
							'message' => __('No spaces or special characters allowed.', 'atticthemes_social')
						)
					);
				}
				//---------
			}
			die;
		}

		public function areValidArrayKeys( $array ) {
			if( is_string($array) ) {
				$array = $this->decode( $array );
			}

			if( $array ) {
				foreach( $array as $key => $value ) {
					if( preg_match('/[^a-zA-Z0-9\-\_]+/', $key) ) {
						return false;
					}
				}
			}
			
			return true;
		}

		public function decode( $data ) {
			if( is_array($data) ) {
				return $data;
			} else {
				return json_decode( base64_decode($data), true );
			}
		}
	}

	function atsi_after_setup_theme() {
		AttichThemes_Social::Init();
	}
	add_action( 'after_setup_theme', 'atsi_after_setup_theme' );
}
?>