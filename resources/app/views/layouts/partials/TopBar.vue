<script setup>
import { onMounted, ref } from "vue";
import { useAuthUserStore } from "@store/user";
import { useRouter } from "vue-router";

const store = useAuthUserStore();
const username = store.user.name;
const roles = store.user.roles;
const router = useRouter();

async function toggleSideBar() {
    if ($("#mainNavShow").closest(".with-side-menu").length === 1) {
        if ($(window).innerWidth() <= "991.98") {
            $("body").addClass("main-navbar-show");
        } else {
            $("body").toggleClass("main-sidebar-hide");
        }
    } else {
        $("body").addClass("main-navbar-show");
    }
}

async function fullScreenMode() {
    $("html").addClass("fullscreenie");
    if (
        (document.fullScreenElement !== undefined &&
            document.fullScreenElement === null) ||
        (document.msFullscreenElement !== undefined &&
            document.msFullscreenElement === null) ||
        (document.mozFullScreen !== undefined && !document.mozFullScreen) ||
        (document.webkitIsFullScreen !== undefined &&
            !document.webkitIsFullScreen)
    ) {
        if (document.documentElement.requestFullScreen) {
            document.documentElement.requestFullScreen();
        } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullScreen) {
            document.documentElement.webkitRequestFullScreen(
                Element.ALLOW_KEYBOARD_INPUT
            );
        } else if (document.documentElement.msRequestFullscreen) {
            document.documentElement.msRequestFullscreen();
        }
    } else {
        $("html").removeClass("fullscreenie");
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }
}

async function logout() {
    store.logout()
        .then((response) => {
            router.push({
                path: "/",
            });
        })
        .catch((error) => {
            console.log(error);
        });
}


onMounted(async () => {
    $(".main-header .dropdown > a").on("click", function (e) {
        e.preventDefault();
        $(this).parent().toggleClass("show");
        $(this).parent().siblings().removeClass("show");
    });
});
</script>

<template>
    <div class="main-header with-side-menu"> <!-- class="with-side-menu" -->
        <div class="container">
            <div class="main-header-left">
                <a @click="toggleSideBar()" class="main-header-menu-icon d-lg-none" href="javascript:void(0)" id="mainNavShow"><span></span></a>
                <a class="main-logo" href="index.html">
                    <img :src="`${publicPath}/assets/images/logo.png`" class="header-brand-img desktop-logo" alt="logo">
                    <img :src="`${publicPath}/assets/images/icon.png`" class="header-brand-img icon-logo" alt="logo">
                    <img :src="`${publicPath}/assets/images/logo-light.png`" class="header-brand-img desktop-logo theme-logo" alt="logo">
                    <img :src="`${publicPath}/assets/images/icon-light.png`" class="header-brand-img icon-logo theme-logo" alt="logo">
                </a>
            </div>

            <div class="main-header-right">
                <div class="dropdown d-md-flex">
                    <a @click="fullScreenMode()" class="nav-link icon full-screen-link">
                        <i class="las la-expand fullscreen-button"></i>
                    </a>
                </div>
                <div class="dropdown main-profile-menu">
                    <a class="main-img-user" href="">
                        <img alt="avatar" :src="`${publicPath}/assets/images/user.png`">
                    </a>
                    <div class="dropdown-menu">
                        <div class="header-navheading text-center">
                            <h6 class="main-headNav-title">{{ username }}</h6>
                            <p class="main-headNav-text">{{ roles }}</p>
                        </div>
                        <ul class="profileMenu">
                            <li class="dropdown-item">
                                <router-link  to="/edit-profile">
                                    <i class="las la-user"></i> {{ $t('messages.profile') }}
                                </router-link>
                            </li>
                            <li class="dropdown-item">
                                <a href="#" @click.prevent="logout">
                                    <i class="las la-power-off"></i> {{ $t('messages.sign_out') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
