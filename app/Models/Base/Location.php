<?php
    namespace App\Models\Base;
    class Location extends \App\Models\Core\Location {
        public function findState() {
            return $this->whereNull('parent_id')->orderBy('name', 'asc');
        }
        public function findCity($parent_id) {
            return $this->where(['parent_id' => $parent_id])->orderBy('name', 'asc');
        }
    }
