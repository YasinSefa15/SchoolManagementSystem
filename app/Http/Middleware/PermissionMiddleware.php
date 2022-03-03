<?php

namespace App\Http\Middleware;

use App\Models\Lecture;
use App\Models\LectureToExam;
use App\Models\Permission;
use App\Models\StudentToSupervisior;
use App\Models\TypeToPermission;
use App\Models\UserType;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->get('user')['type'] == 'admin'){
            return $next($request);
        }
        $userPermission = DB::table('type_to_permissions')
            ->where('type_id','=',$request->get('user')['type_id'])
            ->join('module_to_routes','module_to_routes.id','=','type_to_permissions.module_to_route_id')
            ->where('module_to_routes.route_name','=',$request->route()->getName())
            ->select('type_to_permissions.type')
            ->first();

        if ($userPermission){
            if ($userPermission->type == 'general'){
                return $next($request);
            }


            if($request->get('user')['type'] == 'lecturer'){
                if ($request->route()->user_id){
                    $rel = StudentToSupervisior::where('lecturer_id',$request->get('user')['id'])
                        ->where('student_id',$request->route()->id)
                        ->first();

                }elseif (($request->route()->lecture_id)){
                    $rel = Lecture::where('lecturer_id',$request->get('user')['id'])
                        ->where('id',$request->route()->lecture_id)
                        ->first();
                }elseif(($request->route()->exam_id)){
                    $rel = LectureToExam::where('exam_id',$request->route()->exam_id)
                        ->where('id',$request->route()->lecture_id)
                        ->first();
                }
                if ($rel)
                    return $next($request);
            }
            if (($request->route()->id)){
                if ($request->route()->id == $request->get('user')['id']){
                    return $next($request);
                }
            }
        }
        return response()->json([
            "code" => Response::HTTP_FORBIDDEN,
            "result" => "Yetkisiz işlem",
        ],Response::HTTP_FORBIDDEN);
    }
}
