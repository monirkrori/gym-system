<?php

namespace App\Services;

use App\Models\MembershipPlan;
use App\Models\Trainer;
use App\Models\User;
use App\Models\UserMembership;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserMembershipService{

    public function getMembershipStatistics(){
        return[
          'total' => UserMembership::count(),
          'active' => UserMembership::where('status','active')->count(),
          'expired' => UserMembership::where('status','expired')->count(),
        ];
    }

    public function getMembershipsPaginated($perPage = 10)
    {
        return UserMembership::with(['user', 'package', 'plan'])->paginate($perPage);
    }

    public function createMembership(array $data){
        $user = User::findOrFail($data['user_id']);
        $plan = MembershipPlan::findOrFail($data['plan_id']);
        $endDate =  Carbon::parse($data['start_date'])->addMonths($plan->duration_month);



        $trainer = Trainer::where('user_id', $user->id)->first();
        $existingMembership = UserMembership::where('user_id', $user->id)->first();

        DB::transaction(function () use ($data, $trainer, $existingMembership,$user ){
            if($trainer){
                $trainer->delete();
                $user->removeRole('trainer');
                $user->assignRole('member');
            } elseif ($existingMembership) {
                throw new \Exception('العضو موجود مسبقاً في النادي');
                }
          $user =  UserMembership::create($user);
        });
        return $user;
    }

    public function updateMembership(UserMembership $membership, array $data)
    {
        $membership->update($data);
        return $membership;
    }

    public function deleteMembership(UserMembership $membership)
    {
        $membership->delete();
    }

}
