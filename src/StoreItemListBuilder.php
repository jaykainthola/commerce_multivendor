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
class StoreItemListBuilder extends EntityListBuilder {
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
    $header['purchaseable_entity'] = $this->t('Product');
    $header['quantity'] = $this->t('Quantity');
    return $header + parent::buildHeader();
  }
  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\manage_inventory\Entity\Inventory */
    
    $row['purchaseable_entity'] = $entity->purchasable_entity->value;
    $row['quantity'] = $entity->quantity->value;
    return $row + parent::buildRow($entity);
  }
}
