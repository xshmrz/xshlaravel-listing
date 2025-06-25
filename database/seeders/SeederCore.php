<?php
    namespace Database\Seeders;
    use EnumListingCategoryStatus;
    use Illuminate\Database\Seeder;
    class SeederCore extends Seeder {
        public function run() : void {
            $location                  = Location()->whereNotNull(parent_id)->pluck(id);
            $user                      = User();
            $user->first_name          = 'Root';
            $user->last_name           = 'Demo';
            $user->email               = 'root@demo.com';
            $user->password            = md5('123456');
            $user->gsm                 = fake()->numerify('532#######');
            $user->role                = \EnumUserRole::Root;
            $user->status              = \EnumUserStatus::Active;
            $user->can_login_panel     = \EnumUserCanLoginPanel::No;
            $user->can_login_dashboard = \EnumUserCanLoginDashboard::Yes;
            $user->save();
            for ($iUser = 1; $iUser < 500; $iUser++) {
                $user                      = User();
                $user->location_id         = fake()->randomElement($location);
                $user->first_name          = fake()->firstName;
                $user->last_name           = fake()->lastName;
                $user->email               = \Str::slug($user->first_name.'-'.$user->last_name).'@demo.com';
                $user->password            = md5('123456');
                $user->gsm                 = fake()->numerify('532#######');
                $user->role                = \EnumUserRole::User;
                $user->status              = \EnumUserStatus::Active;
                $user->can_login_panel     = \EnumUserCanLoginPanel::No;
                $user->can_login_dashboard = \EnumUserCanLoginDashboard::No;
                $user->is_vendor           = \EnumUserIsVendor::Yes;
                $user->save();
            }
            $demo = include "Data.php";
            foreach ($demo["listing_category"] as $key => $value) {
                $listing_category         = ListingCategory();
                $listing_category->name   = $value;
                $listing_category->status = EnumListingCategoryStatus::Active;
                $listing_category->save();
                foreach (Location()->whereNotNull(parent_id)->limit(10)->get() as $location) {
                    for ($iListing = 1; $iListing <= rand(5, 10); $iListing++) {
                        $listing                      = Listing();
                        $listing->location_id         = $location->id;
                        $listing->user_id             = null;
                        $listing->listing_category_id = $listing_category->id;
                        $listing->name                = fake()->words(rand(2, 3), true);
                        $listing->save();
                        for ($iWorker = 1; $iWorker <= rand(1, 5); $iWorker++) {
                            $worker             = Worker();
                            $worker->listing_id = $listing->id;
                            $worker->first_name = fake()->firstName;
                            $worker->last_name  = fake()->lastName;
                            $worker->email      = \Str::slug($worker->first_name.'-'.$worker->last_name).'@demo.com';
                            $worker->password   = md5('123456');
                            $worker->gsm        = fake()->numerify('532#######');
                            $worker->save();
                        }
                    }
                }
            }
        }
    }
