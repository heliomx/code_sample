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
        DB::table('programs')->insert(['name' => 'Amado Batista', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'As Melhores do Dia', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Brasil Brega Show', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Caminhando com Jesus Cristo', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Caminho Santo', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Cantores do Brasil', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Comentario direto de Brasília', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Consagração a Nossa Senhora', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Country Brasil', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Culto Brasil', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Encontro Com Ari Santos', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Eu Você e a Jovem Guarda', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Forro Brasilis', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Ligacão Nacional', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Luiz Gonzaga', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Mundo Sertanejo', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Palavras de Fé', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Radio Escuta', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Roberto Carlos e Companhia', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Saude Com Beleza', 'program_type' => 'D']);
        DB::table('programs')->insert(['name' => 'Vivendo Com Otimismo', 'program_type' => 'D']);
    }
}

