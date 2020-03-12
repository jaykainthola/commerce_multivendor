<?php
/**
 * @file
 * Contains \Drupal\commerce_multivendor\Entity\StoreItem.
 */
namespace Drupal\commerce_multivendor\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\commerce_multivendor\Entity\StoreItemInterface;
use Drupal\core_extend\Entity\EntityActiveTrait;
use Drupal\core_extend\Entity\EntityCreatedTrait;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\commerce\PurchasableEntityInterface;

/**
 * Defines the Store Locations entity.
 *
 * @ingroup commerce_multivendor
 *
 * @ContentEntityType(
 *   id = "commerce_store_item",
 *   label = @Translation("Store Item"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\commerce_multivendor\StoreItemListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\commerce_multivendor\Form\StoreItemForm",
 *       "edit" = "Drupal\commerce_multivendor\Form\StoreItemForm",
 *       "delete" = "Drupal\commerce_multivendor\Form\StoreItemDeleteForm",
 *     },
 *     "access" = "Drupal\commerce_multivendor\StoreItemAccessControlHandler",
 *   },
 *   base_table = "commerce_store_item",
 *   data_table = "commerce_store_item_field_data",
 *   admin_permission = "administer commerce inventory entity",
 *   fieldable = TRUE,
  *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid", 
 *     "label" = "name",
 *     "langcode" = "langcode",
 *     "owner" = "uid",
 *     "uid" = "uid",
 *   },
 *   links = {
 *     "canonical" = "/commerce_store_locations/{commerce_store_locations}",
 *     "edit-form" = "/commerce_store_locations/{commerce_store_locations}/edit",
 *     "delete-form" = "/storelocations/{commerce_store_locations}/delete",
 *     "collection" = "/commerce_store_locations/list"
 *   },
 *   field_ui_base_route = "commerce_multivendor.store_locations_settings",
 * )
 * 
 */
class StoreItem extends ContentEntityBase implements StoreItemInterface {
    
  use EntityActiveTrait;
  use EntityChangedTrait;
  use EntityCreatedTrait;
  
   /**
   * The quantity available manager.
   *
   * @var \Drupal\commerce_multivendor\QuantityManagerInterface
   */
  protected $quantityAvailable;
  
  /**
   * Returns the quantity available manager.
   *
   * @return \Drupal\commerce_multivendor\QuantityManagerInterface
   *   The quantity available manager.
   */
  protected function getQuantityAvailableManager() {
    if (is_null($this->quantityAvailable)) {
      $this->quantityAvailable = \Drupal::service('commerce_multivendor.quantity_available');
    }
    return $this->quantityAvailable;
  }
  
  /**
   * {@inheritdoc}
   *
   * When a new entity instance is added, set the user_id entity reference to
   * the current user as the creator of the instance.
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'uid' => \Drupal::currentUser()->id(),
    );
  }
  
 /**
   * {@inheritdoc}
   */
  public function setLocationId($location_id) {
    if (is_int($location_id)) {
      $this->set('store_id', $location_id);
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLocationId() {
    if (!$this->get('store_id')->isEmpty()) {
      return $this->get('store_id')->target_id;
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function setLocation(InventoryLocationInterface $location) {
    $this->set('store_id', $location->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLocation() {
    if (!$this->get('store_id')->isEmpty()) {
      return $this->get('store_id')->entity;
    }
    return NULL;
  }
  
  /**
   * {@inheritdoc}
   */
  public function getPurchasableEntityTypeId() {
    if ($this->get('purchasable_entity')->isEmpty() == FALSE) {
      return $this->get('purchasable_entity')->target_type;
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getPurchasableEntityType() {
    return $this->entityTypeManager()->getDefinition($this->getPurchasableEntityTypeId(), FALSE);
  }

  /**
   * {@inheritdoc}
   */
  public function getPurchasableEntityId() {
    if ($this->get('purchasable_entity')->isEmpty() == FALSE) {
      return $this->get('purchasable_entity')->target_id;
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getPurchasableEntity() {
    if ($this->get('purchasable_entity')->isEmpty() == FALSE) {
      return $this->get('purchasable_entity')->entity;
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getPurchasableEntityLabel($link = FALSE) {
    if ($purchasable = $this->getPurchasableEntity()) {
      if ($link) {
        return $purchasable->toLink()->toString();
      }
      return $purchasable->label();
    }
    return t('(Missing purchasable)');
  }

  /**
   * {@inheritdoc}
   */
  public function setPurchasableEntity(PurchasableEntityInterface $purchasableEntity) {
    // Set Purchasable Entity information if it has been given an ID.
    if ($purchasableEntity->id()) {
      $this->set('purchasable_entity', $purchasableEntity);
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getQuantity($available_only = TRUE) {
    // Exit early if id isn't set.
    if (is_null($this->id())) {
      return 0;
    }

    // Return available quantity.
    if ($available_only) {
      return $this->getQuantityAvailableManager()->getQuantity($this->id());
    }

    // Return on-hand quantity.
    return $this->getQuantityOnHandManager()->getQuantity($this->id());
  }

  /**
   * {@inheritdoc}
   *
   * Define the field properties here.
   *
   * Field name, type and size determine the table structure.
   *
   * In addition, we can define how the field and its content can be manipulated
   * in the GUI. The behaviour of the widgets used can be determined here.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    // Standard field, used as unique if primary index.
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['store_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Inventory Store'))
      ->setDescription(t('The location to track inventory of this purchasable entity.'))
      ->setCardinality(1)
      ->setRequired(TRUE)
      ->setReadOnly(TRUE)
      ->addConstraint('UniquePurchasableEntity')
      ->setSetting('target_type', 'commerce_store_locations')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['purchasable_entity'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Purchasable'))
      ->setDescription(t('The purchasable item in the inventory.'))
      ->setSetting('exclude_entity_types', FALSE)
      ->setSetting('entity_type_ids', [])
      ->setCardinality(1)
      ->setReadOnly(TRUE)
      ->setRequired(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['quantity'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Quantity'))
      ->setDescription(t('The quantity adjustment.'))
      ->setReadOnly(TRUE)
      ->setRequired(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }
  
  /**
   * Default value callback for 'uid' base field definition.
   *
   * @see ::baseFieldDefinitions()
   *
   * @return array
   *   An array of default values.
   */
  public static function getCurrentUserId() {
      return [\Drupal::currentUser()->id()];
  }
}

