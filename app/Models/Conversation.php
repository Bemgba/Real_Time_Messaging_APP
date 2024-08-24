<?php

namespace App\Models;
use App\Models\User;
// use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id1',
        'user_id2',
        'last_message_id',

    ];

    public function lastMessage(){

        return $this->belongsTo(Message::class,'last_message_id');
    }
 public function user1(){
        return $this->belongsTo(User::class,'user_id1');
    }
    public function user2(){
        return $this->belongsTo(User::class,'user_id2');
    } 

    public static function getConversationsForSiderbar(User $user){
        // $users=User::getUsersExceptUser($user);
           $users=User::getUsersExceptUser($user);
        
        $groups=Group::getGroupsForUser($user);
        return $users->map(function(User $user){
            return $user->toConversationArray();
        })->concat($groups->map(function(Group $group){
            return $group->toConversationArray();

        }));

    }
    // public static function getConversationsForSidebar(User $exceptUser)
    // {
    //     // Create an instance of User or use the passed instance if possible
    //     // Assuming $exceptUser can be used to call instance methods
    //     // or ensure to call getUsersExceptUser on an appropriate instance.
    
    //     // Retrieve users except the specified user
    //     $users = $exceptUser->getUsersExceptUser($exceptUser);
        
    //     // Retrieve groups for the specified user (assuming this is a static method)
    //     $groups = Group::getGroupsForUser($exceptUser);
    
    //     // Map users to conversation arrays
    //     $userConversations = $users->map(function(User $user) {
    //         return $user->toConversationArray();
    //     });
    
    //     // Map groups to conversation arrays
    //     $groupConversations = $groups->map(function(Group $group) {
    //         return $group->toConversationArray();
    //     });
    
    //     // Concatenate user and group conversations
    //     return $userConversations->concat($groupConversations);
    // }

}
