<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class ServiceRestore extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
      \DB::table('services')->truncate();
      \DB::table('service_actions')->truncate();
      \DB::table('roles')->truncate();
      \DB::table('role_access_lists')->truncate();
      \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
      $baseUrl = config('app.DB_SEEDER_SERVICE_RESTORE_API_LINK');
      $serviceJson = \Storage::disk('local')->get('bu\services.json');
      if($baseUrl){
        $serviceJson = str_replace('localhost\/thinka-api\/public\/api', $baseUrl, $serviceJson);
      }
      $services = json_decode($serviceJson, true);
      $serviceAction = json_decode(\Storage::disk('local')->get('bu\service_actions.json'), true);
      $roles = json_decode(\Storage::disk('local')->get('bu\roles.json'), true);
      $roleAccessList = json_decode(\Storage::disk('local')->get('bu\role_access_lists.json'), true);
      \DB:: table('services') -> insert($services);
      \DB:: table('service_actions') -> insert($serviceAction);
      \DB:: table('roles') -> insert($roles);
      \DB:: table('role_access_lists') -> insert($roleAccessList);
    }
}
