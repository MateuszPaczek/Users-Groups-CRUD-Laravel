<?php

namespace App\Http\Controllers;

use App\Groups;
use App\GroupsHasUsers;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupsController extends Controller
{

    public function manage()
    {

        return view('manageGroups');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = DB::table('groups')
            ->leftJoin('groups_has_users', 'groups_has_users.groups_id', "=", 'groups.id')
            ->leftJoin('users', 'groups_has_users.users_id', '=', 'users.id')
            ->select('groups.id', 'groups.groupName')
            ->selectRaw('GROUP_CONCAT(users.userName) as usersNames')
            ->selectRaw('GROUP_CONCAT(users.id) as usersId')
            ->groupBy('groups.id', 'groups.groupName')
            ->orderBy('groups.id', 'ASC')
            ->paginate(10);

        $response = [
            'pagination' => [
                'total' => $groups->total(),
                'per_page' => $groups->perPage(),
                'current_page' => $groups->currentPage(),
                'last_page' => $groups->lastPage(),
                'from' => $groups->firstItem(),
                'to' => $groups->lastItem()
            ],
            'data' => $groups
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'groupName' => 'required|unique:groups'
        ]);

        $group = new Groups();
        $group->groupName = $request->get('groupName');
        $group->save();

        if ($request->get('usersNames') != NULL) {
            $usersIds = explode(',', $request->get('usersNames'));
            foreach ($usersIds as $userId) {
                $user = Users::find($userId);
                $user->groups()->attach($group->id);
            }
        }
        return response()->json($group);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'groupName' => 'required|unique:groups,groupName,' . $id
        ]);

        $edit = Groups::find($id)->update($request->all());

        $users = Users::all();
        foreach ($users as $user) {
            $user->groups()->detach($id);

        }
        if ($request->get('usersNames') != NULL) {
            $usersId = explode(',', $request->get('usersNames'));
            foreach ($usersId as $userId) {
                $user = Users::find($userId);
                $user->groups()->attach($id);
            }
        }
        return response()->json($edit);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $groups = GroupsHasUsers::all();
        foreach ($groups as $group)
            if ($group->groups_id == $id)
                $group->delete();

        Groups::find($id)->delete();
        return response()->json(['done']);
    }

    public function groupsList()
    {

        $groups = Groups::all();
        return response()->json($groups);
    }
}
