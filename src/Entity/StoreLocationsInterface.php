<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Drupal\commerce_multivendor\Entity;


use Drupal\address\AddressInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a Inventory entity.
 * @ingroup manage_inventory
 */
interface StoreLocationsInterface extends ContentEntityInterface, EntityOwnerInterface {
  
  /**
   * Gets the store name.
   *
   * @return string
   *   The store name.
   */
  public function getName();

  /**
   * Sets the store name.
   *
   * @param string $name
   *   The store name.
   *
   * @return $this
   */
  public function setName($name);

  /**
   * Gets the store address.
   *
   * @return \Drupal\address\AddressInterface
   *   The store address.
   */
  public function getAddress();

  /**
   * Sets the store address.
   *
   * @param \Drupal\address\AddressInterface $address
   *   The store address.
   *
   * @return $this
   */
  public function setAddress(AddressInterface $address);

}
