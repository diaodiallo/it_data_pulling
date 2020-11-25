<?php
/**
 * Created by PhpStorm.
 * User: ddiallo
 * Date: 2/12/20
 * Time: 1:44 PM
 */

namespace Drupal\it_data_pulling\Form;

use \Drupal\Core\Form\ConfirmFormBase;
use \Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\it_data_pulling\ItDataPullingService;


class ItDataPullingTestConnexionForm extends confirmFormBase {

  public function getFormId() {
    return 'it_data_pulling_test_connexion';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['header']['#markup'] = t('Ready? This will test the connexion to the remote server.');
    return parent::buildForm($form, $form_state);
  }

  public function getQuestion() {

    return null;
    //return $this->t('Ready? This will test the connexion to the remote server.');
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

    $itDataPullingService = new ItDataPullingService();

    $messenger = \Drupal::messenger();
    $messenger->addMessage($itDataPullingService->testLogin());
    $form_state->setRedirect('it_data_pulling.settings');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('it_data_pulling.settings');
  }

}