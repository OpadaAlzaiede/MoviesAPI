<?php

namespace App\Http\Traits;

use App\Models\Department;
use App\Models\Notify;

use App\Models\UnreadApplication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;



trait UnReadable
{

    public function setAsUnread($model, $request) {

        $requestStatus = $request->status ?? 1;
        $roles = $request->path()->get();

        foreach($roles as $role) {
            $userQuery = $role->users()->where('municipality_id', Auth::user()->municipality_id);
            if(!is_null($role->pivot->department_id)) $userQuery->where('department_id', $role->pivot->department_id);
            $users = $userQuery->get();

            if($requestStatus == 1) {
                if($role->pivot->is_served == 0) {
                    foreach($users as $user) {
                        $this->setRequestAsUnreadInPhase($model, $request->id, $user->id, 0);
                    }
                }else {
                    foreach($users as $user) {
                        $this->setRequestAsUnreadInPhase($model, $request->id, $user->id, 1);
                    }
                }
            }
            else if ($requestStatus == 2) {
                foreach($users as $user) {
                    $this->setRequestAsUnreadInPhase($model, $request->id, $user->id, 2);
                }
            }
            else {
                foreach($users as $user) {
                    $this->setRequestAsUnreadInPhase($model, $request->id, $user->id, 3);
                }
            }
        }
    }

    protected function setRequestAsUnreadInPhase($model, $requestId, $userId, $type) {

            $oldNotification = Notify::where('notifyable_id', $requestId)
                                            ->where('notifyable_type', $model)
                                            ->where('user_id', $userId)
                                            ->where('type', $type)
                                            ->where('is_read', false)
                                            ->get();

            $otherStateNotifications = Notify::where('notifyable_id', $requestId)
                                                        ->where('notifyable_type', $model)
                                                        ->where('user_id', $userId)
                                                        ->where('type', '!=', $type)
                                                        ->where('is_read', false)
                                                        ->get();

            foreach($otherStateNotifications as $notify)
                $notify->delete();

            if(count($oldNotification) > 0)
                return;

            $notify = new Notify();
            $notify->user_id = $userId;
            $notify->notifyable_id = $requestId;
            $notify->notifyable_type = $model;
            $notify->type = $type;
            $notify->municipality_id = Auth::user()->municipality_id;
            $notify->save();
    }

    public function setAsRead($user_id, $requestId, $type, $model) {

        $notifies = Notify::where('user_id', $user_id)
                            ->where('notifyable_id', $requestId)
                            ->where('type', $type)
                            ->where('notifyable_type', $model)
                            ->get();

        foreach($notifies as $notify)
        {
            $notify->delete();
        }
    }
}
