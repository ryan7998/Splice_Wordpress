<?php

/**
 * Custom Navigation Walker for Multi-Level Dropdown Menus
 *
 * @package Splice_Theme
 */

if (!class_exists('Splice_Theme_Walker_Nav_Menu')) {
    class Splice_Theme_Walker_Nav_Menu extends Walker_Nav_Menu
    {
        /**
         * Starts the list before the elements are added.
         */
        public function start_lvl(&$output, $depth = 0, $args = null)
        {
            $indent = str_repeat("\t", $depth);
            $submenu = ($depth > 0) ? ' sub-menu' : '';
            $output .= "\n$indent<ul class=\"dropdown-menu$submenu\">\n";
        }

        /**
         * Ends the list of after the elements are added.
         */
        public function end_lvl(&$output, $depth = 0, $args = null)
        {
            $indent = str_repeat("\t", $depth);
            $output .= "$indent</ul>\n";
        }

        /**
         * Starts the element output.
         */
        public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
        {
            $indent = ($depth) ? str_repeat("\t", $depth) : '';

            $li_attributes = '';
            $class_names = $value = '';

            $classes = empty($item->classes) ? array() : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;

            // Add dropdown classes for items with children
            if ($args->walker->has_children) {
                $classes[] = 'dropdown';
            }

            // Add depth classes
            if ($depth > 0) {
                $classes[] = 'dropdown-item';
            }

            $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
            $class_names = ' class="' . esc_attr($class_names) . '"';

            $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
            $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

            $output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';

            $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
            $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
            $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
            $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

            // Add dropdown attributes for items with children
            if ($args->walker->has_children) {
                $attributes .= ' class="dropdown-toggle"';
                $attributes .= ' data-toggle="dropdown"';
                $attributes .= ' aria-haspopup="true"';
                $attributes .= ' aria-expanded="false"';
            }

            $item_output = $args->before;
            $item_output .= '<a' . $attributes . '>';
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;

            // Add dropdown arrow for items with children
            if ($args->walker->has_children) {
                $item_output .= ' <span class="dropdown-arrow">▼</span>';
            }

            $item_output .= '</a>';
            $item_output .= $args->after;

            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
        }

        /**
         * Ends the element output.
         */
        public function end_el(&$output, $item, $depth = 0, $args = null)
        {
            $output .= "</li>\n";
        }

        /**
         * Traverse elements to create list from elements.
         */
        public function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
        {
            if (!$element) {
                return;
            }

            $id_field = $this->db_fields['id'];

            // Display this element.
            if (is_object($args[0])) {
                $args[0]->has_children = !empty($children_elements[$element->$id_field]);
            }

            parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
        }
    }
}
