<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Departamento;
use App\Models\Funcionario;

Route::post('/departamentos', function(Request $request) {
    $request->validate(['nome' => 'required']);
    return Departamento::create(['nome' => $request->nome]);
});

Route::get('/departamentos', function() {
    return Departamento::all();
});

Route::get('/departamentos/{id}', function($id) {
    $departamento = Departamento::find($id);
    if (!$departamento) return response()->json(['error' => 'Departamento não encontrado'], 404);
    return $departamento;
});

Route::put('/departamentos/{id}', function(Request $request, $id) {
    $departamento = Departamento::find($id);
    if (!$departamento) return response()->json(['error' => 'Departamento não encontrado'], 404);
    $departamento->update($request->only('nome'));
    return $departamento;
});

Route::delete('/departamentos/{id}', function($id) {
    $departamento = Departamento::find($id);
    if (!$departamento) return response()->json(['error' => 'Departamento não encontrado'], 404);
    $departamento->delete();
    return response()->json(null, 204);
});

Route::get('/departamentos/{id}/funcionarios', function($id) {
    $departamento = Departamento::with('funcionarios')->find($id);
    if (!$departamento) return response()->json(['error' => 'Departamento não encontrado'], 404);
    return $departamento;
});

Route::get('/departamentos/{id}/funcionarios-list', function($id) {
    $departamento = Departamento::find($id);
    if (!$departamento) return response()->json(['error' => 'Departamento não encontrado'], 404);
    return $departamento->funcionarios;
});

Route::post('/funcionarios', function(Request $request) {
    $request->validate([
        'nome' => 'required',
        'cargo' => 'required',
        'departamento_id' => 'required|exists:departamentos,id'
    ]);
    return Funcionario::create($request->only('nome', 'cargo', 'departamento_id'));
});

Route::get('/funcionarios', function() {
    return Funcionario::all();
});

Route::get('/funcionarios/{id}', function($id) {
    $funcionario = Funcionario::find($id);
    if (!$funcionario) return response()->json(['error' => 'Funcionário não encontrado'], 404);
    return $funcionario;
});

Route::put('/funcionarios/{id}', function(Request $request, $id) {
    $funcionario = Funcionario::find($id);
    if (!$funcionario) return response()->json(['error' => 'Funcionário não encontrado'], 404);
    $request->validate([
        'departamento_id' => 'exists:departamentos,id'
    ]);
    $funcionario->update($request->only('nome', 'cargo', 'departamento_id'));
    return $funcionario;
});

Route::delete('/funcionarios/{id}', function($id) {
    $funcionario = Funcionario::find($id);
    if (!$funcionario) return response()->json(['error' => 'Funcionário não encontrado'], 404);
    $funcionario->delete();
    return response()->json(null, 204);
});

Route::get('/funcionarios-com-departamento', function() {
    return Funcionario::with('departamento')->get();
});

Route::get('/funcionarios/{id}/departamento', function($id) {
    $funcionario = Funcionario::with('departamento')->find($id);
    if (!$funcionario) return response()->json(['error' => 'Funcionário não encontrado'], 404);
    return $funcionario->departamento;
});
