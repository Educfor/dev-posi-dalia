<?php

/**
 * Description of main.php
 *
 * @author Nicolas Beurion
 */

class Main 
{
    
    private $controllerName = 'inscription'; // valeur indéfinie
    private $data = array();
    private $template = 'inscript_content';
    

    public function __construct()
    {
        
    }
    
    
    public function loadModel($name)
    {
        require_once (ROOT.'models/'.strtolower($name).'.php');
        $this->name = new $name;
    }
    
    
    public function setResponse($response_data)
    {
	//echo "setResponse<br/>";
        $this->data = array_merge($this->data, $response_data);
    }
    
    
    public function setTemplate($requestTemplate)
    {
	//echo "setResponse<br/>";
        $this->template = $requestTemplate;
    }

    public function render($filename)
    {
	//echo "render<br/>";
        extract($this->data);
        
        ob_start();
        require(ROOT.'views/'.$this->controllerName.'/'.$filename.'.php');
        $template_content = ob_get_clean();
        
        if (!$this->template)
        {
            echo $template_content;
        }
        else
        {
            require(ROOT.'views/templates/'.$this->template.'.php');
        }
    }
    
    
    
    
    
    
    public function validateFormValues($formValuesList)
    {
        $returnData = array();
  
        foreach($formValuesList as $formValue)
        {
            $formatData = array(); 
            
            $data = $this->filterData($formValue['value'], $formValue['type'], $formValue['min_length'], $formValue['max_length']);
            
            if (!$data)
            {
                $this->registerError("form_data", $formValue['message']." n'est pas correctement saisi");
            }
            else if ($data == 'empty' && $formValue['required'])
            {
                $this->registerError("form_empty", $formValue['message']." n'a pas été saisi");
            }
            else 
            {
                $formatData['name'] = $formValue['name'];
                $formatData['value'] = $data;
            }
            
            $returnData[] = $formatData;
        }

        return $returnData;
    }
    
    
    public function filterData($value, $type, $minLength = 0, $maxLength = 0)
    {
        $validValue = "empty";
        
        if (!empty($value))
        {
            switch ($type)
            {
                case "string" :
                    $validValue = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW | FILTER_FLAG_ENCODE_HIGH);
                    break;

                case "integer" :
                    if (preg_match("`^[0-9]*$`", $value))
                    {
                        $validValue = $value;
                    }
                    break;

                case "date" :
                    if (preg_match("`^[0-3][0-9](/|-|\s)[0-1][0-9](/|-|\s)[1-2][0-9][0-9][0-9]$`", $value))
                    {
                        $validValue = $value;
                    }
                    break;

                case "email" :
                    $validValue = filter_var($value, FILTER_VALIDATE_EMAIL);
                    break;
                
                default :
                    break;
            }
            
            if ($maxLength == 0)
            {
                $maxLength = $minLength;
            }
            
            if ($minLength > 0 && $maxLength > 0)
            {
                if (strlen($validValue) < $minLength || strlen($validValue) > $maxLength)
                {
                    $validValue = false;
                }
            }
        }
        
        return $validValue;
    }

    
    
    
    /**
     * Enregistre la ou les valeur "errors" contenu dans la valeur ou le tableau passés en paramètres.
     * 
     * @param array Le tableau à "nettoyer" de ses erreurs.
     * 
     * @return boolean Si erreurs trouvé => true, sinon => false.
     */
    public function filterDataErrors(&$data)
    {
        if (isset($data['errors']) && !empty($data['errors']))
        {
            foreach ($data['errors'] as $dataError)
            {
                $this->registerError($dataError['type'], $dataError['message']);
            }
            unset($data['errors']);
            return true;
        }
        else 
        {
            return false;
        }
    }
    
    
    /**
     * Enregistre une nouvelle entrée dans le tableau des erreurs.
     * 
     * @params string Le type d'erreur.
     * @params string Le message de l'erreur.
     */
    public function registerError($errorType, $errorMessage)
    {
        $this->errors[] = array('type' => $errorType, 'message' => $errorMessage);
    }
    
    public function hasErrors()
    {
        if (count($this->errors) > 0)
        {
            return true;
        }
        else 
        {
            return false;
        }
    }
    
    public function getErrors()
    {
        
    }
    
    public function getErrorsByType()
    {
        
    }
    
}

?>
