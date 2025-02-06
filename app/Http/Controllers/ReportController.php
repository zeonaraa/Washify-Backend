<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function generateMemberReport()
{
    $members = Member::with('outlet')->get();

    $pdf = Pdf::loadView('reports.member_report', compact('members'))
              ->setPaper('A4', 'portrait');

    return response($pdf->output(), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="Laporan_Members.pdf"');
}

}
