import { createApp } from 'vue';
import Index from './Index.vue';
import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css';

const app = createApp({
    components: { Index },
});

app.use(ElementPlus);

app.mount('#app');
