<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\Contract;
use App\Models\ContractVendor;
use App\Models\ReviewLegal;
use Flasher\Prime\FlasherInterface;
use App\Models\Vendor;
use App\Models\Template;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerController extends Controller
{
    // CONTRACT MONITORING
    public function contracts_monitoring()
    {
        return view('buyer.contracts-monitoring', [
            "contracts" => Contract::where('user_detail_id', Auth::id())->get()
        ]);
    }

    public function contract_monitoring(Contract $contract)
    {
        $contract_vendor = $contract->vendors()->get();
        return view('buyer.contract-monitoring', compact('contract', 'contract_vendor'));
    }

    public function contract_monitoring_create()
    {
        return view('buyer.contract-create', [
            "vendor" => Vendor::all(),
            "templates" => Template::all(),
        ]);
    }

    public function contract_monitoring_store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'name' => 'required|max:255',
            'oe' => 'required|numeric',
            'template_id' => 'required',
            'vendor' => 'required',
        ]);

        $contract = Contract::create([
            'name' => $request->name,
            'oe' => $request->oe,
            'user_detail_id' => Auth::user()->id,
            'template_id' => $request->template_id,
        ]);

        $contract->vendors()->attach($request->vendor, ["status_id" => 1]);

        $flasher->addSuccess('Berhasil menambahkan pekerjaan!');

        return redirect()->route('buyer.contracts-monitoring');
    }

    public function contract_detail(Contract $contract, Vendor $vendor)
    {
        $contracts = $contract->vendors()->where('vendor_id', $vendor->id)->withPivot('id')->first();
        $review_hukum = ReviewLegal::where('contract_vendor_id', $contracts->pivot->id)->get();
        $approvals = Approval::where('contract_vendor_id', $contracts->pivot->id)->orderBy('created_at', 'DESC')->get();

        return view('buyer.contract-detail', compact('contracts', 'contract', 'review_hukum', 'approvals'));
    }

    public function contract_edit(Contract $contract, Vendor $vendor)
    {
        $contracts = Contract::where('id', $contract->id)->first();
        $contract = $contracts->vendors()->where('vendor_id', $vendor->id)->withPivot('id')->first();
        return view('buyer.contract-edit', compact('contract'));
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
            'director' => $request->director,
            'phone' => $request->phone,
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
            'description' => 'Data Kontrak Diisi Oleh Buyer',
        ]);

        $flasher->addSuccess('Berhasil mengubah data kontrak!');

        return redirect()->route('buyer.contract-detail', ['contract' => $contract->id, 'vendor' => $vendor->id]);
    }

    // CONTRACT REVIEW VENDOR
    public function contracts_review_vendor()
    {
        $contracts = ContractVendor::whereIn('status_id', [2])->get();
        return view('buyer.contracts-review-vendor', compact('contracts'));
    }

    public function contract_review_vendor(Contract $contract, Vendor $vendor)
    {
        $contracts = $contract->vendors()->where('vendor_id', $vendor->id)->withPivot('id')->first();
        return view('buyer.contract-review-vendor', compact('contracts', 'contract'));
    }

    public function contract_review_vendor_return(Request $request, Contract $contract, Vendor $vendor, FlasherInterface $flasher)
    {
        // validate input
        $request->validate([
            'description' => 'required'
        ]);

        // get contract_detail id
        $contract_detail = $contract->vendors()->where('vendor_id', $vendor->id)->withPivot('id')->first();

        // create approval
        Approval::create([
            'contract_vendor_id' => $contract_detail->pivot->id,
            'name' => Auth::user()->name,
            'status' => 2,
            'description' => $request->description,
        ]);

        $contract->vendors()->updateExistingPivot($vendor->id, [
            'status_id' => 1,
        ]);

        $flasher->addSuccess('Berhasil mengembalikan ke vendor!');

        return redirect()->back();
    }

    public function contract_review_vendor_review(Request $request, Contract $contract, Vendor $vendor, FlasherInterface $flasher)
    {
        // validate input
        $request->validate([
            'description' => 'required'
        ]);

        // get contract_detail id
        $contract_detail = $contract->vendors()->where('vendor_id', $vendor->id)->withPivot('id')->first();

        // create approval
        Approval::create([
            'contract_vendor_id' => $contract_detail->pivot->id,
            'name' => Auth::user()->name,
            'status' => 2,
            'description' => $request->description,
        ]);

        $contract->vendors()->updateExistingPivot($vendor->id, [
            'status_id' => 3,
        ]);

        $flasher->addSuccess('Berhasil mengajukan review ke hukum!');

        return redirect()->back();
    }

    // CONTRACT REVIEW HUKUM
    public function contracts_review_legal()
    {
        $contracts = ContractVendor::whereIn('status_id', [3, 4])->get();
        return view('buyer.contracts-review-legal', compact('contracts'));
    }

    public function contract_review_legal(Contract $contract, Vendor $vendor)
    {
        $contracts = $contract->vendors()->where('vendor_id', $vendor->id)->withPivot('id')->first();
        $review_hukum = ReviewLegal::where('contract_vendor_id', $contracts->pivot->id)->get();
        return view('buyer.contract-review-legal', compact('contracts', 'contract', 'review_hukum'));
    }

    public function contract_vendor_avp(Request $request, Contract $contract, Vendor $vendor, FlasherInterface $flasher)
    {
        // validate input
        $request->validate([
            'description' => 'required'
        ]);

        // get contract_detail id
        $contract_detail = $contract->vendors()->where('vendor_id', $vendor->id)->withPivot('id')->first();

        // create approval
        Approval::create([
            'contract_vendor_id' => $contract_detail->pivot->id,
            'name' => Auth::user()->name,
            'status' => 2,
            'description' => $request->description,
        ]);

        $contract->vendors()->updateExistingPivot($vendor->id, [
            'status_id' => 5,
        ]);

        $flasher->addSuccess('Berhasil mengajukan permintaan persetujuan ke AVP!');

        return redirect()->back();
    }

    // CONTRACT Approval
    public function contracts_approval()
    {
        $contracts = ContractVendor::whereIn('status_id', [4, 5, 6, 7, 8])->get();
        return view('buyer.contracts-approval', compact('contracts'));
    }

    public function contract_approval(Contract $contract, Vendor $vendor)
    {
        $contracts = $contract->vendors()->where('vendor_id', $vendor->id)->withPivot('id')->first();
        $review_hukum = ReviewLegal::where('contract_vendor_id', $contracts->pivot->id)->get();
        return view('buyer.contract-approval', compact('contracts', 'contract', 'review_hukum'));
    }
}
