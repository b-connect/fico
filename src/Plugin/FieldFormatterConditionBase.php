<?php

namespace Drupal\fico\Plugin;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for Field formatter condition plugins.
 */
abstract class FieldFormatterConditionBase extends PluginBase implements FieldFormatterConditionInterface {

  /**
   * {@inheritdoc}
   */
  public function getDefinition($plugin_id, $exception_on_invalid = TRUE) {}

  /**
   * {@inheritdoc}
   */
  public function getDefinitions() {}

  /**
   * {@inheritdoc}
   */
  public function hasDefinition($plugin_id) {}

  /**
   * {@inheritdoc}
   */
  public function createInstance($plugin_id, array $configuration = []) {}

  /**
   * {@inheritdoc}
   */
  public function getInstance(array $options) {}

  /**
   * Access control function.
   *
   * @param array $build
   *   The current build array.
   * @param string $field
   *   The current field name.
   * @param array $settings
   *   The current settings array.
   */
  abstract public function access(&$build, $field, $settings);

}
