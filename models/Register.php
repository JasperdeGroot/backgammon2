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
    
    public function user($email, $alias){
        $this->registry->insert(array($email, $alias, IRegistry::DATETIME_FORMAT) );
    }
    
    public function findByEmail($email){
        return $this->registry->get(0, $email);
    }
    
    
    public function listUsers(){
        $date = date(IRegistry::DATETIME_FORMAT, time() - (120 *60 ) );
        $content = $this->registry->getAllAfter( $date );
    }
}
