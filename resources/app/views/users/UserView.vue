<script lang="ts" setup>
import {getCurrentInstance, ref, onMounted} from "vue";
import {Plus} from "@element-plus/icons-vue";
import {useRoute, useRouter} from "vue-router";
import UserApi from "@api/user.api";

const route = useRoute();
const router = useRouter();
const app = getCurrentInstance();
const t = app.appContext.config.globalProperties.$t;
const user = ref({});
const activeName = ref("comments");
const tableData = [
    {
        date: "2016-05-03",
        PM: "Tom",
        address: "No. 189, Grove St, Los Angeles",
        from: "2019-02-11",
        to: "2019-02-11",
    },
    {
        date: "2016-05-02",
        PM: "Tom",
        address: "No. 189, Grove St, Los Angeles",
        from: "2019-02-11",
        to: "2019-02-11",
    },
    {
        date: "2016-05-04",
        PM: "Tom",
        address: "No. 189, Grove St, Los Angeles",
        from: "2019-02-11",
        to: "2019-02-11",
    },
    {
        date: "2016-05-01",
        PM: "Tom",
        address: "No. 189, Grove St, Los Angeles",
        from: "2019-02-11",
        to: "2019-02-11",
    },
];

async function getUser() {
    // UserApi.get({id: route.params.id}, {embed: "avatar,cover,department,roles"}).then(
    //     (response) => {
    //         user.value = response.data.data;
    //     }
    // );
}

// onMounted(async () => {
//     await getUser();
// });
</script>

