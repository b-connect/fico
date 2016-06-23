<?php
namespace Drupal\fico\Plugin\Field\FieldFormatter\Condition;

use Drupal\fico\Plugin\FieldFormatterConditionBase;
use Drupal\user\Entity\Role;

/**
 * The plugin for check empty fields.
 *
 * @FieldFormatterCondition(
 *   id = "hide_on_role",
 *   label = @Translation("Hide when current user has role"),
 *   types = {
 *     "all"
 *   },
 *   settingsForm = TRUE
 * )
 */
class HideOnRole extends FieldFormatterConditionBase {

  /**
   * {@inheritdoc}
   */
  public function formElements($settings) {
    $user_roles = [];
    foreach (Role::loadMultiple() as $role) {
      $user_roles[$role->id()] = $role->label();
    }
    $default_roles = isset($settings['settings']['roles']) ? $settings['settings']['roles'] : NULL;
    $elements['roles'] = array(
      '#type' => 'select',
      '#multiple' => TRUE,
      '#title' => t('Select roles'),
      '#options' => $user_roles,
      '#default_value' => $default_roles,
    );
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function access(&$build, $field, $settings) {
    if (array_intersect(\Drupal::currentUser()->getRoles(), $settings['settings']['roles']) && \Drupal::currentUser()->Id() != 1) {
      $build[$field]['#access'] = FALSE;
    };
  }

}
