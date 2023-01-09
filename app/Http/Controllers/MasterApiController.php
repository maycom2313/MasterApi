<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Intervention\Image\ImageManagerStatic as Image;

class MasterApiController extends BaseController
{
    public function index()
    {
        return response()->json($this->model->all());
    }

    public function store(Request $request)
    {
        /* $this->validate($request, $this->model->rules());  */

        $dataForm = $request->all();
        

        if ($request->hasFile($this->upload) && $request->file($this->upload)->isValid()) {

            $requestFile = $request->file($this->upload);

            $extension = $requestFile->extension();
            
            $name = uniqid(date('His')) . "." . $extension;
            
            $upload = Image::make($dataForm[$this->upload])->resize(177, 236)->save(storage_path("app/public/clientes/$name", 70));
            if (!$upload) {
                return response()->json(['error' => 'falha ao fazer o upload'], 500);
            } else {
                $dataForm[$this->upload] = $name;
                /*  return $dataForm['image']; */
            }
        }

        $data = $this->model->create($dataForm);
        return response()->json($data, 201);
    }

    public function show($id)
    {
        if (!$data =$this->model->find($id)) {
            return response()->json(['error' => 'nada foi encontrado'], 404);
        } else {
            return response()->json($data);
        }
    }

    public function update(Request $request, $id)
    {
        if (!$data =$this->model->find($id)) 
            return response()->json(['error' => 'nada foi encontrado'], 404);

            /* $this->validate($request, $this->model->rules()); */

        $dataForm = $request->all();

        if ($request->hasFile($this->upload) && $request->file($this->upload)->isValid()) {
            $arquivo = $this->model->arquivos($id);
            if($arquivo){
                FacadesStorage::disk('public')->delete("/{$this->path}/$arquivo");
            }
            $requestFile = $request->file($this->upload);
            $extension = $requestFile->extension();
            $name = uniqid(date('His')) . "." . $extension;
            $upload = Image::make($dataForm['image'])->resize(177, 236)->save(storage_path("app/public/{$this->path}/$name", 70));
            if (!$upload) {
                return response()->json(['error' => 'falha ao fazer o upload'], 500);
            } else {
                $dataForm['image'] = $name;
                /*  return $dataForm['image']; */
            }
        }

        $data->update($dataForm);
        return response()->json($data, 201);
    }

    public function destroy($id)
    {
        //se nao for encontrado um valor na variavel cliente, irá retornar um json com um erro de 404(not found)
        if (!$data =$this->model->find($id)) 
            return response()->json(['error' => 'nada foi encontrado'], 404);
        //se estiver imagem, o FacadeStorage vai deletar a imagem
        $arquivo = $this->model->arquivos($id);
        if ($arquivo)
        {
            FacadesStorage::disk('public')->delete("/{$this->path}/$arquivo");
        }
        $data->delete();
        return response()->json(['success'=> 'deletado com sucesso']);
    }
}
