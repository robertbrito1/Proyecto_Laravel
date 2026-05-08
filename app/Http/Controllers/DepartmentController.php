<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de departamentos académicos.
 * Permite listar, crear, editar y eliminar departamentos.
 */
class DepartmentController extends Controller
{
    /**
     * Muestra el listado de departamentos con sus coordinadores y estadísticas.
     */
    public function index()
    {
        // Obtenemos los departamentos con el conteo de usuarios por rol
        // Para simplificar, asumiremos que el coordinador es el primer usuario con rol 'coordinadorFFE' en ese departamento
        $departments = Department::with(['users' => function($query) {
            $query->where('is_active', true);
        }])->get()->map(function($dept) {
            $coordinador = $dept->users->where('role', 'coordinadorFFE')->first();
            $tutoresCount = $dept->users->where('role', 'tutor')->count();
            
            return (object) [
                'id' => $dept->id,
                'name' => $dept->name,
                'coordinador_name' => $coordinador ? $coordinador->name : 'Sin asignar',
                'tutores_count' => $tutoresCount,
                'is_active' => $dept->is_active,
                'code' => $dept->code,
            ];
        });

        $stats = [
            'activos' => Department::where('is_active', true)->count(),
            'tutores' => User::where('role', 'tutor')->where('is_active', true)->count(),
            'convenios' => \App\Models\Agreement::count(), // O el filtro que corresponda
        ];

        return view('coordinacion.departamentos', compact('departments', 'stats'));
    }

    /**
     * Almacena un nuevo departamento.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'code' => 'nullable|string|max:50|unique:departments,code',
        ]);

        Department::create($data);

        return redirect()->route('coordinacion.departamentos')
            ->with('success', 'Departamento creado correctamente.');
    }

    /**
     * Actualiza un departamento existente.
     */
    public function update(Request $request, Department $department)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'code' => 'nullable|string|max:50|unique:departments,code,' . $department->id,
            'is_active' => 'boolean',
        ]);

        $department->update($data);

        return redirect()->route('coordinacion.departamentos')
            ->with('success', 'Departamento actualizado correctamente.');
    }

    /**
     * Elimina (o desactiva) un departamento.
     */
    public function destroy(Department $department)
    {
        // En lugar de eliminar, podríamos simplemente desactivar si tiene datos vinculados
        if ($department->users()->exists() || $department->agreements()->exists()) {
            $department->update(['is_active' => false]);
            return redirect()->route('coordinacion.departamentos')
                ->with('success', 'Departamento desactivado (contiene datos vinculados).');
        }

        $department->delete();

        return redirect()->route('coordinacion.departamentos')
            ->with('success', 'Departamento eliminado correctamente.');
    }
}
