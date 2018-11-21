import Vue from 'vue'
import Router from 'vue-router'
import AppHome from '../components/AppHome'
import AppForum from '../components/AppForum'
import AppForumQuery from '../components/AppForumQuery'
import AppTourPlanner from '../components/AppTourPlanner'
import AppMessaging from '../components/AppMessaging'
import AppBlog from '../components/AppBlog'
import AppBlogPost from '../components/AppBlogPost'
import AppTourPackage from '../components/AppTourPackage'
import AppTourPackageDetail from '../components/AppTourPackageDetail'
import AppProfile from '../components/AppProfile'
import AppFriends from '../components/AppFriends'

import store from '../store'
import {AUTH_VALIDATE} from "../store/actions";

Vue.use(Router);

const ifAuthenticated = (to, from, next) => {
    if (store.getters.isAuthenticated) {
        return new Promise((resolve, reject) => {
            store.dispatch(AUTH_VALIDATE)
                .then(response => {
                    next();
                    console.log(response);
                }, error => {
                    next('/');
                    console.log(error);
                });
        })

    }
    else next('/');
}

export default new Router({
    // mode: 'history',
    routes: [
        {
            path: '/',
            name: 'app-home',
            component: AppHome
        },
        {
            path: '/forum',
            name: 'app-forum',
            component: AppForum
        },
        {
            path: '/blog',
            name: 'app-blog',
            component: AppBlog
        },
        {
            path: '/blog/:slug',
            name: 'app-blog-post',
            component: AppBlogPost,
            props: true
        },
        {
            path: '/forum/:id',
            name: 'app-forum-query',
            component: AppForumQuery,
            props: true
        },
        {
            path: '/tourplanner',
            name: 'app-tour-planner',
            component: AppTourPlanner,
            beforeEnter: ifAuthenticated
        },
        {
            path: '/message',
            name: 'app-messaging',
            component: AppMessaging,
            beforeEnter: ifAuthenticated
        },
        {
            path: '/tourpackage',
            name: 'app-tour-package',
            component: AppTourPackage,
        },
        {
            path: '/tourpackage/:slug',
            name: 'app-tour-package-detail',
            component: AppTourPackageDetail,
            props: true
        },
        {
            path: '/profile',
            name: 'app-profile',
            component: AppProfile,
            beforeEnter: ifAuthenticated
        },
        {
            path: '/:slug/friends',
            name: 'app-friends',
            component: AppFriends,
            beforeEnter: ifAuthenticated
        },
    ]
})
