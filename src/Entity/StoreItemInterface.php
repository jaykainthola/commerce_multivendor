<?php

namespace Drupal\commerce_multivendor\Entity;

use Drupal\commerce\PurchasableEntityInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\core_extend\Entity\EntityActiveInterface;
use Drupal\core_extend\Entity\EntityCreatedInterface;

/**
 * Provides an interface for defining Inventory Item entities.
 *
 * @ingroup commerce_inventory
 */
interface StoreItemInterface extends ContentEntityInterface, EntityActiveInterface, EntityChangedInterface, EntityCreatedInterface {

  /**
   * Sets this Inventory Item's related Location id.
   *
   * @param int $location_id
   *   The Inventory Location id to relate to this Inventory item.
   *
   * @return $this
   *   Return this object.
   */
  public function setLocationId($location_id);

  /**
   * Get this Inventory Item's related Location id.
   *
   * @return int|null
   *   The related Location id.
   */
  public function getLocationId();

  /**
   * Sets this Inventory Item's related Location entity.
   *
   * @param \Drupal\commerce_inventory\Entity\InventoryLocationInterface $location
   *   The Inventory Location entity to relate to this Inventory item.
   *
   * @return $this
   *   Return this object.
   */
  public function setLocation(InventoryLocationInterface $location);

  /**
   * Get this Inventory Item's related Location entity.
   *
   * @return \Drupal\commerce_inventory\Entity\InventoryLocationInterface|null
   *   The Inventory Item's Location entity.
   */
  public function getLocation();


  /**
   * Get this Inventory Item's related Purchasable Entity type.
   *
   * @return string|null
   *   The Purchasable Entity type id.
   */
  public function getPurchasableEntityTypeId();

  /**
   * Get this Inventory Item's related Purchasable Entity type id.
   *
   * @return \Drupal\Core\Entity\EntityTypeInterface|null
   *   The Purchasable Entity Type entity-type instance. Null otherwise.
   */
  public function getPurchasableEntityType();

  /**
   * Get this Inventory Item's related Purchasable Entity id.
   *
   * @return int|null|string
   *   The Purchasable Entity ID. Null otherwise.
   */
  public function getPurchasableEntityId();

  /**
   * Sets this Inventory Item's related Purchasable Entity.
   *
   * @param \Drupal\commerce\PurchasableEntityInterface $purchasableEntity
   *   The Purchasable Entity to relate to this Inventory item.
   *
   * @return $this
   *   Return this object.
   */
  public function setPurchasableEntity(PurchasableEntityInterface $purchasableEntity);

  /**
   * Get this Inventory Item's related Purchasable Entity.
   *
   * @return \Drupal\commerce\PurchasableEntityInterface|null
   *   The Inventory Item's Purchasable Entity.
   */
  public function getPurchasableEntity();

  /**
   * Get this Inventory Item's related Purchasable Entity's label.
   *
   * @param bool $link
   *   Whether the label should be linked.
   *
   * @return \Drupal\Core\Link|\Drupal\Core\StringTranslation\TranslatableMarkup|string
   *   The Inventory Item's Purchasable Entity label.
   */
  public function getPurchasableEntityLabel($link = FALSE);

  /**
   * Returns the current quantity count of this Inventory Item.
   *
   * @param bool $available_only
   *   Whether the count should only include available (unspoken-for) inventory.
   *
   * @return float
   *   The inventory count.
   */
  public function getQuantity($available_only = TRUE);

 
}
