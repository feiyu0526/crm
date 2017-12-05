<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . '/controllers/Back.php');

class Ajax extends Back {

    public function search_client() {
        header("Content-Type:application/json; charset=UTF-8");
        $this->load->model('Client_model');
        $client_model = new Client_model();
        $search_criteria = $this->input->post('mask');
        $result = $client_model->find_client_by_criteria($search_criteria);
        echo json_encode($result);
    }

    public function close_alerte() {
        header("Content-Type:application/json; charset=UTF-8");

        $alerte_id = (int) $this->uri->segment(4);
        $result = array("alerte_id" => $alerte_id, "update_status" => 0);

        if ($alerte_id > 0) {
            $this->load->model('Alerte_model');
            $alerte_model = new Alerte_model();
            $alerte_model->close_alerte_by_id($alerte_id);
            $result = array("alerte_id" => $alerte_id, "update_status" => 1);
        }

        echo json_encode($result);
    }

    public function checkEventNotificationStatus() {
        header("Content-Type:application/json; charset=UTF-8");
        $this->load->model('Sms_model');
        $sms_model = new Sms_model();
        $id = (int) $this->input->post('event_id');
        $check_sms = $sms_model->get_sms_log_by_rdv($id);
		
		
        $result = array();
        if (isset($check_sms)) {
            if (strtotime($check_sms->sending_date) >= time()) {
                $result['message_sent'] = 1;
            } else {
                $result['message_scheduled'] = 1;
            }
        } else {
            $result['message_error'] = 1;
        }
        echo json_encode($result);
    }

    public function generateJsonResponse() {
        header("Content-Type:application/json; charset=UTF-8");
        $this->load->model('Rdv_model');
        $rdv_model = new Rdv_model();
		
		
		$id_ressource = $this->input->get("id_ressource");
        $data = array();
        $rdv = $rdv_model->get_all_rdv_by_uid($this->oc_auth->get_user_id(),$id_ressource);

        foreach ($rdv as $kRdv => $vRdv) {
            //$item = array();
			
		
            $data[$kRdv]["id"] = $vRdv->rdv_id;
            $data[$kRdv]["start_date"] = $vRdv->dt_start;
            $data[$kRdv]["end_date"] = $vRdv->dt_end;
			/*$temp = explode(" ",$vRdv->dt_end);
			$houre =  explode(":",$temp[1]);
			$h1 = $houre[0]+1;
			$fin = $temp[0]." 0".$h1.":".$houre[1].":".$houre[2];
			//$data[$kRdv]["end_date"] = $fin;*/
            $data[$kRdv]["text"] = $vRdv->note_prd;
            $data[$kRdv]["details"] = $vRdv->note_prd;
            
            $data[$kRdv]["sms"] = $vRdv->sms;
            $data[$kRdv]["clt"] = $vRdv->clt_non_pointe;
            $data[$kRdv]["id_ressource"] = $vRdv->id_ressource;
            $data[$kRdv]["id_client"] = $vRdv->id_client;
            $data[$kRdv]["nom_client"] = $vRdv->nom_client;
			//$prd_rdv = $rdv_model->get_prd_rdv_by_rdv_id($vRdv->rdv_id);
			
			
			
			$produit_test = $rdv_model->get_les_produit_by_rendevous($vRdv->rdv_id);
			
			$prd_rdv = $rdv_model->get_prd_rdv_by_rdv_id($vRdv->rdv_id);
			
			
			$produt_libelle = "";
            if ($prd_rdv) {
                foreach ($prd_rdv as $kPrd => $vPrd) {
                    $item = array('id_prd' => $vPrd->id_prd, 'pu' => $vPrd->prod_pu, 'libelle' => $vPrd->
                        prod_label, 'qte' => $vPrd->qte, 'remise' => $vPrd->prod_remise, 'p_ttc' => $vPrd->
                        prod_prix_ttc);
                    $produit[] = $item;
					$produt_libelle .= $vPrd->prod_label;
                }
            }
			
			$produt_libelle =" ";
			if (sizeof($produit_test)) {
                /*foreach ($prd_rdv as $kPrd => $vPrd) {
                    
					$produt_libelle .= $vPrd->prod_label;
                }*/
				
				$produt_libelle = $produit_test[0]->production_libelle;
            }
			$data[$kRdv]["produit"] =  $produit;
			$data[$kRdv]["production"] = $produt_libelle;
			
        }
        echo json_encode($data);
    }

