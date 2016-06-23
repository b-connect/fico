<?php
namespace Drupal\fico\Plugin\Field\FieldFormatter\Condition;

use Drupal\fico\Plugin\FieldFormatterConditionBase;
use Drupal\Core\Field\FieldConfigInterface;

/**
 * The plugin for check empty fields.
 *
 * @FieldFormatterCondition(
 *   id = "hide_if_empty",
 *   label = @Translation("Hide when target field is empty"),
 *   types = {
 *     "all"
 *   },
 *   settingsForm = TRUE
 * )
 */
class HideIfEmpty extends FieldFormatterConditionBase {

  /**
   * {@inheritdoc}
   */
  public function formElements($settings) {
    $fields = [];
    $options = [];
    $entityManager = \Drupal::service('entity.manager');
    if (!empty($settings['entity_type']) && !empty($settings['bundle'])) {
      $fields = array_filter(
        $entityManager->getFieldDefinitions($settings['entity_type'], $settings['bundle']), function ($field_definition) {
          return $field_definition instanceof FieldConfigInterface;
        }
      );
    }
    foreach ($fields as $field_name => $field) {
      if ($field_name != $settings['field_name']) {
        $options[$field_name] = $field->label();
      }
    }

    $default_value = isset($settings['settings']['target_field']) ? $settings['settings']['target_field'] : NULL;
    $elements['target_field'] = [
      '#type' => 'select',
      '#title' => t('Field'),
      '#options' => $options,
      '#default_value' => $default_value,
    ];
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function access(&$build, $field, $settings) {
    if (empty($build[$settings['settings']['target_field']]['#items'])) {
      $build[$field]['#access'] = FALSE;
    }
  }

}
