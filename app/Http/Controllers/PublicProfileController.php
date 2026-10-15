<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $query = User::query()->latest();

        if ($user) {
            $query->where('id', '!=', $user->id)
                ->whereNotIn(
                    'id',
                    $user->following()->select('users.id')
                );
        }

        $users = $query->simplePaginate(12);

        return view('profile.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $posts = $user->posts()->latest()->paginate(6);

        // dump($user->followers);
        return view('profile.show', ['user' => $user, 'posts' => $posts]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    

    public function followers(Request $request, User $user)
    {
        return $this->followListResponse(
            $request,
            $user->followers()
        );
    }

    public function following(Request $request, User $user)
    {
        return $this->followListResponse(
            $request,
            $user->following()
        );
    }

    private function followListResponse(Request $request, $query)
    {
        $people = $query
            ->with('media')
            ->orderByDesc('followers.created_at')
            ->paginate(10);

        return response()->json([
            'data' => $people->getCollection()->map(function (User $person) use ($request) {
                return [
                    'id' => $person->id,
                    'name' => $person->name,
                    'username' => $person->username,
                    'avatar' => $person->imageUrl(),
                    'profile_url' => route('profile.show', $person),

                    // Does the logged-in user follow this person?
                    'is_following' => $request->user()
                        ? $person->isFollowedBy($request->user())
                        : false,

                    // Existing route used for both Follow and Unfollow
                    'follow_url' => route('follow', $person),
                ];
            })->values(),

            'next_page_url' => $people->nextPageUrl(),
            'total' => $people->total(),
        ]);
    }
}
