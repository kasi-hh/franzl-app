import axios from 'axios'
import {LocalStorage} from 'quasar'

axios.defaults.headers.common['Authorization'] = LocalStorage.has('token') ? 'Bearer '+LocalStorage.getItem('token') : '';