    public function generateJsonResponseEdit() {
        header("Content-Type:application/json; charset=UTF-8");
        $this->load->model('Rdv_model');

        $data = array();
        $produit = array();

        $rdv_model = new Rdv_model();
        $id = (int) $this->input->post('event_id');
        $rdv = $rdv_model->get_rdv_by_id($id);
		
	

        if (!empty($rdv)) {
            $data["id"] = (int) $rdv->rdv_id;
            $data["start_date"] = $rdv->dt_start;
            $data["end_date"] = $rdv->dt_end;
            $data["text"] = $rdv->note_prd;
            $data["details"] = $rdv->note_prd;
            $data["id_ressource"] = $rdv->id_ressource;
            $data["id_client"] = $rdv->id_client;
            $data["nom_client"] = $rdv->nom_client;
            $data["sms"] = $rdv->sms;
            $data["clt"] = $rdv->clt_non_pointe;
			
			
            $prd_rdv = $rdv_model->get_prd_rdv_by_rdv_id($rdv->rdv_id);
			
			
			$produt_libelle = "";
            if ($prd_rdv) {
                foreach ($prd_rdv as $kPrd => $vPrd) {
                    $item = array('id_prd' => $vPrd->id_prd, 'pu' => $vPrd->prod_pu, 'libelle' => $vPrd->
                        prod_label, 'qte' => $vPrd->qte, 'remise' => $vPrd->prod_remise, 'p_ttc' => $vPrd->
                        prod_prix_ttc);
                    $produit[] = $item;
					$produt_libelle .= $vPrd->prod_label;
                }
            }
			
			//var_dump($produit);
            $data["produit"] = $produit;
			$data["produit_libelle"] =$produt_libelle;
        }

        $user_model = new User_model();
        $logged_user = $user_model->get_user_by_uid($this->oc_auth->get_user_id());
        $data['is_sms'] = (int) $logged_user->is_sms;
	
        echo json_encode(array('data' => $data));
    }

    public function search_cities() {
        header("Content-Type:application/json; charset=UTF-8");
        $this->load->model('Client_model');
        $client_model = new Client_model();
        $criteria = $this->input->get('criteria');
        $cities = $client_model->find_cities_by_criteria($criteria);
        echo json_encode($cities);
    }

    function search_production() {
        header("Content-Type:application/json; charset=UTF-8");
        $this->load->model('Production_model');
        $prodution_model = new Production_model();
        $search_criteria = $this->input->post('mask');
        $logged_user = $this->oc_auth->get_user_id();
        $result = $prodution_model->find_production_by_criteria($search_criteria, $logged_user);
        echo json_encode($result);
    }

    public function sms_sending() {
        header("Content-Type:application/json; charset=UTF-8");
        $this->load->library('api_sms');

        $data = array();

        $data['contact'] = $this->input->post('sms_contact');
        $data['text'] = $this->input->post('sms_text');
        $data['state'] = 0;
		
        /** SMS LOGS */
        if (!empty($data['contact']) && !empty($data['text'])) {
            $api_sms = new Api_sms();
            $api_sms->set_destination_number($data['contact']);
            $api_sms->set_sms($data['text']);
            $data['state'] = $api_sms->send_sms();
			
            if ($data['state']) {
                $this->load->model('Sms_model');
                $message_log = array(
                    'user' => $this->oc_auth->get_user_id(),
                    'phone_number' => $data['contact'],
                    'message' => $data['text'],
                    'sending_type' => 'SMS individuel'
                );
                $sms_log_model = new Sms_model();
                $sms_log_model->save_log($message_log);
            }
        }

        echo json_encode($data);
    }

    function sms_activation() {
        $client_id = (int) $this->uri->segment(4);
        $this->load->model('Client_model');
        $client_model = new Client_model();
        $client_model->update_messaging_status($client_id, 1);
        redirect('user/client/index/read/' . $client_id);
    }

