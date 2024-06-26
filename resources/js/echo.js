import Echo from '@ably/laravel-echo';

import * as Ably from 'ably';
window.Ably = Ably;

window.Echo = new Echo({
    broadcaster: 'ably',
});
