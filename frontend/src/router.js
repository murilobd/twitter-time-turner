import { createRouter, createWebHistory } from 'vue-router';
import Tweets from "./views/tweets/tweets.vue";
import NewTweet from "./views/new-tweet/new-tweet.vue";
import Login from "./views/login/login.vue";
import useUser from './composables/useUser.js';

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
    }
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});

router.beforeEach(async (to, from, next) => {
    const { isAuthenticated } = useUser();

    if (to.meta.requiresAuth && !isAuthenticated.value) {
        next({name: "login"});
    } else {
        next();
    }
});

export default router;
