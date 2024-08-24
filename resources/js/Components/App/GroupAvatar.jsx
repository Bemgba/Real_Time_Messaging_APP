// import react from "@heroicons/react";
// import { UsersIcon } from "@heroicons/react/solid";
import { UserIcon } from "@heroicons/react/24/solid";


const GroupAvatar = ({}) =>{
    return(
    <>
    <div className={`avatar placeholder`}>
        <div className={`bg-gray-400 text-gray-800 rounded-full`}>
            <span className="text-x1">
                <UserIcon  className="w-4"/>
            </span>
        </div>
    </div>
    </>
);
};
export default GroupAvatar;