// Configura Axios para que las peticiones AJAX incluyan cabeceras esperadas por Laravel.
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
