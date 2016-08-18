<?php


namespace JeroenNoten\LaravelNewsletter\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Excel;

class Parser extends Controller
{
    /**
     * @var Excel
     */
    private $excel;

    public function __construct(Excel $excel)
    {
        $this->middleware('auth');
        $this->excel = $excel;
        $this->excel->noHeading();
        $this->excel->skip(1);
    }

    public function parse(Request $request)
    {
        $file = $request->file('file');
        return $this->excel->load($file)->get();
    }
}