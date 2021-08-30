<?php
namespace framework;
class Request {
    
    
    
    public function __construct(
        private string $uri,
        private string $method){
         
    }

        /**
         * Get the value of uri
         */ 
        public function getUri():string
        {
                return $this->uri;
        }

        /**
         * Get the value of method
         */ 
        public function getMethod():string
        {
                return $this->method;
        }
}