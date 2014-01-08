<?php

require_once 'CRM/Core/Page.php';

class CRM_Initsegment_Page_Adddepart extends CRM_Core_Page {
    public $donor_group_id;
    public $platinum_group_id;
    public $magazine_group_id;
    public $organization_group_id;
    public $gold_group_id;
    public $depart_group_id;
    
    function run() {
        $this->donor_group_id = $this->_retrieveGroupId("Donors");
        $this->platinum_group_id = $this->_retrieveGroupId("Platinum");
        $this->magazine_group_id = $this->_retrieveGroupId("Magazine only-receivers");
        $this->organization_group_id = $this->_retrieveGroupId("Organizations");
        $this->gold_group_id = $this->_retrieveGroupId("Gold");
        $this->depart_group_id = $this->_retrieveGroupId("Departure Hall");
        /*
         * retrieve newly created donors with completed donation(s)
         */
        $new_query = "SELECT entity_id, d_opprettet_31 AS create_date, b.id AS 
            contribution_id, total_amount, receive_date, display_name
            FROM civicrm_value_maf_norway_import_1577 a
            JOIN civicrm_contribution b ON a.entity_id = b.contact_id
            LEFT JOIN civicrm_contact c ON a.entity_id = c.id
            WHERE d_opprettet_31 >= '2013-11-01' AND contribution_status_id = 1";
        $dao_new = CRM_Core_DAO::executeQuery($new_query);
        $added_contacts = array();
        while ($dao_new->fetch()) {
            /*
             * only if contact not yet in groups already
             */
            if (!$this->_contactInGroups($dao_new->entity_id)) {
                $this->_addToDeparture($dao_new->entity_id);
                $added_contact['contact_id'] = $dao_new->entity_id;
                $added_contact['display_name'] = $dao_new->display_name;
                $added_contact['created_date'] = $dao_new->create_date;
                $added_contact['contribution_id'] = $dao_new->contribution_id;
                $added_contact['total_amount'] = $dao_new->total_amount;
                $added_contact['receive_date'] = $dao_new->receive_date;
                $added_contacts[] = $added_contact;
            }
        }
        $this->assign('endText', "Added all donors created since 1-11-2013 with completed donation(s) to Departure Hall");
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
                        $group_contact_data['group_id'] == $this->depart_group_id) {
                    return true;
                }                
            }
        }   
    }
    private function _addToDeparture($contact_id) {
        if (!empty($contact_id)) {
           $params = array(
               'version' => 3,
               'group_id' => $this->depart_group_id,
               'contact_id' => $contact_id,
               'is_active' => 1
           );
           civicrm_api("GroupContact", "Create", $params);
        }
    }
}
