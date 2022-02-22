import { createApp, h } from 'vue';
import App from "./App.vue";
import LayoutApp from "./layouts/app/app.vue";
import LayoutAuth from "./layouts/auth/auth.vue";
import router from "./router.js";
import SimpleTable from "./components/simple-table.vue";
import TttButton from "./components/ttt-button.vue";
import TttLoading from "./components/ttt-loading.vue";
import TttSimpleModal from "./components/ttt-simple-modal.vue";

import './style.css';



createApp(App)
    .use(router)
    .component("SimpleTable", SimpleTable)
    .component("TttButton", TttButton)
    .component("TttLoading", TttLoading)
    .component("TttSimpleModal", TttSimpleModal)
    .component("LayoutApp", LayoutApp)
    .component("LayoutAuth", LayoutAuth)
    .mount('#app');