    function sms_desactivation() {
        $client_id = (int) $this->uri->segment(4);
        $this->load->model('Client_model');
        $client_model = new Client_model();
        $client_model->update_messaging_status($client_id, 0);
        redirect('user/client/index/read/' . $client_id);
    }

    public function save_event() {
        header("Content-Type:application/json; charset=UTF-8");
        $post_data = json_decode($this->input->post('data'));

        $this->load->model('Rdv_model');
        $rdv_model = new Rdv_model();

		/*if($post_data->end_date && $post_data->end_date!="")
		{
			
			var_dump($post_data);
		}*/
		
		
		
        $data_rdv = array(
            'user' => $this->oc_auth->get_user_id(),
            'note_prd' => $post_data->text,
            'id_client' => $post_data->id_client,
            'id_ressource' =>isset($post_data->id_ressource) ?  $post_data->id_ressource : 1,
            'nom_client' => $post_data->nom_client,
            'dt_start' => $post_data->start_date,
            'dt_end' => $post_data->end_date,
            'sms' => $post_data->sms ? 1 : 0,
            'clt_non_pointe' => $post_data->clt ? 1 : 0
        );

        if (isset($post_data->is_new) && $post_data->is_new) { // NEW ENTRY
            /* SAVE RDV */
            $latest_id = $rdv_model->save_rdv($data_rdv); // GET NEW RDV ID
            $nb_prd = isset($post_data->inputLength)? $post_data->inputLength : 0;
            for ($i = 1; $i <= $nb_prd; $i++) {
                $id_prd = 'id_produit' . $i;
                $prod_label = 'prodlibelle_' . $i;
                $prod_pu = 'prod_prix_ttc_' . $i;
                $qte = 'qte_' . $i;
                $prod_remise = 'remise_' . $i;
                $data_rel = array(
                    'id_rdv' => $latest_id,
                    'id_prd' => $post_data->$id_prd,
                    'prod_label' => $post_data->$prod_label,
                    'prod_pu' => $post_data->$prod_pu,
                    'qte' => $post_data->$qte,
                    'prod_remise' => $post_data->$prod_remise,
                    'prod_prix_ttc' => 0
                );
                /* SAVE PRODUCT RDV */
                $rdv_model->save_prd_rdv($data_rel);
            }
        } else { // UPDATE
            $rdv_id = $post_data->id;
            $rdv_model->update_rdv($data_rdv, $rdv_id);

            /* REMOVE ALL RELATIONS */
			
			if(!empty($post_data->inputLength)){
				
				$rdv_model->delete_prd_rdv($rdv_id);
				$nb_prd = 0;
				
				if(!empty($post_data->inputLength))
				{
					$nb_prd = $post_data->inputLength;
				}
				for ($i = 1; $i <= $nb_prd; $i++) {
					$id_prd = 'id_produit' . $i;
					$prod_label = 'prodlibelle_' . $i;
					$prod_pu = 'prod_prix_ttc_' . $i;
					$qte = 'qte_' . $i;
					$prod_remise = 'remise_' . $i;
					$data_rel = array(
						'id_rdv' => $rdv_id,
						'id_prd' => $post_data->$id_prd,
						'prod_label' => $post_data->$prod_label,
						'prod_pu' => $post_data->$prod_pu,
						'qte' => $post_data->$qte,
						'prod_remise' => $post_data->$prod_remise,
						'prod_prix_ttc' => 0
					);
					/* SAVE PRODUCT RDV */
					$rdv_model->save_prd_rdv($data_rel);
				}
			}
        }
        echo json_encode($data_rdv);
    }

