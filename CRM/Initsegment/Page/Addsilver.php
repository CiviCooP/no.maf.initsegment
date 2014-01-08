<?php

require_once 'CRM/Core/Page.php';
set_time_limit(0);

class CRM_Initsegment_Page_Addsilver extends CRM_Core_Page {
    public $donor_group_id;
    public $platinum_group_id;
    public $magazine_group_id;
    public $organization_group_id;
    public $gold_group_id;
    public $depart_group_id;
    public $silver_group_id;
    function run() {
        $this->donor_group_id = $this->_retrieveGroupId("Donors");
        $this->platinum_group_id = $this->_retrieveGroupId("Platinum");
        $this->magazine_group_id = $this->_retrieveGroupId("Magazine only-receivers");
        $this->organization_group_id = $this->_retrieveGroupId("Organizations");
        $this->depart_group_id = $this->_retrieveGroupId("Departure Hall");
        $this->gold_group_id = $this->_retrieveGroupId("Gold");
        $this->silver_group_id = $this->_retrieveGroupId("Silver");
        /*
         * retrieve contributions in the last 18 months
         */
        $silver_query = "SELECT COUNT(a.id) AS count_contributions, a.id AS contribution_id, 
            contact_id, display_name FROM civicrm_contribution a LEFT JOIN 
            civicrm_contact b ON contact_id = b.id WHERE receive_date > '2012-06-30' 
            GROUP BY contact_id";
        $dao_silver = CRM_Core_DAO::executeQuery($silver_query);
        $added_contacts = array();
        while ($dao_silver->fetch()) {
            /*
             * only if contact not yet in groups already
             */
            if (!$this->_contactInGroups($dao_silver->contact_id)) {
                $this->_addToSilver($dao_silver->contact_id);
                $added_contact['contact_id'] = $dao_silver->contact_id;
                $added_contact['display_name'] = $dao_silver->display_name;
                $added_contact['contributions'] = $dao_silver->count_contributions;
                $added_contacts[] = $added_contact;
            }
        }
        $this->assign('endText', "Added all donors in the last 18 months to Silver");
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
                        $group_contact_data['group_id'] == $this->gold_group_id ||
                        $group_contact_data['group_id'] == $this->depart_group_id ||
                        $group_contact_data['group_id'] == $this->silver_group_id) {
                    return true;
                }                
            }
        }   
    }
    private function _addToSilver($contact_id) {
        if (!empty($contact_id)) {
           $params = array(
               'version' => 3,
               'group_id' => $this->silver_group_id,
               'contact_id' => $contact_id,
               'is_active' => 1
           );
           civicrm_api("GroupContact", "Create", $params);
        }
    }
}
