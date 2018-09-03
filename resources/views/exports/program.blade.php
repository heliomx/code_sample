<table>
    <thead>
        <tr>
            <td colspan="9">{{ $program->name }} ({{ $clients->count() }} {{ $helper->title() }})</td>
        </tr>
        <tr>
            <td>Id</td>
            <td>Nome</td>
            <td>E-mail</td>
            <td>Tipo</td>
            <td>Cidade</td>
            <td>UF</td>
            <td>Telefones</td>
            <td>Downloads</td>
            <td>Anotações</td>
        </tr>
    </thead>
    <tbody>
        @foreach($clients as $client)
        <tr>
            <td>{{ $client->id }}</td>
            <td>{{ $client->radio_name }}</td>
            <td>{{ $client->user->email }}</td>
            <td>{{ $helper->radioType($client->radio_type) }}</td>
            <td>{{ $client->address_city }}</td>
            <td>{{ $client->address_uf }}</td>
            <td>{{ $helper->formatTel($client->tel) }} | {{ $helper->formatMobile($client->tel_mobile) }}</td>
            <td>{{ $client->downloads_count }} </td>
            <td>{{ $client->annotations }} </td>
        </tr>
        @endforeach
    </tbody>

</table>