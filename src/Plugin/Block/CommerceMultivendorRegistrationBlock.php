<?php

/**
 *
 * @file
 * Contains \Drupal\custom_feature\Plugin\Block\ArticleBlock.
 */
namespace Drupal\commerce_multivendor\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\user\Entity\User;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Provides a 'commerce_multivendor' block.
 *
 * @Block(
 * 
 * id = "commerce_multivendor_seller_registration_block",
 * admin_label = @Translation("Seller Registration Block"),
 * category = @Translation("Commerce Multivendor")
 * )
 */
class CommerceMultivendorRegistrationBlock extends BlockBase {

    /**
     *
     * {@inheritdoc}
     */
    public function build() {
    	        
        $output = '<div class="seller-registration-link">' . Link::fromTextAndUrl('Click here to register', \Drupal\Core\Url::fromUserInput('/register/seller'))->toString() . '</div>';
        
        $build['#cache']['max-age'] = 0;
        
        $build = array( 
            '#type' => 'markup',
            '#markup' => $output,
            '#cache' => array( 
                'max-age' => 0 
            ) 
        );
       
        return $build;
    }
}
