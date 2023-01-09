<?php

namespace App\Http\Controllers;



use App\Models\Cliente;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\MasterApiController;
use App\Models\Documento;

class DocumentoApiController extends MasterApiController
{
    protected $model;
    protected $path;
    protected $upload;
    
    public function __construct(Documento $doc, Request $request)
    {
        $this->model = $doc;
    }
    public function clientes($id)
    {
        if (!$data =$this->model->with('cliente')->find($id)) {
            return response()->json(['error' => 'nada foi encontrado'], 404);
        } else {
            return response()->json($data);
        }
    }
     
}
