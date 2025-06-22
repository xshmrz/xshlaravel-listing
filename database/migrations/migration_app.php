<?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    return new class extends Migration {
        public function up() {
            Schema::create("location", function (Blueprint $table) {
                $table->id();
                $table->string("name")->nullable();
                $table->decimal("lat", 11, 8)->nullable();
                $table->decimal("lng", 11, 8)->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->string("parent_id")->nullable();
                $table->string("parent_name")->nullable();
            });
            Schema::create("user", function (Blueprint $table) {
                $table->id();
                $table->foreignId("location_id")->nullable()->references('id')->on("location");
                $table->string("email")->nullable();
                $table->string("password")->nullable();
                $table->string("first_name")->nullable();
                $table->string("last_name")->nullable();
                $table->string("gsm")->nullable();
                $table->date("birthday")->nullable();
                $table->enum("gender", EnumUserGender::asArray())->default(EnumUserGender::I_Do_Not_Want_To_Specify);
                $table->enum("role", EnumUserRole::asArray())->default(EnumUserRole::User);
                $table->enum("status", EnumUserStatus::asArray())->default(EnumUserStatus::Active);
                $table->enum("can_login_panel", EnumUserCanLoginPanel::asArray())->default(EnumUserCanLoginPanel::No);
                $table->enum("can_login_dashboard", EnumUserCanLoginDashboard::asArray())->default(EnumUserCanLoginDashboard::No);
                $table->enum("is_vendor", EnumUserIsVendor::asArray())->default(EnumUserIsVendor::No);
                $table->json("upload")->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
            Schema::create("listing_category", function (Blueprint $table) {
                $table->id();
                $table->string("name")->nullable();
                $table->enum("status", EnumListingCategoryStatus::asArray())->default(EnumListingCategoryStatus::Passive);
                $table->json("upload")->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
            Schema::create("listing", function (Blueprint $table) {
                $table->id();
                $table->foreignId("location_id")->nullable()->references('id')->on("location");
                $table->foreignId("user_id")->nullable()->references('id')->on("user");
                $table->foreignId("listing_category_id")->nullable()->references('id')->on("listing_category");
                $table->string("name")->nullable();
                $table->string("gsm")->nullable();
                $table->string("address")->nullable();
                $table->decimal("lat", 11, 8)->nullable();
                $table->decimal("lng", 11, 8)->nullable();
                $table->enum("status", EnumListingStatus::asArray())->default(EnumListingStatus::Passive);
                $table->json("upload")->nullable();
                $table->json("working_day")->nullable();
                $table->json("working_hour")->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
            Schema::create("worker", function (Blueprint $table) {
                $table->id();
                $table->foreignId("listing_id")->nullable()->references('id')->on("listing");
                $table->string("email")->nullable();
                $table->string("password")->nullable();
                $table->string("first_name")->nullable();
                $table->string("last_name")->nullable();
                $table->string("gsm")->nullable();
                $table->date("birthday")->nullable();
                $table->enum("gender", EnumWorkerGender::asArray())->default(EnumWorkerGender::I_Do_Not_Want_To_Specify);
                $table->enum("role", EnumWorkerRole::asArray())->default(EnumWorkerRole::User);
                $table->enum("status", EnumWorkerStatus::asArray())->default(EnumWorkerStatus::Active);
                $table->enum("can_login_panel", EnumWorkerCanLoginPanel::asArray())->default(EnumWorkerCanLoginPanel::No);
                $table->json("upload")->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
            Schema::create("customer", function (Blueprint $table) {
                $table->id();
                $table->foreignId("listing_id")->nullable()->references('id')->on("listing");
                $table->string("email")->nullable();
                $table->string("password")->nullable();
                $table->string("first_name")->nullable();
                $table->string("last_name")->nullable();
                $table->string("gsm")->nullable();
                $table->date("birthday")->nullable();
                $table->enum("gender", EnumCustomerGender::asArray())->default(EnumCustomerGender::I_Do_Not_Want_To_Specify);
                $table->timestamps();
                $table->softDeletes();
            });
            Schema::create("appointment", function (Blueprint $table) {
                $table->id();
                $table->foreignId("user_id")->nullable()->references('id')->on("user");
                $table->foreignId("listing_id")->nullable()->references('id')->on("listing");
                $table->foreignId("worker_id")->nullable()->references('id')->on("worker");
                $table->foreignId("customer_id")->nullable()->references('id')->on("customer");
                $table->date("day")->nullable();
                $table->string("hour")->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
            Schema::create("income_expense_category", function (Blueprint $table) {
                $table->id();
                $table->string("name")->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
            Schema::create("income_expense", function (Blueprint $table) {
                $table->id();
                $table->foreignId("listing_id")->nullable()->references('id')->on("listing");
                $table->foreignId("income_expense_category_id")->nullable()->references('id')->on("income_expense_category");
                $table->date("day")->nullable();
                $table->decimal("amount", 11, 8)->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
            Schema::create("comment", function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->softDeletes();
            });
            Schema::create("invoice", function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->softDeletes();
            });
            Schema::create("settings", function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->softDeletes();
            });

        }
        public function down() {
            Schema::dropIfExists("settings");
            Schema::dropIfExists("invoice");
            Schema::dropIfExists("comment");
            Schema::dropIfExists("income_expense");
            Schema::dropIfExists("income_expense_category");
            Schema::dropIfExists("appointment");
            Schema::dropIfExists("customer");
            Schema::dropIfExists("worker");
            Schema::dropIfExists("listing");
            Schema::dropIfExists("listing_category");
            Schema::dropIfExists("user");
            Schema::dropIfExists("location");
        }
    };
