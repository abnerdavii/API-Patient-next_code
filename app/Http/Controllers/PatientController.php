<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {
        $page = (int)$request->input('page', 1);
        $perPage=min(max((int)$request->input('per_page', 10), 1), 100);
        $orderBy = (string)$request->input('order_by', 'id');
        $orderDir=strtolower((string)$request->input('order_dir','desc'))==='asc'?'asc':'desc';
        $q = trim((string)$request->input('q',''));

        $allowedOrderBy=['id', 'name', 'date_of_birth', 'adress', 'phone', 'medical_history', 'created_at'];
        if(!in_array($orderBy, $allowedOrderBy, true)){
            $orderBy ='id';
        }

        $query=Patient::query()
            ->when($q !== '', function($qq) use ($q){
                $qq->where(function ($w) use($q){
                        $w->where('name','like', "%{$q}%")
                        ->orWhere('date_of_birth', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");
                    });
                })->orderBy($orderBy, $orderDir);

            $paginated=$query->paginate($perPage,['*'],'page',$page);

            return response()->json([
                'data'=> $paginated->items(),
                'total'=> $paginated->total(),
                'page'=> $paginated->currentPage(),
                'perPage'=> $paginated->perPage(),
                'totalPages'=> $paginated->lastPage(),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'date_of_birth' => ['required'],
            'adress' => ['required', 'string', 'max:100'],
            'phone' => ['sometimes', 'string', 'max:25'],
            'medical_history' => ['sometimes', 'string']
        ], [
            'name.required' => 'Campo nome é obrigatório.',
            'date_of_birth.required' => 'Campo data de nascimento é obrigatório.',
            'adress.required' => 'Campo endereço é obrigatório.'
        ]);
        $patient = Patient::create($data);
        return response()->json($patient, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id):JsonResponse
    {
        $patient = Patient::findOrFail($id);
        if(!$patient) {
            return response()->json('Paciente não encontrado.', 404);
        }
        return response()->json($patient, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): JsonResponse
    {
        $patient = Patient::findOrFail($id);
        if(!$patient) {
            return response()->json('Paciente não encontrado.', 404);
        }
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:100'],
            'date_of_birth' => ['sometimes', 'date'],
            'adress' => ['sometimes', 'string', 'max:100'],
            'phone' => ['sometimes', 'string', 'max:25'],
            'medical_history' => ['sometimes', 'string']
        ]);
        $patient->update($data);
        return response()->json($patient->fresh(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        if(!$patient) {
            return response()->json('Paciente não encontrado.', 404);
        }
        $patient->delete();
        return response()->json()->setStatusCode(204);
    }
}
