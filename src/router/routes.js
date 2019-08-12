import AppLayout from '../layouts/AppLayout'
import LoginLayout from '../layouts/LoginLayout'

import Home from '../pages/Home'
import Show from '../pages/Show'
import Post from '../pages/Post'
import Settings from '../pages/Settings'
import Camera from '../pages/Camera'
import Error404 from '../pages/Error404'

const routes = [
  {
    path: '/',
    component: AppLayout,
    children: [
      { path: '', name:'home', component: Home },
      { path: 'show', props:true, name:'show', component: Show },
      { path: 'post', props:true, name:'post', component: Post },
      { path: 'settings', name:'settings', component: Settings },
      { path: 'camera', name:'camera', component: Camera },
    ]
  },
   { path: '/login', name:'login', component: LoginLayout },

]

// Always leave this as last one
if (process.env.MODE !== 'ssr') {
  routes.push({
    path: '*',
    component: Error404
  })
}
export default routes
