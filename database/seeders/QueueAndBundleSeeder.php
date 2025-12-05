<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QueueAndBundleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // truncate queues, queue_tickets e bundles
        DB::table('queues')->truncate();
        DB::table('queue_tickets')->truncate();
        DB::table('bundles')->truncate();

        // generate queues for company id = 1
        $queues = $this->generateQueues();

        echo "Filas criadas com sucesso! Lista de hash_codes" . PHP_EOL;
        foreach ($queues as $queue) {
            echo $queue['hash_code'] . PHP_EOL;
        }

        // generate bundles for company id = 1
        $bundles = $this->generateBundles(array_map(function ($queue) {
            return $queue['hash_code'];
        }, $queues));
        echo PHP_EOL;
        echo "Bundles criados com sucesso! Lista de credenciais" . PHP_EOL;
        foreach ($bundles as $bundle) {
            echo "Bundle name: " . $bundle['name'] . PHP_EOL;
            echo "Crendencial username: " . $bundle['credential_username'] . PHP_EOL . "Credencial password: " . $bundle['credential_password'] . PHP_EOL;
            echo "----------------------------------------" . PHP_EOL;
        }
    }

    private function generateQueues()
    {
        $queueColors = [
            'prefix_bg_color' => '#ff0000',
            'prefix_text_color' => '#ffffff',
            'number_bg_color' => '#cccccc',
            'number_text_color' => '#000000',
        ];

        $data = [
            [
                'name' => 'Fila número 01',
                'description' => 'Fila de atendimento geral para clientes',
                'service_name' => 'Atendimento Geral',
                'service_desk' => 'Balcão 01',
                'queue_prefix' => 'A',
                'queue_total_digits' => 2,
                'queue_colors' => json_encode($queueColors),
                'hash_code' => Str::random(64),
                'status' => 'active',
            ],
            [
                'name' => 'Fila número 02',
                'description' => 'Fila de atendimento para cardiologia',
                'service_name' => 'Cardiologia',
                'service_desk' => 'Balcão 02',
                'queue_prefix' => 'B',
                'queue_total_digits' => 2,
                'queue_colors' => json_encode($queueColors),
                'hash_code' => Str::random(64),
                'status' => 'active',
            ],
            [
                'name' => 'Fila número 03',
                'description' => 'Fila de atendimento para oftalmologia',
                'service_name' => 'Oftalmologia',
                'service_desk' => 'Balcão 03',
                'queue_prefix' => 'C',
                'queue_total_digits' => 2,
                'queue_colors' => json_encode($queueColors),
                'hash_code' => Str::random(64),
                'status' => 'active',
            ],
            [
                'name' => 'Fila número 04',
                'description' => 'Fila de atendimento para pediatria',
                'service_name' => 'Pediatria',
                'service_desk' => 'Balcão 04',
                'queue_prefix' => 'D',
                'queue_total_digits' => 2,
                'queue_colors' => json_encode($queueColors),
                'hash_code' => Str::random(64),
                'status' => 'active',
            ],
            [
                'name' => 'Fila número 05',
                'description' => 'Fila de atendimento para dermatologia',
                'service_name' => 'Dermatologia',
                'service_desk' => 'Balcão 05',
                'queue_prefix' => 'E',
                'queue_total_digits' => 2,
                'queue_colors' => json_encode($queueColors),
                'hash_code' => Str::random(64),
                'status' => 'active',
            ],
            [
                'name' => 'Fila número 06',
                'description' => 'Fila de atendimento para ortopedia',
                'service_name' => 'Ortopedia',
                'service_desk' => 'Balcão 06',
                'queue_prefix' => 'F',
                'queue_total_digits' => 2,
                'queue_colors' => json_encode($queueColors),
                'hash_code' => Str::random(64),
                'status' => 'active',
            ],
        ];

        foreach ($data as $queue) {
            DB::table('queues')->insert([
                'id_company' => 1, // Assuming company_id = 1
                'name' => $queue['name'],
                'description' => $queue['description'],
                'service_name' => $queue['service_name'],
                'service_desk' => $queue['service_desk'],
                'queue_prefix' => $queue['queue_prefix'],
                'queue_total_digits' => $queue['queue_total_digits'],
                'queue_colors' => $queue['queue_colors'],
                'hash_code' => $queue['hash_code'],
                'status' => $queue['status'],
            ]);
        }

        return array_map(function ($queue) {
            return [
                'hash_code' => $queue['hash_code'],
            ];
        }, $data);
    }

    private function generateBundles($hash_codes)
    {
        $data = [
            [
                'name' => 'Filas de espera 01',
                'queues' => array_slice($hash_codes, 0, 3),
                'credential_username' => Str::random(64),
                'credential_password' => Str::random(64),
            ],
            [
                'name' => 'Filas de espera 02',
                'queues' => array_slice($hash_codes, -3),
                'credential_username' => Str::random(64),
                'credential_password' => Str::random(64),
            ],
            [
                'name' => 'Filas de espera 03',
                'queues' => $hash_codes,
                'credential_username' => Str::random(64),
                'credential_password' => Str::random(64),
            ],
        ];

        foreach ($data as $bundle) {
            DB::table('bundles')->insert([
                'id_company' => 1, // Assuming company_id = 1
                'name' => $bundle['name'],
                'queues' => json_encode($bundle['queues']),
                'credential_username' => $bundle['credential_username'],
                'credential_password' => bcrypt($bundle['credential_password']),
            ]);
        }

        // return the bundles credentials
        return array_map(function ($bundle) {
            return [
                'name' => $bundle['name'],
                'credential_username' => $bundle['credential_username'],
                'credential_password' => $bundle['credential_password'],
            ];
        }, $data);
    }
}
