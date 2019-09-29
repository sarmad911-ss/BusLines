<?php

namespace App\Http\Controllers;

use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

class MainController extends Controller
{
    public function indexPage()
    {
        return redirect()->route('listTripView');
        return view('pages.index');
    }

    public function abortBrowser()
    {
        return abort(404);
    }

    public function docGenerateAction(\Request $request){
	    try {
		    $templateProcessor = new TemplateProcessor(resource_path("docs/testDoc.docx"));
		    $templateProcessor->setValue('name', 'Test name');
		    $templateProcessor->setValue(array('email', 'price'), array('test@email.com', '1250'));
		    if(!\Storage::exists("public/docs"))
		    	\Storage::makeDirectory("public/docs");
			$templateProcessor->saveAs(\Storage::path("public/docs/testDoc.docx"));


		    Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
		    Settings::setPdfRendererPath(\App::path("/../vendor/dompdf/dompdf"));

		    $wordPdf = IOFactory::load(\Storage::path("public/docs/testDoc.docx"));
		    $pdfWriter = IOFactory::createWriter($wordPdf , 'PDF');
		    $pdfWriter->save(\Storage::path("public/docs/testDoc.pdf"));
	    } catch (CopyFileException $e) {

	    } catch (CreateTemporaryFileException $e) {

	    }


    }

}
