<?php
class ADE_admin{


		public function __construct(){
	      add_action('admin_menu', [$this, 'ADE_admin_menu'] );

		}


		public function ADE_admin_menu(){
		$hook = add_options_page(
		        __( 'Auto Disable Gutenberg', 'auto-disable-editor' ),
		        __( 'Auto Disable Gutenberg ', 'auto-disable-editor' ),
		        'manage_options',
		        'auto-disable-petersburg',
		        [$this, 'admin_display' ]
		        );

		add_action( 'admin_print_scripts-' . $hook, [$this, 'load_admin_css_and_js'] );
		}


		public function load_admin_css_and_js($hook){
			wp_enqueue_script( 'codepopular-support-chat', ADE_PLUGIN_URL. '/Admin/js/chat.js', false, time(), true );

		}

		public function admin_display(){
			?>

			<div class="wrap">
				<h1>
				  <span class="dashicons dashicons-yes-alt" style="font-size: inherit; line-height: unset;"></span>
				   Auto Disable Gutenberg</h1>
				  <br>
				  <h3 style="color:green">
                      Thank you for install auto disable editor. you can disable gutenberg block editor to enable this plugin. Enjoy it.
                      <h3>
				  <div style="font-size:14px">Hire Us to get help- <a href="https://www.codepopular.com" target="__blank">CodePopular</a></div>
			</div>


			<?php
		}

}


if(class_exists('ADE_admin')){
	new ADE_admin;
}
