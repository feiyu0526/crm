<?php

class Rdv_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_rdv_by_id($rdv_id) {
        $this->db->select("*")
                ->from('rdv')
                ->where('rdv_id', $rdv_id);
        $db = $this->db->get();
        return $db->row(0);
    }

	
	//GROUP_CONCAT(pg.value SEPARATOR ',') AS score_value
	
	
	public function get_les_produit_by_rendevous($rdv_id)
	{
		
		$this->db->select("*,DATE_FORMAT(dt_start,'%d/%m/%Y') as date_start_rendevous,
		 GROUP_CONCAT( CONCAT(prod_label,CONCAT('(',CONCAT(prd_rdv.qte,') ')) ) SEPARATOR ',') as production_libelle");
		$this->db->from('prd_rdv')
				->join('production', 'production.prod_id = prd_rdv.id_prd')
				->join('rdv', 'prd_rdv.id_rdv = rdv.rdv_id')
				->where('id_rdv', $rdv_id);
		$this->db->group_by('id_rdv'); 
		  
		$db = $this->db->get();
        return $db->result();
		
	}
	
	
    public function get_prd_rdv_by_rdv_id($rdv_id) {
        $this->db->select("*,DATE_FORMAT(dt_start,'%d/%m/%Y') as date_start_rendevous")
                ->from('prd_rdv')
                ->join('production', 'production.prod_id = prd_rdv.id_prd')
                ->join('rdv', 'prd_rdv.id_rdv = rdv.rdv_id')
                ->where('id_rdv', $rdv_id);
        $db = $this->db->get();
        return $db->result();
    }

    public function get_prd_rdv_by_client_id($client_id) {
        $this->db->select("*")
                ->from('prd_rdv')
                ->join('production', 'production.prod_id = prd_rdv.id_prd')
                ->join('rdv', 'prd_rdv.id_rdv = rdv.rdv_id')
                ->where('rdv.id_client', $client_id);
        $db = $this->db->get();
        return $db->result();
    }

    public function get_all_rdv_by_uid($uid,$id_ressource=0) {
        $this->db->select("*")
                ->from('rdv')
                ->join('client', 'rdv.id_client = client.client_id')
                ->where('rdv.user', $uid);
				
		if($id_ressource>0){
		
			$this->db->where("rdv.id_ressource",$id_ressource);
		
		
		}
				
			
        $db = $this->db->get();
		
		
	
        return $db->result();
    }

    public function get_rdv_closed_date($uid, $date) {
        $this->db->select("*")
                ->from('rdv')
                ->join('client', 'rdv.id_client = client.client_id')
                ->where('rdv.user = "' . $uid . '" AND rdv.dt_start LIKE "%' . $date . '%"');
        $db = $this->db->get();
        return $db->result();
    }

    public function save_rdv($rdv) {
        $this->db->insert('rdv', $rdv);
        return $this->db->insert_id();
    }

    public function save_prd_rdv($prd_rdv) {
        $this->db->insert('prd_rdv', $prd_rdv);
        return $this->db->insert_id();
    }

    public function update_rdv($rdv, $rdv_id) {
        $this->db->update('rdv', $rdv, array('rdv_id' => $rdv_id));
    }

    public function delete_prd_rdv($rdv_id) {
        $this->db->delete('prd_rdv', array('id_rdv' => $rdv_id));
    }

    public function delete_rdv($rdv_id) {
        $this->db->delete('rdv', array('rdv_id' => $rdv_id));
    }

    function test_rdv_query($uid, $table_field, $table_condition, $table_value) {
        $this->db->select("rdv_id")
                ->from('rdv');
        switch ($table_condition) {
            case '~': {
                    $this->db->where('rdv.user = "' . $uid . '" AND ' . $table_field . ' LIKE "%' . $table_value . '%"');
                }break;
            case '=': {
                    $this->db->where('rdv.user = "' . $uid . '" AND ' . $table_field . ' = "' . $table_value . '"');
                }break;
            case '<=': {
                    $this->db->where('rdv.user = "' . $uid . '" AND ' . $table_field . ' <= "' . $table_value . '"');
                }break;
            case '>=': {
                    $this->db->where('rdv.user = "' . $uid . '" AND ' . $table_field . ' >= "' . $table_value . '"');
                }break;
        }
        $db = $this->db->get();
        return $db->result();
    }

    function test_prd_rdv_query($uid, $table_field, $table_condition, $table_value) {
        $this->db->select("*")
                ->from('rdv')
                ->join('prd_rdv', 'rdv.rdv_id = prd_rdv.id_rdv')
                ->join('resource', 'rdv.id_ressource = resource.resource_id')
                ->join('client', 'rdv.id_client = client.client_id');
        switch ($table_condition) {
            case '~': {
                    $this->db->where('rdv.user = "' . $uid . '" AND ' . $table_field . ' LIKE "' . $table_value . '%"');
                }break;
            case '=': {
                    $this->db->where('rdv.user = "' . $uid . '" AND ' . $table_field . ' = "' . $table_value . '"');
                }break;
            case '<=': {
                    $this->db->where('rdv.user = "' . $uid . '" AND ' . $table_field . ' <= "' . $table_value . '"');
                }break;
            case '>=': {
                    $this->db->where('rdv.user = "' . $uid . '" AND ' . $table_field . ' >= "' . $table_value . '"');
                }break;
        }
        $db = $this->db->get();
        return $db->result();
    }
	
	 function get_client_field_options($field_id) {
        $this->db->select("*")
                ->from('client_field_options')
                ->join('client_options', 'client_options.option_id = client_field_options.option_id')
                ->where('client_field_options.field_id', $field_id);
        $db = $this->db->get();
        return $db->result();
    }
	
	function get_client_fields_by_user($uid) {
        $this->db->select("*")
                ->from('client_fields_filter')
                ->join('client_fields', 'client_fields_filter.field_id = client_fields.field_id')
                ->where('client_fields_filter.uid', $uid);
        $db = $this->db->get();
        return $db->result();
    }
    function get_rdv_by_ids($ids) {
        $this->db->select("*")
                ->from('rdv')
                ->join('resource', 'rdv.id_ressource = resource.resource_id')
                ->join('client', 'rdv.id_client = client.client_id')
                ->where('rdv_id IN(' . $ids . ')');
        $db = $this->db->get();
		
		
		$resultat = $db->result_array();
		
		// GET LIST dynamique file
		$res_retour = array();
		if(sizeof($resultat)){
			foreach($resultat as $cli){
				
				$client_fields = $this->get_client_fields_by_user($cli["user"]);
				
				$dynamic_fields = json_decode($cli["dynamic_fields"] , true);
				foreach ($client_fields AS $item)
				{
					if (!empty($item->label)) 
					{
						
						if ($item->field_type == 'boolean')
						{
							
							$checked = "";
							if ((isset($dynamic_fields[$item->field_id]) && intval($dynamic_fields[$item->field_id]) == 1))
							{
								$checked = "OUI";
							}
							if ((isset($dynamic_fields[$item->field_id]) && intval($dynamic_fields[$item->field_id]) == 0)) {
								$checked = 'NON';
							} 
							$cli[$item->label] = $checked;
							
						}
						elseif ($item->field_type == 'dropdown') 
						{
							$selected = "";
							$field_options = $this->get_client_field_options($item->field_id);
							 foreach ($field_options AS $option){
								if (isset($dynamic_fields[$item->field_id]) && $dynamic_fields[$item->field_id] == $option->option_id){
									$selected = $option->option_value;
									break;
								}
							 }
							 $cli[$item->label] = $selected;
							
						}
						else {
							$value = "";
							if ((isset($dynamic_fields[$item->field_id]))){
								$value = $dynamic_fields[$item->field_id];
								
							}
							$cli[$item->label] = $value;
							/* var_dump($item->label);
							var_dump($value);*/
						}
					}
				}
				unset($cli["dynamic_fields"]);
				$res_retour[] = (object)$cli;
			}
			
		}
		
		return $res_retour;
		
		
		
		
       // return $db->result();
    }

    function get_prd_rdv_by_ids($ids) {
        $this->db->select("*")
                ->from('rdv')
                ->join('prd_rdv', 'rdv.rdv_id = prd_rdv.id_rdv')
                ->join('resource', 'rdv.id_ressource = resource.resource_id')
                ->join('client', 'rdv.id_client = client.client_id')
                ->where('prd_rdv_id IN(' . $ids . ')');
        $db = $this->db->get();
        $resultat = $db->result_array();
		
		// GET LIST dynamique file
		$res_retour = array();
		if(sizeof($resultat)){
			foreach($resultat as $cli){
				
				$client_fields = $this->get_client_fields_by_user($cli["user"]);
				
				$dynamic_fields = json_decode($cli["dynamic_fields"] , true);
				foreach ($client_fields AS $item)
				{
					if (!empty($item->label)) 
					{
						
						if ($item->field_type == 'boolean')
						{
							
							$checked = "";
							if ((isset($dynamic_fields[$item->field_id]) && intval($dynamic_fields[$item->field_id]) == 1))
							{
								$checked = "OUI";
							}
							if ((isset($dynamic_fields[$item->field_id]) && intval($dynamic_fields[$item->field_id]) == 0)) {
								$checked = 'NON';
							} 
							$cli[$item->label] = $checked;
							
						}
						elseif ($item->field_type == 'dropdown') 
						{
							$selected = "";
							$field_options = $this->get_client_field_options($item->field_id);
							 foreach ($field_options AS $option){
								if (isset($dynamic_fields[$item->field_id]) && $dynamic_fields[$item->field_id] == $option->option_id){
									$selected = $option->option_value;
									break;
								}
							 }
							 $cli[$item->label] = $selected;
							
						}
						else {
							$value = "";
							if ((isset($dynamic_fields[$item->field_id]))){
								$value = $dynamic_fields[$item->field_id];
								
							}
							$cli[$item->label] = $value;
							/* var_dump($item->label);
							var_dump($value);*/
						}
					}
				}
				unset($cli["dynamic_fields"]);
				$res_retour[] = (object)$cli;
			}
			
		}
		
		return $res_retour;
    }

}
