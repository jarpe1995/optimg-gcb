<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Image;

class OptmizarController extends Controller
{
    public function optimizar(Request $request)
    {
    	$directorio = public_path() . '/facturas';
    	$files = File::allFiles($directorio);
    	$archivos = [];
    	
    	foreach ($files as $file){
    		array_push($archivos, (string)$file);
    	}

    	$files = $archivos;
        $cont = 0;
        
        foreach ($files as $file) {
            // $file = strtolower($file);
            if (strpos($file, '.pdf')) {

              exec('convert -density 100x100 -quality 60 -compress jpeg '.$file.' '.$file.';');

            }else if (strpos($file, '.jpeg') || strpos($file, '.png') || strpos($file, '.jpg')) {

                $img = Image::make($file);
                $img->orientate();
                $img->resize(540, null, function($constraint) {
                 $constraint->aspectRatio();
             })->save($file);
                $cont += 1;
            }
        }
            // return $cont;
        return back();
    }

    public function getCodeBar(Request $request)
    {
        $request->session()->flush();

        $var1 = $request->code1;
        $var2 = $request->code2;
        $var3 = $request->code3;
        $var4 = $request->code4;
        $desc1 = $request->descripcion1;
        $desc2 = $request->descripcion2;
        // return redirect()->back()->with('var1', [$var1]);
        return view('welcome',compact('var1', 'var2', 'var3', 'var4', 'desc1', 'desc2'));
        // return back()->with(['var1' => $var1, 'var2' => $var2, 'var3' => $var3, 'var4' => $var4, 'desc1' => $desc1, 'desc2' => $desc2]);
    }
}
