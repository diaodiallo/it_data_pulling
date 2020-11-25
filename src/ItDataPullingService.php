<?php
/**
 * Created by PhpStorm.
 * User: ddiallo
 * Date: 2/12/20
 * Time: 1:00 PM
 */

namespace Drupal\it_data_pulling;

use Drupal\Core\Config\ConfigFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Exception\ConnectException;
use Drupal\Core\Entity;
use Drupal\it_data_pulling\Util\ItDataPullingUtility;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Class ItDataPullingService
 *
 * @package Drupal\it_data_pulling
 */
class ItDataPullingService implements ItDataPullingServiceInterface {

  private $username;

  private $password;

  private $baseUrl;

  private $contentType;

  /**
   * Constructs a new ItDataPullingService object.
   */
  public function __construct() {

    $config = \Drupal::service('config.factory')
      ->getEditable('it_data_pulling.settings');

    $this->username = $config->get('user_name');
    $this->password = $config->get('password');
    $this->baseUrl = $config->get('link');
    $this->contentType = $config->get('content_type');
  }

  /**
   *
   */
  public function testLogin() {

    try {
      $this->login('');
      return 'Successful connexion';
    } catch (ClientException $e) {
      \Drupal::logger('it_data_pulling')->notice($e->getMessage());
      return 'Invalid user or password';
    } catch (TooManyRedirectsException $e) {
      return $e->getMessage();
    } catch (ConnectException $e) {
      \Drupal::logger('it_data_pulling')->notice($e->getMessage());
      return 'Sorry can\'t access to this server please verify the link, ie: https://api.ona.io/api/v1/';
    }

  }

  /**
   * Login to the remote server.
   *
   * @param $url
   *
   * @return mixed
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function login(string $url) {
    $client = new Client();

    $response = $client->request('GET', $this->baseUrl . $url, [
      'auth' => [
        $this->username,
        $this->password,
        'basic',
      ],
    ]);

    return json_decode($response->getBody()->getContents(), TRUE);
  }

  /**
   * @return array of remote server forms
   */
  public function getForms() {
    $url = 'forms';
    $forms = [];

    try {
      $result = $this->login($url);
      foreach ($result as $form) {
        $owner = explode('/', $form['owner']);
        $owner = end($owner);
        $forms[$form['formid']] = $form['title'] . ' - owner: ' . $owner;
      }
      return $forms;
    } catch (ClientException $e) {
      \Drupal::logger('it_data_pulling')->notice($e->getMessage());
      return 'Invalid user or password';
    } catch (TooManyRedirectsException $e) {
      return $e->getMessage();
    } catch (ConnectException $e) {
      \Drupal::logger('it_data_pulling')->notice($e->getMessage());
      return 'Sorry can\'t access to this server please verify the link, ie: https://api.ona.io/';
    }
  }

  public function getContentTypes() {

    $types = \Drupal::entityTypeManager()
      ->getStorage('node_type')
      ->loadMultiple();

    $options = [];
    foreach ($types as $node_type) {
      $options[$node_type->id()] = $node_type->label();
    }

    return $options;
  }

  /**
   * Get the data from a specific form
   */

  public function getData($url) {

    try {
      return $this->login($url);
    } catch (ClientException $e) {
      \Drupal::logger('it_data_pulling')->notice($e->getMessage());
      return 'Invalid user or password';
    } catch (TooManyRedirectsException $e) {
      return $e->getMessage();
    } catch (ConnectException $e) {
      \Drupal::logger('it_data_pulling')->notice($e->getMessage());
      return 'Sorry can\'t access to this server please verify the link, ie: https://api.ona.io/';
    }
  }

  /**
   * Comapare existing node and all meetings.
   */

