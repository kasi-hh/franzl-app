import axios from 'axios'
import {LocalStorage} from 'quasar'

axios.defaults.headers.common['Authorization'] = LocalStorage.has('token') ? 'Bearer '+LocalStorage.getItem('token') : '';

export default async ({ Vue }) => {
  Vue.prototype.$axios = axios
}
