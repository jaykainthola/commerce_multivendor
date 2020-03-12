<?php

namespace Drupal\commerce_multivendor\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
/**
 * Form controller for the manage_inventory entity edit forms.
 *
 * @ingroup commerce_multivendor
 */
class StoreLocationsForm extends ContentEntityForm {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\commerce_multivendor\Entity\StoreLocations */
    $form = parent::buildForm($form, $form_state);
    
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $form_state->setRedirect('entity.commerce_store_locations.list');
    $entity = $this->getEntity();
    $entity->save();
  }
}