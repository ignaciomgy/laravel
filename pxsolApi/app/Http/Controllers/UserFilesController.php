<?php

namespace App\Http\Controllers;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use App\UserFiles;
use App\Users;
use Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;



class UserFilesController extends Controller
{   

    #1. Permite subir un archivo a un usuario, luego lista el archivo más el resto de sus archivos activos.
    public function store(Request $request)
    { 

        $output = [];
        $result = ['user_id', 'uploaded_file', 'files'];
    
        //se admiten formatos de imagen png y jpg
       $validator = Validator::make($request->all(), 
              [ 
              'user_id' => 'required',
              'file' => 'required|mimes:png,jpg|max:2048',
             ]);   
 
        //check si estan los atributos requeridos
        if ($validator->fails()) {   
            return response()->json(['Faltan atributos'=>'invalid'], 200);                        
        }   

        //check si el usuario ingresado existe
        if (Users::find($request->user_id)->doesntExist()) {
            return response()->json(['Usuario no encontrado'=>'invalid'], 404);  
        }
  
        if ($files = $request->file('file')) {           

            $nombre =  $files->getClientOriginalName();

            //checkeo que el archivo se haya subido correctamente
            if (!Storage::disk('local')->put($nombre, File::get($files))) {
                return response()->json(['Error al subir el archivo.'=>'invalid'], 201);  
            }

            $path = "/storage/app/public/" . $nombre;                      

            $u_files = UserFiles::create(['user_id' => $request->user_id,  'file_name' => $nombre, 'url' => $path]); 
            
            //preparo los elementos a devolver
            $result["user_id"] = $request->user_id;
            $result["uploaded_file"] = $u_files->fresh();            
            
            $files_user = DB::table('user_files')
                        ->select(DB::raw('*'))
                        ->where([['id', '<>', $u_files['id']],
                        ['user_id', '=', $request->user_id],
                        ['deleted_at', '=', null]])
                        ->get();

            $result["files"] = $files_user;

            array_push($output, $result);

            return response()->json($output, 200);

        }
    }

    #2 Endpoint. Retorna los archivos activos de un usuario especifico.
    public function auserfiles($user_id)
    {
        $result = ['user_id', 'files'];

        //check si el usuario ingresado existe
        if (UserFiles::where('user_id', $user_id)->doesntExist()) {
            return response()->json(['Usuario no encontrado'=>'invalid'], 404);  
        }

        $result["user_id"] = $user_id;

        $users_files = UserFiles::where([['user_id', $user_id], ['deleted_at', '=', null]])->get();

        $result['files'] = $users_files;

        return response()->json($result, 200);  
    }


    #3. Retorno todos los archivos activos de cada usuario.
    public function allusersandfiles() {

        $output = [];
        $files = array();
        $result = ['user_id', 'files'];

        foreach(Users::all() as $user) {       
            
            $result["user_id"] = $user->id;

            $files = UserFiles::where([['user_id', $user->id], ['deleted_at', '=', null]])->get();
            
            $result["files"] = $files;

            array_push($output, $result);
            
        }

        return response()->json($output, 200);  
    }

}
