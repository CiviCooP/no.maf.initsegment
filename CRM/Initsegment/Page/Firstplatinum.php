<?php

require_once 'CRM/Core/Page.php';

class CRM_Initsegment_Page_Firstplatinum extends CRM_Core_Page {
    public $donor_group_id;
    public $platinum_group_id;
    public $magazine_group_id;
    public $organization_group_id;
    function run() {
        $this->donor_group_id = $this->_retrieveGroupId("Donors");
        $this->platinum_group_id = $this->_retrieveGroupId("Platinum");
        $this->magazine_group_id = $this->_retrieveGroupId("Magazine only-receivers");
        $this->organization_group_id = $this->_retrieveGroupId("Organizations");
        /*
         * retrieve first donation for contacts
         */
        $first_query = "SELECT a.id AS contribution_id, a.contact_id, MIN(receive_date) AS first_date, total_amount, b.display_name 
            FROM civicrm_contribution a LEFT JOIN civicrm_contact b ON a.contact_id = b.id  
            GROUP BY a.contact_id";
        $dao_first = CRM_Core_DAO::executeQuery($first_query);
        $added_contacts = array();
        while ($dao_first->fetch()) {
            /*
             * only if first contribution is 10.000 or more
             */
            if ($dao_first->total_amount >= 10000) {
                /*
                 * only if contact not yet in group Donor (because in Magazine only, 
                 * Organization or Platinum) already
                 */
                if (!$this->_contactInDonor($dao_first->contact_id)) {
                    $this->_addToPlatinum($dao_first->contact_id);
                    $added_contact['contact_id'] = $dao_first->contact_id;
                    $added_contact['display_name'] = $dao_first->display_name;
                    $added_contact['contribution_id'] = $dao_first->contribution_id;
                    $added_contact['total_amount'] = $dao_first->total_amount;
                    $added_contact['receive_date'] = $dao_first->first_date;
                    $added_contacts[] = $added_contact;
                }
            }
        }
        $this->assign('endText', "Added all first donations of 10.000 or more to Platinum");
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
    private function _contactInDonor($contact_id) {
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
                        $group_contact_data['group_id'] == $this->platinum_group_id) {
                    return true;
                }                
            }
        }   
    }
    private function _addToPlatinum($contact_id) {
        if (!empty($contact_id)) {
           $params = array(
               'version' => 3,
               'group_id' => $this->platinum_group_id,
               'contact_id' => $contact_id,
               'is_active' => 1
           );
           civicrm_api("GroupContact", "Create", $params);
        }
    }
}
