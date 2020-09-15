<?php

namespace App\Http\Controllers;

use App\Candidate;
use App\User;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Redis;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     * @return JsonResponse
     */
    public function index()
    {
        //
        $user = auth()->user();
        if($user->hasRole("manager")){
            $candidates=Candidate::all();
        }
        else{
            $candidates=Candidate::where(["owner"=>$user->id])->get();
        }
        return response()->json([
            'meta'=>[
                'success'=> true,
                'errors'=> []
            ],
            'data'=>$candidates
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        //
        $this->validate($request,[
            'name' => 'required',
            'source' => 'required',
        ]);
        $user = Auth::user();
        $candidate = Candidate::create($request->only(['title','source']));
        $user->createdBy()->save($candidate);
        $owner=$this->validateOwner($request->owner);
        if(isset($owner))
        {
            $owner->candidates()->save($candidate);
        }
        else
        {
            return response()->json([
                'meta'=>[
                    'success'=>false,
                    'errors'=> [
                        'owner'=>'owner invalid'
                    ]
                ]
            ]);
        }

        return response()->json([
            'meta'=>[
                'success'=> true,
                'errors'=> []
            ],
            'data'=>$candidate
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
        $user = auth()->user();
        if($user->hasRole("manager")){
            $candidate=Candidate::where(["id"=>$id])->first();
        }
        else{
            $candidate=Candidate::where(["owner"=>$user,"id"=>$id])->first();
        }
        return response()->json([
            'meta'=>[
                'success'=> true,
                'errors'=> []
            ],
            'data'=>$candidate
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function validateOwner($owner)
    {
        if(is_int($owner))
        {
            $user=User::where($owner)->first();
            if(isset($user))
            {
               return $user;
            }
        }
        if(is_numeric($owner))
        {
            $user=User::where(['id'=>$owner])->first();
            if(isset($user))
            {
                return $user;
            }
        }
        if(isset($owner['id']))
        {
            $user=User::where(['id'=>$owner['id']])->first();
            if(isset($user))
            {
                return $user;
            }
        }
        if(isset($owner['username']))
        {
            $user=User::where(['username'=>$owner['username']])->first();
            if(isset($user))
            {
                return $user;
            }
        }
        return [];
    }
}
