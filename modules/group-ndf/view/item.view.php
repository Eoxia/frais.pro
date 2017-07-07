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

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div class="note action-attribute"
		data-group_id="<?php echo esc_attr( $ndf->id ); ?>"
		data-action="open_note_de_frais"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'open_note_de_frais' ) ); ?>">
	<div class="container">
		<div class="header">
			<h2 class="title"><?php echo esc_html( $ndf->title ); ?></h2>
			<span class="button export action-attribute"
					data-id="<?php echo esc_attr( $ndf->id ); ?>"
					data-action="export_note_de_frais"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'export_note_de_frais' ) ); ?>"><i class="icon ion-share"></i></span>
		</div>
		<div class="content gridwrapper">
			<div class="ttc element">
				<span class="value"><?php echo esc_html( $ndf->ttc ); ?></span>
				<span class="currency">€</span>
				<span class="taxe">TTC</span>
			</div>
			<div class="tva element">
				<span class="value"><?php echo esc_html( $ndf->tx_tva ); ?></span>
				<span class="currency">€</span>
				<span class="taxe">TVA</span>
			</div>
			<div class="status">Status : <span class="value"><?php echo $ndf->validation_status; ?></span></div>
			<div class="update">MAJ : <span class="value"><?php echo esc_html( $ndf->date_modified ); ?></span></div>
		</div>
	</div>
</div>
