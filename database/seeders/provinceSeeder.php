<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Seeder;

/**
 * ProvinceSeeder
 *
 * Popula a base de dados com as 11 províncias de Moçambique
 * e os respectivos distritos.
 *
 * Executado automaticamente pelo DatabaseSeeder.
 * Usa updateOrCreate para ser idempotente (pode correr várias vezes sem duplicar).
 */
class ProvinceSeeder extends Seeder
{
    public function run(): void
    {
        // Dados: [código => [nome, [distritos]]]
        $provinces = [
            'MZ-MPM' => [
                'name'      => 'Cidade de Maputo',
                'districts' => [
                    'Kampfumo', 'Nlhamankulu', 'KaMaxakeni', 'KaMubukwana',
                    'KaMavota', 'KaTembe', 'KaNyaka',
                ],
            ],
            'MZ-L' => [
                'name'      => 'Maputo Província',
                'districts' => [
                    'Boane', 'Magude', 'Manhiça', 'Marracuene',
                    'Matola', 'Matutuíne', 'Moamba', 'Namaacha',
                ],
            ],
            'MZ-G' => [
                'name'      => 'Gaza',
                'districts' => [
                    'Bilene', 'Chibuto', 'Chicualacuala', 'Chigubo',
                    'Chókwè', 'Guijá', 'Mabalane', 'Mandlakazi',
                    'Massangena', 'Massingir', 'Xai-Xai',
                ],
            ],
            'MZ-I' => [
                'name'      => 'Inhambane',
                'districts' => [
                    'Funhalouro', 'Govuro', 'Homoíne', 'Inharrime',
                    'Inhassoro', 'Jangamo', 'Mabote', 'Massinga',
                    'Morrumbene', 'Panda', 'Vilankulo', 'Zavala',
                ],
            ],
            'MZ-S' => [
                'name'      => 'Sofala',
                'districts' => [
                    'Búzi', 'Caia', 'Chemba', 'Cheringoma',
                    'Chibabava', 'Dondo', 'Gorongosa', 'Machanga',
                    'Maríngue', 'Marromeu', 'Muanza', 'Nhamatanda', 'Beira',
                ],
            ],
            'MZ-T' => [
                'name'      => 'Tete',
                'districts' => [
                    'Angónia', 'Cahora-Bassa', 'Changara', 'Chifunde',
                    'Chiuta', 'Dôa', 'Macanga', 'Magoé',
                    'Marara', 'Moatize', 'Mutarara', 'Tsangano', 'Zumbo', 'Tete',
                ],
            ],
            'MZ-B' => [
                'name'      => 'Manica',
                'districts' => [
                    'Báruè', 'Chimoio', 'Gondola', 'Guro',
                    'Macossa', 'Manica', 'Mossurize', 'Sussundenga', 'Tambara',
                ],
            ],
            'MZ-Q' => [
                'name'      => 'Zambézia',
                'districts' => [
                    'Alto Molócuè', 'Chinde', 'Derre', 'Gilé',
                    'Guruè', 'Ile', 'Inhassunge', 'Lugela',
                    'Maganja da Costa', 'Milange', 'Mocuba', 'Mopeia',
                    'Morrumbala', 'Namacurra', 'Namarrói', 'Nicoadala',
                    'Pebane', 'Quelimane',
                ],
            ],
            'MZ-N' => [
                'name'      => 'Nampula',
                'districts' => [
                    'Angoche', 'Eráti', 'Ilha de Moçambique', 'Lalaua',
                    'Larde', 'Liupo', 'Malema', 'Meconta',
                    'Mecubúri', 'Memba', 'Mogincual', 'Mogovolas',
                    'Moma', 'Monapo', 'Mossuril', 'Muecate',
                    'Murrupula', 'Nacala-Porto', 'Nacala-a-Velha', 'Nacarôa',
                    'Nampula', 'Rapale',
                ],
            ],
            'MZ-A' => [
                'name'      => 'Niassa',
                'districts' => [
                    'Chimbunila', 'Cuamba', 'Lago', 'Lichinga',
                    'Majune', 'Mandimba', 'Marrupa', 'Maúa',
                    'Mavago', 'Mecanhelas', 'Mecula', 'Metarica',
                    'Muembe', 'N\'Gauma', 'Ngapa', 'Sanga', 'Unango',
                ],
            ],
            'MZ-P' => [
                'name'      => 'Cabo Delgado',
                'districts' => [
                    'Ancuabe', 'Balama', 'Chiúre', 'Ibo',
                    'Macomia', 'Mecúfi', 'Meluco', 'Metuge',
                    'Mocímboa da Praia', 'Montepuez', 'Mueda', 'Muidumbe',
                    'Namuno', 'Nangade', 'Palma', 'Pemba', 'Quissanga',
                ],
            ],
        ];

        foreach ($provinces as $code => $data) {
            // Cria ou actualiza a província
            $province = Province::updateOrCreate(
                ['code' => $code],
                ['name' => $data['name'], 'is_active' => true]
            );

            // Cria os distritos desta província
            foreach ($data['districts'] as $districtName) {
                // Código do distrito: código da província + slug do nome
                $districtCode = $code . '-' . substr(strtoupper(preg_replace('/[^a-zA-Z]/', '', $districtName)), 0, 5);

                District::updateOrCreate(
                    ['name' => $districtName, 'province_id' => $province->id],
                    ['code' => $districtCode, 'is_active' => true]
                );
            }
        }

        $this->command->info('✅ Províncias e distritos de Moçambique inseridos.');
    }
}