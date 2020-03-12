<?php

namespace Drupal\commerce_multivendor;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Url;
/**
 * Provides a list controller for content_entity_manage_inventory entity.
 *
 * @ingroup manage_inventory
 */
class StoreLocationsListBuilder extends EntityListBuilder {
  /**
   * {@inheritdoc}
   *
   * We override ::render() so that we can add our own content above the table.
   * parent::render() is where EntityListBuilder creates the table using our
   * buildHeader() and buildRow() implementations.
   */
  public function render() {
    $build['description'] = array(
      '#markup' => $this->t('.'),
    );
    $build['table'] = parent::render();
    return $build;
  }
  /**
   * {@inheritdoc}
   *
   * Building the header and content lines for the inventory list.
   *
   * Calling the parent::buildHeader() adds a column for the possible actions
   * and inserts the 'edit' and 'delete' links as defined for the entity type.
   */
  public function buildHeader() {    
    $header['name'] = $this->t('Name');
    $header['address_city'] = $this->t('City');
    $header['address_state'] = $this->t('State');    
    return $header + parent::buildHeader();
  }
  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\manage_inventory\Entity\Inventory */
    
    $row['name'] = $entity->link();
    $row['address_city'] = $entity->address->locality;
    $row['address_state'] = $entity->address->administrative_area;    
    return $row + parent::buildRow($entity);
  }
}
