<?php

namespace App\Http\Helpers;

use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

class Document
{
    protected $templatePath;
    protected $saveWordDirectory = '/documents/word/';
    protected $savePdfDirectory = '/documents/pdf/';

    public function __construct($templatePath = null)
    {
        $this->templatePath = $templatePath;
    }

    public function docx($name, $data)
    {
        try {
            $templateProcessor = new TemplateProcessor($this->templatePath);

            /** Filling data */
            $templateProcessor->setValue(array_keys($data), array_values($data));

            /** Saving */
            $dirPath = "public".$this->saveWordDirectory;
            if(!\Storage::exists($dirPath))
                \Storage::makeDirectory($dirPath);
            $templateProcessor->saveAs(\Storage::path("$dirPath$name.docx"));

            /** Returning info about created file */
            $info['path'] = $this->saveWordDirectory;
            $info['name'] = $name.'.docx';
            $info['size'] = filesize(\Storage::path("$dirPath$name.docx"));
            return $info;
        } catch (CopyFileException $e) {
            return $e;
        } catch (CreateTemporaryFileException $e) {
            return $e;
        }
    }

    public function pdf($filePath)
    {
        Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
        Settings::setPdfRendererPath(\App::path("/../vendor/dompdf/dompdf"));

        /** get file name from full path */
        $segments = explode('/', $filePath);
        if(empty($segments[1]))
            $segments = explode('\\', $filePath);
        $name = explode('.',end($segments), -1)[0];
        $dirPath = "public$this->savePdfDirectory";
        if(!\Storage::exists($dirPath))
            \Storage::makeDirectory($dirPath);
        $wordPdf = IOFactory::load($filePath);
        $pdfWriter = IOFactory::createWriter($wordPdf, 'PDF');
        $filename = $name.'.pdf';
        header("Content-type:application/pdf");
        header( 'Content-Disposition: attachment; filename="'.$filename.'"');
        return $pdfWriter->save('php://output');

//        /** Returning info about created file */
//        $info['path'] = $this->savePdfDirectory;
//        $info['name'] = $name.'.pdf';
//        $info['size'] = filesize(\Storage::path("$dirPath$name.pdf"));
//        return $info;
    }

    public static function getWordPath()
    {
        return '/documents/word/';
    }

    public static function getPDFPath()
    {
        return '/documents/pdf/';
    }

    public static function getNameFromPath($filePath)
    {
        /** get file name from full path */
        $segments = explode('/', $filePath);
        if(empty($segments[1]))
            $segments = explode('\\', $filePath);
        $name = explode('.',end($segments), -1)[0];
        return $name;
    }
}
