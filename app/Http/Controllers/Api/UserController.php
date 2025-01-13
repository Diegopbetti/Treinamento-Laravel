<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    const ITENS_PER_PAGE = 6;
    
    public function index(){
        $page = $_GET['page'];
        $skip = ($page - 1) * UserController::ITENS_PER_PAGE;
        $total_pages = ceil(User::count() / UserController::ITENS_PER_PAGE);

        $users = User::get()->skip($skip)->take(UserController::ITENS_PER_PAGE);

        return response()->json([
            'users' => $users,
            'total_pages' => $total_pages,
            'status' => 200
        ]);
    }
    public function show(string $id){
        try{
            $user = User::findOrFail($id);

            return response()->json([
                'user' => $user,
                'status' => 200,
            ]);
        }catch(Exception $e){
            return response()->json([
                'error' => $e->getMessage(),
                'status' => 202
            ]);
        }
    }
/**
 * @OA\Post(
 *     path="/api/users",
 *     summary="Cria um novo usuário",
 *     description="Cria um novo usuário no sistema e retorna os detalhes do usuário",
 *     operationId="createUser",
 *     tags={"Usuários"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password"},
 *             @OA\Property(property="name", type="string", example="João Silva"),
 *             @OA\Property(property="email", type="string", example="joao.silva@example.com"),
 *             @OA\Property(property="password", type="string", example="senha123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Usuário criado com sucesso",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", format="int64", example=1),
 *             @OA\Property(property="name", type="string", example="João Silva"),
 *             @OA\Property(property="email", type="string", example="joao.silva@example.com"),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-13T12:00:00"),
 *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-13T12:00:00")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Dados inválidos"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro interno do servidor"
 *     )
 * )
 */

    public function store(Request $request){
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        User::create($data);

        return response()->json([
            'message' => 'Usuário Criado com sucesso',
            'status' => 200
        ]);
    }
    public function update(Request $request, string $id){
        try{
            $user = User::findOrFail($id);

            $data = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string' 
            ]);

            $user->update($data);

            return response()->json([
                'message' => "Usuário editado com sucesso",
                'user' => new UserResource($user),
                'status' => 200,
            ]);
        }catch(Exception $e){
            return response()->json([
                'error' => $e->getMessage(),
                'status' => 202
            ]);
        }
    }
    public function destroy(string $id){
        try{
            $user = User::findOrFail($id);

            $user->delete();

            return response()->json([
                'message' => "usuário deletado com sucesso",
                'status' => 200,
            ]);
        }catch(Exception $e){
            return response()->json([
                'error' => $e->getMessage(),
                'status' => 202
            ]);
        }
    }
}
