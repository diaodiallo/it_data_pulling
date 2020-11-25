<?php
/**
 * Created by PhpStorm.
 * User: ddiallo
 * Date: 2/13/20
 * Time: 5:14 PM
 */

namespace Drupal\it_data_pulling\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\it_data_pulling\ItDataPullingService;
use Drupal\it_data_pulling\Util\ItDataPullingUtility;


class ItDataPullingPullForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return "it_data_pulling_pull";
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('it_data_pulling.settings');

    $form['header']['#markup'] = t('Choose the way you want to pull data from the remote server');

    $form['pull_type'] = [
      '#type' => 'radios',
      '#title' => $this->t('Choose the type of pulling'),
      '#options' => [
        'last_pull' => $this->t('Pull from the last time'),
        'full_pull' => $this->t('Full pulling'),
      ],
      '#default_value' => $config->get('pull_type'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    // Add a button to pull data.
    $form['actions']['pull'] = [
      '#type' => 'submit',
      '#value' => $this->t('Pull'),
    ];
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $config = \Drupal::service('config.factory')
      ->getEditable('it_data_pulling.settings');
    $itDataPullingService = new ItDataPullingService();

    $formid = $config->get('forms');
    $url = 'data/' . $formid;
    $pull_type = $form_state->getValue('pull_type');
    $last_pull = $config->get('last_pull');
    if ($pull_type == 'full_pull' || is_null($last_pull)) {
      $removed = $itDataPullingService->removeContent();
      $data = $itDataPullingService->getData($url);
      $newCount = 0;
      $updateCount = 0;

      //drupal_set_message(json_encode($data[6]));
      foreach ($data as $single) {
        if ($itDataPullingService->createContent($single)) {
          $updateCount = $updateCount + 1;
        }
        else {
          $newCount = $newCount + 1;
        }
      }
      $messenger = \Drupal::messenger();
      $messenger->addMessage('Full pulling: ' . $removed . ' content removed and ' . $newCount . ' new content(s) created');
    }
    elseif ($pull_type == 'last_pull') {
      //todo test if submission change when edited
      $param = '?query={"_submission_time":{"$gte":"' . date('Y-m-d', $last_pull) . '"}}';
      $url = $url . $param;
      $data = $itDataPullingService->getData($url);
      $newCount = 0;
      $updateCount = 0;

      foreach ($data as $single) {
        if ($itDataPullingService->createContent($single)) {
          $updateCount = $updateCount + 1;
        }
        else {
          $newCount = $newCount + 1;
        }
      }
      $messenger = \Drupal::messenger();
      $messenger->addMessage('From last commit: ' . $updateCount . ' content(s) updated and ' . $newCount . ' new content(s) created');
    }

    $config->set('pull_type', $form_state->getValue('pull_type'));
    $config->set('last_pull', \Drupal::time()->getRequestTime());
    $config->save();
    \Drupal::logger('it_data_pulling')->info('Run pulling.');
    $form_state->setRedirect('it_data_pulling.settings');
  }

}