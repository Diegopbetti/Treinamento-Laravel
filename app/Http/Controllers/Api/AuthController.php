<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Info(
 *  title="Treinamento API",
 *  version="1.0",
 *  contact={
 *      "email":"projetos@codejr.com.br"
 *  }
 * )
 * @OA\SecurityScheme(
 *  type="http",
 *  description="Acess token obtido na autenticação",
 *  name="Authorization",
 *  in="header",
 *  scheme="bearer",
 *  bearerFormat="JWT",
 *  securityScheme="bearerToken"
 * )
 */


class AuthController extends Controller
{
   /**
 * @OA\Get(
 *     path="/api/users",
 *     summary="Lista todos os usuários",
 *     description="Retorna uma lista de usuários registrados no sistema",
 *     operationId="getUsers",
 *     tags={"Usuários"},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de usuários retornada com sucesso",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", format="int64", example=1),
 *                 @OA\Property(property="name", type="string", example="João Silva"),
 *                 @OA\Property(property="email", type="string", example="joao.silva@example.com"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-13T12:00:00"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-13T12:00:00")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Não autorizado"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro interno do servidor"
 *     )
 * )
 */

    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if(Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('token-name')->plainTextToken;
            return response()->json([
                'user' => new UserResource(Auth::user()),
                'token' => $token,
                'status' => 200
            ]);
        }
        return response()->json([
            'message' => "Email ou senha inválidos",
            'status' => 205
        ]);
    }
}
