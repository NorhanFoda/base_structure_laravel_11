<script setup>
import {ref, shallowRef, onMounted, reactive, getCurrentInstance} from 'vue'
import UserApi from "@api/user.api";
import {useLoaderStore} from "@store/loader";
import { useRouter } from 'vue-router';

const loaderStore = useLoaderStore();
const app = getCurrentInstance();
const t = app.appContext.config.globalProperties.$t;
const router = useRouter();
let resources = ref([]);
let openModal = shallowRef(false);
let title = shallowRef('');
let pagination = shallowRef({
    page: 1
});
let filters = ref({
    page: 1,
    keyword: '',
    embed: 'roles:id,roles.permissions:id',
    includeEmptyRelations: false,
    columns: 'id,name'
});

async function getResources(page = 1) {
    filters.value.page = page;
    UserApi.list(filters.value)
        .then(({data}) => {
            resources.value = data.data;
            pagination.value = data.meta;
        })
        .catch(error => {
            console.log(error);
        })
}

onMounted(async () => {
    await getResources();
})

</script>
<template>
    <div class="main-content side-content">
        <div class="container">
            <page-header title="sidebar.users"/>
            <div class="card">
                <div class="card-body pb-0">
                    <Filters @submit="getResources" :model="filters"/>
                </div>
            </div>

            <spinner/>
            <el-table :data="resources" style="width: 100%" v-if="resources.length">
                <el-table-column label="#" type="index"/>
                <el-table-column :label="$t('pages.name')" prop="name"/>
            </el-table>
            <strong v-if="!resources.length && !loaderStore.loading" class="text-danger">{{
                    $t('global.no_results')
                }}</strong>
            <Pagination :pagination="pagination" @paginate="getResources"/>
        </div>
    </div>
</template>



