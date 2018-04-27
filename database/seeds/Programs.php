<?php

use Illuminate\Database\Seeder;

class Programs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('programs')->insert(['name' => 'Amado Batista',]);
        DB::table('programs')->insert(['name' => 'As Melhores do Dia',]);
        DB::table('programs')->insert(['name' => 'Brasil Brega Show',]);
        DB::table('programs')->insert(['name' => 'Caminhando com Jesus Cristo',]);
        DB::table('programs')->insert(['name' => 'Caminho Santo',]);
        DB::table('programs')->insert(['name' => 'Cantores do Brasil',]);
        DB::table('programs')->insert(['name' => 'Comentario direto de Brasilia',]);
        DB::table('programs')->insert(['name' => 'Consagracao a Nossa Senhora',]);
        DB::table('programs')->insert(['name' => 'Country Brasil',]);
        DB::table('programs')->insert(['name' => 'Culto Brasil',]);
        DB::table('programs')->insert(['name' => 'Encontro Com Ari Santos',]);
        DB::table('programs')->insert(['name' => 'Eu Voce e a Jovem Guarda',]);
        DB::table('programs')->insert(['name' => 'Forro Brasilis',]);
        DB::table('programs')->insert(['name' => 'Ligacao Nacional',]);
        DB::table('programs')->insert(['name' => 'Luiz Gonzaga',]);
        DB::table('programs')->insert(['name' => 'Mundo Sertanejo',]);
        DB::table('programs')->insert(['name' => 'Palavras de Fe',]);
        DB::table('programs')->insert(['name' => 'Radio Escuta',]);
        DB::table('programs')->insert(['name' => 'Roberto Carlos e Companhia',]);
        DB::table('programs')->insert(['name' => 'Saude Com Beleza',]);
        DB::table('programs')->insert(['name' => 'Vivendo Com Otimismo',]);
    }
}

