<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\DocumentTemplate;
use App\Models\Organization;
use App\Models\Region;
use App\Models\RegistrationJournal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'         => 'Admin User',
            'email'        => 'admin@edo.uz',
            'password'     => Hash::make('password'),
            'position'     => 'Bosh mutaxassis',
            'organization' => 'Davlat boshqaruvi',
        ]);

        RegistrationJournal::insert([
            ['name' => 'Kiruvchi hujjatlar jurnali',    'prefix' => 'KH',  'description' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chiquvchi hujjatlar jurnali',   'prefix' => 'CH',  'description' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ichki hujjatlar jurnali',       'prefix' => 'IH',  'description' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Buyruqlar jurnali',             'prefix' => 'BUY', 'description' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Qarorlar jurnali',              'prefix' => 'QAR', 'description' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DocumentTemplate::insert([
            ['name' => 'Umumiy xat',        'description' => 'Standart rasmiy xat shabloni',     'file_path' => 'templates/umumiy_xat.docx',        'file_name' => 'umumiy_xat.docx',        'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Buyruq',            'description' => 'Buyruq hujjati shabloni',           'file_path' => 'templates/buyruq.docx',            'file_name' => 'buyruq.docx',            'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Qaror',             'description' => 'Qaror hujjati shabloni',            'file_path' => 'templates/qaror.docx',             'file_name' => 'qaror.docx',             'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ariza',             'description' => 'Ariza shabloni',                    'file_path' => 'templates/ariza.docx',             'file_name' => 'ariza.docx',             'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tushuntirish xati', 'description' => 'Tushuntirish xati shabloni',        'file_path' => 'templates/tushuntirish_xati.docx', 'file_name' => 'tushuntirish_xati.docx', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $regions = [
            'Toshkent shahri', 'Toshkent viloyati', 'Andijon viloyati',
            'Farg\'ona viloyati', 'Namangan viloyati', 'Samarqand viloyati',
            'Buxoro viloyati', 'Qashqadaryo viloyati', 'Surxondaryo viloyati',
            'Jizzax viloyati', 'Sirdaryo viloyati', 'Navoiy viloyati',
            'Xorazm viloyati', 'Qoraqalpog\'iston Respublikasi',
        ];

        foreach ($regions as $regionName) {
            Region::create(['name' => $regionName]);
        }

        $tashkent = Region::where('name', 'Toshkent shahri')->first();
        $districts = ['Bektemir', 'Chilonzor', 'Hamza', 'Mirobod', 'Mirzo Ulug\'bek', 'Sergeli', 'Shayxontohur', 'Uchtepa', 'Yakkasaroy', 'Yashnobod', 'Yunusobod'];
        foreach ($districts as $d) {
            District::create(['region_id' => $tashkent->id, 'name' => $d]);
        }

        $categories = [
            ['name' => 'Vazirliklar', 'type' => 'category', 'is_category' => true, 'sort_order' => 1, 'children' => [
                'Iqtisodiyot va moliya vazirligi',
                'Adliya vazirligi',
                'Ichki ishlar vazirligi',
                'Mudofaa vazirligi',
                'Tashqi ishlar vazirligi',
                'Sog\'liqni saqlash vazirligi',
                'Xalq ta\'limi vazirligi',
                'Oliy ta\'lim, fan va innovatsiyalar vazirligi',
                'Qishloq xo\'jaligi vazirligi',
                'Energetika vazirligi',
                'Raqamli texnologiyalar vazirligi',
                'Qurilish vazirligi',
                'Transport vazirligi',
            ]],
            ['name' => 'Inspeksiyalar', 'type' => 'inspection', 'is_category' => true, 'sort_order' => 2, 'children' => [
                'Soliq inspeksiyasi',
                'Sanitariya inspeksiyasi',
                'Yong\'in xavfsizligi inspeksiyasi',
                'Ekologiya inspeksiyasi',
                'Mehnat inspeksiyasi',
            ]],
            ['name' => 'Agentliklar', 'type' => 'agency', 'is_category' => true, 'sort_order' => 3, 'children' => [
                'Statistika agentligi',
                'Davlat aktivlarini boshqarish agentligi',
                'Davlat xizmatlari agentligi',
                'Oziq-ovqat xavfsizligi agentligi',
                'Geodeziya, kartografiya va davlat kadastri agentligi',
            ]],
            ['name' => 'Banklar', 'type' => 'bank', 'is_category' => true, 'sort_order' => 4, 'children' => [
                'O\'zbekiston Milliy banki',
                'Markaziy bank',
                'Xalq banki',
                'Agrobank',
                'Ipoteka banki',
                'Sanoatqurilishbank',
                'Kapitalbank',
                'Hamkorbank',
            ]],
            ['name' => 'Hokimliklar', 'type' => 'government', 'is_category' => true, 'sort_order' => 5, 'children' => [
                'Toshkent shahar hokimligi',
                'Toshkent viloyati hokimligi',
                'Andijon viloyati hokimligi',
                'Farg\'ona viloyati hokimligi',
                'Namangan viloyati hokimligi',
                'Samarqand viloyati hokimligi',
            ]],
            ['name' => 'Boshqa tashkilotlar', 'type' => 'organization', 'is_category' => true, 'sort_order' => 6, 'children' => [
                'O\'zbekiston Respublikasi Bosh prokuraturasi',
                'O\'zbekiston Respublikasi Hisob palatasi',
                'Markaziy saylov komissiyasi',
                'Huquqiy ta\'lim markazi',
                'E-Gov portali',
            ]],
        ];

        foreach ($categories as $catData) {
            $cat = Organization::create([
                'name'        => $catData['name'],
                'type'        => $catData['type'],
                'is_category' => true,
                'sort_order'  => $catData['sort_order'],
            ]);

            foreach ($catData['children'] as $childName) {
                Organization::create([
                    'parent_id'   => $cat->id,
                    'name'        => $childName,
                    'type'        => $catData['type'],
                    'is_category' => false,
                    'sort_order'  => 0,
                ]);
            }
        }
    }
}
