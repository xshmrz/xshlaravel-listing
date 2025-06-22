<?php
    namespace App\Http\Controllers\Site;
    use App\Http\Controllers\Controller;
    class Custom extends Controller {
        public function find($listing_category_id, $location_id) {
            $this->data["listing_category_id"] = $listing_category_id;
            $this->data["location_id"]         = $location_id;
            render_view($this->data);
        }
    }
