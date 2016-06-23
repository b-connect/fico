<?php
namespace Drupal\fico\Plugin\Field\FieldFormatter\Condition;

use Drupal\fico\Plugin\FieldFormatterConditionBase;
use Drupal\Core\Field\FieldConfigInterface;

/**
 * The plugin for check empty fields.
 *
 * @FieldFormatterCondition(
 *   id = "hide_if_string",
 *   label = @Translation("Hide when target field contains a string"),
 *   types = {
 *     "all"
 *   },
 *   settingsForm = TRUE
 * )
 */
class HideIfString extends FieldFormatterConditionBase {

  /**
   * {@inheritdoc}
   */
  public function formElements($settings) {
    $fields = [];
    $options = [];
    $allowed_field_types = [
      "text",
      "text_long",
      "text_with_summary",
      "string",
      "list_string",
      "string_long",
    ];
    $entityManager = \Drupal::service('entity.manager');
    if (!empty($settings['entity_type']) && !empty($settings['bundle'])) {
      $fields = array_filter(
        $entityManager->getFieldDefinitions($settings['entity_type'], $settings['bundle']), function ($field_definition) {
          return $field_definition instanceof FieldConfigInterface;
        }
      );
    }
    foreach ($fields as $field_name => $field) {
      if ($field_name != $settings['field_name'] && in_array($field->getType(), $allowed_field_types)) {
        $options[$field_name] = $field->label();
      }
    }

    $default_target = isset($settings['settings']['target_field']) ? $settings['settings']['target_field'] : NULL;
    $default_string = isset($settings['settings']['string']) ? $settings['settings']['string'] : NULL;
    $elements['target_field'] = [
      '#type' => 'select',
      '#title' => t('Select target field'),
      '#options' => $options,
      '#default_value' => $default_target,
    ];
    $elements['string'] = array(
      '#type' => 'textfield',
      '#title' => t('Enter target string'),
      '#default_value' => $default_string,
    );
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function access(&$build, $field, $settings) {
    $found = fico_string_search($build, $field, $settings);
    if ($found == TRUE) {
      $build[$field]['#access'] = FALSE;
    }
  }

}
