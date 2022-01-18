import { createApp, h } from 'vue';
import App from "./App.vue";
import LayoutApp from "./layouts/app/app.vue";
import router from "./router.js";
import SimpleTable from "./components/simple-table.vue";
import './style.css';



createApp(App)
    .use(router)
    .component("SimpleTable", SimpleTable)
    .component("LayoutApp", LayoutApp)
    .mount('#app');
