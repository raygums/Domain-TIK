<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FormGeneratorController extends Controller
{
    /**
     * Tampilkan form paperless (untuk TIK)
     * Form digital yang bisa dilihat dan dicetak langsung
     */
    public function showPaperless(string $ticketNumber)
    {
        $submission = Submission::with(['unit.category', 'details'])
            ->where('ticket_number', $ticketNumber)
            ->firstOrFail();

        return view('forms.form-paperless', compact('submission'));
    }

    /**
     * Generate dan download form hardcopy sebagai PDF
     * Untuk diserahkan ke pimpinan/dekan
     */
    public function downloadHardcopy(string $ticketNumber)
    {
        $submission = Submission::with(['unit.category', 'details'])
            ->where('ticket_number', $ticketNumber)
            ->firstOrFail();

        $serviceName = match($submission->service_type) {
            'hosting' => 'Hosting',
            'vps' => 'VPS',
            default => 'Sub_Domain'
        };

        $filename = "Form_Permohonan_{$serviceName}_{$ticketNumber}.pdf";

        $pdf = Pdf::loadView('forms.form-hardcopy', compact('submission'));
        
        // Set paper size dan orientasi
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download($filename);
    }

    /**
     * Preview form hardcopy di browser (tanpa download)
     */
    public function previewHardcopy(string $ticketNumber)
    {
        $submission = Submission::with(['unit.category', 'details'])
            ->where('ticket_number', $ticketNumber)
            ->firstOrFail();

        $pdf = Pdf::loadView('forms.form-hardcopy', compact('submission'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream("Form_{$ticketNumber}.pdf");
    }

    /**
     * Halaman pemilihan jenis form yang akan digenerate
     */
    public function selectForm(string $ticketNumber)
    {
        $submission = Submission::with(['unit.category', 'details'])
            ->where('ticket_number', $ticketNumber)
            ->firstOrFail();

        return view('forms.select-form', compact('submission'));
    }
}
