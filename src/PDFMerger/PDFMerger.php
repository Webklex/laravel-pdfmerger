<?php
/*
* File:     PDFMerger.php
* Category: PDFMerger
* Author:   M. Goldenbaum
* Created:  01.12.16 20:18
* Updated:  -
*
* Description:
*  -
*/

namespace Webklex\PDFMerger;

use setasign\Fpdi\Fpdi as FPDI;
use setasign\Fpdi\PdfParser\StreamReader;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class PDFMerger {

    /**
     * Access the filesystem on an oop base
     *
     * @var Filesystem
     */
    protected $oFilesystem = Filesystem::class;

    /**
     * Hold all the files which will be merged
     *
     * @var Collection
     */
    protected $aFiles = Collection::class;

    /**
     * Holds every tmp file so they can be removed during the deconstruction
     *
     * @var Collection
     */
    protected $tmpFiles = Collection::class;

    /**
     * The actual PDF Service
     *
     * @var FPDI
     */
    protected $oFPDI = FPDI::class;

    /**
     * The final file name
     *
     * @var string
     */
    protected $fileName = 'undefined.pdf';

    /**
     * Construct and initialize a new instance
     * @param Filesystem $oFilesystem
     */
    public function __construct(Filesystem $oFilesystem){
        $this->oFilesystem = $oFilesystem;
        $this->oFPDI = new FPDI();
        $this->tmpFiles = collect([]);

        $this->init();
    }

    /**
     * The class deconstructor method
     */
    public function __destruct() {
        $oFilesystem = $this->oFilesystem;
        $this->tmpFiles->each(function($filePath) use($oFilesystem){
            $oFilesystem->delete($filePath);
        });
    }

    /**
     * Initialize a new internal instance of FPDI in order to prevent any problems with shared resources
     * Please visit https://www.setasign.com/products/fpdi/manual/#p-159 for more information on this issue
     *
     * @return self
     */
    public function init(){
        $this->oFPDI = new FPDI();
        $this->aFiles = collect([]);
        return $this;
    }

    /**
     * Stream the merged PDF content
     *
     * @return string
     */
    public function stream(){
        return $this->oFPDI->Output($this->fileName, 'I');
    }

    /**
     * Download the merged PDF content
     *
     * @return string
     */
    public function download(){
        $output = $this->output();
        return new Response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' =>  'attachment; filename="' . $this->fileName . '"',
            'Content-Length' => strlen($output),
        ]);
    }

    /**
     * Save the merged PDF content to the filesystem
     *
     * @return string
     */
    public function save($filePath = null){
        return $this->oFilesystem->put($filePath?$filePath:$this->fileName, $this->output());
    }

    /**
     * Get the merged PDF content
     *
     * @return string
     */
    public function output(){
        return $this->oFPDI->Output($this->fileName, 'S');
    }

    /**
     * Set the final filename
     * @param string $fileName
     *
     * @return string
     */
    public function setFileName($fileName){
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * Set the final filename
     * @param string $string
     * @param mixed $pages
     * @param mixed $orientation
     *
     * @return string
     */
    public function addString($string, $pages = 'all', $orientation = null){

        $filePath = storage_path('tmp/'.Str::random(16).'.pdf');
        $this->oFilesystem->put($filePath, $string);
        $this->tmpFiles->push($filePath);

        return $this->addPDF($filePath, $pages, $orientation);
    }

    /**
     * Add a PDF for inclusion in the merge with a valid file path. Pages should be formatted: 1,3,6, 12-16.
     * @param string $filePath
     * @param string $pages
     * @param string $orientation
     *
     * @return self
     *
     * @throws \Exception if the given pages aren't correct
     */
    public function addPDF($filePath, $pages = 'all', $orientation = null) {
        if (file_exists($filePath)) {
            if (!is_array($pages) && strtolower($pages) != 'all') {
                throw new \Exception($filePath."'s pages could not be validated");
            }

            $this->aFiles->push([
                'name'  => $filePath,
                'pages' => $pages,
                'orientation' => $orientation
            ]);
        } else {
            throw new \Exception("Could not locate PDF on '$filePath'");
        }

        return $this;
    }

    /**
     * Merges your provided PDFs and outputs to specified location.
     * @param string $orientation
     *
     * @return void
     *
     * @throws \Exception if there are now PDFs to merge
     */
    public function merge($orientation = null) {
        $this->doMerge($orientation, false);
    }

    /**
     * Merges your provided PDFs and adds blank pages between documents as needed to allow duplex printing
     * @param string $orientation
     *
     * @return void
     *
     * @throws \Exception if there are now PDFs to merge
     */
    public function duplexMerge($orientation = 'P') {
        $this->doMerge($orientation, true);
    }

    protected function doMerge($orientation, $duplexSafe) {

        if ($this->aFiles->count() == 0) {
            throw new \Exception("No PDFs to merge.");
        }

        $oFPDI = $this->oFPDI;

        $this->aFiles->each(function($file) use($oFPDI, $orientation, $duplexSafe){
            $file['orientation'] = is_null($file['orientation'])?$orientation:$file['orientation'];
            $count = $oFPDI->setSourceFile(StreamReader::createByString(file_get_contents($file['name'])));

            if ($file['pages'] == 'all') {

                for ($i = 1; $i <= $count; $i++) {
                    $template   = $oFPDI->importPage($i);
                    $size       = $oFPDI->getTemplateSize($template);
                    $autoOrientation = isset($file['orientation']) ? $file['orientation'] : $size['orientation'];

                    $oFPDI->AddPage($autoOrientation, [$size['width'], $size['height']]);
                    $oFPDI->useTemplate($template);
                }
            } else {
                foreach ($file['pages'] as $page) {
                    if (!$template = $oFPDI->importPage($page)) {
                        throw new \Exception("Could not load page '$page' in PDF '" . $file['name'] . "'. Check that the page exists.");
                    }
                    $size = $oFPDI->getTemplateSize($template);
                    $autoOrientation = isset($file['orientation']) ? $file['orientation'] : $size['orientation'];

                    $oFPDI->AddPage($autoOrientation, [$size['width'], $size['height']]);
                    $oFPDI->useTemplate($template);
                }
            }

            if ($duplexSafe && $oFPDI->page % 2) {
                $oFPDI->AddPage($file['orientation'], [$size['width'], $size['height']]);
            }
        });
    }
}
