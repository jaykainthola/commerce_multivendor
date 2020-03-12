<?php
/**
 * @file
 * Contains \Drupal\commerce_multivendor\Entity\StoreLocations.
 */
namespace Drupal\commerce_multivendor\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\commerce_multivendor\Entity\StoreItemInterface;
use Drupal\user\UserInterface;
use CommerceGuys\Addressing\AddressFormat\AddressField;
use CommerceGuys\Addressing\AddressFormat\FieldOverride;
use Drupal\address\AddressInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Store Locations entity.
 *
 * @ingroup commerce_multivendor
 *
 * @ContentEntityType(
 *   id = "commerce_store_locations",
 *   label = @Translation("Store Locations"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\commerce_multivendor\StoreLocationsListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\commerce_multivendor\Form\StoreLocationsForm",
 *       "edit" = "Drupal\commerce_multivendor\Form\StoreLocationsForm",
 *       "delete" = "Drupal\commerce_multivendor\Form\StoreLocationsDeleteForm",
 *     },
 *     "access" = "Drupal\commerce_multivendor\StoreLocationsAccessControlHandler",
 *   },
 *   base_table = "commerce_store_locations",
 *   data_table = "commerce_store_field_data",
 *   admin_permission = "administer commerce_multivendor entity",
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
 *     "collection" = "/commerce_store_locations/list",
 *     "inventory" = "/commerce_store_locations/{commerce_store_locations}/inventory"
 *   },
 *   field_ui_base_route = "commerce_multivendor.store_locations_settings",
 * )
 * 
 */
class StoreLocations extends ContentEntityBase implements StoreLocationsInterface {
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
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  
  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->getEntityKey('owner');
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  
  /**
   * {@inheritdoc}
   */
  public function getAddress() {
    return $this->get('address')->first();
  }

  /**
   * {@inheritdoc}
   */
  public function setAddress(AddressInterface $address) {
    // $this->set('address', $address) results in the address being appended
    // to the item list, instead of replacing the existing first item.
    $this->address = $address;
    return $this;
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
    
     $fields = parent::baseFieldDefinitions($entity_type);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the inventory entity.'))
      ->setReadOnly(TRUE);
    
    
      $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Owner'))
      ->setDescription(t('The store owner.'))
      ->setDefaultValueCallback('Drupal\commerce_multivendor\Entity\StoreLocations::getCurrentUserId')
      ->setSetting('target_type', 'user')
      ->setDisplayOptions('form', [
          'type' => 'entity_reference_autocomplete',
          'weight' => 50,
      ]);
      
      
    
    // Name field for the contact.
    // We set display options for the view as well as the form.
    // Users with correct privileges can change the view and edit configuration.
    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Store Locations entity.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
     $fields['address'] = BaseFieldDefinition::create('address')
      ->setLabel(t('Address'))
      ->setDescription(t('The store address.'))
      ->setCardinality(1)
      ->setRequired(TRUE)
      ->setSetting('field_overrides', [
        AddressField::GIVEN_NAME => ['override' => FieldOverride::HIDDEN],
        AddressField::ADDITIONAL_NAME => ['override' => FieldOverride::HIDDEN],
        AddressField::FAMILY_NAME => ['override' => FieldOverride::HIDDEN],
        AddressField::ORGANIZATION => ['override' => FieldOverride::HIDDEN],
      ])
      ->setDisplayOptions('form', [
        'type' => 'address_default',
        'settings' => [
          'default_country' => 'IN',
        ],
        'weight' => 3,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
  
     
      $fields['path'] = BaseFieldDefinition::create('path')
      ->setLabel(t('URL alias'))
      ->setDescription(t('The store URL alias.'))
      ->setTranslatable(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'path',
        'weight' => 30,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setCustomStorage(TRUE);
      
      
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