    public function drag_drop_event() {
        header("Content-Type:application/json; charset=UTF-8");
        $post_data = json_decode($this->input->post('data'));

        $this->load->model('Rdv_model');
        $rdv_model = new Rdv_model();

        $data_rdv = array(
            'user' => $this->oc_auth->get_user_id(),
            'note_prd' => $post_data->text,
            'id_client' => $post_data->id_client,
            'id_ressource' => $post_data->id_ressource,
            'nom_client' => $post_data->nom_client,
            'dt_start' => $post_data->start_date,
            'dt_end' => $post_data->end_date,
            'sms' => $post_data->sms ? 1 : 0,
            'clt_non_pointe' => $post_data->clt ? 1 : 0
        );

        if (isset($post_data->is_new) && !$post_data->is_new) {
            $rdv_id = $post_data->id;
            $rdv_model->update_rdv($data_rdv, $rdv_id);
        }

        echo json_encode($data_rdv);
    }

    public function delete_event() {
        header("Content-Type:application/json; charset=UTF-8");
        $this->load->model('Rdv_model');
        $rdv_model = new Rdv_model();

        $rdv_id = $this->input->post('event_id');
        /* REMOVE RDV */
        $rdv_model->delete_rdv($rdv_id);
        /* REMOVE ALL RELATIONS */
        $rdv_model->delete_prd_rdv($rdv_id);
        echo json_encode(array("success" => true));
    }

    public function test_query() {
        header("Content-Type:application/json; charset=UTF-8");

        $table_selection = $this->input->post('table_selection');
        $table_field = $this->input->post('table_field');
        $table_condition = $this->input->post('table_condition');
        $table_value = $this->input->post('table_value');
        $table_operator = $this->input->post('table_operator');

        $table = '';
        $array_size = sizeof($table_selection);
        $result = $this->_launch_query($table_selection, $table_field, $table_condition, $table_value);
        for ($i = 0; $i < $array_size; $i++) {
            $table = $table_selection[$i];
            break;
        }

        $prepared = $this->_prepare_result($table, $result, $table_operator);
        $final = $this->_final_result($table, $prepared);

        if (!empty($final)) {
            echo json_encode(array(
                "table" => $table,
                "results" => $final
            ));
        } else {
            echo json_encode(array(
                "table" => $table,
                "results" => array("state" => "aucun r&eacute;sultat")
            ));
        }
    }
	public function get_list_des_chien_par_client()
	{
			$id_client = $this->input->post("client_id");
			$ids = implode(',', $id_client);
			$crud = new grocery_CRUD();
            $crud->set_theme('flexigrid');
            $crud->set_table('client_object_entities');
            $crud->set_subject('Chien');
			//$crud->set_relation('client_id', 'client',"nom");
            $crud->where('object_id = 1 AND client_id IN ('.$ids.') '); // 1 => entité chien
            $crud->columns('nom_chien'); // 1 => entité chien

            $crud->display_as(array(
                'nom_chien' => 'Nom du chien',
                'client_id' => 'Client',
                'object_id' => 'Chien',
                'dynamic_fields' => 'Informations'
            ));
			$crud->unset_add();
			$crud->unset_edit();
			$crud->unset_read();
			$crud->unset_delete();
			$crud->callback_column('nom_chien', array($this, 'callbak_entity_title'));
            $output = $crud->render();
			$this->load->view("sections/liste_chien_client", $output);
	}
    public function query_simulation() {
        $table_selection = $this->input->post('table_selection');
        $table_field = $this->input->post('table_field');
        $table_condition = $this->input->post('table_condition');
        $table_value = $this->input->post('table_value');
        $table_operator = $this->input->post('table_operator');

        $table = '';
        $array_size = sizeof($table_selection);
        $result = $this->_launch_query($table_selection, $table_field, $table_condition, $table_value);
        for ($i = 0; $i < $array_size; $i++) {
            $table = $table_selection[$i];
            break;
        }

        $prepared = $this->_prepare_result($table, $result, $table_operator);

        $final = $this->_final_result($table, $prepared);
		$i= 0;
		$count = 0;
		$temp_indice = 0;
		foreach( $final as $res){
			
			
			if($count<count((array)$res)){
				$count = count((array)$res);
				$temp_indice = $i;
			}
			
			
			
			
			$i++;
		}
		//var_dump($temp_indice);
		//var_dump($count );
        $output = new stdClass();
        $output->result = $final;
		$output->indice = $temp_indice;
		
        $output->current_table = $table;
        $output->ids = implode(',', $prepared);

        $this->load->view("sections/marketing_query_simulation", $output);
    }

