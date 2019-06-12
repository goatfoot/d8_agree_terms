<?php

namespace Drupal\agree_terms\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\OptionsProviderInterface;
use Drupal\Core\TypedData\DataDefinition;
use Zend\Feed\Uri;

/**
 * Defines the 'agree_terms' entity field type.
 *
 * @FieldType(
 *   id = "agree_terms",
 *   label = @Translation("Agree Terms"),
 *   description = @Translation("An entity field containing a Agree terms value."),
 *   default_widget = "agree_terms_checkbox",
 *   default_formatter = "agree_terms_formatter",
 * )
 */
class AgreeTermsItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return [
        'on_label' => new TranslatableMarkup('Agreed'),
        'off_label' => new TranslatableMarkup('No Response'),
        'terms_path' => '',
        'form_label' => new TranslatableMarkup('I agree to the Terms and Conditions'),
      ] + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('boolean')
      ->setLabel(t('Agree to terms value'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'int',
          'size' => 'tiny',
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $element = [];

    $element['on_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('"Agreed" display label'),
      '#default_value' => $this->getSetting('on_label'),
      '#required' => TRUE,
    ];
    $element['off_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('"No Response" display label'),
      '#default_value' => $this->getSetting('off_label'),
      '#required' => TRUE,
    ];
    $element['form_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Form checkbox label'),
      '#default_value' => $this->getSetting('form_label'),
      '#required' => TRUE,
    ];
    $element['terms_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Terms Path'),
      '#description' => $this->t('Leave blank for no link'),
      '#default_value' => $this->getSetting('terms_path'),
      '#required' => FALSE,
    ];

    return $element;
  }

}
