<?php

require_once 'initsegment.civix.php';
/**
 * Implementation of hook civicrm_navigationMenu
 * to create a segmentation menu and menu items
 * 
 * @author Erik Hommel (erik.hommel@civicoop.org http://www.civicoop.org)
 * @date 8 Jan 2014
 * 
 * @param array $params
 */
function initsegment_civicrm_navigationMenu( &$params ) {
    $maxKey = ( max( array_keys($params) ) );
    $params[$maxKey+1] = array (
        'attributes' => array (
            'label'      => 'Initial data segmentation',
            'name'       => 'Initial data segmentation',
            'url'        => null,
            'permission' => null,
            'operator'   => null,
            'separator'  => null,
            'parentID'   => null,
            'navID'      => $maxKey+1,
            'active'     => 1
    ),
        'child' =>  array (
            '1' => array (
                'attributes' => array (
                    'label'      => 'First donation into platinum',
                    'name'       => 'First donation into platinum',
                    'url'        => 'civicrm/firstplatinum',
                    'operator'   => null,
                    'separator'  => 0,
                    'parentID'   => $maxKey+1,
                    'navID'      => 0,
                    'active'     => 1
                ),
                'child' => null
            ),
            '2' => array(
                'attributes'    => array (
                    'label'     => 'Active recurring into gold',
                    'name'      => 'Active recurring into gold',
                    'url'       =>  'civicrm/recurgold',
                    'operator'  => null,
                    'separator' => 0,
                    'parentID'  => $maxKey+1,
                    'navID'     => 2,
                    'active'    => 1
                )
            ),
            '3' => array(
                'attributes'    => array (
                    'label'     => 'Add donors to departure hall',
                    'name'      => 'Add donors to departure hall',
                    'url'       =>  'civicrm/adddepart',
                    'operator'  => null,
                    'separator' => 0,
                    'parentID'  => $maxKey+1,
                    'navID'     => 3,
                    'active'    => 1
                )
            ),
            '4' => array(
                'attributes'    => array (
                    'label'     => 'Add donors to silver',
                    'name'      => 'Add donors to silver',
                    'url'       =>  'civicrm/addsilver',
                    'operator'  => null,
                    'separator' => 0,
                    'parentID'  => $maxKey+1,
                    'navID'     => 4,
                    'active'    => 1
                )
            ),
            '5' => array(
                'attributes'    => array (
                    'label'     => 'Add donors to bronze',
                    'name'      => 'Add donors to bronze',
                    'url'       =>  'civicrm/addbronze',
                    'operator'  => null,
                    'separator' => 0,
                    'parentID'  => $maxKey+1,
                    'navID'     => 5,
                    'active'    => 1
                )
            )
        ) 
    );
}
/**
 * Implementation of hook_civicrm_config
 */
function initsegment_civicrm_config(&$config) {
  _initsegment_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function initsegment_civicrm_xmlMenu(&$files) {
  _initsegment_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 */
function initsegment_civicrm_install() {
  return _initsegment_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function initsegment_civicrm_uninstall() {
  return _initsegment_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function initsegment_civicrm_enable() {
  return _initsegment_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function initsegment_civicrm_disable() {
  return _initsegment_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 */
function initsegment_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _initsegment_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function initsegment_civicrm_managed(&$entities) {
  return _initsegment_civix_civicrm_managed($entities);
}
