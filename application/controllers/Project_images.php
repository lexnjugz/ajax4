<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class Project_images extends CI_Controller
{

	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function index(){
		$this->load->view('upload_project_img_view');
	}


	//For the process of uploading project images
	function project_image_upload(){

        $config['upload_path']   = FCPATH.'/project_imgs/';
        $config['allowed_types'] = 'gif|jpg|png|ico';
        $this->load->library('upload',$config);

        if($this->upload->do_upload('userfile')){
        	$token=$this->input->post('token_image');
        	$img_name=$this->upload->data('file_name');
        	$this->db->insert('project_imgs',array('project_image'=>$img_name,'token'=>$token));
        }


	}


	//	To delete a photo
	function project_image_delete(){

		//Take token photo
		$token=$this->input->post('token');

		
		$image=$this->db->get_where('project_imgs',array('token'=>$token));


		if($image->num_rows()>0){
			$result=$image->row();
			$img_name=$result->img_name;
			if(file_exists($file=FCPATH.'/project_imgs/'.$img_name)){
				unlink($file);
			}
			$this->db->delete('project_imgs',array('token'=>$token));

		}


		echo "{}";
	}
	 function project_slider_images(){
                    $uploadpath = base_url().'project_imgs/';
                    $rs = $this->db->get('project_imgs');
                    $rr = $this->db->join('project', 'project.project_Id=project_imgs_Id.project_Id', 'left');
                    
                    foreach ($rs->result() as $row) {
                        $alt = $row->project_image;
                        $id = $row->project_imgs_Id;
                        $token = $row->token;
                        echo "<li class='thumbnail' token='$token'>
                            <span id='$row->project_imgs_Id' class='btn btn-info btn-block btn-delete'><i class='glyphicon glyphicon-remove'></i>&nbsp;&nbsp;&nbsp;Delete</span>
                            <img src='$uploadpath$alt' alt='$alt' style='height: 150px; width: 150px'>
                                <span class='btn btn-warning btn-block'>$alt</span></li>";
                         
                    }
                }
                 
    function project_deleteimg(){

      $image =  $this->db->where('project_imgs_Id', $this->input->post('id'));
      $this->db->delete('project_imgs'); 
         
    }

}