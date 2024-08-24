const UserAvatar = ({ user, online = null, profile = false }) => {
    // Determine the size class for the avatar
    const sizeClass = profile ? "w-40 h-40" : "w-8 h-8";

    // Determine the border color based on online status
    const borderColor = online === true ? "border-green-500" : online === false ? "border-gray-500" : "";

    return (
        <div className={`relative ${sizeClass} ${borderColor} border-2 rounded-full overflow-hidden`}>
            {user.avatar_url ? (
                <img src={user.avatar_url} alt="User Avatar" className="w-full h-full object-cover" />
            ) : (
                <div className="flex items-center justify-center w-full h-full bg-gray-400 text-gray-800">
                    <span className="text-xl font-bold">{user.name.substring(0, 1)}</span>
                </div>
            )}
            {/* Online/Offline Indicator */}
            {online !== null && (
                <div
                    className={`absolute bottom-0 right-0 w-4 h-4 rounded-full ${online === true ? "bg-green-500" : "bg-gray-500"} border-2 border-white`}
                />
            )}
        </div>
    );
};

export default UserAvatar;
