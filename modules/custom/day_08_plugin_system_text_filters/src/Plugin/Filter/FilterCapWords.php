<?php
namespace Drupal\day_08_plugin_system_text_filters\Plugin\Filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * @Filter(
 *   id = "filter_cap_words",
 *   title = @Translation("Cap Words Filter"),
 *   description = @Translation("This filter capitalize some words"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 * )
 */
class FilterCapWords extends FilterBase{

  public function process($text, $langcode) {

    $words = $this->settings['caps_words_list'];
    $words = explode(', ', $words);

    foreach($words as $key => $value){
      dump($value);
      $new_value = strtoupper($value);
      $text = str_replace($value, $new_value, $text);
      dump($text);
    }

    return new FilterProcessResult($text);
  }

  public function settingsForm(array $form, FormStateInterface $form_state) {

    $form['caps_words_list'] = [
      '#type' => 'textarea',
      '#title' => 'Words to Capitalize',
      '#default_value' => $this->settings['caps_words_list'],
      '#description' => $this->t('Enter the list of words in small case, which should be capitalized. <br/> Separate multiple words with comma (,) <br/><br/> Example: drupal, wordpress, jommla'),
    ];

    return $form;
  }

}
