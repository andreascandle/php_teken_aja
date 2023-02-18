<?php

// namespace tests\unit;

if (!class_exists('PHPUnit_Framework_TestCase') && class_exists('\PHPUnit\Framework\TestCase')) {
    class_alias('\PHPUnit\Framework\TestCase', 'PHPUnit_Framework_TestCase');
}

class Testing extends PHPUnit_Framework_TestCase {

    private $apiKeyTest = '';

    protected function setUp(): void{
        $this->apiKeyTest = "V7G3iZfh6TCyAwdUg0KCir5sSFSHr188";
    }

    public function testRegister() {        
        $TekenAjaApi = new \TekenAja\Api($this->apiKeyTest);        
        $selfieImage = dirname(__DIR__, 1)."/images_test/ghozali-jual-foto-selfie-di-nft-dapat-rp12-6-miliar-ternyata-begini-cara-kerjanya-USz4uJ39yh.jpeg";
        $ktpImage = dirname(__DIR__, 1)."/images_test/l-img20210420015823jpg20210420005933.jpeg";        
        $tekenAja = $TekenAjaApi->register('andreas@singa.id','Andreas',1,'1989-05-03','jakarta','3175050305890008','085156229923','jl. trikora raya no. 14',13760,$ktpImage, $selfieImage);
        $responseData = json_decode($tekenAja->raw_body, true);   
        print_r($responseData);
        if(isset($responseData['status']) && isset($responseData['ref_id']) && isset($responseData['code']) &&  isset($responseData['timestamp']) && isset($responseData['message'])){
            return true;
        }else{
            return false;
        }        
    }

    public function testRegisterCheck() {        
        $TekenAjaApi = new \TekenAja\Api($this->apiKeyTest);        
        $tekenAja = $TekenAjaApi->registerCheck('3175050305890008','“check_nik”','andreas@singa.id');
        $responseData = json_decode($tekenAja->raw_body, true);        
        if(isset($responseData['status']) && isset($responseData['ref_id']) && isset($responseData['code']) &&  isset($responseData['timestamp']) && isset($responseData['message'])){
            return true;
        }else{
            return false;
        }        
    }
}