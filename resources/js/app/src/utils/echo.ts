import Echo from 'laravel-echo';

import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    authEndpoint: import.meta.env.VITE_API_DEFAULT_URL + 'broadcasting/auth',
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: false,
    enabledTransports: ['ws'],
    cluster: 'mt1',
    withCredentials: true,
    auth: {
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token'),
        },
    },

});