    function save_query() {
        header("Content-Type:application/json; charset=UTF-8");

        $query = new stdClass();
        $query->table_selection = $table_selection = $this->input->post('table_selection');
        $query->table_field = $table_field = $this->input->post('table_field');
        $query->table_condition = $table_condition = $this->input->post('table_condition');
        $query->table_value = $table_value = $this->input->post('table_value');
        $query->table_operator = $table_operator = $this->input->post('table_operator');
        $request_name = $this->input->post('request_name');

        $array_size = sizeof($table_selection);
        $result = $this->_launch_query($table_selection, $table_field, $table_condition, $table_value);
        for ($i = 0; $i < $array_size; $i++) {
            $table = $table_selection[$i];
            break;
        }

        $prepared = $this->_prepare_result($table, $result, $table_operator);
        $final = $this->_final_result($table, $prepared);

        if (!empty($final)) { // IF QUERY RESULT NOT EMPTY
            $result = array(
                "user" => $this->oc_auth->get_user_id(),
                "table_request" => $table,
                "request_name" => $request_name,
                "data_posted" => json_encode($query),
            );

            $this->load->model('Request_model');
            $request_model = new Request_model();
            $request_model->save_request($result);

            echo json_encode(array(
                "table" => $table,
                "state" => "Requ&ecirc;te enregistr&eacute;e."
            ));
        } else {
            echo json_encode(array(
                "table" => $table,
                "state" => "Votre requ&ecirc;te affiche aucun r&eacute;sultat. Requ&ecirc;te non enregistr&eacute;e."
            ));
        }
    }

    function save_campaign() {
        header("Content-Type:application/json; charset=UTF-8");

        $campaign_request = $this->input->post('campaign_request');
        $campaign_name = $this->input->post('campaign_name');
        $campaign_date = $this->input->post('sending_date');
        $campaign_text = $this->input->post('campaign_text');

        $campaign = array(
            "user" => $this->oc_auth->get_user_id(),
            "request_id" => $campaign_request,
            "name" => $campaign_name,
            "message" => $campaign_text,
            "sending_date" => $campaign_date
        );

        // IF DATE IS EQUAL TO TODAY'S DATE - SEND IT DIRECTLY AND SAVE 
        $date1 = date('Y-m-d', strtotime($campaign_date));
        $date2 = date('Y-m-d');
        if ($date1 == $date2) {
            // SEND CAMPAIGN
        }

        // SAVE CAMPAIGN AND SEND IT LATER BY CRON

        $this->load->model('Request_model');
        $request_model = new Request_model();

        if ($request_model->save_campaign($campaign)) {
            echo json_encode(array(
                "state" => "Campagne enregistr&eacute;e et programm&eacute;e."
            ));
        } else {
            echo json_encode(array(
                "state" => "Une erreur est survenue pendant l'enregistrement. Campagne non enregistr&eacute;e"
            ));
        }
    }
    
    function export_all() {        
        $data = new stdClass();
        $data->columns = $this->_get_export_columns();
        $data->list = $this->_get_export_list($data->columns);
        $this->_export_data($data);
    }

    protected function _export_data($data) {

        /**
         * No need to use an external library here. The only bad thing without using external library is that Microsoft Excel is complaining
         * that the file is in a different format than specified by the file extension. If you press "Yes" everything will be just fine.
         * */
        $string_to_export = "";
        foreach ($data->columns as $column) {
            $string_to_export .= $column->display_as . "\t";
        }
        $string_to_export .= "\n";

        foreach ($data->list as $num_row => $row) {
            foreach ($data->columns as $column) {
                if (isset($row->{$column->field_name})) {
                    $string_to_export .= $this->_trim_export_string($row->{$column->field_name}) . "\t";
                }
            }
            $string_to_export .= "\n";
        }

        // Convert to UTF-16LE and Prepend BOM
        $string_to_export = "\xFF\xFE" . mb_convert_encoding($string_to_export, 'UTF-16LE', 'UTF-8');

        $filename = "export-" . date("Y-m-d_H:i:s") . ".xls";

        header('Content-type: application/vnd.ms-excel;charset=UTF-16LE');
        header('Content-Disposition: attachment; filename=' . $filename);
        header("Cache-Control: no-cache");
        echo $string_to_export;
        die();
    }

