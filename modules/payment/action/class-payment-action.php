<?php
/**
 * Classe gérant les paiements
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.7.0
 * @version 1.7.0
 * @copyright 2017-2019 Eoxia
 * @package Eoxia/NodeDeFrais
 */

namespace frais_pro;

defined( 'ABSPATH' ) || exit;

/**
 * Classe gérant les notes de frais.
 */
class Payment_Action {

	/**
	 * Les actions pour les paiements.
	 *
	 * @since 1.7.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_modal_payment', array( $this, 'open_modal_payment' ) );

		add_action( 'wp_ajax_fp_save_payment', array( $this, 'save_payment' ) );
	}

	public function open_modal_payment() {
		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$note = Note_Class::g()->get( array( 'id' => $id ), true );

		ob_start();
		\eoxia\View_Util::exec( 'frais-pro', 'payment', 'main', array(
			'id'   => $id,
			'note' => $note,
		) );
		$modal_view = ob_get_clean();

		ob_start();
		\eoxia\View_Util::exec( 'frais-pro', 'payment', 'buttons' );
		$buttons_view = ob_get_clean();

		wp_send_json_success( array(
			'view'         => $modal_view,
			'buttons_view' => $buttons_view,
		) );
	}

	public function save_payment() {
		$id              = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$payment_date    = ! empty( $_POST['payment_date'] ) ? sanitize_text_field( $_POST['payment_date'] ) : '';
		$payment_type    = ! empty( $_POST['payment_type'] ) ? sanitize_text_field( $_POST['payment_type'] ) : '';
		$payment_number  = ! empty( $_POST['payment_number'] ) ? sanitize_text_field( $_POST['payment_number'] ) : '';
		$payment_comment = ! empty( $_POST['payment_comment'] ) ? sanitize_text_field( $_POST['payment_comment'] ) : '';
		$payment_amount  = ! empty( $_POST['payment_amount'] ) ? sanitize_text_field( $_POST['payment_amount'] ) : '';

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$payment = Payment::g()->get( array( 'schema' => true ), true );

		$payment->data['post_id']        = $id;
		$payment->data['date']           = $payment_date;
		$payment->data['content']        = $payment_comment;
		$payment->data['payment_type']   = $payment_type;
		$payment->data['payment_number'] = $payment_number;
		$payment->data['payment_amount'] = $payment_amount;

		$payment = Payment::g()->update( $payment->data );

		$note = Note_Class::g()->get( array( 'id' => $id ), true );

		$status_list = Note_Status_Class::g()->get();
		$payed_status = null;

		if ( ! empty( $status_list ) ) {
			foreach( $status_list as $key => $status ) {
				if ( 1 === $key ) {
					$payed_status = $status;
					break;
				}
			}
		}

		$note->data['taxonomy'][ Note_Status_Class::g()->get_type() ] = array( $payed_status->data['id'] ); // 1 Is Payed status.

		// On lance la mise à jour de la note.
		$note = Note_Class::g()->update( $note->data );

		$success = __( sprintf( 'Save payment REF %s with success', $payment->data['unique_identifier'] ), 'frais-pro' );
		ob_start();
		\eoxia\View_Util::exec( 'frais-pro', 'payment', 'button-success' );
		$buttons = ob_get_clean();

		ob_start();
		Note_De_Frais_Class::g()->display( $id );
		$note_view = ob_get_clean();

		wp_send_json_success( array(
			'namespace'        => 'fraisPro',
			'module'           => 'payment',
			'callback_success' => 'savedPayment',
			'view'             => $success,
			'buttons_view'     => $buttons,
			'note_view'        => $note_view,
		) );
	}

}

new Payment_Action();
