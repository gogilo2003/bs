import axios from 'axios';

declare global {
    interface Window { axios: any }
}

window.axios = axios;
(window.axios as any).defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
