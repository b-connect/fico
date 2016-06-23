<?php

namespace Drupal\fico\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a field formatter condition item annotation object.
 *
 * @see \Drupal\fico\Plugin\FieldFormatterConditionManager
 * @see plugin_api
 *
 * @Annotation
 */
class FieldFormatterCondition extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The field formatter condition label in the select box.
   *
   * @var string
   */
  public $label;

  /**
   * This field formatter condition is allowed for the following field types.
   *
   * @var array
   */
  public $types;

  /**
   * This field formatter condition is is use a settings form.
   *
   * @var bool
   */
  public $settingsForm;

}
