<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Blood Sugar Readings</div>

                    <vue-good-table
                        :columns="columns"
                        :rows="rows"
                        max-height="calc(100vh - 55px)"
                        pagination
                        compactMode
                    />
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
            columns: [
                {
                    label: "Date",
                    field: "read_at",
                    type: "date",
                    dateInputFormat: "yyyy-MM-dd HH:mm:ss", // expects 2018-03-16
                    dateOutputFormat: "eee, d-MMM-yyyy h:mm:ss a", // outputs Mar 16th 2018
                },
                {
                    label: "Type",
                    field: "type",
                },
                {
                    label: "Reading",
                    field: "reading",
                    type: "number",
                },
            ],
            rows: [],
        };
    },
    mounted() {
        axios.get("/api/v1/readings").then((response) => {
            this.rows = response.data.data;
        });
    },
};
</script>
