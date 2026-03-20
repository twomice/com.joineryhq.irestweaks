<?php

require_once 'irestweaks.civix.php';
// phpcs:disable
use CRM_Irestweaks_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_postProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postProcess
 */
function irestweaks_civicrm_postProcess($formName, &$form) {
  // Detect contribution page and contribution confirm page
  if ($formName === 'CRM_Contribute_Form_Contribution_Confirm') {
    // Get the source param by the entryURL in the controler array
    $controller = $form->getVar('controller');
    // Process to get the source param
    $params = explode('?', $controller->_entryURL);
    parse_str(end($params), $parseURL);

    foreach ($parseURL as $key => $value) {
      // Remove amp; since it was not remove using parse_str
      $newKey = str_replace('amp;', '', $key);

      if ($newKey === 'source') {
        // Get the newly created contribution
        $lastContribution = civicrm_api3('Contribution', 'get', [
          'sequential' => 1,
          'id' => $form->_contributionID,
          'return' => ['id', 'contribution_source'],
        ]);

        // Add the source param value to the current source
        $newSource = $lastContribution['values'][0]['contribution_source'] . ' - ' . $value;

        // Update the source
        $result = civicrm_api3('Contribution', 'create', [
          'id' => $form->_contributionID,
          'source' => $newSource,
        ]);

        break;
      }
    }
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function irestweaks_civicrm_config(&$config) {
  _irestweaks_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function irestweaks_civicrm_install() {
  _irestweaks_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function irestweaks_civicrm_enable() {
  _irestweaks_civix_civicrm_enable();
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function irestweaks_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function irestweaks_civicrm_navigationMenu(&$menu) {
//  _irestweaks_civix_insert_navigation_menu($menu, 'Mailings', array(
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ));
//  _irestweaks_civix_navigationMenu($menu);
//}
