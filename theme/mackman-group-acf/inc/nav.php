<?php
/**
 * Navigation with accessible drop-downs.
 *
 * Follows the WAI-ARIA "disclosure navigation" pattern, not an application
 * menu (no role="menu"), so links behave as links and Tab moves through them.
 *
 * - A parent that is a page keeps its link, followed by a separate button
 *   that shows or hides its sub-pages (a "split" control).
 * - A parent with no page of its own (URL "#") becomes the button itself.
 * - Each button has aria-expanded and aria-controls pointing at its list.
 *
 * Behaviour (hover, Escape, click outside) is in assets/js/nav.js. Without
 * JavaScript, CSS shows the sub-pages on hover and keyboard focus.
 *
 * @package mackman-group-acf
 */

defined( 'ABSPATH' ) || exit;

/**
 * Walker that adds the drop-down buttons and IDs.
 */
class MG_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * IDs of the sub-menu lists, by depth, waiting for start_lvl().
	 *
	 * @var string[]
	 */
	private $pending_ids = array();

	/**
	 * A unique ID for an item's sub-menu. The menu is printed twice (header
	 * and mobile panel), so the menu's class is part of the ID.
	 *
	 * @param WP_Post  $item Menu item.
	 * @param stdClass $args wp_nav_menu() arguments.
	 * @return string
	 */
	private function submenu_id( $item, $args ) {
		$prefix = ! empty( $args->menu_class ) ? sanitize_html_class( strtok( $args->menu_class, ' ' ) ) : 'menu';
		return $prefix . '-sub-' . (int) $item->ID;
	}

	/**
	 * Start a sub-menu list.
	 *
	 * @param string   $output Output so far.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$id      = array_pop( $this->pending_ids );
		$output .= '<ul class="sub-menu"' . ( $id ? ' id="' . esc_attr( $id ) . '"' : '' ) . '>';
	}

	/**
	 * End a sub-menu list.
	 *
	 * @param string   $output Output so far.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</ul>';
	}

	/**
	 * Start an item. Adds the drop-down button for parents.
	 *
	 * @param string   $output            Output so far.
	 * @param WP_Post  $data_object       Menu item.
	 * @param int      $depth             Depth.
	 * @param stdClass $args              Arguments.
	 * @param int      $current_object_id Current object ID.
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$item         = $data_object;
		$has_children = in_array( 'menu-item-has-children', (array) $item->classes, true );

		if ( ! $has_children || $depth > 0 ) {
			parent::start_el( $output, $item, $depth, $args, $current_object_id );
			return;
		}

		$id                  = $this->submenu_id( $item, $args );
		$this->pending_ids[] = $id;
		$title               = wp_strip_all_tags( apply_filters( 'nav_menu_item_title', $item->title, $item, $args, $depth ) );
		$chevron             = '<span class="submenu-toggle__chevron" aria-hidden="true"></span>';

		$item_output = '';
		parent::start_el( $item_output, $item, $depth, $args, $current_object_id );

		if ( empty( $item->url ) || '#' === $item->url ) {
			// No page of its own: the button is the whole control.
			$button      = sprintf(
				'<button class="submenu-toggle submenu-toggle--parent" type="button" aria-expanded="false" aria-controls="%1$s">%2$s%3$s</button>',
				esc_attr( $id ),
				esc_html( $title ),
				$chevron
			);
			$item_output = preg_replace( '~<a\b[^>]*>.*?</a>~s', $button, $item_output, 1 );
		} else {
			// A page: keep the link and add a separate button.
			$item_output .= sprintf(
				'<button class="submenu-toggle" type="button" aria-expanded="false" aria-controls="%1$s"><span class="screen-reader-text">%2$s</span>%3$s</button>',
				esc_attr( $id ),
				/* translators: %s: menu item title */
				esc_html( sprintf( __( '%s sub-pages', 'mackman-group-acf' ), $title ) ),
				$chevron
			);
		}

		$output .= $item_output;
	}
}
