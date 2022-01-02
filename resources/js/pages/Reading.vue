<template>
    <div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            Blood Sugar Reading <i class="fab fa-user"></i>
                        </div>

                        <div class="card-body">
                            <div class="mb-3">
                                <label for="dateInput" class="form-label"
                                    >Date</label
                                >
                                <date-picker
                                    id="dateInput"
                                    v-model="read_at"
                                    :config="options"
                                ></date-picker>
                            </div>
                            <div class="mb-3">
                                <label for="typeInput" class="form-label"
                                    >Type</label
                                >
                                <select
                                    class="form-control"
                                    name="typeInput"
                                    id="typeInput"
                                    v-model="type"
                                >
                                    <option value="fbs">Fasting</option>
                                    <option value="rbs">Random</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="readingInput" class="form-label"
                                    >Reading</label
                                >
                                <input
                                    type="text"
                                    class="form-control"
                                    name="readingInput"
                                    id="readingInput"
                                    aria-describedby="helpId"
                                    placeholder="Reading"
                                    v-model="reading"
                                />
                            </div>
                        </div>
                        <div class="card-footer">
                            <button
                                type="button"
                                class="btn btn-primary"
                                @click="save"
                            >
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            reading: null,
            read_at: new Date(),
            type: "fbs",
            options: {
                inline: false,
                sideBySide: true,
                format: "YYYY-MM-DD HH:mm:ss",
                icons: {
                    time: "far fa-clock",
                    date: "fas fa-calendar-alt",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down",
                    previous: "fa fa-chevron-left",
                    next: "fa fa-chevron-right",
                    today: "far fa-clock",
                    clear: "fas fa-trash-alt",
                },
            },
        };
    },
    methods: {
        save() {
            let data = {
                reading: this.reading,
                read_at: this.read_at,
                type: this.type,
            };
            axios
                .post("api/v1/readings", data)
                .then((response) => {
                    response.data.reading;
                    this.$router.push("/");
                })
                .catch((error) => {
                    console.log(error.response.data);
                    if (error.response.status == 415) {
                        error.response.data.details.forEach((element) => {
                            console.log(element);
                        });
                    }
                });
        },
    },
};
</script>
