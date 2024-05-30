<?php
    class Admin_model extends CI_Model{
        public function __construct(){
            $this->load->database();
        }
        public function insert($table_name,$data){
			$this->db->insert($table_name,$data);
			return $this->db->insert_id();
        }
        public function get_data($table_name){
            return $this->db->get($table_name)->result_array();
        }
        public function update($table_name,$id,$data){
            $this->db->where('id', $id);
            return $this->db->update($table_name,$data);
        }
        public function update_by_attr_id($table_name,$attr,$id,$data){
            $this->db->where($attr, $id);
            return $this->db->update($table_name,$data);
        }
        public function get_data_by_id($table_name,$id){
            $this->db->where('id', $id);
            return $this->db->get($table_name)->row();
        }
    
        public function get_data_by_attr_id($table_name,$attr,$id){
            $this->db->where($attr, $id);
            return $this->db->get($table_name)->result();
        }
        public function get_data_by_2attr_id($table_name,$attr1,$attr2,$id1,$id2){
            $this->db->where($attr1, $id1);
            $this->db->where($attr2, $id2);
            return $this->db->get($table_name);
		}
        public function del_data($table_name,$id){
            $this->db->where('id', $id);
            $this->db->delete($table_name);
            return true;
        }
        public function del_data_by_attr_id($table_name,$attr,$id){
            $this->db->where($attr, $id);
            $this->db->delete($table_name);
            return true;
		}
		public function insert_image($data = array()){
			if(!empty($data)){
				$insert = $this->db->insert_batch('gallery_images',$data);
				return $insert?$this->db->insert_id():false;
			}
			return false;
		}
    }
?>
