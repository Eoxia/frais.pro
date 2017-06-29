<?php
/**
 * Vue principale de l'application
 *
 * @package Eoxia\Plugin
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {	exit; } ?>

<ul>
	<?php
	if ( ! empty( $groups_ndf ) ) :
		foreach ( $groups_ndf as $ndf ) :
			\eoxia\View_Util::exec( 'note-de-frais', 'group-ndf', 'item', array(
				'ndf' => $ndf,
			) );
		endforeach;
	endif;
	?>
</ul>
