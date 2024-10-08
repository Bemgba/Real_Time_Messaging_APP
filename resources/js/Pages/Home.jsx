import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import ChatLayout from '@/Layouts/chatLayout';
// import { Head } from '@inertiajs/react';

export default function Home({ auth }) {
    return (
        <>
            Messages
        </>
    );
}

Home.layout=(page)=>{
    return(
        <AuthenticatedLayout  user={page.props.auth.user}>
           
           <ChatLayout children={page} /> 
        </AuthenticatedLayout>
    )
}
