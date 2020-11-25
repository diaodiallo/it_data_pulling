<?php
/**
 * Created by PhpStorm.
 * User: ddiallo
 * Date: 2/12/20
 * Time: 12:59 PM
 */

namespace Drupal\it_data_pulling;

/**
 * Interface ItDataPullingServiceInterface
 *
 * @package Drupal\it_data_pulling
 */

Interface ItDataPullingServiceInterface {

  public function login(string $url);

  public function testLogin();

  /**
   * Connexion function
   */

}