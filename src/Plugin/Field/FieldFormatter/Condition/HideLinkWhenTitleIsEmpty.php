<?php
namespace Drupal\fico\Plugin\Field\FieldFormatter\Condition;

use Drupal\fico\Plugin\FieldFormatterConditionBase;

/**
 * The plugin for check empty fields.
 *
 * @FieldFormatterCondition(
 *   id = "hide_link_when_title_is_empty",
 *   label = @Translation("Hide link when link title is empty"),
 *   types = {
 *     "link_field",
 *     "link"
 *   },
 *   settingsForm = FALSE
 * )
 */
class HideLinkWhenTitleIsEmpty extends FieldFormatterConditionBase {

  /**
   * {@inheritdoc}
   */
  public function access(&$build, $field, $settings) {
    if (!empty($build[$field]['#items'])) {
      foreach ($build[$field]['#items'] as &$item) {
        $info = &$item->getValue($field);
        if (!$info['title'] || $info['title'] === $info['uri']) {
          $build[$field]['#access'] = FALSE;
        }
      }
    }

    if (empty($build[$field]['#items'])) {
      $build[$field]['#access'] = FALSE;
    }
  }

}
