<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    
    public function followUnfollow(Request $request, User $user)
    {
        $currentUser = $request->user();

        if (!$currentUser) {
            return response()->json([
                'message' => 'Please log in to follow users.',
            ], 401);
        }

        if ($currentUser->is($user)) {
            return response()->json([
                'message' => 'You cannot follow yourself.',
            ], 422);
        }

        $isFollowing = $currentUser->following()
            ->where('users.id', $user->id)
            ->exists();

        if ($isFollowing) {
            $currentUser->following()->detach($user->id);
            $isFollowing = false;
        } else {
            $currentUser->following()->syncWithoutDetaching([$user->id]);
            $isFollowing = true;
        }

        return response()->json([
            'success' => true,

            // Used by the modal
            'is_following' => $isFollowing,

            // Used by your existing profile card
            'following' => $isFollowing,
            'followerCount' => $user->followers()->count(),
            'followingCount' => $user->following()->count(),

            'message' => $isFollowing
                ? 'User followed successfully.'
                : 'User unfollowed successfully.',
        ]);
    }
}
