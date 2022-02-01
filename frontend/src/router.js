import { createRouter, createWebHistory } from 'vue-router';
import Tweets from "./views/tweets/tweets.vue";
import NewTweet from "./views/new-tweet/new-tweet.vue";
import Login from "./views/login/login.vue";
import Register from "./views/register/register.vue";
import { defaultFetch } from './plugins/http.plugin.js';

const routes = [
    {
        path: "/",
        name: "tweets",
        component: Tweets,
        meta: { requiresAuth: true }
    },
    {
        path: "/new-tweet",
        name: "new-tweet",
        component: NewTweet,
        meta: { requiresAuth: true }
    },
    {
        path: "/login",
        name: "login",
        component: Login
    },
    {
        path: "/register",
        name: "register",
        component: Register
    }
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});

router.beforeEach(async (to, from, next) => {
    // check if in url there's a query param called "access-token" to login user
    const accessToken = to.query['access-token'];
    if (accessToken) {
        try {
            // get user infos
            const backendUrl = import.meta.env.VITE_BACKEND_URL;
            const userFetch = await defaultFetch(`${backendUrl}/api/user`, {
                method: "GET",
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${accessToken}`
                }
            });
            const userData = await userFetch.json();

            localStorage.setItem("access-token", accessToken);
            if (!userData.twitter_oauth_ok) {
                window.location.href = `${backendUrl}/request_tweeter_oauth`;
            }

            return next({
                to: "/"
            })
        } catch (error) {
            console.error(error);
        }
    }

    next();
});

export default router;
