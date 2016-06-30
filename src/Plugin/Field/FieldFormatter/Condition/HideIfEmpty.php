<?php
namespace Drupal\fico\Plugin\Field\FieldFormatter\Condition;

use Drupal\fico\Plugin\FieldFormatterConditionBase;
use Drupal\field\Entity\FieldStorageConfig;

/**
 * The plugin for check empty fields.
 *
 * @FieldFormatterCondition(
 *   id = "hide_if_empty",
 *   label = @Translation("Hide when target field is empty"),
 *   types = {
 *     "all"
 *   }
 * )
 */
class HideIfEmpty extends FieldFormatterConditionBase {

  /**
   * {@inheritdoc}
   */
  public function alterForm(&$form, $settings) {
    $options = [];
    $fields = $this->getEntityFields($settings['entity_type'], $settings['bundle']);

    foreach ($fields as $field_name => $field) {
      if ($field_name != $settings['field_name']) {
        $options[$field_name] = $field->getLabel();
      }
    }

    $default_value = isset($settings['settings']['target_field']) ? $settings['settings']['target_field'] : NULL;
    $form['target_field'] = [
      '#type' => 'select',
      '#title' => t('Field'),
      '#options' => $options,
      '#default_value' => $default_value,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function access(&$build, $field, $settings) {
    if (isset($build[$settings['settings']['target_field']]['#items'])) {
      $fields = $build[$settings['settings']['target_field']]['#items'];
      if (is_object($fields)) {
        $field_storage = FieldStorageConfig::loadByName($settings['entity_type'], $settings['settings']['target_field']);
        switch ($field_storage->getType()) {
          case 'comment':
            $values = $fields->getValue();
            if ($values[0]['comment_count'] == 0) {
              $build[$field]['#access'] = FALSE;
            }
            break;

          case 'boolean':
            $values = $fields->getValue();
            if ($values[0]['value'] == 0) {
              $build[$field]['#access'] = FALSE;
            }
            break;
        }
      }
    }
    else {
      $entity = $build['#' . $build['#entity_type']];
      if ($entity->get($settings['settings']['target_field'])->isEmpty()) {
        $build[$field]['#access'] = FALSE;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function summary($settings) {
    $options = [];
    $fields = $this->getEntityFields($settings['entity_type'], $settings['bundle']);

    foreach ($fields as $field_name => $field) {
      if ($field_name != $settings['field_name']) {
        $options[$field_name] = $field->getLabel();
      }
    }

    return t("Condition: %condition (%settings)", [
      "%condition" => t('Hide when target field is empty'),
      '%settings' => $options[$settings['settings']['target_field']],
    ]);
  }

}
