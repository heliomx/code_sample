<?php

use Illuminate\Database\Seeder;

class Contents extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('contents')->delete();
        $doc = <<<EOT
        {
            "slides": [
              {
                "id": "lkjasdf-43534ks-dfg-sm",
                "img": "/img/mock/amado hi.jpg",
                "link": "#/programas/2",
                "order": 1,
                "title": "Amado Batista - O Rei",
                "description":
                  "Programa distribuído pela Rádio Estúdio Brasil para 800 emissoras."
              },
              {
                "id": "m348934tnv8hv-asdfn244-vxf4",
                "img": "/storage/content/m348934tnv8hv-asdfn244-vxf4.jpeg",
                "link": "#/programas/2",
                "order": 2,
                "title": "Cantores do Brasil",
                "description":
                  "Cantores do Brasil é um programa semanal com a apresentação de Edelson Moura que é show"
              }
            ],
            "seeAlso": [
              {
                "id": "eaf0a1b0-73ba-11e8-8187-411b1a1a662d",
                "img": "/storage/content/eaf0a1b0-73ba-11e8-8187-411b1a1a662d.jpeg",
                "link": "http://saudecombeleza.com.br",
                "title": "Saude com Beleza"
              },
              {
                "id": "m3468934tnv8hv-asdfn244-vxf4",
                "img": "/img/mock/amado hi.jpg",
                "link": "#/programas/1",
                "title": "TV Estúdio Brasil"
              }
            ],
            "welcome":
              "A 10 Anos no mercado a Rádio Estúdio Brasil vem inovando sempre nos seus conceitos e nos seus produtos. Nossa empresa oferece programas de áudio produzidos, gravados e editados para mais de 1.200 Emissoras de Rádio no Brasil e no Mundo.&nbsp;&nbsp;<div><br></div><div><a href=\"/quemsomos\">Nossa equipe</a>&nbsp;cuida de todos os detalhes: Edição de Áudio , Sonoplastia.&nbsp; <br>Tudo para lhe oferecer de forma Gratuita conteúdo para sua emissora de Rádio.<br></div>",
            "ourPrograms":
              "São diversos programas a escolha da sua emissora: programas gratuitos, com conteudos jornalísticos, programas diários com duração de uma ou duas horas e programas semanais.<div><br><p><strong>Confira o conteúdo que preparamos para sua emissora.</strong></p></div>",
            "selectedPrograms": [2, 20, 12]
          }
EOT;
        DB::table('contents')->insert(['doc_type' => 'home', 'doc' => $doc ]);

        $doc = <<<EOT
        {
            "slides": [
              {
                "id": "lkjasdf-43534ks-dfg-sm",
                "img": "/img/mock/amado hi.jpg",
                "link": "#/programas/2",
                "order": 1,
                "title": "Amado Batista - O Rei",
                "description":
                  "Programa distribuído pela Rádio Estúdio Brasil para 800 emissoras."
              },
              {
                "id": "m348934tnv8hv-asdfn244-vxf4",
                "img": "/storage/content/m348934tnv8hv-asdfn244-vxf4.jpeg",
                "link": "#/programas/2",
                "order": 2,
                "title": "Cantores do Brasil",
                "description":
                  "Cantores do Brasil é um programa semanal com a apresentação de Edelson Moura que é show"
              }
            ],
            "seeAlso": [
              {
                "id": "eaf0a1b0-73ba-11e8-8187-411b1a1a662d",
                "img": "/storage/content/eaf0a1b0-73ba-11e8-8187-411b1a1a662d.jpeg",
                "link": "http://saudecombeleza.com.br",
                "title": "Saude com Beleza"
              },
              {
                "id": "m3468934tnv8hv-asdfn244-vxf4",
                "img": "/img/mock/amado hi.jpg",
                "link": "#/programas/1",
                "title": "TV Estúdio Brasil"
              }
            ],
            "welcome":
              "A 10 Anos no mercado a Rádio Estúdio Brasil vem inovando sempre nos seus conceitos e nos seus produtos. Nossa empresa oferece programas de áudio produzidos, gravados e editados para mais de 1.200 Emissoras de Rádio no Brasil e no Mundo.&nbsp;&nbsp;<div><br></div><div><a href=\"/quemsomos\">Nossa equipe</a>&nbsp;cuida de todos os detalhes: Edição de Áudio , Sonoplastia.&nbsp; <br>Tudo para lhe oferecer de forma Gratuita conteúdo para sua emissora de Rádio.<br></div>",
            "ourPrograms":
              "São diversos programas a escolha da sua emissora: programas gratuitos, com conteudos jornalísticos, programas diários com duração de uma ou duas horas e programas semanais.<div><br><p><strong>Confira o conteúdo que preparamos para sua emissora.</strong></p></div>",
            "selectedPrograms": [2, 20, 12]
          }
EOT;
    }
}