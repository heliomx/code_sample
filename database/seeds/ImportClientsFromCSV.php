<?php

use Illuminate\Database\Seeder;
use App\Client;
use App\User;

class ImportClientsFromCSV extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Importando clientes...');
        $file = storage_path('csv/clientes.csv');

        $customerArr = $this->csvToArray($file);

        $this->command->info(count($customerArr) . ' clientes');

        for ($i = 0; $i < count($customerArr); $i ++)
        {
            $userCheck = User::whereEmail($customerArr[$i]['email'])->first();
            if (!$userCheck)
            {
                try {
                    DB::beginTransaction();
                    $pswd = $rand = substr(md5(microtime()),rand(0,26),5);
                    $user = User::create([
                        'name' => $customerArr[$i]['nome'],
                        'email' => $customerArr[$i]['email'],
                        'role'  => 'C',
                        'password' => bcrypt($pswd),
                        'migration_password' => $pswd
                    ]);
    
                    $doc = intval(preg_replace('/[^0-9]+/', '', $customerArr[$i]['documento']));
                    $cep = intval(preg_replace('/[^0-9]+/', '', $customerArr[$i]['cep']));
                    
                    $client = Client::create([
                        'user_id'       => $user->id,
                        'radio_type'    => $customerArr[$i]['tipo'] == 'pj' ? 'F' : 'W',
                        'radio_name'    => $customerArr[$i]['radio'],
                        'cpf'  => $customerArr[$i]['tipo'] == 'pj' ? null : ($doc == 0 ? null : $doc),
                        'cnpj'  => $customerArr[$i]['tipo'] != 'pj' ? null : ($doc == 0 ? null : $doc),
                        'address'   => $customerArr[$i]['endereco'],
                        'address_cep' => $cep,
                        'address_complement'    => $customerArr[$i]['complemento'],
                        'address_city' => $customerArr[$i]['cidade'],
                        'address_uf'    => $customerArr[$i]['estado'],
                        'tel' => $customerArr[$i]['tel_fixo'],
                        'tel_mobile'   => $customerArr[$i]['tel_celular'],
                        'site' => $customerArr[$i]['site'],
                        'status' => Client::STATUS_MIGRATION,
                    ]);
        
                    DB::commit();
                    $this->command->info("$i OK  " . $customerArr[$i]['nome'] . ' - ' .  $customerArr[$i]['email']);
                    
                } catch ( \Exception $e ) {
                    DB::rollback();
                    $this->command->error("$i ERROR " . $customerArr[$i]['nome'] . ' - ' .  $customerArr[$i]['email'] . $e);
                }
            } else {
                $this->command->warn("$i  " . $userCheck->name . ' Já existe');
            }
            
        }

        $this->command->info('Done!');
    }

    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
}
