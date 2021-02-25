<?php
/**
 * Classe gérant les actions des sociétés.
 *
 * Création, modification, recherche.
 *
 * @author    Evarisk <dev@evarisk.com>
 * @copyright (c) 2006-2018 Evarisk <dev@evarisk.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   DigiRisk\Classes
 *
 * @since     6.0.0
 */

namespace digi;

defined( 'ABSPATH' ) || exit;

/**
 * Society Action class.
 */
class Society_Action {

    /**
     * Constructeur.
     *
     * @since 6.0.0
     */
    public function __construct() {
        add_action( 'wp_ajax_save_society', array( $this, 'callback_save_society' ) );
        add_action( 'wp_ajax_delete_society', array( $this, 'callback_delete_society' ) );
        add_action( 'wp_ajax_search_establishment', array( $this, 'callback_search_establishment' ) );
        add_action( 'wp_ajax_display_edit_view', array( $this, 'callback_display_edit_view' ) );

        // ALLALALALALALALALA LALALA
        add_action( 'wp_ajax_save_society_description', array( $this, 'callback_save_society_description' ) );

    }

    /**
     * Sauvegardes les données d'une societé
     *
     * @since 6.0.0
     */
    public function callback_save_society() {
        check_ajax_referer( 'save_society' );

        $id    = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0; // WPCS: input var ok.
        $title = ! empty( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : ''; // WPCS: input var ok.

        if ( empty( $id ) ) {
            wp_send_json_error();
        }

        $society                = Society_Class::g()->show_by_type( $id );
        $society->data['title'] = $title;

        Society_Class::g()->update_by_type( $society );

        ob_start();
        DUER_Class::g()->display_page( $id, 'true' );
        wp_send_json_success( array(
            'namespace'        => 'digirisk',
            'module'           => 'society',
            'callback_success' => 'savedSocietySuccess',
            'society'          => $society,
            'template'         => ob_get_clean(),
        ) );
    }

    /**
     * Sauvegardes les données d'une societé
     *
     * @since 6.0.0
     */
    public function callback_save_society_description() {
//        check_ajax_referer( 'save_society_description' );

        $id    = ! empty( $_POST['idSociety'] ) ? (int) $_POST['idSociety'] : 0; // WPCS: input var ok.
        $content = ! empty( $_POST['societyDescription'] ) ? sanitize_text_field( wp_unslash( $_POST['societyDescription'] ) ) : ''; // WPCS: input var ok.

        if ( empty( $id ) ) {
            wp_send_json_error();
        }

        $society                = Society_Class::g()->show_by_type( $id );
        $society->data['content'] = $content;

        Society_Class::g()->update_by_type( $society );

        ob_start();
        DUER_Class::g()->display_page( $id, 'true' );
        wp_send_json_success( array(
            'namespace'        => 'digirisk',
            'module'           => 'society',
            'callback_success' => 'savedSocietySuccess',
            'society'          => $society,
            'template'         => ob_get_clean(),
        ) );
    }

    /**
     * Supprimes une société
     *
     * @since 6.0.0
     */
    public function callback_delete_society() {
        check_ajax_referer( 'delete_society' );

        $id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0; // WPCS: input var ok.

        Society_Class::g()->delete_child( $id );

        $society                 = Society_Class::g()->show_by_type( $id );
        $society->data['status'] = 'trash';
        Society_Class::g()->update_by_type( $society );

        ob_start();
        Digirisk::g()->display( $society->data['parent_id'] );
        wp_send_json_success( array(
            'template'         => ob_get_clean(),
            'namespace'        => 'digirisk',
            'module'           => 'society',
            'callback_success' => 'deletedSocietySuccess',
        ) );
    }

    /**
     * Recherches une société depuis $_GET['term'].
     *
     * @since 6.4.0
     */
    public function callback_search_establishment() {
        global $wpdb;
        $term = sanitize_text_field( $_GET['term'] );

        $posts_type = array(
            'digi-group',
            'digi-workunit',
        );

        $posts_founded = array();
        $ids_founded   = array();

        $results = $wpdb->query( $wpdb->prepare( 'SELECT ID FROM {$wpdb->posts} WHERE ID LIKE %s AND post_type IN(%s)', array(
            '%' . $term . '%',
            implode( $posts_type, ',' ),
        ) ) );

        if ( ! empty( $results ) ) {
            foreach ( $results as $post ) {
                $ids_founded[] = $post->ID;
            }
        }

        $query = new \WP_Query( array(
            'post_type'   => $posts_type,
            's'           => $term,
            'post_status' => array( 'publish', 'draft' ),
        ) );

        if ( ! empty( $query->posts ) ) {
            foreach ( $query->posts as $post ) {
                if ( ! in_array( $post->ID, $ids_founded, true ) ) {
                    $ids_founded[] = $post->ID;
                }
            }
        }

        $results = $wpdb->query(
            $wpdb->prepare(
                'SELECT PM.post_id FROM {$wpdb->postmeta} AS PM
					JOIN {$wpdb->posts} AS P ON P.ID=PM.post_id
				WHERE PM.meta_key="_wpdigi_unique_identifier"
					AND PM.meta_value LIKE %s
					AND P.post_type IN(%s)', array( '%' . $term . '%', implode( $posts_type, ',' ) )
            )
        );

        if ( ! empty( $results ) ) {
            foreach ( $results as $post ) {
                $ids_founded[] = $post->post_id;
            }
        }

        if ( ! empty( $ids_founded ) ) {
            foreach ( $ids_founded as $id_founded ) {
                $s = Society_Class::g()->show_by_type( $id_founded );

                if ( ! empty( $s ) ) {
                    $posts_founded[] = array(
                        'label' => $s->unique_identifier . ' ' . $s->title,
                        'value' => $s->unique_identifier . ' ' . $s->title,
                        'id'    => $s->id,
                    );
                }
            }
        }

        if ( empty( $posts_founded ) ) {
            $posts_founded[] = array(
                'label' => __( 'Aucun résultat', 'digirisk' ),
                'value' => __( 'Aucun résultat', 'digirisk' ),
                'id'    => 0,
            );
        }

        wp_die( wp_json_encode( $posts_founded ) );
    }

    public function callback_display_edit_view(){
        check_ajax_referer( 'display_edit_view' );
        $element          = isset( $_POST[ 'element' ] ) ? sanitize_text_field( $_POST[ 'element' ] ) : '';
        $society_id       = isset( $_POST[ 'society_id' ] ) ? (int) $_POST[ 'society_id' ] : 0;
        $legal_display_id = isset( $_POST[ 'legal_display_id' ] ) ? (int) $_POST[ 'legal_display_id' ] : 0;

        $society = Society_Class::g()->get_actual_society( $society_id );

        $legal_display = Legal_Display_Class::g()->get_legal_display( $society->data[ 'id' ] );

        Legal_Display_Class::g()->display_configuration_view( $society, $element );
    }

}

new Society_Action();
