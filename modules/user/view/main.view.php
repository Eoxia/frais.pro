<?php
/**
 * Vue principale de l'ajout des champs des utilisateurs
 *
 * @package Eoxia\Plugin
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {	exit; } ?>

<h2><?php esc_html_e( 'Informations de la voiture', 'note-de-frais' ); ?></h2>

<table class="form-table">
	<tbody>
		<tr class="user-marque-wrap">
			<th><label for="marque">Marque</label></th>
			<td><input type="text" name="marque" id="marque" class="regular-text ltr"></td>
		</tr>

		<tr class="user-marque-wrap">
			<th><label for="chevaux">Chevaux</label></th>
			<td>
				<select name="chevaux" id="chevaux">
				<?php

				if ( ! empty( \eoxia\Config_Util::$init['note-de-frais']->chevaux ) ) :
					foreach ( \eoxia\Config_Util::$init['note-de-frais']->chevaux as $chevaux ) :
						?>
						<option><?php echo esc_html( $chevaux ); ?></option>
						<?php
					endforeach;
				endif;
				?>
			</select>
			</td>
		</tr>

		<tr class="user-marque-wrap">
			<th><label for="prixkm">Prix/km</label></th>
			<td><input type="text" name="prixkm" id="prixkm" class="regular-text ltr"></td>
		</tr>

		<tr class="user-marque-wrap">
			<th><label for="marque">Carte grise</label></th>
			<td>test</td>
		</tr>
	</tbody>
</table>
