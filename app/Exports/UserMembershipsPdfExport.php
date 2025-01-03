<?php

namespace App\Exports;

use App\Models\UserMembership;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;


class UserMembershipsPdfExport
{
    public function export()
    {
        $memberships = UserMembership::with(['user', 'package'])->get();

        $pdf = PDF::loadView('exports.memberships-pdf', [
            'memberships' => $memberships
        ]);

        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'cairo',
        ]);

        return $pdf->download('memberships.pdf');
    }
}
