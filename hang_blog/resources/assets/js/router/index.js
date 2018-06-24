import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '../components/Home.vue';
import Dashboard from '../components/Dashboard.vue';
import Register from '../components/Auth/Register.vue';
import Login from '../components/Auth/Login.vue';
import Logout from '../components/Auth/Logout.vue';
import Profile from '../components/User/Profile.vue';
import UpdateProfile from '../components/User/UpdateProfile.vue';
import UpdatePassword from '../components/User/UpdatePassword.vue';
import UserVerify from '../components/Auth/UserVerify.vue';
import VerificationEmail from '../components/Auth/VerificationEmail.vue';
import ResetPasswordEmailForm from '../components/User/ResetPasswordEmailForm.vue';
import ResetPassword from '../components/User/ResetPassword.vue';
import InsertPostForm from '../components/Post/InsertPostForm.vue';
import User from '../components/User/User.vue';
import Blog from '../components/Post/Blog.vue';
import store from '../store';

Vue.use(VueRouter);

const router = new VueRouter({
  base: '/',
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/register',
      name: 'register',
      component: Register,
      meta: {
        auth: false
      }
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
      meta: {
        auth: false
      }
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: Dashboard,
      meta: {
        auth: true
      }
    },
    {
      path: '/logout',
      name: 'logout',
      component: Logout
    },
    {
      path: '/profile/:nicknameParameter',
      name: 'profile',
      component: Profile,
      meta: {
        auth: false
      },
      props: true
    },
    {
      path: '/update_profile',
      name: 'update_profile',
      component: UpdateProfile,
      meta: {
        auth: true
      }
    },
    {
      path: '/update_password',
      name: 'update_password',
      component: UpdatePassword,
      meta: {
        auth: true
      }
    },
    {
      path: '/user_verify',
      name: 'user_verify',
      component: UserVerify,
      meta: {
        auth: false
      }
    },
    {
      path: '/user/verify/:token',
      name: 'verification_email',
      component: VerificationEmail,
      props: true,
      meta: {
        auth: false
      }
    },
    {
      path: '/password/reset',
      name: 'password_reset_form',
      component: ResetPasswordEmailForm,
      meta: {
        auth: false
      }
    },
    {
      path: '/password/reset/:token',
      name: 'password_reset_action',
      component: ResetPassword,
      props: true,
      meta: {
        auth: false
      }
    },
    {
      path: '/post/create',
      name: 'create_post',
      component: InsertPostForm,
      meta: {
        auth: true
      }
    },
    {
      path: '/user/:nickname/post/:post_id',
      name: 'post_content',
      component: Blog,
      meta: {
        auth: false,
      },
      props: true
    },
    {
      path: '/user/:nickname',
      name: 'user_blog',
      component: User,
      meta: {
        auth: false
      },
      props: true
    }
  ]
});
router.beforeEach((to, from, next) => {
  if (to.meta.auth) {
    if (store.state.isLogged) {
      return next();
    } else {
      return next('/login');
    }
  }
  return next();
});

export default router;
