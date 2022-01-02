<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Login</div>

                    <div class="card-body">
                        <div class="mb-3">
                            <label for="emailInput" class="form-label"
                                >Email</label
                            >
                            <input
                                type="email"
                                class="form-control"
                                name="emailInput"
                                id="emailInput"
                                aria-describedby="emailHelpId"
                                placeholder="Email"
                                v-model="credentials.email"
                            />
                        </div>
                        <div class="mb-3">
                            <label for="passwordInput" class="form-label"
                                >Password</label
                            >
                            <input
                                type="password"
                                class="form-control"
                                name="passwordInput"
                                id="passwordInput"
                                placeholder="Password"
                                v-model="credentials.password"
                            />
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-grid gap-2">
                            <button
                                type="button"
                                class="btn btn-primary"
                                @click="login"
                            >
                                Login
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            credentials: {
                email: null,
                password: null,
            },
        };
    },
    methods: {
        login() {
            axios
                .post("/api/v1/login", this.credentials)
                .then((response) => {
                    if ((response.status = 200)) {
                        localStorage.setItem(
                            "token",
                            response.data.access_token
                        );
                        window.location = "/";
                    } else {
                        alert("Authentication failed");
                    }
                })
                .catch((error) => {
                    // console.log(error.response.data);
                    alert(error.response.data.message);
                });
        },
    },
};
</script>
