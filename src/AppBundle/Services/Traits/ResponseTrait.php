<?php

namespace AppBundle\Services\Traits;

trait ResponseTrait
{

    /**
     * 
     * @param type $code
     * @param type $message
     * @param type $data
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function successJsonResponse($code, $message, $data)
    {

        return new \Symfony\Component\HttpFoundation\JsonResponse(
                [
                    'status'        => 'Success',
                    'status_code'   => $code,
                    'message'       => $message,
                    'data'          => $data,
                ], $code);
    }

    /**
     * 
     * @param type $code
     * @param type $message
     * @param type $data
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function errorJsonResponse($code, $message, array $data, array $errors)
    {

        return new \Symfony\Component\HttpFoundation\JsonResponse(
                [
                    'status'        => 'Error',
                    'status_code'   => $code,
                    'message'       => $message,
                    'data'          => $data,
                    'error'         => $errors,
                ], $code);
    }
    
    /**
     * Get general error message
     * 
     * @param \Symfony\Component\Form\Form $form
     * @return string
     */
    protected function getError(\Symfony\Component\Form\Form $form, $translator)
    {
        $errors = $this->getErrorMessages($form);
        
        if (array_key_exists('email', $errors)) {
            
            return $errors['email'];
        } else if (array_key_exists('password', $errors)) {
            
            return $errors['password'];
        }
        
        return $translator->trans('validation.errors', [], 'messages');
        
    }

    /**
     * Get error messages from form
     *
     * @param \Symfony\Component\Form\Form $form
     * @return array Array of errors
     */
    protected function getErrorMessages(\Symfony\Component\Form\Form $form) {

        $errors = array();

        /**
         * collect main form errors
         */
        $formErrors = $form->getErrors(true, true);
        $errorsAsString = (string) $form->getErrors(true, false);
        
        $errorsAsString = str_replace("\n    ", "", $errorsAsString);
        
        $errorsAsString = str_replace("ERROR: ", ", ", $errorsAsString);
        
        $errorsAsString = rtrim(str_replace("\n", "|", $errorsAsString), "|");
        
        foreach (explode('|', $errorsAsString) as $key => $value)
        {
            $fieldError = explode(':,', $value);
            if (isset($fieldError[1])) {
                
                $errors[$fieldError[0]] = trim($fieldError[1]);
            } else {
                
                $errors["global_".$key] = ltrim($fieldError[0], ', ');
            }
        }
        

//        foreach ($formErrors as $key => $error) {
//
//            $template = $error->getMessageTemplate();
//            $parameters = $error->getMessageParameters();
//            //dump($parameters);exit;
//            foreach ($parameters as $var => $value) {
//                $template = str_replace($var, $value, $template);
//            }
//
//            $errors[$key] = $template;
//        }
//
//        /**
//         * collect child form errors
//         */
//        if ($formErrors->getChildren()) {
//            foreach ($formErrors->getChildren() as $child) {
//                if (!$child->isValid()) {
//                    $errors[$child->getName()] = self::getErrorMessages($child);
//                }
//            }
//        }

        return $errors;
    }

}
