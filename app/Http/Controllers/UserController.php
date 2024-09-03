<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;


class UserController extends Controller
{
    private function ipRange($cidr)
    {
        $range = array();
        $cidr = explode('/', $cidr);
        $range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int) $cidr[1]))));
        $range[1] = long2ip((ip2long($range[0])) + pow(2, (32 - (int) $cidr[1])) - 1);
        return $range;
    }

    public function data(Request $request)
    {

        if ($request->user_ids) {
            User::whereIn('id', explode(',', $request->user_ids))->update(['blocked' => true]);
        }

        $searchedIP = $request->ip;
        $subnetMask = $request->mask;
        $users = User::where('created_at', '>', $request->date_from ??= (new \DateTime('today midnight'))->format('Y-m-d H:i:s'))
            ->where('created_at', '<', $request->date_to ??= (new \DateTime(''))->format('Y-m-d H:i:s'))
            ->where('email', 'like', '%@' . $request->domain ??= '%');

        if ($searchedIP) {
            $users->whereHas('preferences', function ($query) use ($searchedIP, $subnetMask) {
                if ($subnetMask) {
                    $query->whereBetween('ip', $this->ipRange($searchedIP . $subnetMask));
                } else {
                    $query->where('ip', 'LIKE', '%' . $searchedIP . '%');
                }
            });
        }
        if ($request->hasBlocked == 'on') {
            $users->where('blocked', '=', true);
        } else {
            $users->where('blocked', '=', false);
        }

        return view(
            'dashboard',
            [
                'users' => $users->orderBy('created_at')->paginate($request->count ??= 50)
            ]
        );
    }
}