    protected function _trim_export_string($value) {
        $string = str_replace(array("&nbsp;", "&amp;", "&gt;", "&lt;"), array(" ", "&", ">", "<"), $value);
        return strip_tags(str_replace(array("\t", "\n", "\r"), "", $string));
    }

    protected function _get_export_columns() {

        $columns = array();

        $table_name = $this->input->get('table');
        $ids = $this->input->get('ids');
        switch ($table_name) {
            case 'rdv': {
                    $field_name = array('genre', 'nom', 'prenom', 'dt_nais', 'pays', 'adresse', 'cp', 'ville', 'tel_fixe', 'tel_mobile', 'email', 'sms_versaire');
                    $display_as = array('Genre', 'Nom', 'Prénom', 'Date naissance', 'Pays', 'Adresse', 'Code postal', 'Ville', 'Téléphone fixe', 'Portable', 'Email', 'Envois SMS');

                    $this->load->model('Client_model');
                    $client_model = new Client_model();

                    $client_fields = $client_model->get_client_fields_by_user($this->oc_auth->get_user_id());
                    foreach ($client_fields AS $field) {
                        array_push($field_name, $field->field_id);
                        array_push($display_as, "\"" . $field->label . "\"");
                    }

                    $total_columns = sizeof($field_name);
                    for ($i = 0; $i < $total_columns; $i++) {
                        $field = new stdClass();
                        $field->field_name = $field_name[$i];
                        $field->display_as = $display_as[$i];
                        $columns[] = $field;
                    }
                }break;
            case 'prd_rdv': {
                    
                }break;
            case 'client': {
                    $field_name = array('genre', 'nom', 'prenom', 'dt_nais', 'pays', 'adresse', 'cp', 'ville', 'tel_fixe', 'tel_mobile', 'email', 'sms_versaire');
                    $display_as = array('Genre', 'Nom', 'Prénom', 'Date naissance', 'Pays', 'Adresse', 'Code postal', 'Ville', 'Téléphone fixe', 'Portable', 'Email', 'Envois SMS');

                    $this->load->model('Client_model');
                    $client_model = new Client_model();

                    $client_fields = $client_model->get_client_fields_by_user($this->oc_auth->get_user_id());
                    foreach ($client_fields AS $field) {
                        array_push($field_name, $field->field_id);
                        array_push($display_as, "\"" . $field->label . "\"");
                    }

                    $total_columns = sizeof($field_name);
                    for ($i = 0; $i < $total_columns; $i++) {
                        $field = new stdClass();
                        $field->field_name = $field_name[$i];
                        $field->display_as = $display_as[$i];
                        $columns[] = $field;
                    }
                }break;
            case 'object': {
                    
                }break;
        }

        return $columns;
    }

    protected function _get_export_list($columns) {
        $this->load->model('Client_model');
        $client_model = new Client_model();
        $lists = array();

        $clients = $client_model->get_clients_by_uid($this->oc_auth->get_user_id());
        foreach ($clients AS $item) {
            if ($item->dynamic_fields && !empty($item->dynamic_fields)) {
                $dynamic_fields = json_decode($item->dynamic_fields, true);
                foreach ($dynamic_fields AS $key => $value) {
                    $item->$key = $this->_get_client_field_value($key, $value);
                }
            }
            unset($item->dynamic_fields);
            $lists[] = $item;
        }

        return $lists;
    }

    protected function _get_client_field_value($field_id, $value) {
        $this->load->model('Client_model');
        $client_model = new Client_model();
        $field = $client_model->get_client_field_by_field_id($field_id);
        if ($field->field_type == 'dropdown') {
            $option_data = $client_model->get_client_field_option_value($value);
            $option = (isset($option_data->option_value) && !empty($option_data->option_value)) ? $option_data->option_value : null;
        } else {
            $option = $value;
        }
        return $option;
    }

}
