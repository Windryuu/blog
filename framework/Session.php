<?php

namespace framework;

use entity\User;

class Session {
    public function __construct(
        private ?User $user
    ) {
        
    }

        /**
         * Get the value of user
         */ 
        public function getUser(): ?User
        {
                return $this->user;
        }
}