import { createRouter, createWebHistory } from 'vue-router';
import Tweets from "./views/tweets/tweets.vue";
import NewTweet from "./views/new-tweet/new-tweet.vue";
import Login from "./views/login/login.vue";
import Register from "./views/register/register.vue";

const routes = [
    {
        path: "/",
        name: "tweets",
        component: Tweets
    },
    {
        path: "/new-tweet",
        name: "new-tweet",
        component: NewTweet
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

export default router;
