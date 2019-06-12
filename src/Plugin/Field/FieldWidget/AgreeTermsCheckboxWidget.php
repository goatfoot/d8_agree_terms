<?php

namespace Drupal\agree_terms\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Plugin implementation of the 'agree_terms_checkbox' widget.
 *
 * @FieldWidget(
 *   id = "agree_terms_checkbox",
 *   label = @Translation("Single on/off checkbox"),
 *   field_types = {
 *     "agree_terms"
 *   },
 *   multiple_values = TRUE
 * )
 */
class AgreeTermsCheckboxWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'new_window' => TRUE,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['new_window'] = [
      '#type' => 'checkbox',
      '#title' => t('Display terms in a new window.'),
      '#default_value' => $this->getSetting('new_window'),
      '#weight' => -1,
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $new_window = $this->getSetting('new_window');
    $summary[] = t('Display terms in new window: @new_window', ['@new_window' => ($new_window ? t('Yes') : 'No')]);

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['value'] = $element + [
      '#type' => 'checkbox',
      '#default_value' => !empty($items[0]->value),
    ];

    $label = $this->fieldDefinition->getSetting('form_label');
    $path = $this->fieldDefinition->getSetting('terms_path');
    $target = $this->getSetting('new_window') ? '_blank' : '_self';

    if($path) {
      $element['value']['#title'] = Link::fromTextAndUrl($label, Url::fromUri(
        "internal:/$path",
        [
          'absolute' => FALSE,
          'attributes' => ['target' => $target],
        ]
      ))->toString();
    } else {
      $element['value']['#title'] = $label;
    }

    return $element;
  }

}