  /**
   * Create new content
   */
  public function createContent($data) {

    $itDataPullingUtility = new ItDataPullingUtility();
    $mapping = $itDataPullingUtility->getMapping();
    $exist = FALSE;

    $entity = \Drupal::entityTypeManager()
      ->getStorage('node');
    $my_feedback = $entity->loadByProperties(['title' => $data['_id']]);
    if (!empty($my_feedback)) {
      $entity->delete($my_feedback);
      $exist = TRUE;
    }

    $entity_definition = \Drupal::entityTypeManager()
      ->getDefinition('node');
    $values = [
      $entity_definition->getKey('bundle') => $this->contentType,
      'title' => $data['_id'],
    ];
    foreach ($mapping as $map) {
      $onaValue = '';
      //todo later add the vocabulary in the util array
      if ($taxoName = $itDataPullingUtility->isTaxo($data[$map[2]])) {
        if ($map[2] == 'selected_country_Country') {
          $onaValue = $itDataPullingUtility->getTid($taxoName, 'country');
        }
        elseif ($map[2] == 'selected_country_County') {
          $onaValue = $itDataPullingUtility->getTid($taxoName, 'kenya_county');
        }
        elseif ($map[2] == 'selected_country') {
          $onaValue = $itDataPullingUtility->getTid($taxoName, 'kenya_impact_team');
        }
      }
      elseif ($map[2] == 'officer_organization') {
        $taxoName = $itDataPullingUtility->getTn($data[$map[2]]);
        $onaValue = $itDataPullingUtility->getTid($taxoName, 'organization');
      }
      //todo manage date fields to be more dynamic (having another methods)
      elseif ($map[2] == 'scheduled_date_of_meeting' || $map[2] == 'date_of_meeting' || $map[2] == 'scheduled_start_time' ||
        $map[2] == 'start_time_of_meeting' || $map[2] == 'end_time_of_meeting') {
        //drupal_set_message(json_encode($data[$map[2]]));
        $dateTime = '';
        if (strlen($data[$map[2]]) == 10) {
          $dateTime = $data[$map[2]].'T00:00:00';
        }
        elseif (strlen($data[$map[2]]) == 18) {
          $dateTime = $data['date_of_meeting'].'T'.substr($data[$map[2]], 0, 8);
        }
//        drupal_set_message(json_encode(sizeof($data[$map[2]])));
        $onaValue = [[
          "value" => $dateTime
        ]
        ];
      }
      else {
        $onaValue = $data[$map[2]];
      }
      if ($map[0] == 'field') {
        $values[$map[1]] = $onaValue;
      }
      //todo find the awy make dynamic how multi select detected(testing if a field is a multi select
      //Ind 1, Ind 2, )
      elseif ($map[0] == 'paragraph') {
        $paragraph = Paragraph::create([
          'type' => $map[2],
        ]);
        foreach ($map[3] as $value) {
          if ($value[0] == 'leadership_present' || $value[0] == 'attendance') {
            $valueTable = explode(' ', $data[$value[0]]);
            if (sizeof($valueTable) > 1) {
              $paragraph->set($value[1], $valueTable);
            }
            else {
              $paragraph->set($value[1], $data[$value[0]]);
            }
          }
          else {
            $paragraph->set($value[1], $data[$value[0]]);
          }
        }
        $paragraph->save();

        $values[$map[1]] = [
          [
            'target_id' => $paragraph->id(),
            'target_revision_id' => $paragraph->getRevisionId(),
          ],
        ];
      }
    }
    $entity->create($values)->save();

    return $exist;
  }

  /**
   * Remove content to pull data
   */
  public function removeContent() {

    //todo use Batch API for performance reason
    $storage_handler = \Drupal::entityTypeManager()->getStorage("node");
    $entities = $storage_handler->loadByProperties(["type" => $this->contentType]);
    try {
      $storage_handler->delete($entities);
    } catch (Entity\EntityStorageException $e) {
      \Drupal::logger('it_data_pulling')->info($e->getMessage());
    }

    return count($entities);
  }

  /**
   * Get year
   */

  /**
   * get month
   */

  /**
   * build dateTime strings
   */
}