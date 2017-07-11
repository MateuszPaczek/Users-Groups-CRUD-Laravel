<?php

namespace App\Http\Controllers;

use App\Groups;
use App\GroupsHasUsers;
use App\User;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\Null_;

class UsersController extends Controller
{

    public function manage(){

        return view('manageUsers');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('groups_has_users')
            ->rightJoin('groups', 'groups_has_users.groups_id', "=", 'groups.id')
            ->rightJoin('users', 'groups_has_users.users_id', '=', 'users.id')
            ->select('users.id', 'users.userName','users.password', 'users.firstName', 'users.lastName', 'users.dateOfBirth')
            ->selectRaw('GROUP_CONCAT(groups.groupName) as groupName')
            ->groupBy('users.id', 'users.userName', 'users.password', 'users.firstName', 'users.lastName', 'users.dateOfBirth')
            ->orderBy('users.id', 'ASC')
            ->paginate(10);

        $response = [
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem()
            ],
            'data' => $users
        ];

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'userName' => 'required',
            'password' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'dateOfBirth' => 'required',
        ]);

        $user = new Users();
        $user->userName = $request->get('userName');
        $user->password = $request->get('password');
        $user->firstName = $request->get('firstName');
        $user->lastName = $request->get('lastName');
        $user->dateOfBirth = $request->get('dateOfBirth');
        $user->save();

        if($request->get('groupName'))
            for($i=1;$i<Groups::all()->count()+1;$i++){
                if (strpos($request->get('groupName'), Groups::find($i)->groupName) !== false) {
                    $user->groups()->attach($i);
                }
            }

        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'userName' => 'required',
            'password' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'dateOfBirth' => 'required',
        ]);

        $edit = Users::all()->find($id);
        $edit->update($request->all());

        $groups = Groups::all();
        foreach ($groups as $group) {
            $edit->groups()->detach($group->id);
            if (strpos($request->get('groupName'), Groups::find($group->id)->groupName) !== false) {
                $edit->groups()->attach($group->id);
            }
        }

        return response()->json($edit);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $groups = GroupsHasUsers::all();
            foreach ($groups as $group)
                if($group->users_id == $id)
                    $group->delete();

        User::find($id)->delete();
        return response()->json(['done']);
    }
}
