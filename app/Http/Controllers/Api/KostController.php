<?php

namespace App\Http\Controllers\Api;

use App\Models\Kost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KostController extends Controller
{
    public function checkAvailability($id)
    {
        $user = Auth::user();
        $msg = 'your credit is not affected due to room not available or owner cannot be reach';
        if ($user->credit < 5) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'insuficient credit',
                ],
                403
            );
        }
        if ($kost = Kost::find($id)) {
            if ($kost->owner
                and $kost->available_room_count
            ) {
                $user->credit -= 5;
                $user->update();
                $msg = 'your credit is deducted by 5';
            }
            return response()->json(
                [
                    'success' => true,
                    'data' => $kost->serializeWithAvailability(),
                    'message' => $msg,
                ],
                200
            );
        }
        return response()->json(
            [
                'success' => false,
                'message' => 'id ' . $id . ' not found',
            ],
            400
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $offset = 0;
        $limit = 10;
        $s = '';
        $op = 'like';
        $sort = 'price';
        $order = 'asc';
        $qs = $request->query->all();
        if (in_array($qs['sort'], config('customvar.allowed_filter_kost'))
            and array_key_exists('sort', $qs)
        ) {
            $sort = $qs['sort'];
        }

        if (array_key_exists('order', $qs)
            and in_array($qs['order'], ['asc', 'desc'])
        ) {
            $order = $qs['order'];
        }

        if (!array_key_exists('offset', $qs)
            or !$qs['offset']
        ) {
            $qs['offset'] = $offset;
        }

        if (!array_key_exists('limit', $qs)
            or !$qs['limit']
        ) {
            $qs['limit'] = $limit;
        }

        if (!array_key_exists('s', $qs)
            or !$qs['s']
        ) {
            $qs['s'] = $s;
        }

        if (!array_key_exists('filter', $qs)
            or !$qs['filter']
        ) {
            $qs['filter'] = null;
        }

        if (in_array($qs['filter'], config('customvar.allowed_filter_kost'))) {
            if ($qs['filter'] == 'price') {
                if (array_key_exists('op', $qs)
                    and in_array($qs['op'], config('customvar.allowed_operator_kost'))
                ) {
                    $op = $qs['op'];
                } else {
                    return response()->json(
                        [
                            'success' => false,
                            'allowed_operator' => config('customvar.allowed_operator_kost'),
                        ],
                        200
                    );
                }
            } else {
                $qs['s'] = '%' . $qs['s'] . '%';
            }
            $kosts = Kost::where($qs['filter'], $op, $qs['s'])
                ->offset($qs['offset'])
                ->limit($qs['limit'])
                ->orderBy($sort, $order)
                ->get();
            $total = Kost::where($qs['filter'], $op, $qs['s'])->get()->count();
        } else {
            $kosts = Kost::offset($qs['offset'])
                ->limit($qs['limit'])
                ->orderBy($sort, $order)
                ->get();
            $total = Kost::get()->count();
        }

        return response()->json(
            [
                'success' => true,
                'data' => $kosts,
                'total' => $total,
            ],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'name' => 'nullable|regex:/^[\w- ]*$/',
            'city' => 'nullable|regex:/^[\w- ]*$/',
            'price' => 'nullable|regex:/[0-9]/|min:0',
            'available_room_count' => 'nullable|regex:/[0-9]/|min:0',
            'total_room_count' => 'nullable|regex:/[0-9]/|min:1',
        ];

        if (Auth::user()->role == config('constants.roles.kostowner')) {
            $request->user_id = Auth::user()->id;
        }

        Validator::make($request->all(), $rules)->validate();
        if (!User::where('id', $request->user_id)->where('role', config('constants.roles.kostowner'))->get()->first()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'user_id ' . $request->user_id . ' not found',
                ],
                400
            );
        }
        $kost = new Kost();
        $kost->fill($request->only($kost->getFillable()));
        $kost->save();

        return response()->json(
            [
                'success' => true,
                'data' => $kost,
            ],
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kost  $kost
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if ($kost = Kost::find($id)) {
            return response()->json(
                [
                    'success' => true,
                    'data' => $kost,
                ],
                201
            );
        }
        return response()->json(
            [
                'success' => false,
                'message' => 'id ' . $id . ' not found',
            ],
            400
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kost  $kost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($kost = Kost::find($id)) {
            $user = Auth::user();
            if ($user->role == config('constants.roles.kostowner')
                and $user->id != $kost->user_id
            ) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'you are not owner or admin',
                    ],
                    403
                );
            }
            $rules = [
                'user_id' => 'required',
                'name' => 'nullable|regex:/^[\w- ]*$/',
                'city' => 'nullable|regex:/^[\w- ]*$/',
                'price' => 'nullable|regex:/[0-9]/|min:0',
                'available_room_count' => 'nullable|regex:/[0-9]/|min:0',
                'total_room_count' => 'nullable|regex:/[0-9]/|min:1',
            ];

            if (Auth::user()->role == config('constants.roles.kostowner')) {
                $request->user_id = Auth::user()->id;
            }

            Validator::make($request->all(), $rules)->validate();
            if (!User::where('id', $request->user_id)
                    ->where('role', config('constants.roles.kostowner'))
                    ->get()
                    ->first()
            ) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'user_id ' . $request->user_id . ' not found',
                    ],
                    400
                );
            }

            $kost->update($request->only($kost->getFillable()));
            return response()->json(
                [
                    'success' => true,
                    'data' => $kost,
                ],
                201
            );
        }
        return response()->json(
            [
                'success' => false,
                'message' => 'id ' . $id . ' not found',
            ],
            400
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kost  $kost
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($kost = Kost::find($id)) {
            $kost->delete();
            return response()->json([
                'success' => true,
                "message" => "delete data success",
                "data" => $kost,
            ], 200);
        }
        return response()->json(
            [
                'success' => false,
                'message' => 'id ' . $id . ' not found',
            ],
            201
        );
    }
}