<template>
    <div class="main-content side-content">
        <div class="container">
            <page-header title="sidebar.users" :active="false">
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $t("messages.view_user") }}
                </li>
            </page-header>
            <spinner v-if="0"></spinner>
            <div v-if="1">
                <div class="bg-block">
                    <img :src="user.cover?.url" alt="bg"/>
                </div>
                <div class="emp-info d-flex flex-column align-items-center text-center">
                    <img :src="user.avatar?.url" alt="profile"
                         class="mb-3"/>
                    <h1 class="fs-24">{{ user.name }}</h1>
                    <h3>{{ user.job_title }}</h3>
                    <a href="#" class="dep">{{user.department?.name}} ✨</a>
                </div>

                <div class="row top-row">
                    <!-- Basic Information -->
                    <div class="col-md-6 mb-4">
                        <div class="card pt-3 px-3 h-100 basic-info">
                            <h4 class="text-capitalize card-main-title mb-3">
                                {{ $t("messages.basic_info")}}
                            </h4>

                            <div class="mb-3">
                                <h6 class="sub-title text-lightGray">
                                    {{ $t("messages.join_date")}}
                                </h6>
                                <span class="value black-text">{{user.employment_start_date}}</span>
                            </div>
                            <div class="mb-3">
                                <h6 class="sub-title text-lightGray">
                                    {{ $t("messages.employee_id")}}
                                </h6>
                                <span class="value black-text">#{{user.id}}</span>
                            </div>
                            <div class="mb-3">
                                <h6 class="sub-title text-lightGray">
                                    {{ $t("messages.birth_date")}}
                                </h6>
                                <span class="value black-text">{{user.date_of_birth}}</span>
                            </div>
                            <div class="mb-3">
                                <h6 class="sub-title text-lightGray">
                                    {{ $t("messages.email")}}
                                </h6>
                                <span class="value black-text">{{user.email}}</span>
                            </div>
                            <div class="mb-3">
                                <h6 class="sub-title text-lightGray">{{ $t("messages.phone")}}</h6>
                                <span class="value black-text">{{user.phone}}</span>
                            </div>
                            <div class="mb-3">
                                <h6 class="sub-title text-lightGray">
                                    {{ $t("messages.interests")}}
                                </h6>
                                <div class="value black-text d-flex flex-wrap align-items-center mt-3">
                                    {{user.interests}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Basic Information -->
                    <!-- Awards -->
                    <div class="col-md-6 mb-4">
                        <div class="card chart-card pt-3 px-3 h-100 awards">
                            <h4 class="text-capitalize card-main-title mb-0">
                                Awards
                            </h4>
                            <!-- in case of there are awards -->
                            <ul class="scroll-list mb-0">
                                <li
                                    class="d-flex w-100 justify-content-between py-3 align-items-center flex-wrap border-bottom"
                                >
                                    <h6 class="fs-14 fw-normal mb-0">
                                        <img
                                            :src="`${publicPath}/assets/images/award.png`"
                                            alt="award"
                                        />
                                        Employee of The Month
                                    </h6>
                                    <small class="fs-12 text-lightGray">
                                        2022 / 07 / 01
                                    </small>
                                </li>
                            </ul>

                            <!-- in case of empty -->
                            <!--                       <div class="d-flex flex-column justify-content-center align-items-center h-100 px-3">-->
                            <!--                           <img :src="`${publicPath}/assets/images/prize.png`" alt="prize" class="prize-img mb-3"/>-->
                            <!--                            <h6 class=" m-0 text-capitalize text-center no-award-text">-->
                            <!--                                sorry your card is empty, but never give up work hard because something good waits for you.-->
                            <!--                            </h6>-->
                            <!--                        </div>-->
                        </div>
                    </div>
                    <!-- Awards -->
                    <!-- tabs -->
                    <div class="col-12">
                        <div class="card user-tabs pt-3 px-3 h-100">
                            <el-tabs
                                v-model="activeName"
                                type="card"
                                class="demo-tabs"
                            >
                                <el-tab-pane label="Comments" name="comments">
                                    <div class="d-flex w-100 my-3">
                                        <el-button
                                            type="primary"
                                            class="btn open_modal btn-primary"
                                            :icon="Plus"
                                            data-route="{!! route('team.addCommentForm',['employee_id'=>$developer->id]) !!}"
                                            href="javascript:void(0)"
                                        >
                                            Add Comment
                                        </el-button>
                                    </div>

                                    <div class="w-100 mt-3">
                                        <div class="media mb-4 border p-3 rounded">
                                            <div class="media-body">
                                                <div
                                                    class="d-flex justify-content-between align-items-center flex-wrap"
                                                >
                                                <span class="mb-2 fs-14">
                                                    <b>Amany Kassem</b>
                                                    -
                                                    <span
                                                        class="text-lightGray fs-13"
                                                    >2022 / 07 / 01</span
                                                    >
                                                </span>
                                                    <a
                                                        href="javascript:void(0)"
                                                        data-route="{!! route('team.deleteComment',$comment->id) !!}"
                                                        data-id="{!! $comment->id !!}"
                                                        class="delete btn btn-danger btn-icon mb-2 fs-12"
                                                    >
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </div>
                                                <p class="m-0 p-0 border-0">
                                                    Lorem Ipsum is simply dummy text
                                                    of the printing and typesetting
                                                    industry. Lorem Ipsum has been
                                                    the industry's standard dummy
                                                    text ever since the 1500s, when
                                                    an unknown printer took a galley
                                                    of type and scrambled it to make
                                                    a type specimen book.
                                                </p>
                                            </div>
                                        </div>
                                        <div class="media mb-4 border p-3 rounded">
                                            <div class="media-body">
                                                <div
                                                    class="d-flex justify-content-between align-items-center flex-wrap"
                                                >
                                                <span class="mb-2 fs-14">
                                                    <b>Amany Kassem</b>
                                                    -
                                                    <span
                                                        class="text-lightGray fs-13"
                                                    >2022 / 07 / 01</span
                                                    >
                                                </span>
                                                    <a
                                                        href="javascript:void(0)"
                                                        data-route="{!! route('team.deleteComment',$comment->id) !!}"
                                                        data-id="{!! $comment->id !!}"
                                                        class="delete btn btn-danger btn-icon mb-2 fs-12"
                                                    >
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </div>
                                                <p class="m-0 p-0 border-0">
                                                    Lorem Ipsum is simply dummy text
                                                    of the printing and typesetting
                                                    industry. Lorem Ipsum has been
                                                    the industry's standard dummy
                                                    text ever since the 1500s, when
                                                    an unknown printer took a galley
                                                    of type and scrambled it to make
                                                    a type specimen book.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- in case of empty -->
                                    <!--                                <div class="d-flex flex-column justify-content-center align-items-center mt-3">-->
                                    <!--                                    <img :src="`${publicPath}/assets/images/empty.png`"  class="empty-img mb-3" alt="empty"/>-->
                                    <!--                                    <h6 class="text-lightBlue m-0 text-capitalize text-center fw-bold">No Comments Yet</h6>-->
                                    <!--                                </div>-->
                                </el-tab-pane>
                                <el-tab-pane label="Projects" name="projects">
                                    <el-table
                                        :data="tableData"
                                        class="w-100 tab-table mt-3"
                                    >
                                        <el-table-column label="#" type="index"/>
                                        <el-table-column label="Name">
                                            <template #default="scope">
                                            <span class="card-ico"
                                            ><i
                                                class="fas fa-code"
                                                aria-hidden="true"
                                            ></i
                                            ></span>
                                                <a href="#"><span>Roms</span></a>
                                            </template>
                                        </el-table-column>
                                        <el-table-column
                                            prop="date"
                                            label="Date"
                                            width="180"
                                        />
                                        <el-table-column
                                            prop="from"
                                            label="From"
                                            width="180"
                                        />
                                        <el-table-column
                                            prop="to"
                                            label="To"
                                            width="180"
                                        />
                                        <el-table-column
                                            prop="PM"
                                            label="PM"
                                            width="180"
                                        />

                                        <el-table-column label="Action">
                                            <template #default="scope">
                                            <span
                                                class="badge badge-light-success fs-14 p-2 rounded-lg mw-100 text-capitalize"
                                            >Active</span
                                            >
                                            </template>
                                        </el-table-column>
                                    </el-table>
                                    <!-- in case of empty -->
                                    <!--                                <div class="d-flex flex-column justify-content-center align-items-center mt-3">-->
                                    <!--                                    <img :src="`${publicPath}/assets/images/empty.png`"  class="empty-img mb-3" alt="empty"/>-->
                                    <!--                                    <h6 class="text-lightBlue m-0 text-capitalize text-center fw-bold">No Comments Yet</h6>-->
                                    <!--                                </div>-->
                                </el-tab-pane>
                                <el-tab-pane
                                    label="Assigned Tickets"
                                    name="assigned"
                                >
                                    <el-table
                                        :data="tableData"
                                        class="w-100 tab-table mt-3"
                                    >
                                        <el-table-column label="#" type="index"/>
                                        <el-table-column label="Title">
                                            <template #default="scope">
                                                <a href="#" class="text-primary">
                                                    #512 - تنسيق طباعة كشف المؤجر و
                                                    الشواغر
                                                </a>
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Project">
                                            <template #default="scope">
                                                Iskan PMS System
                                                <div>
                                                <span
                                                    class="text-danger fw-bold fs-12"
                                                >No SLA
                                                </span>
                                                </div>
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Created By">
                                            <template #default="scope">
                                                <button
                                                    class="btn bg-transparent open_modal"
                                                    data-id="{!! $ticket->client->id ?? ''!!}"
                                                    data-route="{!! route('admin.client.profile',($ticket->client->id ?? '')) !!}"
                                                    href="javascript:void(0)"
                                                >
                                                    <i
                                                        class="fa-solid fa-info me-2"
                                                    ></i>
                                                    Amany Kassem
                                                </button>
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Last Reply">
                                            <template #default="scope">
                                                Khaled Alwakeel
                                                <br/>
                                                138 Days ago
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Created">
                                            <template #default="scope">
                                                164 Days ago
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Priority">
                                            <template #default="scope">
                                            <span
                                                class="badge badge-light-danger fs-14 p-2 rounded-lg mw-100 text-capitalize"
                                            >High</span
                                            >
                                            </template>
                                        </el-table-column>
                                    </el-table>

                                    <!-- in case of empty -->
                                    <!--                                <div class="d-flex flex-column justify-content-center align-items-center mt-3">-->
                                    <!--                                    <img :src="`${publicPath}/assets/images/empty.png`"  class="empty-img mb-3" alt="empty"/>-->
                                    <!--                                    <h6 class="text-lightBlue m-0 text-capitalize text-center fw-bold">No Comments Yet</h6>-->
                                    <!--                                </div>-->
                                </el-tab-pane>
                                <el-tab-pane label="Activity log" name="log">
                                    <div class="bg-lightGray2 mt-3 p-3 rounded-3">
                                        <div class="mb-3">
                                            <h5 class="m-0">
                                            <span class="card-ico"
                                            ><i
                                                class="fas fa-user"
                                                aria-hidden="true"
                                            ></i
                                            ></span>
                                                <span>Project: --</span>
                                            </h5>
                                        </div>
                                        <div>
                                            <div>
                                                <b>Date/Time:</b> 2022-09-08
                                                <b>-</b>
                                                form: 07:57 AM to: 04:00 PM
                                                <b>-</b>
                                                08:03 Hours
                                            </div>
                                            <div class="mb-3">
                                                <b>Expected to Date:</b> 2022-09-08
                                            </div>
                                            <div>
                                                <p>
                                                    <b>Osimi file managements :</b>
                                                    <br/><br/>
                                                    - standup meeting.<br/>
                                                    - workflow meeting with Eng.
                                                    Neamat , Eng. Ahmed Yousef and
                                                    Eng. Alabsy.<br/>
                                                    - workflow ui page (done).
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-lightGray2 mt-3 p-3 rounded-3">
                                        <div class="mb-3">
                                            <h5 class="m-0">
                                            <span class="card-ico"
                                            ><i
                                                class="fas fa-user"
                                                aria-hidden="true"
                                            ></i
                                            ></span>
                                                <span>Project: --</span>
                                            </h5>
                                        </div>
                                        <div>
                                            <div>
                                                <b>Date/Time:</b> 2022-09-08
                                                <b>-</b>
                                                form: 07:57 AM to: 04:00 PM
                                                <b>-</b>
                                                08:03 Hours
                                            </div>
                                            <div class="mb-3">
                                                <b>Expected to Date:</b> 2022-09-08
                                            </div>
                                            <div>
                                                <p>
                                                    <b>Osimi file managements :</b>
                                                    <br/><br/>
                                                    - standup meeting.<br/>
                                                    - workflow meeting with Eng.
                                                    Neamat , Eng. Ahmed Yousef and
                                                    Eng. Alabsy.<br/>
                                                    - workflow ui page (done).
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- in case of empty -->
                                    <!--                                <div class="d-flex flex-column justify-content-center align-items-center mt-3">-->
                                    <!--                                    <img :src="`${publicPath}/assets/images/empty.png`"  class="empty-img mb-3" alt="empty"/>-->
                                    <!--                                    <h6 class="text-lightBlue m-0 text-capitalize text-center fw-bold">No Comments Yet</h6>-->
                                    <!--                                </div>-->
                                </el-tab-pane>
                                <el-tab-pane label="Leaves" name="leaves">
                                    <el-table
                                        :data="tableData"
                                        class="w-100 tab-table mt-3"
                                    >
                                        <el-table-column label="#" type="index"/>
                                        <el-table-column label="Name / Job Title">
                                            <template #default="scope"></template>
                                        </el-table-column>
                                        <el-table-column label="Leave Type">
                                            <template #default="scope"></template>
                                        </el-table-column>
                                        <el-table-column label="Start Date">
                                            <template #default="scope"></template>
                                        </el-table-column>
                                        <el-table-column label="Period">
                                            <template #default="scope"></template>
                                        </el-table-column>
                                        <el-table-column label="Status">
                                            <template #default="scope"></template>
                                        </el-table-column>
                                        <el-table-column label="Actions">
                                            <template #default="scope"></template>
                                        </el-table-column>
                                    </el-table>

                                    <!-- in case of empty -->
                                    <!--                                <div class="d-flex flex-column justify-content-center align-items-center mt-3">-->
                                    <!--                                    <img :src="`${publicPath}/assets/images/empty.png`"  class="empty-img mb-3" alt="empty"/>-->
                                    <!--                                    <h6 class="text-lightBlue m-0 text-capitalize text-center fw-bold">No Comments Yet</h6>-->
                                    <!--                                </div>-->
                                </el-tab-pane>
                                <el-tab-pane
                                    label="Overtime requests"
                                    name="overtime"
                                >
                                    <!-- in case of empty -->
                                    <div
                                        class="d-flex flex-column justify-content-center align-items-center mt-3"
                                    >
                                        <img
                                            :src="`${publicPath}/assets/images/empty.png`"
                                            class="empty-img mb-3"
                                            alt="empty"
                                        />
                                        <h6
                                            class="text-lightBlue m-0 text-capitalize text-center fw-bold"
                                        >
                                            No overtime requests Yet
                                        </h6>
                                    </div>
                                </el-tab-pane>
                                <el-tab-pane label="Assigned Assets" name="assets">
                                    <!-- in case of empty -->
                                    <div
                                        class="d-flex flex-column justify-content-center align-items-center mt-3"
                                    >
                                        <img
                                            :src="`${publicPath}/assets/images/empty.png`"
                                            class="empty-img mb-3"
                                            alt="empty"
                                        />
                                        <h6
                                            class="text-lightBlue m-0 text-capitalize text-center fw-bold"
                                        >
                                            No assets Yet
                                        </h6>
                                    </div>
                                </el-tab-pane>
                                <el-tab-pane label="Docs" name="docs">
                                    <!-- in case of empty -->
                                    <div
                                        class="d-flex flex-column justify-content-center align-items-center mt-3"
                                    >
                                        <img
                                            :src="`${publicPath}/assets/images/empty.png`"
                                            class="empty-img mb-3"
                                            alt="empty"
                                        />
                                        <h6
                                            class="text-lightBlue m-0 text-capitalize text-center fw-bold"
                                        >
                                            No docs Yet
                                        </h6>
                                    </div>
                                </el-tab-pane>
                            </el-tabs>
                        </div>
                    </div>
                    <!-- end of tabs -->
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
