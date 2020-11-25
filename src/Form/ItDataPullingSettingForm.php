<?php
/**
 * Created by PhpStorm.
 * User: ddiallo
 * Date: 2/12/20
 * Time: 1:28 PM
 */

namespace Drupal\it_data_pulling\Form;

use \Drupal\Core\Form\ConfigFormBase;
use \Drupal\Core\Form\FormStateInterface;
use Drupal\it_data_pulling\ItDataPullingService;

class ItDataPullingSettingForm extends configFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'it_data_pulling_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['it_data_pulling.settings'];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('it_data_pulling.settings');

    $itDataPullingService = new ItDataPullingService();
    $formOptions = $itDataPullingService->getForms();
    $contentTypeOptions = $itDataPullingService->getContentTypes();

    $form['access_settings'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Access settings'),
    );

    $form['access_settings']['data_type'] = [
      '#title' => $this->t('Data type'),
      '#type' => 'radios',
      '#options' => [
        'odk' => $this->t('ODK data'),
        'dhis2' => $this->t('DHIS 2 data'),
      ],
      '#description' => $this->t('Pull data from ONA or a DHIS 2 instance.'),
      '#default_value' => $config->get('data_type'),
    ];

    $form['access_settings']['link'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Link to the remote server'),
      '#size' => 50,
      '#maxlength' => 50,
      '#default_value' => $config->get('link'),
      '#required' => TRUE,
    );

    $form['access_settings']['user_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Fill the access user name'),
      '#size' => 20,
      '#maxlength' => 20,
      '#default_value' => $config->get('user_name'),
      '#required' => TRUE,
    );

    $form['access_settings']['password'] = array(
      '#type' => 'password',
      '#title' => $this->t('Fill the access password'),
      '#size' => 20,
      '#maxlength' => 20,
      '#default_value' => $config->get('password'),
      '#required' => TRUE,
    );

    $form['data'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Remote data'),
    );

    $form['data']['content_type'] = array(
      '#type' => 'select',
      '#title' => $this->t('Select the content type to use.'),
      '#description' => $this->t('Please do the field mapping'),
      '#options' => !empty($contentTypeOptions) ? $contentTypeOptions : 'No content type',
      '#default_value' => $config->get('content_type'),
    );

    $form['data']['forms'] = array(
      '#type' => 'select',
      '#title' => $this->t('Select the form to use.'),
      '#description' => $this->t('Please verify the connexion and save before'),
      '#options' => !empty($formOptions) ? $formOptions : 'No forms in this connexion',
      '#default_value' => $config->get('forms'),
    );

    //todo add cron time params: can see in denormalizer

    return parent::buildForm($form, $form_state);

  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
//    $this->config('it_data_pulling.settings')
//      ->setData($form_state->getValues())
//      ->save();
    $config = \Drupal::service('config.factory')->getEditable('it_data_pulling.settings');
    $config->set('link', $form_state->getValue('link'));
    $config->set('user_name', $form_state->getValue('user_name'));
    $config->set('password', $form_state->getValue('password'));
    $config->set('forms', $form_state->getValue('forms'));
    $config->set('content_type', $form_state->getValue('content_type'));
    $config->save();

    parent::submitForm($form, $form_state);
  }
}