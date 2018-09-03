<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Jobs\ExportReport;

class ReportController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();
            
        if ($user->role == User::ROLE_ADMIN)
        {
            $reportId = str_random(5);
            $user->initReport($reportId);
            $user->updateReport(0, 'Iniciando exportação do relatório', 0, 0);
            ExportReport::dispatch([
                'id' => $reportId,
                'month' => $request->input('month'),
                'year' => $request->input('year')
            ])->delay(now()->addSeconds(1));
            
            return response()->json([
                'success' => true
            ]);
        } else {
            abort(401, 'Não autorizado');
        }
    }

    public function status(Request $request)
    {
        $user = Auth::user();
            
        if ($user->role == User::ROLE_ADMIN)
        {
            return response()->json($user->meta['report']);
        } else {
            abort(401, 'Não autorizado');
        }
    }

    public function download(Request $request)
    {
        $user = Auth::user();
            
        if ($user->role == User::ROLE_ADMIN)
        {
            if($user->meta['report']['file'])
            {
                $user->updateReport(4, 'Download do relatório', 0, 0);
                return Storage::download($user->meta['report']['file']);
            }
        } else {
            abort(401, 'Não autorizado');
        }
    
    }
}
