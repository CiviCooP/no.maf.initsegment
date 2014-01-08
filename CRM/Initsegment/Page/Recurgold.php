<?php

require_once 'CRM/Core/Page.php';

class CRM_Initsegment_Page_Recurgold extends CRM_Core_Page {
    public $donor_group_id;
    public $platinum_group_id;
    public $magazine_group_id;
    public $organization_group_id;
    public $gold_group_id;
    
    function run() {
        $this->donor_group_id = $this->_retrieveGroupId("Donors");
        $this->platinum_group_id = $this->_retrieveGroupId("Platinum");
        $this->magazine_group_id = $this->_retrieveGroupId("Magazine only-receivers");
        $this->organization_group_id = $this->_retrieveGroupId("Organizations");
        $this->gold_group_id = $this->_retrieveGroupId("Gold");
        /*
         * retrieve active recurring contributions
         */
        $recur_query = "SELECT a.id AS recur_id, contact_id, amount, frequency_unit, display_name 
            FROM civicrm_contribution_recur a LEFT JOIN civicrm_contact b ON  contact_id = b.id 
            WHERE end_date is null OR end_date > '2014-01-08'";
        $dao_recur = CRM_Core_DAO::executeQuery($recur_query);
        $added_contacts = array();
        while ($dao_recur->fetch()) {
            /*
             * only if contact not yet in groups already
             */
            if (!$this->_contactInGroups($dao_recur->contact_id)) {
                $this->_addToGold($dao_recur->contact_id);
                $added_contact['contact_id'] = $dao_recur->contact_id;
                $added_contact['display_name'] = $dao_recur->display_name;
                $added_contact['recur_id'] = $dao_recur->recur_id;
                $added_contact['amount'] = $dao_recur->amount;
                $added_contact['frequency'] = $dao_recur->frequency_unit;
                $added_contacts[] = $added_contact;
            }
        }
        $this->assign('endText', "Added all active recurring contributions to Gold");
        $this->assign('addedContacts', $added_contacts);
        parent::run();
    }
    private function _retrieveGroupId($group_title) {
        $group_id = 0;
        if (empty($group_title)) {
            return $group_id;
        }
        $params = array(
            'title' => $group_title,
            'version' => 3
        );
        $group_api = civicrm_api("Group", "Getsingle", $params);
        if (!civicrm_error($group_api) && isset($group_api['id'])) {
            $group_id = $group_api['id'];
        }
        return $group_id;
    }
    private function _contactInGroups($contact_id) {
        if (empty($contact_id)) {
            return false;
        }
        $params = array(
            'version' => 3,
            'contact_id' => $contact_id
        );
        $group_contact_api = civicrm_api("GroupContact", "Get", $params);
        
        if (!civicrm_error($group_contact_api) && isset($group_contact_api['values'])) {
            foreach($group_contact_api['values'] as $group_contact_id => $group_contact_data) {
                if ($group_contact_data['group_id'] == $this->donor_group_id || 
                        $group_contact_data['group_id'] == $this->magazine_group_id ||
                        $group_contact_data['group_id'] == $this->organization_group_id ||
                        $group_contact_data['group_id'] == $this->platinum_group_id ||
                        $group_contact_data['group_id'] == $this->gold_group_id) {
                    return true;
                }                
            }
        }   
    }
    private function _addToGold($contact_id) {
        if (!empty($contact_id)) {
           $params = array(
               'version' => 3,
               'group_id' => $this->gold_group_id,
               'contact_id' => $contact_id,
               'is_active' => 1
           );
           civicrm_api("GroupContact", "Create", $params);
        }
    }
}
