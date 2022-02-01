import { createApp, h } from 'vue';
import App from "./App.vue";
import LayoutApp from "./layouts/app/app.vue";
import LayoutAuth from "./layouts/auth/auth.vue";
import router from "./router.js";
import SimpleTable from "./components/simple-table.vue";
import TttButton from "./components/ttt-button.vue";

import httpPlugin from './plugins/http.plugin.js';
import './style.css';



createApp(App)
    .use(httpPlugin)
    .use(router)
    .component("SimpleTable", SimpleTable)
    .component("TttButton", TttButton)
    .component("LayoutApp", LayoutApp)
    .component("LayoutAuth", LayoutAuth)
    .mount('#app');
