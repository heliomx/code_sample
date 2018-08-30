<table>
    <thead>
    <tr>
        <td style="vertical-align:middle" rowspan="2">Programa</td>
        <td rowspan="2">Clientes</td>
        <td colspan="6">Clientes Ativos</td>
        <td colspan="6">Clientes Ociosos</td>
        <td valign="middle" rowspan="2">Downloads</td>
    </tr>
    <tr>
        <td>Web</td>
        <td>FM</td>
        <td>AM</td>
        <td>TV</td>
        <td>TV Web</td>
        <td>Total</td>
        <td>Web</td>
        <td>FM</td>
        <td>AM</td>
        <td>TV</td>
        <td>TV Web</td>
        <td>Total</td>
    </tr>
    </thead>
    <tbody>
    @foreach($programs as $program)
        <?php $p = $helper->map($program); ?>
        <tr>
            <td>{{ $p['name'] }}</td>
            <td>{{ $p['total'] }}</td>
            <td>{{ $p['active_radios']['W'] }}</td>
            <td>{{ $p['active_radios']['F'] }}</td>
            <td>{{ $p['active_radios']['A'] }}</td>
            <td>{{ $p['active_radios']['T'] }}</td>
            <td>{{ $p['active_radios']['V'] }}</td>
            <td>
            {{ 
                $p['active_radios']['A'] + 
                $p['active_radios']['F'] + 
                $p['active_radios']['W'] + 
                $p['active_radios']['T'] + 
                $p['active_radios']['V'] 
            }}
            </td>
            <td>{{ $p['idle_radios']['W'] }}</td>
            <td>{{ $p['idle_radios']['F'] }}</td>
            <td>{{ $p['idle_radios']['A'] }}</td>
            <td>{{ $p['idle_radios']['T'] }}</td>
            <td>{{ $p['idle_radios']['V'] }}</td>
            <td>
            {{ 
                $p['idle_radios']['A'] + 
                $p['idle_radios']['F'] + 
                $p['idle_radios']['W'] + 
                $p['idle_radios']['T'] + 
                $p['idle_radios']['V'] 
            }}
            </td>
            <td>{{ $p['downloads'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>