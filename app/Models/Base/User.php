<?php
    namespace App\Models\Base;
    /**
     * Class User
     * @property string|null $full_name
     */
    class User extends \App\Models\Core\User {
        protected $appends = ['full_name'];
        public function getFullNameAttribute() : string {
            return $this->first_name." ".$this->last_name;
        }

    }
