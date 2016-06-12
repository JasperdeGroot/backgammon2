<?php

namespace Model;

use Model\Registry\IRegistry;

/**
 * @author tomtom
 */
class Register {

    protected $username;
    protected $registry;
    
    public function __construct(IRegistry $registry) {
        $this->registry = $registry;
    }
    
    public function user($email, $alias, $password){
        $this->registry->insert(array($email, $alias, sha1($password), date(IRegistry::DATETIME_FORMAT) ) );
    }
    
    public function findByEmail($email){
        return $this->registry->get(0, $email);
    }
    
    public function listUsers(){
        $date = new \DateTime();
        // Alleen de gebruikers die het laatste uur hebben geregistreerd
        $date->sub(new \DateInterval('PT1H'));
        $content = $this->registry->getAllAfter( $date );
        return $content;
    }
}
