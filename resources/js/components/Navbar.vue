<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <router-link class="navbar-brand" to="/">BS</router-link>
            <button
                class="navbar-toggler d-lg-none"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapsibleNavId"
                aria-controls="collapsibleNavId"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div
                v-if="logedin"
                class="collapse navbar-collapse"
                id="collapsibleNavId"
            >
                <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <router-link class="nav-link" to="/">HOME</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" to="/reading"
                            >READING</router-link
                        >
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" @click="logout">LOGOUT</a>
                    </li>
                </ul>
            </div>
            <div v-else class="collapse navbar-collapse" id="collapsibleNavId">
                <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <router-link class="nav-link" to="/login"
                            >LOGIN</router-link
                        >
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
<script>
import router from "../routes";

export default {
    methods: {
        logout() {
            localStorage.clear();
            window.location = "/login";
        },
    },
    computed: {
        logedin: {
            get() {
                if (localStorage.getItem("token")) {
                    return true;
                }
                return false;
            },
        },
    },
};
</script>
