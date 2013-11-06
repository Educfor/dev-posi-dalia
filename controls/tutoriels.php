<?php

class Tutoriels extends Controller
{
    var $models = array('Tutoriel');
            
    function home()
    {
        $responses = array();
        
        //$this->loadModel('Tutoriel');
        //echo ($this->Tutoriel->table);
        $responses['tutos'] = $this->Tutoriel->getAll();
        //print_r($data);
        
        $this->setResponses($responses);
        $this->render('home');
    }
    
    function view_single($id)
    {
        //echo $id;
        //$this->loadModel('Tutoriel');
        $response['tuto'] = $this->Tutoriel->find($id);
        $this->set($response);
        $this->render('single');
    }
}

?>
