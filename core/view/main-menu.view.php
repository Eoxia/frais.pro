<?php
/**
 * Frais.pro main view. Call dashboard or include update message.
 *
 * @package Frais.pro
 * @subpackage Notes_Templates
 *
 * @since 1.0.0.
 */

namespace frais_pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="nav-wrap">
	<div id="logo">
		<h1><a href="<?php echo admin_url( 'admin.php?page=frais-pro' ); ?>"><img src="<?php echo PLUGIN_NOTE_DE_FRAIS_URL . '/core/assets/images/icone-fond-blanc.png'; ?>" alt="Frais PRO" /></a></h1>
	</div>

	<div class="nav-menu">
		<?php
		if ( ! empty( Note_De_Frais_Class::g()->menu ) ) :
			foreach ( Note_De_Frais_Class::g()->menu as $item ) :
				?>
				<div class="item <?php echo esc_attr( $item['class'] ); ?>"><a href="<?php echo esc_url( $item['link'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a></div>
				<?php
			endforeach;
		endif;
		?>
	</div>
</div>
