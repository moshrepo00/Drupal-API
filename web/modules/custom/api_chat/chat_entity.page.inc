<?php

/**
 * @file
 * Contains chat_entity.page.inc.
 *
 * Page callback for Chat entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Chat entity templates.
 *
 * Default template: chat_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_chat_entity(array &$variables) {
  // Fetch ChatEntity Entity Object.
  $chat_entity = $variables['elements']['#chat_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
