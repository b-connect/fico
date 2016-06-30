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
 *   }
 * )
 */
class HideOnRole extends FieldFormatterConditionBase {

  /**
   * {@inheritdoc}
   */
  public function alterForm(&$form, $settings) {
    $user_roles = [];
    foreach (Role::loadMultiple() as $role) {
      $user_roles[$role->id()] = $role->label();
    }
    $default_roles = isset($settings['settings']['roles']) ? $settings['settings']['roles'] : NULL;

    $form['roles'] = array(
      '#type' => 'select',
      '#multiple' => TRUE,
      '#title' => t('Select roles'),
      '#options' => $user_roles,
      '#default_value' => $default_roles,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function access(&$build, $field, $settings) {
    if (array_intersect(\Drupal::currentUser()->getRoles(), $settings['settings']['roles']) && \Drupal::currentUser()->id() != 1) {
      $build[$field]['#access'] = FALSE;
    };
  }

  /**
   * {@inheritdoc}
   */
  public function summary($settings) {
    $roles = [];
    foreach (Role::loadMultiple() as $role) {
      if (in_array($role->id(), $settings['settings']['roles'])) {
        $roles[] = $role->label();
      }
    }
    return t("Condition: %condition (%settings)", [
      "%condition" => t('Hide when current user has role'),
      '%settings' => implode(', ', $roles),
    ]);
  }

}
