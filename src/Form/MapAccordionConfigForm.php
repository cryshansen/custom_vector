<?php
/**
* this file is to handle locations of distributors or locations of a company.
*/
namespace Drupal\custom_vectormap\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class MapAccordionConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_vectormap_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'custom_vectormap.settings',
    ];
  }

  /**
   * Build the configuration form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('custom_vectormap.settings');
    
    // Initialize locations array in the form state if not set.
    if ($form_state->get('locations') === NULL) {
      $form_state->set('locations', $config->get('locations') ?? []);
    }
    $locations = $form_state->get('locations');
    
    $form['locations'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Map Locations'),
      '#prefix' => '<div id="locations-wrapper">',
      '#suffix' => '</div>',
    ];
    
    $form['locations']['items'] = [
      '#type' => 'table',
      '#header' => [
        $this->t('ID'),
        $this->t('Title'),
        $this->t('Body'),
        $this->t('Latitude'),
        $this->t('Longitude'),
        $this->t('Country'),
        $this->t('Country Code'),
        $this->t('Operations'),
      ],
      '#empty' => $this->t('No locations available.'),
    ];

    foreach ($locations as $key => $location) {
      $form['locations']['items'][$key]['id'] = [
        '#type' => 'textfield',
        '#default_value' => $location['id'],
        '#size' => 10,
        '#required' => TRUE,
      ];
      $form['locations']['items'][$key]['title'] = [
        '#type' => 'textfield',
        '#default_value' => $location['title'],
        '#size' => 20,
        '#required' => TRUE,
      ];
      $form['locations']['items'][$key]['body'] = [
        '#type' => 'textarea',
        '#default_value' => $location['body'],
        '#rows' => 2,
      ];
      $form['locations']['items'][$key]['latitude'] = [
        '#type' => 'number',
        '#default_value' => $location['latitude'],
        '#step' => 0.000001,
        '#required' => TRUE,
      ];
      $form['locations']['items'][$key]['longitude'] = [
        '#type' => 'number',
        '#default_value' => $location['longitude'],
        '#step' => 0.000001,
        '#required' => TRUE,
      ];
      $form['locations']['items'][$key]['country'] = [
        '#type' => 'textfield',
        '#default_value' => $location['country'],
        '#size' => 50,
        '#required' => TRUE,
      ];
      $form['locations']['items'][$key]['country_code'] = [
        '#type' => 'textfield',
        '#default_value' => $location['country_code'],
        '#size' => 5,
        '#required' => TRUE,
      ];
      $form['locations']['items'][$key]['remove'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove'),
        '#submit' => ['::removeLocation'],
        '#name' => 'remove_' . $key,
        '#ajax' => [
          'callback' => '::ajaxCallback',
          'wrapper' => 'locations-wrapper',
        ],
      ];
    }

    $form['add_location'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add Location'),
      '#submit' => ['::addLocation'],
      '#ajax' => [
        'callback' => '::ajaxCallback',
        'wrapper' => 'locations-wrapper',
      ],
    ];

    return parent::buildForm($form, $form_state);

  }

  /**
   * Adds a location row.
   TODO: need to create paragraph element to add more locations than the default in config form
   */
 public function addLocation(array &$form, FormStateInterface $form_state) {
     
     // Get the current list of locations.
    $locations = $form_state->get('locations') ?? [];
    
    // Add a new empty location entry.
    $locations[] = [
      'id' => '',
      'title' => '',
      'body' => '',
      'latitude' => '',
      'longitude' => '',
      'country' => '',
      'country_code' => '',
    ];

    // Update the form state and set it to rebuild.
    $form_state->set('locations', $locations);
    $form_state->setRebuild();
  }

  /**
   * Removes a location row.
   TODO: need to create paragraph element to remove a location in config form
   */
  public function removeLocation(array &$form, FormStateInterface $form_state) {
    $locations = $form_state->get('locations');
    $triggering_element = $form_state->getTriggeringElement();
    $row_index = str_replace('remove_', '', $triggering_element['#name']);
    unset($locations[$row_index]);
    $form_state->set('locations', array_values($locations));
    $form_state->setRebuild();
  }
  
  public function ajaxCallback(array &$form, FormStateInterface $form_state) {
    return $form['locations'];
  }
  
  /**
   * Submit handler.
   */
  /*public function submitForm(array &$form, FormStateInterface $form_state) {
     $this->config('custom_vectormap.settings')
      ->set('id', $form_state->getValue('id'))
      ->set('title', $form_state->getValue('title'))
      ->set('body', $form_state->getValue('body'))
      ->set('latitude', $form_state->getValue('latitude'))
      ->set('longitude', $form_state->getValue('longitude'))
      ->set('country',$form_state->getValue('country'))
      ->set('country_code', $form_state->getValue('country_code'))
      ->save();

    parent::submitForm($form, $form_state);
  }*/
  
 public function submitForm(array &$form, FormStateInterface $form_state) {
     $values = $form_state->getValues();
     
    // Retrieve locations from the form state, defaulting to an empty array if not set.
  $locations = $values['items'];
  \Drupal::logger('custom_vectormap')->notice('Saving locations: @locations', ['@locations' => print_r($locations, TRUE)]);
  // Save only non-empty location entries.
  $locations = array_filter($locations, function ($location) {
    return !empty($location['id']) || !empty($location['title']);
  });
\Drupal::logger('custom_vectormap')->notice('Saving locations: @locations', ['@locations' => print_r($locations, TRUE)]);    
    $this->config('custom_vectormap.settings')
      ->set('locations', $locations)
      ->save();

    parent::submitForm($form, $form_state);
  }
}
