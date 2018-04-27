<?php

use Faker\Generator as Faker;

$factory->define(App\Program::class, function (Faker $faker) {
    return [
        'user_id'       => $user->id,
        'radio_type'    => $request->input('radio_type'),
        'radio_name'    => $request->input('radio_name'),
        'business_category' => $request->input('business_category'),
        'cpf'  => $request->input('cpf'),
        'cnpj'  => $request->input('cnpj'),
        'address'   => $request->input('address'),
        'address_cep' => $request->input('address_cep'),
        'address_complement'    => $request->input('address_complement'),
        'address_city' => $request->input('address_city'),
        'address_uf'    => $request->input('address_uf'),
        'tel' => $request->input('tel'),
        'tel_mobile'   => $request->input('tel_mobile'),
        'tel_mobile_carrier' => $request->input('tel_mobile_carrier'),
        'site' => $request->input('site'),
        'status' => $request->input('status'),
    ];
});
