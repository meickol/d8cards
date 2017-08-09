<?php
/**
 * Created by PhpStorm.
 * User: maicollopez
 * Date: 8/7/17
 * Time: 23:27
 */

namespace Drupal\day_07_cron_queuing\Plugin\QueueWorker;


use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 * Class CronQueuing
 *
 * @QueueWorker(
 *   id = "email_cron_queue",
 *   title = @Translation("Email Cron Queue"),
 *   cron = {"time" = 90}
 * )
 *
 */
class EmailCronQueuing extends QueueWorkerBase implements ContainerFactoryPluginInterface {


  /**
   * The mail manager
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected $mailManager;

  /**
   * The mail manager
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerManager;

  /**
   * EmailCronQueuing constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Mail\MailManagerInterface $mail_manager
   *  The mail manager.
   * * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_manager
   *  The logger manager.
   *
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, $mail_manager, $logger_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->mailManager = $mail_manager;
    $this->loggerManager = $logger_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition,
      $container->get('plugin.manager.mail'),
      $container->get('logger.factory')
    );
  }

  public function processItem($data) {

    $user = User::load($data->uid);

    $module = 'day_07_cron_queuing';
    $key = 'create_user';
    $to = $user->getEmail();
    $params['body'] = 'User Created - From queue';
    $params['subject'] = 'Message from queue';
    $langcode = $user->getPreferredLangcode();
    $result = $this->mailManager->mail($module, $key, $to, $langcode, $params, NULL, TRUE);

    if ($result['result'] != true) {
      $message = t('There was a problem sending your email notification to @email.', array('@email' => $to));
      drupal_set_message($message, 'error');
    }
    else {
      $message = t('An email notification has been sent to @email ', array('@email' => $to));
      drupal_set_message($message);

    }
    $this->loggerManager->get('day_07_cron_queuing')->notice($message);

  }

}