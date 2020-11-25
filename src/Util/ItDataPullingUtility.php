<?php
/**
 * Created by PhpStorm.
 * User: ddiallo
 * Date: 2/12/20
 * Time: 12:57 PM
 */

namespace Drupal\it_data_pulling\Util;


class ItDataPullingUtility {

  public function getMapping() {
    return [
      ['field','field_officer_name','officer_name'],
      ['field', 'field_officer_design','officer_design'],
      ['field', 'field_officer_organization','officer_organization'],
      ['field', 'field_other_organization','other'],
      ['field', 'field_country','selected_country_Country'],
      ['field', 'field_selected_county','selected_country_County'],
      ['field', 'field_selected_team','selected_country'],
      ['field','field_scheduled_meeting_date','scheduled_date_of_meeting'],
      ['field', 'field_meeting_date','date_of_meeting'],
      ['field', 'field_meeting_scheduled_time','scheduled_start_time'],
      ['field', 'field_meeting_start_time','start_time_of_meeting'],
      ['field', 'field_meeting_end_time','end_time_of_meeting'],
      ['field', 'field_participants_at_start', 'members_start'],
      ['field', 'field_participants_at_end', 'members_end'],
      ['paragraph', 'field_indicator_1', 'indicator_1', [['leadership_present', 'field_who_it_leader'],['other_leadership', 'field_other_it_leader']]],
      ['paragraph', 'field_indicator_2', 'indicator_2', [['attendance', 'field_who_attended'],['attendance_other', 'field_other_attended'],['attendance_IP', 'field_attendance_ip']]],
      ['paragraph', 'field_indicator_3', 'indicator_3', [['meeting_notification', 'field_notification'],['notification_comment', 'field_notification_comment']]],
      ['paragraph', 'field_indicator_4', 'indicator_4', [['majority_on_time', 'field_majority_on_time'],['on_time_comment', 'field_on_time_comment']]],
      ['paragraph', 'field_indicator_5', 'indicator_5', [['agenda_provided', 'field_agenda_provided'],['agenda_followed', 'field_agenda_followed'], ['agenda_provded_comment', 'field_agenda_provided_comment'], ['adopted_agenda', 'field_adopted_agenda'], ['other_agenda', 'field_other_agenda'], ['agenda_complete', 'field_agenda_complete'], ['agenda_missing_components', 'field_agenda_missing_components'], ['agenda_complete_comment', 'field_agenda_complete_comment']]],
      ['paragraph', 'field_indicator_6', 'indicator_6', [['data_materials_available', 'field_data_materials_available'],['data_materials_missing', 'field_data_materials_missing'],['data_materials_available_comment', 'field_data_materials_comment']]],
      ['paragraph', 'field_indicator_7', 'indicator_7', [['meeting_integration', 'field_meeting_integration'],['name_integrated_meeting', 'field_name_integrated_meeting'],['meeting_integration_comments', 'field_meeting_integra_comment'],['other_integration', 'field_other_integration']]],
      ['paragraph', 'field_indicator_8', 'indicator_8', [['time_adequate', 'field_time_adequate'],['time_adequate_comments', 'field_time_adequate_comments']]],
      ['paragraph', 'field_indicator_9', 'indicator_9', [['action_plan_reviewed', 'field_action_plan_reviewed'],['action_items_implemented', 'field_action_items_implemented'], ['action_items_implemented_comments', 'field_ap_implemented_comments'],['action_plan_reviewed_comments', 'field_plan_reviewed_comments'],['outstanding_action_items', 'field_outstanding_action_items']]],
      ['paragraph', 'field_indicator_10', 'indicator_10', [['members_recognition', 'field_members_recognition'],['members_recognized', 'field_members_recognized'],['recognition', 'field_how_recognition'],['members_recognition_comments', 'field_member_recognition_comment']]],
      ['paragraph', 'field_indicator_11', 'indicator_11', [['all_KPIs_reviewed', 'field_all_kpis_reviewed'],['unreviewed_data_KPIs', 'field_unreviewed_data_kpis'],['all_KPIs_reviewed_comments', 'field_kpis_reviewed_comments']]],
      ['paragraph', 'field_indicator_12', 'indicator_12', [['RCA_conducted', 'field_rca_conducted'],['RCA_Method', 'field_rca_method'],['RCA_KPIs_Problems', 'field_rca_kpis_problems'],['RCA_conducted_comments', 'field_rca_conducted_comments']]],
      ['paragraph', 'field_indicator_13', 'indicator_13', [['action_plan_documented', 'field_action_plan_documented'],['action_plan_timelines', 'field_action_plan_timelines'],['outstanding_actions_included', 'field_outstand_actions_included'], ['outstanding_actions_ommited', 'field_outstand_actions_ommited'],['outstanding_actions_included_comments', 'field_outstand_actions_comment'],['action_plan_comments', 'field_action_plan_comments'], ['action_plan_no_timelines', 'field_action_plan_no_timelines']]],
      ['paragraph', 'field_indicator_14', 'indicator_14', [['Minutes_taken', 'field_minutes_taken']]],
      ['paragraph', 'field_indicator_15', 'indicator_15', [['Meeting_costs_MOH', 'field_meeting_costs_moh'],['Meeting_costs_IP', 'field_meeting_costs_ip']]],
      ['paragraph', 'field_indicator_16', 'indicator_16', [['financier\/accommodation', 'field_accommodation_finance'],['financier\/tea_snacks', 'field_tea_snacks_finance'],['financier\/transport', 'field_transport_finance']]],
      ['paragraph', 'field_indicator_17', 'indicator_17', [['financier_next\/accommodation_next', 'field_accommodation_finance_next'],['financier_next\/tea_snacks_next', 'field_tea_snacks_finance_next'],['financier_next\/transport_next', 'field_transport_finance_next']]],
      ['field','field_team_challenges','challenges_encountered'],
      ['field', 'field_team_pertinent_issues','recurring_issues'],
      ['field', 'field_supply_chain_challenges','challenges_solutions'],
      ['field', 'field_notable_successes','notable_sucesses'],
      ['field', 'field_information_source','information_source'],
    ];
  }

