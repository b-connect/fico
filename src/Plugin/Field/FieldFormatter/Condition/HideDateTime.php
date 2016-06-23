<?php
namespace Drupal\fico\Plugin\Field\FieldFormatter\Condition;

use Drupal\fico\Plugin\FieldFormatterConditionBase;

/**
 * The plugin for check empty fields.
 *
 * @FieldFormatterCondition(
 *   id = "hide_date_time",
 *   label = @Translation("Hide date/time"),
 *   types = {
 *     "datetime",
 *     "date",
 *     "datestamp"
 *   },
 *   settingsForm = TRUE
 * )
 */
class HideDateTime extends FieldFormatterConditionBase {

  /**
   * {@inheritdoc}
   */
  public function formElements($settings) {
    $default_orientation = isset($settings['settings']['orientation']) ? $settings['settings']['orientation'] : NULL;
    $default_cutom_date = isset($settings['settings']['cutom_date']) ? $settings['settings']['cutom_date'] : NULL;
    $elements['orientation'] = [
      '#title' => t('Hide if'),
      '#type' => 'radios',
      '#options' => array(
        'smaller' => t("smaller than today's date"),
        'greater' => t("greater than today's date"),
        'custom_small' => t("smaller then custom date"),
        'greater_small' => t("greater then custom date")
      ),
      '#default_value' => $default_orientation,
    ];
    $elements['cutom_date'] = [
      '#title' => t('Cutom date'),
      '#type' => 'date',
      '#default_value' => $default_cutom_date,
    ];
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function access(&$build, $field, $settings) {
    $custom_date = strtotime($settings['settings']['cutom_date']);
    if (!empty($build[$field]['#items'])) {
      foreach ($build[$field]['#items'] as $item) {
        $info = &$item->getValue($field);
        switch ($settings['settings']['orientation']) {
          case 'smaller':
            if (strtotime($info['value']) < REQUEST_TIME) {
              $build[$field]['#access'] = FALSE;
            }
            break;

          case 'greater':
            if (strtotime($info['value']) > REQUEST_TIME) {
              $build[$field]['#access'] = FALSE;
            }
            break;

          case 'custom_small':
            if (strtotime($info['value']) < $custom_date) {
              $build[$field]['#access'] = FALSE;
            }
            break;

          case 'greater_small':
            if (strtotime($info['value']) > $custom_date) {
              $build[$field]['#access'] = FALSE;
            }
            break;

          default:
            $build[$field]['#access'] = FALSE;
        }
      }
    }

    if (empty($build[$field]['#items'])) {
      $build[$field]['#access'] = FALSE;
    }
  }

}
