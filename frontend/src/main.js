import { createApp, h } from 'vue';
import Tweets from "./pages/tweets/tweets.vue";
import SimpleTable from "./components/simple-table.vue";
import './style.css';

const routes = {
    '/': Tweets
};

const SimpleRouter = {
    data: () => ({
        currentRoute: window.location.pathname
    }),

    computed: {
        CurrentComponent() {
            return routes[this.currentRoute];
        }
    },

    render() {
        return h(this.CurrentComponent);
    }
}

const app = createApp(SimpleRouter)
app.component("SimpleTable", SimpleTable);


app.mount('#app');
