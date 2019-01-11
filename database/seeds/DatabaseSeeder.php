<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        factory(App\User::class, 10)->create()->each(function($user){
            
            $user->companies()->save( $company = factory(App\Company::class)->make());
            for ($i=0; $i < 10; $i++) { 
                $customer;
                if($i % 2 == 0){
                    $customer = factory(App\Client::class)->create();
                    App\ClientCompany::create([
                        'company_id' => $company->id,
                        'client_id' => $customer->id
                    ]);
                }
                $i == 9 ? $numéro = 'D0' . ($i + 1) . '/2019' :  $numéro = 'D00' . ($i + 1) . '/2019' ;
                $company->devis()->save( $devis = factory(App\Devis::class)->make([
                    'numéro' => $numéro,
                    'etabli_par' => $user->id,
                    'client_id' => $customer->id
                ]));
                for ($j=0; $j < 10; $j++) { 
                    $devis->entrees()->save(factory(App\DevisEntree::class)->make());
                }
                $i == 9 ? $numéro = 'F0' . ($i + 1) . '/2019' :  $numéro = 'F00' . ($i + 1) . '/2019' ;
                $company->factures()->save( $facture = factory(App\Facture::class)->make([
                    'numéro' => $numéro,
                    'etabli_par' => $user->id,
                    'client_id' => $customer->id
                ]));
                for ($j=0; $j < 10; $j++) { 
                    $facture->entrees()->save(factory(App\FactureEntree::class)->make());
                }
            }
            
            
        });
    }
}