  public function isTaxo($tn) {

    $taxos = [
      'kenya' => 'Kenya',
      'tanzania' => 'Tanzania',
      'nairobi' => 'Nairobi',
      'trans_nzoia' => 'Trans Nzoia',
      'samburu' => 'Samburu',
      'wajir' => 'Wajir',
      'turkana' => 'Turkana',
      'ruaraka' => 'Ruaraka',
      'saboti' => 'Saboti',
      'kwanza' => 'Kwanza',
      'kiminini' => 'Kiminini',
      'cherangany' => 'Cherangany',
      'samburu_central' => 'Samburu central',
      'langata' => 'Langata',
      'samburu_east' => 'Samburu east',
      'tarbaj' => 'Tarbaj',
      'turkana_south' => 'Turkana south',
      'kibish' => 'Kibish',
      'samburu_county' => 'Samburu county',
      'samburu_north' => 'Samburu north'
    ];
    if (array_key_exists($tn, $taxos))  {
      return $taxos[$tn];
    }
    else {
      return FALSE;
    }
  }

  public function getTn($choice) {

    $choices = [
      '1' => 'inSupply Health',
      '2' => 'MOH County',
      '3' => 'MOH National',
      '4' => 'Other Partner'
    ];
    if (array_key_exists($choice, $choices))  {
      return $choices[$choice];
    }
    else {
      return FALSE;
    }
  }

  public function getTid($tn, $vid) {

    $query = \Drupal::database()->select('taxonomy_term_field_data', 'td');
    $query->addField('td', 'tid');
    $query->condition('td.name', $tn);
    $query->condition('td.vid', $vid);
    $term = $query->execute();
    $tid = $term->fetchField();

    return $tid;
  }

}