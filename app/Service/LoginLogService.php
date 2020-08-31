<?php

namespace App\Service;

use App\Models\Member;
use App\Models\MemberLoginLog;
use App\Models\UserLoginLog;

class LoginLogService
{
    protected $limit_user_ids;
    protected $limit_agent_ids;

    public function __construct()
    {
        $user = auth()->user();
        $this->limit_user_ids = $user->isAgent() ? $user->agent->children()->map(function ($row) {
            return $row->user_id;
        }) : [];
        $this->limit_agent_ids = $user->isAgent() ? $user->agent->children()->map(function ($row) {
            return $row->id;
        }) : [];
    }

    public function userLoginLog(int $user_id)
    {
        $data = request()->all();
        $query = UserLoginLog::where('user_id', $user_id);
        $per_page = intval($data['per_page'] ?? 15);
        $res = $query->paginate($per_page);

        return [
            'content' => $res->map(function ($row) use ($user_id) {
                return [
                    'id' => $row->id,
                    'ip' => $row->ip,
                    'device' => $row->device,
                    'browser' => $row->browser,
                    'login_at' => $row->created_at,
                    'ip_exists' => $row->sameIpExists($row->ip, $user_id),
                ];
            }),
            'total' => $res->total(),
            'page' => $res->currentPage(),
            'per_page' => $res->perPage(),
            'last_page' => $res->lastPage(),
        ];
    }

    public function sameIpUserLogin($ip, $user_id = null)
    {
        $limit_user_ids = $this->limit_user_ids;
        $query = UserLoginLog::whereIn('id', function ($q) use ($user_id, $ip, $limit_user_ids) {
            $q->selectRaw('MAX(id)')
                ->from('user_login_log')
                ->groupBy('user_id')
                ->when($user_id, function ($q) use ($user_id) {
                    return $q->where('user_id', '!=', $user_id);
                })
                ->where('ip', $ip)
                ->when($limit_user_ids, function ($q) use ($limit_user_ids) {
                    return $q->whereIn('user_id', $limit_user_ids);
                });
        });

        $res = $query->get();

        return $res->map(function ($row) {
            return [
                'id' => $row->id,
                'user_id' => $row->user_id,
                'username' => $row->user->username,
                'device' => $row->device,
                'browser' => $row->browser,
                'ip' => $row->ip,
                'login_at' => $row->created_at,
            ];
        });
    }

    public function sameIpMemberLogin($ip, $member_id = null)
    {
        $limit_agent_ids = $this->limit_agent_ids;
        $query = MemberLoginLog::whereIn('id', function ($q) use ($ip, $member_id, $limit_agent_ids) {
            $q->selectRaw('MAX(id)')
                ->from('member_login_log')
                ->where('ip', $ip)
                ->when($member_id, function ($q) use ($member_id) {
                    return $q->where('member_id', '!=', $member_id);
                })->whereIn('member_id', function ($q) use ($limit_agent_ids) {
                    $q->select('id')
                        ->from('member')
                        ->when($limit_agent_ids, function ($q) use ($limit_agent_ids) {
                            return $q->whereIn('agent_id', $limit_agent_ids);
                        });
                })->groupBy('member_id');
        });
        
        $res = $query->get();

        return $res->map(function ($row) {
            return [
                'id' => $row->id,
                'member_id' => $row->member_id,
                'username' => $row->member->username,
                'device' => $row->device,
                'browser' => $row->browser,
                'ip' => $row->ip,
                'login_at' => $row->created_at,
            ];
        });
    }

    public function sameIpMemberRegister($ip, $member_id = null)
    {
        $query = Member::where('register_ip', $ip)
            ->whereIn('agent_id', $this->limit_agent_ids);

        if ($member_id) {
            $query->where('id', '!=', $member_id);
        }

        $res = $query->get();

        return $res->map(function ($row) {
            return [
                'id' => $row->id,
                'username' => $row->username,
                'register_at' => $row->created_at,
            ];
        });
    }

    public function sameIpSameMember($ip, $member_id)
    {
        $res = MemberLoginLog::where('ip', $ip)
            ->where('member_id', $member_id)
            ->get();

        return $res->map(function ($row) {
            return [
                'id' => $row->id,
                'username' => $row->member->username,
                'device' => $row->device,
                'browser' => $row->browser,
                'login_at' => $row->created_at,
            ];
        });
    }

    public function sameIpSameUser($ip, $user_id)
    {
        $res = UserLoginLog::where('ip', $ip)
            ->where('user_id', $user_id)
            ->get();

        return $res->map(function ($row) {
            return [
                'id' => $row->id,
                'username' => $row->user->username,
                'device' => $row->device,
                'browser' => $row->browser,
                'login_at' => $row->created_at,
            ];
        });
    }
}
