<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        $employees = [
            [
                'name' => 'Олександр Усик',
                'email' => 'usik@gmail.com',
                'country_iso' => 'UA',
                'city' => 'Kyiv',
                'street' => 'Головна',
                'house_number' => '1',
                'zip_code' => '01001',
            ],
            [
                'name' => 'Володимир Кличко',
                'email' => 'klichko@gmail.com',
                'country_iso' => 'UA',
                'city' => 'Kyiv',
                'street' => 'Богдана Хмельницького',
                'house_number' => '10',
                'zip_code' => '02020',
            ],
            [
                'name' => 'Віталій Кличко',
                'email' => 'v.klichko@gmail.com',
                'country_iso' => 'UA',
                'city' => 'Kyiv',
                'street' => 'Тараса Шевченка',
                'house_number' => '25',
                'zip_code' => '03030',
            ],
            [
                'name' => 'Василь Ломаченко',
                'email' => 'lomachenko@gmail.com',
                'country_iso' => 'UA',
                'city' => 'Bilhorod-Dnistrovskyi',
                'street' => 'Спортивна',
                'house_number' => '5',
                'zip_code' => '35050',
            ],
            [
                'name' => 'Андрій Шевченко',
                'email' => 'shevchenko@gmail.com',
                'country_iso' => 'UA',
                'city' => 'Kyiv',
                'street' => 'Шевченка',
                'house_number' => '15',
                'zip_code' => '04040',
            ],
            [
                'name' => 'Володимир Боклан',
                'email' => 'volodymyr.boklan@gmail.com',
                'country_iso' => 'UA',
                'city' => 'Kyiv',
                'street' => 'Липська',
                'house_number' => '7',
                'zip_code' => '06060',
            ],
            [
                'name' => 'Тарас Цимбалюк',
                'email' => 'cymbalyuk@gmail.com',
                'country_iso' => 'UA',
                'city' => 'Lviv',
                'street' => 'Івана Франка',
                'house_number' => '12',
                'zip_code' => '79079',
            ],
            [
                'name' => 'Євген Коноплянка',
                'email' => 'konoplyanka@gmail.com',
                'country_iso' => 'UA',
                'city' => 'Kirovohrad',
                'street' => 'Соборності',
                'house_number' => '9',
                'zip_code' => '25025',
            ],
        ];

        $adminRole = Role::create(['name' => 'Admin']);

        foreach ($employees as $person) {
            User::factory()
                ->state([
                    'name' => $person['name'],
                    'email' => $person['email'],
                ])
                ->has(UserAddress::factory([
                    'country_iso' => $person['country_iso'],
                    'city' => $person['city'],
                    'street' => $person['street'],
                    'house_number' => $person['house_number'],
                    'zip_code' => $person['zip_code'],
                ]), 'addresses')
                ->create()->each(function (User $user) use ($adminRole) {
                    $user->assignRole($adminRole);
                });
        }

        User::factory(100)->create()->each(function ($user) {
            UserAddress::factory(rand(1, 2))->create(['user_id' => $user->id]);
        });
    }
}
