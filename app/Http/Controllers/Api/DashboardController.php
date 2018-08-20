<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Download;
use App\Program;
use App\Client;
use App\Signature;
use DB;

class DashboardController extends Controller
{
    public function general()
    {
        $downloadsCount = Download::count();
        $programsCount = Program::withCount(['downloads','files', 'signatures'])
            ->orderBy('downloads_count', 'desc')
            ->get();

        $activeClientsCount = Client::whereStatus('A')->count();
        $activeSignaturesCount = Signature::whereStatus('A')->count();
        $clientsPerState = DB::table('clients')
            ->select(DB::raw('count("address_uf") as qt, address_uf'))
            ->groupBy('address_uf')
            ->orderBy('qt', 'desc')
            ->get();
        
        return [
            'downloads'         => $downloadsCount,
            'programs'          => $programsCount,
            'activeClients'     => $activeClientsCount,
            'activeSignatures'  => $activeSignaturesCount,
            'clientsPerState'   => $clientsPerState
        ];
    }
}