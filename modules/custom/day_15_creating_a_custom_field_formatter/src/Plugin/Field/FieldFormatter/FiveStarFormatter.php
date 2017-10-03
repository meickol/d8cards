<?php
/**
 * Created by PhpStorm.
 * User: maicollopez
 * Date: 8/29/17
 * Time: 15:12
 */

namespace Drupal\day_15_creating_a_custom_field_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\DecimalFormatter;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'image_title_caption' formatter.
 *
 * @FieldFormatter(
 *   id = "five_start_formatter",
 *   label = @Translation("Five Start Format"),
 *   field_types = {
 *     "decimal"
 *   }
 * )
 */
class FiveStarFormatter extends DecimalFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {

      $elements[$delta] = [
        '#theme' => 'five_star_formatter',
        '#ratingvalue' => $item->value * 20,
        '#attached' => array('library'=> array('day_15_creating_a_custom_field_formatter/five_star')),
      ];
    }

    return $elements;
  }

}