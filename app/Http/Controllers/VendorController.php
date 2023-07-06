<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Approval;
use App\Models\Contract;
use App\Models\ContractVendor;
use App\Models\ReviewLegal;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;

class VendorController extends Controller
{
    public function contracts()
    {
        $vendor = Vendor::where('user_detail_id', Auth::id())->first();
        $contracts = $vendor->contracts()->get();

        return view('vendor.contracts', compact('contracts'));
    }

    public function contract(Contract $contract, Vendor $vendor)
    {
        $contracts = $contract->vendors()->where('vendor_id', $vendor->id)->withPivot('id')->first();
        $review_hukum = ReviewLegal::where('contract_vendor_id', $contracts->pivot->id)->get();

        return view('vendor.contract',  compact('contracts', 'contract', 'review_hukum'));
    }


    public function contract_edit(Contract $contract, Vendor $vendor)
    {
        $contracts = Contract::where('id', $contract->id)->first();
        $contract = $contracts->vendors()->where('vendor_id', $vendor->id)->first();
        return view('vendor.contract-edit', compact('contract'));
    }

    public function contract_update(Request $request, Contract $contract, Vendor $vendor, FlasherInterface $flasher)
    {
        $request->validate([
            'number' => 'required',
            'prosentase' => 'required',
            'nilai_kontrak' => 'required',
            'director' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $fileName = now()->format('Ymd') . "_" .  Str::random(20);

        $contract->vendors()->updateExistingPivot($vendor->id, [
            'status_id' => 2,
            'number' => $request->number,
            'prosentase' => $request->prosentase,
            'nilai_kontrak' => $request->nilai_kontrak,
            'director' => $request->director,
            'phone' => $request->phone,
            'address' => $request->address,
            'filename' => $fileName,
        ]);

        // .docx
        $templateProcessor = new TemplateProcessor('word-template/template-kontrak.docx');
        $templateProcessor->setValue('number', $request->number);
        $templateProcessor->setValue('prosentase', $request->prosentase);
        $templateProcessor->setValue('nilai_kontrak', $request->nilai_kontrak);
        $templateProcessor->setValue('director', $request->director);
        $templateProcessor->setValue('phone', $request->phone);
        $templateProcessor->setValue('address', $request->address);
        $templateProcessor->saveAs($fileName . '.docx');

        // .pdf
        $domPdfPath = base_path('vendor/dompdf/dompdf');
        \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
        \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
        $Content = \PhpOffice\PhpWord\IOFactory::load(public_path($fileName . '.docx'));
        $PDFWriter = \PhpOffice\PhpWord\IOFactory::createWriter($Content, 'PDF');
        $PDFWriter->save(public_path($fileName . '.pdf'));

        // get contract_detail id
        $contract_detail = $contract->vendors()->where('vendor_id', $vendor->id)->withPivot('id')->first();

        // create approval
        Approval::create([
            'contract_vendor_id' => $contract_detail->pivot->id,
            'name' => Auth::user()->name,
            'status' => 1,
            'description' => 'Data Kontrak Diisi Oleh Vendor',
        ]);

        $flasher->addSuccess('Berhasil menambah data kontrak!');

        return redirect()->route('vendor.contract', ['contract' => $contract->id, 'vendor' => $vendor->id]);
    }

    // CONTRACT FINAL
    public function contracts_final()
    {
        $contracts = ContractVendor::whereIn('status_id', [10, 11])->get();
        return view('vendor.contracts-final', compact('contracts'));
    }

    public function contract_final(Contract $contract, Vendor $vendor)
    {
        $contracts = $contract->vendors()->where('vendor_id', $vendor->id)->withPivot('id')->first();
        return view('vendor.contract-final', compact('contracts', 'contract'));
    }

    public function contract_upload(Request $request, Contract $contract, Vendor $vendor, FlasherInterface $flasher)
    {
        $request->validate([
            'kontrak' => 'nullable|mimes:pdf|max:10000',
        ]);

        if ($request->hasFile('kontrak')) {
            $kontrak = $request->file('kontrak');
            $nama_kontrak = Str::random(30) . '.' . $kontrak->getClientOriginalExtension();
            $kontrak->move('file_upload', $nama_kontrak);
        }

        $contract->vendors()->updateExistingPivot($vendor->id, [
            'final_vendor' => $nama_kontrak,
            'status_id' => 11
        ]);

        $flasher->addSuccess('Kontrak berhasil diupload!');

        return redirect()->back();
    }
}
