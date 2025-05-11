<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Union;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BangladeshGeoSeeder extends Seeder
{
    public function run()
    {
        // Divisions
        $divisions = [
            ['id' => 1, 'name' => 'Barishal', 'bn_name' => 'বরিশাল'],
            ['id' => 2, 'name' => 'Chattogram', 'bn_name' => 'চট্টগ্রাম'],
            ['id' => 3, 'name' => 'Dhaka', 'bn_name' => 'ঢাকা'],
            ['id' => 4, 'name' => 'Khulna', 'bn_name' => 'খুলনা'],
            ['id' => 5, 'name' => 'Mymensingh', 'bn_name' => 'ময়মনসিংহ'],
            ['id' => 6, 'name' => 'Rajshahi', 'bn_name' => 'রাজশাহী'],
            ['id' => 7, 'name' => 'Rangpur', 'bn_name' => 'রংপুর'],
            ['id' => 8, 'name' => 'Sylhet', 'bn_name' => 'সিলেট']
        ];

        foreach ($divisions as $division) {
            Division::create($division);
        }

        // Districts
        $districts = [
            // Barishal Division
            ['id' => 1, 'division_id' => 1, 'name' => 'Barguna', 'bn_name' => 'বরগুনা'],
            ['id' => 2, 'division_id' => 1, 'name' => 'Barishal', 'bn_name' => 'বরিশাল'],
            ['id' => 3, 'division_id' => 1, 'name' => 'Bhola', 'bn_name' => 'ভোলা'],
            ['id' => 4, 'division_id' => 1, 'name' => 'Jhalokati', 'bn_name' => 'ঝালকাঠি'],
            ['id' => 5, 'division_id' => 1, 'name' => 'Patuakhali', 'bn_name' => 'পটুয়াখালী'],
            ['id' => 6, 'division_id' => 1, 'name' => 'Pirojpur', 'bn_name' => 'পিরোজপুর'],

            // Chattogram Division
            ['id' => 7, 'division_id' => 2, 'name' => 'Bandarban', 'bn_name' => 'বান্দরবান'],
            ['id' => 8, 'division_id' => 2, 'name' => 'Brahmanbaria', 'bn_name' => 'ব্রাহ্মণবাড়িয়া'],
            ['id' => 9, 'division_id' => 2, 'name' => 'Chandpur', 'bn_name' => 'চাঁদপুর'],
            ['id' => 10, 'division_id' => 2, 'name' => 'Chattogram', 'bn_name' => 'চট্টগ্রাম'],
            ['id' => 11, 'division_id' => 2, 'name' => 'Cumilla', 'bn_name' => 'কুমিল্লা'],
            ['id' => 12, 'division_id' => 2, 'name' => 'Cox\'s Bazar', 'bn_name' => 'কক্সবাজার'],
            ['id' => 13, 'division_id' => 2, 'name' => 'Feni', 'bn_name' => 'ফেনী'],
            ['id' => 14, 'division_id' => 2, 'name' => 'Khagrachhari', 'bn_name' => 'খাগড়াছড়ি'],
            ['id' => 15, 'division_id' => 2, 'name' => 'Lakshmipur', 'bn_name' => 'লক্ষ্মীপুর'],
            ['id' => 16, 'division_id' => 2, 'name' => 'Noakhali', 'bn_name' => 'নোয়াখালী'],
            ['id' => 17, 'division_id' => 2, 'name' => 'Rangamati', 'bn_name' => 'রাঙ্গামাটি'],

            // Dhaka Division
            ['id' => 18, 'division_id' => 3, 'name' => 'Dhaka', 'bn_name' => 'ঢাকা'],
            ['id' => 19, 'division_id' => 3, 'name' => 'Faridpur', 'bn_name' => 'ফরিদপুর'],
            ['id' => 20, 'division_id' => 3, 'name' => 'Gazipur', 'bn_name' => 'গাজীপুর'],
            ['id' => 21, 'division_id' => 3, 'name' => 'Gopalganj', 'bn_name' => 'গোপালগঞ্জ'],
            ['id' => 22, 'division_id' => 3, 'name' => 'Kishoreganj', 'bn_name' => 'কিশোরগঞ্জ'],
            ['id' => 23, 'division_id' => 3, 'name' => 'Madaripur', 'bn_name' => 'মাদারীপুর'],
            ['id' => 24, 'division_id' => 3, 'name' => 'Manikganj', 'bn_name' => 'মানিকগঞ্জ'],
            ['id' => 25, 'division_id' => 3, 'name' => 'Munshiganj', 'bn_name' => 'মুন্সিগঞ্জ'],
            ['id' => 26, 'division_id' => 3, 'name' => 'Narayanganj', 'bn_name' => 'নারায়ণগঞ্জ'],
            ['id' => 27, 'division_id' => 3, 'name' => 'Narsingdi', 'bn_name' => 'নরসিংদী'],
            ['id' => 28, 'division_id' => 3, 'name' => 'Rajbari', 'bn_name' => 'রাজবাড়ী'],
            ['id' => 29, 'division_id' => 3, 'name' => 'Shariatpur', 'bn_name' => 'শরীয়তপুর'],
            ['id' => 30, 'division_id' => 3, 'name' => 'Tangail', 'bn_name' => 'টাঙ্গাইল'],

            // Khulna Division
            ['id' => 31, 'division_id' => 4, 'name' => 'Bagerhat', 'bn_name' => 'বাগেরহাট'],
            ['id' => 32, 'division_id' => 4, 'name' => 'Chuadanga', 'bn_name' => 'চুয়াডাঙ্গা'],
            ['id' => 33, 'division_id' => 4, 'name' => 'Jashore', 'bn_name' => 'যশোর'],
            ['id' => 34, 'division_id' => 4, 'name' => 'Jhenaidah', 'bn_name' => 'ঝিনাইদাহ'],
            ['id' => 35, 'division_id' => 4, 'name' => 'Khulna', 'bn_name' => 'খুলনা'],
            ['id' => 36, 'division_id' => 4, 'name' => 'Kushtia', 'bn_name' => 'কুষ্টিয়া'],
            ['id' => 37, 'division_id' => 4, 'name' => 'Magura', 'bn_name' => 'মাগুরা'],
            ['id' => 38, 'division_id' => 4, 'name' => 'Meherpur', 'bn_name' => 'মেহেরপুর'],
            ['id' => 39, 'division_id' => 4, 'name' => 'Narail', 'bn_name' => 'নড়াইল'],
            ['id' => 40, 'division_id' => 4, 'name' => 'Satkhira', 'bn_name' => 'সাতক্ষীরা'],

            // Mymensingh Division
            ['id' => 41, 'division_id' => 5, 'name' => 'Jamalpur', 'bn_name' => 'জামালপুর'],
            ['id' => 42, 'division_id' => 5, 'name' => 'Mymensingh', 'bn_name' => 'ময়মনসিংহ'],
            ['id' => 43, 'division_id' => 5, 'name' => 'Netrokona', 'bn_name' => 'নেত্রকোণা'],
            ['id' => 44, 'division_id' => 5, 'name' => 'Sherpur', 'bn_name' => 'শেরপুর'],

            // Rajshahi Division
            ['id' => 45, 'division_id' => 6, 'name' => 'Bogra', 'bn_name' => 'বগুড়া'],
            ['id' => 46, 'division_id' => 6, 'name' => 'Joypurhat', 'bn_name' => 'জয়পুরহাট'],
            ['id' => 47, 'division_id' => 6, 'name' => 'Naogaon', 'bn_name' => 'নওগাঁ'],
            ['id' => 48, 'division_id' => 6, 'name' => 'Natore', 'bn_name' => 'নাটোর'],
            ['id' => 49, 'division_id' => 6, 'name' => 'Chapainawabganj', 'bn_name' => 'চাঁপাইনবাবগঞ্জ'],
            ['id' => 50, 'division_id' => 6, 'name' => 'Pabna', 'bn_name' => 'পাবনা'],
            ['id' => 51, 'division_id' => 6, 'name' => 'Rajshahi', 'bn_name' => 'রাজশাহী'],
            ['id' => 52, 'division_id' => 6, 'name' => 'Sirajganj', 'bn_name' => 'সিরাজগঞ্জ'],

            // Rangpur Division
            ['id' => 53, 'division_id' => 7, 'name' => 'Dinajpur', 'bn_name' => 'দিনাজপুর'],
            ['id' => 54, 'division_id' => 7, 'name' => 'Gaibandha', 'bn_name' => 'গাইবান্ধা'],
            ['id' => 55, 'division_id' => 7, 'name' => 'Kurigram', 'bn_name' => 'কুড়িগ্রাম'],
            ['id' => 56, 'division_id' => 7, 'name' => 'Lalmonirhat', 'bn_name' => 'লালমনিরহাট'],
            ['id' => 57, 'division_id' => 7, 'name' => 'Nilphamari', 'bn_name' => 'নীলফামারী'],
            ['id' => 58, 'division_id' => 7, 'name' => 'Panchagarh', 'bn_name' => 'পঞ্চগড়'],
            ['id' => 59, 'division_id' => 7, 'name' => 'Rangpur', 'bn_name' => 'রংপুর'],
            ['id' => 60, 'division_id' => 7, 'name' => 'Thakurgaon', 'bn_name' => 'ঠাকুরগাঁও'],

            // Sylhet Division
            ['id' => 61, 'division_id' => 8, 'name' => 'Habiganj', 'bn_name' => 'হবিগঞ্জ'],
            ['id' => 62, 'division_id' => 8, 'name' => 'Moulvibazar', 'bn_name' => 'মৌলভীবাজার'],
            ['id' => 63, 'division_id' => 8, 'name' => 'Sunamganj', 'bn_name' => 'সুনামগঞ্জ'],
            ['id' => 64, 'division_id' => 8, 'name' => 'Sylhet', 'bn_name' => 'সিলেট']
        ];

        foreach ($districts as $district) {
            District::create($district);
        }

        // Upazilas - We'll load from a JSON file due to large data size
        $upazilasJson = File::get(database_path('seeders/data/upazilas.json'));
        $upazilas = json_decode($upazilasJson, true);

        foreach ($upazilas as $upazila) {
            Upazila::create($upazila);
        }

        // Unions - We'll load from a JSON file due to very large data size
        $unionsJson = File::get(database_path('seeders/data/unions.json'));
        $unions = json_decode($unionsJson, true);

        foreach ($unions as $union) {
            Union::create($union);
        }
    }
}
