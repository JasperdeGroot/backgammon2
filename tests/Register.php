<?php

use phpunit\framework\TestCase;
use Model\Register;
use Model\Registry\FileCsv;

/**
 * Description of Register
 *
 * @author 
 */
class Register extends TestCase {

    
    public function testInsert(){
        $csvRegistry = new FileCsv(__DIR__ .'/data/register');
        $register = new Register($csvRegistry);
        $register->user('test@test.com', 'testuser');
        
        
    }

}
