<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class UserResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $this->micro = [
            'id' => $this->id,
            'name' => $this->name,
            'name_en' => $this->getTranslation('name', 'en'),
            'name_ar' => $this->getTranslation('name', 'ar'),
        ];

        $this->mini = [
            'email' => $this->email,
            'phone' => $this->phone,
        ];

        $this->full = [
            $this->mergeWhen(isset($this->api_token), [
                'token' => $this->api_token,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];

        $this->relations = [
            'roles' => $this->relationLoaded('roles') && $this->roles ?
                strtolower(implode(' ,', $this->getRoleNames()->toArray())) : '',
            'roles_ids' => $this->relationLoaded('roles') && $this->roles ? $this->getRoleIds() : [],
            'role_id' => $this->relationLoaded('roles')? $this->getLastRoleId() : null,
            'permissions' => $this->relationLoaded('permissions') && $this->permissions ?
                PermissionResource::collection($this->getAllPermissions()) : null,
            'fcm_tokens' => $this->relationLoaded('tokens') ? $this->getDeviceTokens() : null,

            'task_notifications' => $this->relationLoaded('notifications') ?
            NotificationResource::collection($this->notifications()->ofIsTask(true)->latest()->limit(5)->get()) : null,

            'notifications' => $this->relationLoaded('notifications') ?
                NotificationResource::collection($this->notifications()->ofIsTask(false)->latest()->limit(5)->get()) : null,
        ];

        return $this->getResource();
    }

    public function getRoleIds()
    {
        return $this->whenLoaded('roles', function () {
            return $this->roles->where('name', '!=', 'employee')->sortByDesc('id')->pluck('id')->toArray();
        });
    }

    public function getLastRoleId()
    {
        return $this->whenLoaded('roles', function () {
            return $this->roles->where('name', '!=', 'employee')->sortByDesc('id')->pluck('id')->first();
        });
    }
}
