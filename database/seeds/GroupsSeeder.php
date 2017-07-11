<?php

use Illuminate\Database\Seeder;
use App\Groups;
class GroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group = new Groups();
        $group->groupName = 'Admin';
        $group->save();

        $group = new Groups();
        $group->groupName = 'Moderator';
        $group->save();

        $group = new Groups();
        $group->groupName = 'User';
        $group->save();

    }
}
