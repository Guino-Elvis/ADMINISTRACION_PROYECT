<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiUserProject extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id());

        if (!$user) {
            return response()->json(['error' => 'No autenticado'], Response::HTTP_UNAUTHORIZED);
        }

        $search = request()->query('search');
        $startDate = request()->query('start_date');
        $endDate = request()->query('end_date');
        $sortOrder = request()->query('sort_order', 'desc');
        $perPage = request()->query('per_page', 8);

        if (!is_numeric($perPage) || $perPage <= 0) {
            $perPage = 8;
        }

        if ($user->hasRole('Administrador')) {
            $query = User::with(['projects', 'roles']);
        } else {
            $query = User::whereHas('projects', function ($projectQuery) use ($user) {
                $projectQuery->whereIn('company_id', $user->companies->pluck('id'));
            })->with('projects', 'roles');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhereHas('projects', function ($q2) use ($search) {
                        $q2->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }
        if ($startDate) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $query->where('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $endDate = Carbon::parse($endDate)->endOfDay();
            $query->where('created_at', '<=', $endDate);
        }
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'desc';
        $users = $query->orderBy('created_at', $sortOrder)->paginate($perPage);

        return response()->json($users, Response::HTTP_OK);
    }





    public function store(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make(
                $request->all(),
                [
                    'role' => 'required',
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required',
                    //project campos
                    'project_id' => 'sometimes|nullable|exists:projects,id',
                    'status' => 'required',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            DB::beginTransaction();

            $user = User::create([
                'status' => $request->status,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $user->assignRole($request->role);
            $user->projects()->attach($request->project_id);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'user' => $user->load('projects'),
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }


            $validateData = Validator::make($request->all(), [
                'status' => 'required',
                'role' => 'required',
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|min:6',
                'project_id' => 'sometimes|nullable|exists:projects,id',
            ]);

            if ($validateData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateData->errors()
                ], 401);
            }

            DB::beginTransaction();

            $user->update([
                'status' => $request->status,
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
            ]);




            $user->syncRoles([$request->role]);
            $user->projects()->sync([$request->project_id]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Usuario actualizado correctamente',
                'user' => $user->load('projects'),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            $user->projects()->detach();
            $user->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Usuario eliminado correctamente'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Error al eliminar usuario: ' . $th->getMessage()
            ], 500);
        }
    }
}
