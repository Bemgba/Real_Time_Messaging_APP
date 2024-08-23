import { usePage } from "@inertiajs/react";
import { useEffect, useState } from 'react';

// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

const ChatLayout=({children})=>{
    const page = usePage();
    const conversations= page.props.conversations;
    const selectedConversation= page.props.selectedConversation;
    const [onlineUsers, setOnlineUsers]=useState({});
    const [localConversations, setLocalConversations]=useState([]);
    const [sortedConversations, setSortedConversations]=useState([]);
    console.log("conversations", conversations)
    console.log("selectedConversation", selectedConversation);

   //  useEffect(()=>{ 
   //    setSortedConversations.sort(
   //      localConversations.sort( (a,b)=>{
   //       if(a.blocked_at && b.blocked_at){
   //          return a.blocked_at > b.blocked_at ? 1: -1;
   //       }else if (b.blocked_at){
   //          return -1;
   //       }
   //       if (a.last_message_date && b.last_message_date){
   //          return b.last_message_date.localeCompare(
   //             a.last_message_date
   //          );
   //       }else if(a.last_message_date){
   //          return -1;
   //       }else if(b.last_message_date){
   //          return 1;
   //       }else {
   //          return 0;
   //       }
   //    })
   // );
   // },[localConversations]);
   useEffect(()=>{ 
      // Sort localConversations array
      const sorted = localConversations.sort((a, b) => {
        if (a.blocked_at && b.blocked_at) {
          return a.blocked_at > b.blocked_at ? 1 : -1;
        } else if (b.blocked_at) {
          return -1;
        }
        if (a.last_message_date && b.last_message_date) {
          return b.last_message_date.localeCompare(a.last_message_date);
        } else if (a.last_message_date) {
          return -1;
        } else if (b.last_message_date) {
          return 1;
        } else {
          return 0;
        }
      });
      // Update the sorted state
      setSortedConversations(sorted);
    }, [localConversations]);

    
   useEffect(()=>{
      setLocalConversations(conversations);
   },[conversations]);

    useEffect(()=>{
        console.log("Listen to a specific channel")
        Echo.join('online')

        .here((users)=>{
         // console.log('here',users);
         const onlineUsersObj=Object.fromEntries(
            users.map((user)=>[user.id, user])
         );
         //all already online users and those just joining
         setOnlineUsers((prevOnlineUsers)=>{
            return{...prevOnlineUsers, ...onlineUsersObj};
         });
      })

      .joining((user)=>{
         // console.log('joining',user);
         setOnlineUsers((prevOnlineUsers)=>{
            const updatedUsers= {...prevOnlineUsers};
            updatedUsers[user.id]=user;
            return updatedUsers;
         })
      })

      .leaving((user)=>{
         // console.log('leaving',user);
         setOnlineUsers((prevOnlineUsers)=>{
            const updatedUsers= {...prevOnlineUsers};
           delete updatedUsers[user.id];
            return updatedUsers;
         })
      })

      .error((error)=>{
         console.log('error',error);
      });

      return()=>{
        Echo.leave("online")
      }


    },[])

 return (
 <>
    {/* ChatLayout
    <div>{chil dren}</div> */}
 </>
 
);
};
export default ChatLayout;