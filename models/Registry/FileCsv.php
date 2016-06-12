<?php

namespace Model\Registry;

use Model\Registry\IRegistry;
use Model\Registry\FileCsv\Exception;

/**
 * Uses the file system as a registry
 */
class FileCsv implements IRegistry {
    
    const COLUM_EMAIL = 0;
    const COLUM_ALIAS = 1;
    const COLUM_PASSWORD = 2;
    const COLUM_REGISTER_DATETIME = 3;
    
    protected $filepath; 

    public function __construct($filePath) {
        if(file_exists($filePath)){
            $this->filepath = $filePath;
        } elseif(fopen($filePath, 'x') ) {
            $this->filepath = $filePath;
        } else {
            throw new Exception('Unable to create file');
        }
    }

    public function get($primaryKey, $value){
        $this->validateKey($primaryKey);
        $readHandle = fopen($this->filepath, 'r');
        $content = fgetcsv($readHandle, 1000,',');
        foreach ($content as $line){
            if($line[$primaryKey] == $value){
                fclose($readHandle);
                return $line;
            }
        }
        fclose($readHandle);
        return null;
    }
    
    public function delete($primaryKey, $value) {
        $this->validateKey($primaryKey);
        $readHandle = fopen($this->filepath, 'r');
        $content = fgetcsv($readHandle, ',', 1000);
        // Lees het hele bestand behalve de regel die je niet wil in een nieuw array
        foreach ($content as $line){
            if($line[$primaryKey] != $value){
                $newContent[] = $line . PHP_EOL;
            }
        }
        fclose($readHandle);
        // Schrijf het nieuwe array weg.
        // Dit heeft wel als risico dat als er iemand tussen door
        // ook iets aan het doen is, dat deze insert worden overschreven.
        // fopen mode 'w', maakt het bestand leeg
        $writeHandel = fopen($this->filepath, 'w');
        foreach($newContent as $newLine){
            fwrite($writeHandel, $newContent);
        }
        fclose($writeHandel);
    }
    
    public function insert($row) {
        $addHandle = fopen($this->filepath, 'a');
        fwrite($addHandle, implode(',', $row) . PHP_EOL);
        fclose($addHandle);
        return $this;
    }
    
    public function update($primaryKey, $primaryValue, $updatedValue) {
        $row = $this->get($primaryKey, $primaryValue);
        $readHandle = fopen($this->filepath, 'r');
        $content = fgetcsv($readHandle, 1000, ',');
        foreach ($content as $line){
            if($line == $row){
                fclose($readHandle);
                return $line;
            }
        }
        fclose($readHandle);        
    }
    
    public function getAllAfter(\DateTime $dateTime){
        $content = array_map('str_getcsv', file($this->filepath));
        $allAfter = array();
        foreach ($content as $line){
            $registered = \DateTime::createFromFormat(IRegistry::DATETIME_FORMAT, $line[self::COLUM_REGISTER_DATETIME]);
            if($registered > $dateTime){
                $allAfter[] = $line;
            }
        }
        return $allAfter;
    }
    
    

    /**
     * 
     * @param type $key
     * @return type
     * @throws Exception
     */
    protected function validateKey($key){
        if( ! in_array($key, array(self::COLUM_EMAIL, self::COLUM_ALIAS, self::COLUM_REGISTER_DATETIME))){
            throw new Exception('Unknown Key');
        }
        return $key;
    }
}
